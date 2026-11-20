/**
 * ConsultWays Hub - Core Reactive Client Logic
 * Subdomain: hub.consultways.com
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Initialize PWA Service Worker
  if ('serviceWorker' in navigator && window.location.protocol.startsWith('http')) {
    navigator.serviceWorker.register('/sw.js').catch(err => {
      console.log('SW registration skipped:', err.message);
    });
  }

  // 2. Global State
  window.CW_STATE = {
    activeCategory: 'All',
    searchQuery: '',
    maxPrice: 200,
    wishlist: JSON.parse(localStorage.getItem('cw_wishlist') || '[]'),
    listings: window.CW_INITIAL_LISTINGS || [],
    apps: window.CW_INITIAL_APPS || []
  };

  initApp();
});

function initApp() {
  setupSearchAndFilters();
  setupPostListingOptimizer();
  setupDashboardTabs();
  setupModalHandlers();
  init3DTiltCards();
  updateSoundButtonUI();
}

/* -------------------------------------------------------------
 * 1. SEARCH & CATEGORY FILTERS
 * ------------------------------------------------------------- */
function setupSearchAndFilters() {
  const searchInput = document.getElementById('globalSearchInput');
  const catButtons = document.querySelectorAll('.cat-filter-btn');
  const priceSlider = document.getElementById('priceRangeSlider');
  const priceDisplay = document.getElementById('priceDisplayVal');

  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      window.CW_STATE.searchQuery = e.target.value.toLowerCase().trim();
      renderFilteredGrid();
    });
  }

  catButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      catButtons.forEach(b => {
        b.classList.remove('bg-blue-600', 'text-white', 'shadow-md');
        b.classList.add('bg-white', 'text-slate-700', 'hover:bg-slate-100');
      });
      btn.classList.add('bg-blue-600', 'text-white', 'shadow-md');
      btn.classList.remove('bg-white', 'text-slate-700', 'hover:bg-slate-100');

      window.CW_STATE.activeCategory = btn.getAttribute('data-cat') || 'All';
      renderFilteredGrid();
    });
  });

  if (priceSlider && priceDisplay) {
    priceSlider.addEventListener('input', (e) => {
      const val = parseFloat(e.target.value);
      window.CW_STATE.maxPrice = val;
      priceDisplay.textContent = '$' + val.toFixed(0);
      renderFilteredGrid();
    });
  }
}

function renderFilteredGrid() {
  const grid = document.getElementById('marketplaceGrid');
  if (!grid || !window.CW_STATE.listings) return;

  const { activeCategory, searchQuery, maxPrice } = window.CW_STATE;

  const filtered = window.CW_STATE.listings.filter(item => {
    // Category match
    const catMatch = (activeCategory === 'All') || (item.type === activeCategory);
    // Search match
    const q = searchQuery;
    const textMatch = !q || 
      item.title.toLowerCase().includes(q) || 
      item.description.toLowerCase().includes(q) || 
      (item.seller_name && item.seller_name.toLowerCase().includes(q));
    // Price match ($20 to maxPrice)
    const priceMatch = (item.price_usd <= maxPrice);

    return catMatch && textMatch && priceMatch;
  });

  const countBadge = document.getElementById('listingCountBadge');
  if (countBadge) {
    countBadge.textContent = `${filtered.length} Listings Available`;
  }

  if (filtered.length === 0) {
    grid.innerHTML = `
      <div class="col-span-12 py-16 text-center bg-white rounded-2xl border border-slate-200">
        <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <h3 class="text-xl font-bold text-slate-800 mb-1">No Listings Found</h3>
        <p class="text-slate-500 mb-6">Try adjusting your keyword, category, or maximum price filter ($20 - $200).</p>
        <button onclick="resetFilters()" class="px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700">
          Reset Filters
        </button>
      </div>
    `;
    return;
  }

  grid.innerHTML = filtered.map(item => `
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover-lift flex flex-col justify-between" id="card-${item.id}">
      <div class="relative">
        <img src="${item.image_url}" alt="${escapeHtml(item.title)}" loading="lazy" class="w-full h-48 object-cover bg-slate-100" onerror="this.src='https://consultways.com/wp-content/uploads/2025/01/Discover-the-Future-of-Online-Consultancy-with-Consultways.com-in-UK-USA-and-EU.webp'">
        <span class="absolute top-3 left-3 px-2.5 py-1 text-xs font-bold rounded-full ${getCategoryBadgeClass(item.type)}">
          ${escapeHtml(item.type)}
        </span>
        ${item.seller_verified ? `
          <span class="absolute top-3 right-3 px-2 py-0.5 text-xs font-semibold rounded-full badge-verified flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            Verified
          </span>
        ` : ''}
      </div>

      <div class="p-5 flex-1 flex flex-col justify-between">
        <div>
          <div class="flex items-center gap-1.5 mb-2">
            <span class="text-amber-500 font-bold text-sm">★ ${item.rating || '4.8'}</span>
            <span class="text-slate-400 text-xs">(${item.reviews_count || 18} orders)</span>
            <span class="text-slate-300 text-xs">•</span>
            <span class="text-slate-500 text-xs truncate max-w-[120px]">${escapeHtml(item.seller_name || 'ConsultWays Store')}</span>
          </div>
          <h4 class="font-bold text-slate-900 text-base mb-2 line-clamp-2 hover:text-blue-600 cursor-pointer" onclick="openDetailModal('${item.id}')">
            ${escapeHtml(item.title)}
          </h4>
          <p class="text-slate-500 text-xs line-clamp-2 mb-4">
            ${escapeHtml(item.description)}
          </p>
        </div>

        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
          <div>
            <div class="text-xs text-slate-400 font-medium">Verified Price</div>
            <div class="text-xl font-extrabold text-blue-600">${item.price_display || '$' + item.price_usd.toFixed(2)}</div>
          </div>
          <div class="flex items-center gap-2">
            <button onclick="toggleWishlist('${item.id}')" title="Save to Wishlist" class="p-2 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-500 ${isWishlisted(item.id) ? 'text-red-500 bg-red-50 border-red-200' : ''}">
              <svg class="w-4 h-4" fill="${isWishlisted(item.id) ? 'currentColor' : 'none'}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </button>
            <button onclick="openDetailModal('${item.id}')" class="px-3.5 py-2 bg-slate-900 hover:bg-blue-600 text-white text-xs font-semibold rounded-xl transition-colors">
              View
            </button>
          </div>
        </div>
      </div>
    </div>
  `).join('');
}

function resetFilters() {
  window.CW_STATE.activeCategory = 'All';
  window.CW_STATE.searchQuery = '';
  window.CW_STATE.maxPrice = 200;

  const searchInput = document.getElementById('globalSearchInput');
  if (searchInput) searchInput.value = '';
  const priceSlider = document.getElementById('priceRangeSlider');
  if (priceSlider) priceSlider.value = 200;
  const priceDisplay = document.getElementById('priceDisplayVal');
  if (priceDisplay) priceDisplay.textContent = '$200';

  const catButtons = document.querySelectorAll('.cat-filter-btn');
  catButtons.forEach(b => {
    b.classList.remove('bg-blue-600', 'text-white', 'shadow-md');
    b.classList.add('bg-white', 'text-slate-700', 'hover:bg-slate-100');
    if (b.getAttribute('data-cat') === 'All') {
      b.classList.add('bg-blue-600', 'text-white', 'shadow-md');
    }
  });

  renderFilteredGrid();
}

function getCategoryBadgeClass(type) {
  if (type === 'Physical Products') return 'bg-amber-100 text-amber-800';
  if (type === 'Digital Downloads') return 'bg-blue-100 text-blue-800';
  return 'bg-purple-100 text-purple-800';
}

/* -------------------------------------------------------------
 * 2. AUTO-WEBP CLIENT-SIDE IMAGE COMPRESSOR (<100KB, max 1000px)
 * ------------------------------------------------------------- */
function setupPostListingOptimizer() {
  const fileInput = document.getElementById('listingImageInput');
  const previewImg = document.getElementById('imageCompressedPreview');
  const previewBox = document.getElementById('imagePreviewContainer');
  const sizeBadge = document.getElementById('compressionSizeBadge');
  const dropzone = document.getElementById('listingDropzone');

  if (!fileInput) return;

  const handleFile = (file) => {
    if (!file || !file.type.startsWith('image/')) {
      showToast('Please select a valid image file (JPG, PNG, WebP).', 'error');
      return;
    }

    const originalSizeKb = Math.round(file.size / 1024);
    const reader = new FileReader();

    reader.onload = (e) => {
      const img = new Image();
      img.onload = () => {
        // Target: max 1000px width/height
        const maxDim = 1000;
        let width = img.width;
        let height = img.height;

        if (width > maxDim || height > maxDim) {
          if (width > height) {
            height = Math.round((height * maxDim) / width);
            width = maxDim;
          } else {
            width = Math.round((width * maxDim) / height);
            height = maxDim;
          }
        }

        const canvas = document.createElement('canvas');
        canvas.width = width;
        canvas.height = height;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0, width, height);

        // Convert to WebP format, quality 0.80
        const webpDataUrl = canvas.toDataURL('image/webp', 0.80);
        const webpSizeKb = Math.round((webpDataUrl.length * 3 / 4) / 1024);

        if (previewImg && previewBox && sizeBadge) {
          previewImg.src = webpDataUrl;
          previewBox.classList.remove('hidden');
          sizeBadge.innerHTML = `
            <span class="text-green-600 font-bold">Auto-WebP Ready:</span> 
            ${webpSizeKb} KB (Reduced from ${originalSizeKb} KB — Saved ${Math.max(0, originalSizeKb - webpSizeKb)} KB)
          `;
        }

        // Store compressed payload on window for submission
        window.CW_COMPRESSED_IMAGE = webpDataUrl;
      };
      img.src = e.target.result;
    };
    reader.readAsDataURL(file);
  };

  fileInput.addEventListener('change', (e) => {
    if (e.target.files && e.target.files[0]) {
      handleFile(e.target.files[0]);
    }
  });

  if (dropzone) {
    ['dragenter', 'dragover'].forEach(eventName => {
      dropzone.addEventListener(eventName, (e) => {
        e.preventDefault();
        dropzone.classList.add('dragover');
      });
    });
    ['dragleave', 'drop'].forEach(eventName => {
      dropzone.addEventListener(eventName, (e) => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
      });
    });
    dropzone.addEventListener('drop', (e) => {
      if (e.dataTransfer.files && e.dataTransfer.files[0]) {
        handleFile(e.dataTransfer.files[0]);
      }
    });
  }

  // Listing Form Submit Handler with AI Auto-Approval
  const form = document.getElementById('freeListingForm');
  if (form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const title = document.getElementById('listingTitle')?.value.trim();
      const type = document.getElementById('listingType')?.value;
      const price = parseFloat(document.getElementById('listingPrice')?.value || 0);
      const seller = document.getElementById('sellerName')?.value.trim();
      const email = document.getElementById('sellerEmail')?.value.trim();
      const desc = document.getElementById('listingDesc')?.value.trim();

      if (!title || !desc || !seller || !email) {
        showToast('Please fill in all required fields.', 'error');
        return;
      }

      if (price < 20 || price > 200) {
        showToast('Price must be between $20.00 and $200.00 USD according to marketplace standards.', 'error');
        return;
      }

      const submitBtn = document.getElementById('submitListingBtn');
      const aiStatusAlert = document.getElementById('aiStatusAlert');
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
          <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
          </svg>
          Scanning with AI Moderation...
        `;
      }

      if (aiStatusAlert) {
        aiStatusAlert.classList.remove('hidden');
        aiStatusAlert.innerHTML = `
          <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl text-blue-800 text-sm flex items-center gap-3">
            <svg class="w-5 h-5 text-blue-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <div>
              <p class="font-bold">Pending AI Verification</p>
              <p class="text-xs text-blue-600">Gemini/OpenAI is analyzing your listing title and content for prohibited terms and spam...</p>
            </div>
          </div>
        `;
      }

      // Submit to backend
      try {
        const payload = {
          title,
          type,
          price_usd: price,
          seller_name: seller,
          seller_email: email,
          description: desc,
          image_base64: window.CW_COMPRESSED_IMAGE || ''
        };

        const res = await fetch('/api/moderate.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });

        const data = await res.json();

        if (data.status === 'approved') {
          if (aiStatusAlert) {
            aiStatusAlert.innerHTML = `
              <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-900 text-sm">
                <div class="flex items-center gap-2 font-bold text-green-700 mb-1">
                  <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                  AI Verification Passed & Auto-Published!
                </div>
                <p class="text-xs text-green-700">Your listing is now live across ConsultWays Hub and indexed with social preview tags.</p>
              </div>
            `;
          }
          showToast('🎉 Listing approved and published instantly!', 'success');
          form.reset();
          if (previewBox) previewBox.classList.add('hidden');
          window.CW_COMPRESSED_IMAGE = null;

          setTimeout(() => {
            window.location.href = '/marketplace';
          }, 2000);

        } else {
          // Queued for manual admin
          if (aiStatusAlert) {
            aiStatusAlert.innerHTML = `
              <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-sm">
                <div class="flex items-center gap-2 font-bold text-amber-700 mb-1">
                  <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                  Held in Admin Verification Queue
                </div>
                <p class="text-xs text-amber-700">${data.message || 'Requires brief moderator confirmation before going live.'}</p>
              </div>
            `;
          }
          showToast('Listing submitted to admin queue for review.', 'info');
        }
      } catch (err) {
        showToast('Error connecting to verification server: ' + err.message, 'error');
      } finally {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.textContent = 'Publish Free Listing Now';
        }
      }
    });
  }
}

/* -------------------------------------------------------------
 * 3. DASHBOARD TAB SWITCHING & TRACKING
 * ------------------------------------------------------------- */
function setupDashboardTabs() {
  const tabs = document.querySelectorAll('.dash-nav-tab');
  const sections = document.querySelectorAll('.dash-content-section');

  tabs.forEach(tab => {
    tab.addEventListener('click', (e) => {
      e.preventDefault();
      const targetId = tab.getAttribute('data-target');

      tabs.forEach(t => {
        t.classList.remove('bg-blue-600', 'text-white', 'shadow');
        t.classList.add('text-slate-600', 'hover:bg-slate-100');
      });
      tab.classList.add('bg-blue-600', 'text-white', 'shadow');
      tab.classList.remove('text-slate-600', 'hover:bg-slate-100');

      sections.forEach(s => s.classList.add('hidden'));
      const activeSection = document.getElementById(targetId);
      if (activeSection) activeSection.classList.remove('hidden');
    });
  });
}

// Order Tracking Modal or Expansion
function showOrderDetails(orderId) {
  showToast('Viewing tracking history for order #' + orderId, 'info');
}

// Reveal Digital License Key
function revealKey(elementId) {
  const el = document.getElementById(elementId);
  if (!el) return;
  if (el.getAttribute('data-masked') === 'true') {
    el.textContent = el.getAttribute('data-full-key');
    el.setAttribute('data-masked', 'false');
  } else {
    navigator.clipboard.writeText(el.getAttribute('data-full-key')).then(() => {
      showToast('License key copied to clipboard!', 'success');
    });
  }
}

// Chat simulator in Buyer/Seller messages
function sendSellerMessage() {
  const input = document.getElementById('chatMsgInput');
  const container = document.getElementById('chatMessagesContainer');
  if (!input || !container || !input.value.trim()) return;

  const msg = input.value.trim();
  const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

  // Append user message
  const userBubble = document.createElement('div');
  userBubble.className = 'flex justify-end mb-3';
  userBubble.innerHTML = `
    <div class="bg-blue-600 text-white rounded-2xl rounded-tr-none py-2.5 px-4 max-w-[80%] text-sm shadow-sm">
      <p>${escapeHtml(msg)}</p>
      <span class="text-[10px] text-blue-200 block text-right mt-1">${time}</span>
    </div>
  `;
  container.appendChild(userBubble);
  input.value = '';
  container.scrollTop = container.scrollHeight;

  // Auto seller reply simulation
  setTimeout(() => {
    const sellerBubble = document.createElement('div');
    sellerBubble.className = 'flex justify-start mb-3';
    sellerBubble.innerHTML = `
      <div class="bg-slate-100 text-slate-800 rounded-2xl rounded-tl-none py-2.5 px-4 max-w-[80%] text-sm border border-slate-200">
        <p class="font-semibold text-xs text-blue-600 mb-0.5">Verified Seller Support</p>
        <p>Thank you for reaching out! We have received your inquiry regarding this order and will update tracking details shortly.</p>
        <span class="text-[10px] text-slate-400 block mt-1">${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</span>
      </div>
    `;
    container.appendChild(sellerBubble);
    container.scrollTop = container.scrollHeight;
  }, 1000);
}

/* -------------------------------------------------------------
 * 4. MODAL POPUPS & VIRAL SHARE
 * ------------------------------------------------------------- */
function setupModalHandlers() {
  const modal = document.getElementById('cwDetailModal');
  const backdrop = document.getElementById('cwModalBackdrop');
  const closeBtn = document.getElementById('cwModalCloseBtn');

  if (closeBtn && modal) {
    closeBtn.addEventListener('click', closeModal);
  }
  if (backdrop && modal) {
    backdrop.addEventListener('click', closeModal);
  }
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeModal();
  });
}

function openDetailModal(itemId) {
  const modal = document.getElementById('cwDetailModal');
  if (!modal) return;

  const item = (window.CW_STATE.listings || []).find(l => l.id === itemId);
  if (!item) return;

  const content = document.getElementById('cwModalContent');
  const itemUrl = `https://hub.consultways.com/item/${item.slug}`;
  const shareText = encodeURIComponent(`Check out ${item.title} on ConsultWays Hub! Verified Price: ${item.price_display || '$' + item.price_usd} USD: ${itemUrl}`);

  content.innerHTML = `
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <img src="${item.image_url}" alt="${escapeHtml(item.title)}" class="w-full h-72 object-cover rounded-2xl bg-slate-100 border border-slate-200" onerror="this.src='https://consultways.com/wp-content/uploads/2025/01/Discover-the-Future-of-Online-Consultancy-with-Consultways.com-in-UK-USA-and-EU.webp'">
        
        <!-- Social Viral Share Loop -->
        <div class="mt-4 p-4 bg-slate-50 border border-slate-200 rounded-xl">
          <div class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">1-Click Share & Earn Promo</div>
          <div class="flex items-center gap-2 flex-wrap">
            <a href="https://api.whatsapp.com/send?text=${shareText}" target="_blank" rel="noopener" class="px-3 py-1.5 share-btn-wa text-xs font-bold rounded-lg flex items-center gap-1.5 shadow-sm">
              WhatsApp
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(itemUrl)}" target="_blank" rel="noopener" class="px-3 py-1.5 share-btn-fb text-xs font-bold rounded-lg flex items-center gap-1.5 shadow-sm">
              Facebook
            </a>
            <a href="https://twitter.com/intent/tweet?text=${shareText}" target="_blank" rel="noopener" class="px-3 py-1.5 share-btn-x text-xs font-bold rounded-lg flex items-center gap-1.5 shadow-sm">
              X (Twitter)
            </a>
            <button onclick="copyToClipboard('${itemUrl}')" class="px-3 py-1.5 share-btn-link text-xs font-bold rounded-lg flex items-center gap-1.5 shadow-sm">
              Copy Link
            </button>
          </div>
        </div>
      </div>

      <div class="flex flex-col justify-between">
        <div>
          <div class="flex items-center gap-2 mb-2">
            <span class="px-2.5 py-1 text-xs font-bold rounded-full ${getCategoryBadgeClass(item.type)}">${escapeHtml(item.type)}</span>
            ${item.seller_verified ? `<span class="badge-verified px-2 py-0.5 text-xs font-semibold rounded-full">Verified Merchant</span>` : ''}
          </div>
          <h2 class="text-2xl font-extrabold text-slate-900 leading-tight mb-2">${escapeHtml(item.title)}</h2>
          <div class="flex items-center gap-2 mb-4 text-sm">
            <span class="text-amber-500 font-bold">★ ${item.rating || '4.9'}</span>
            <span class="text-slate-400">(${item.reviews_count || 32} reviews)</span>
            <span class="text-slate-300">•</span>
            <span class="text-slate-600 font-medium">${escapeHtml(item.seller_name || 'ConsultWays Store')}</span>
          </div>
          <p class="text-slate-600 text-sm leading-relaxed mb-6">${escapeHtml(item.description)}</p>
        </div>

        <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between">
          <div>
            <div class="text-xs text-slate-400 font-medium">Standard Price</div>
            <div class="text-2xl font-black text-blue-600">${item.price_display || '$' + item.price_usd.toFixed(2)} USD</div>
          </div>
          <div class="flex items-center gap-2">
            <button onclick="simulateCheckout('${item.id}')" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-lg transition-transform hover:-translate-y-0.5">
              Secure Checkout
            </button>
          </div>
        </div>
      </div>
    </div>
  `;

  modal.classList.remove('hidden');
}

function openAppDetailModal(appId) {
  const modal = document.getElementById('cwDetailModal');
  if (!modal) return;

  const app = (window.CW_STATE.apps || []).find(a => a.id === appId);
  if (!app) return;

  const content = document.getElementById('cwModalContent');
  const storeLink = app.play_store_url || ('https://play.google.com/store/apps/details?id=' + app.package_name);

  content.innerHTML = `
    <div class="flex flex-col md:flex-row gap-6 mb-6">
      <img src="${app.icon}" alt="${escapeHtml(app.name)}" class="w-28 h-28 md:w-32 md:h-32 rounded-3xl object-cover shadow-2xl mx-auto md:mx-0 border-2 border-slate-700 bg-slate-800">
      <div class="flex-1">
        <div class="flex items-center gap-2 mb-2 flex-wrap">
          <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-cyan-950 text-cyan-300 border border-cyan-500/30">${escapeHtml(app.category)}</span>
          <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-emerald-950 text-emerald-400 border border-emerald-500/30 flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            Play Protect Verified
          </span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-black text-white mb-1">${escapeHtml(app.name)}</h2>
        <p class="text-xs text-slate-400 font-medium mb-3">Publisher: <strong class="text-cyan-400">ConsultWays</strong></p>
        
        <div class="flex items-center gap-4 text-xs mb-4">
          <span class="text-amber-400 font-bold flex items-center gap-0.5 text-sm">★ ${app.rating}</span>
          <span class="text-slate-400">(${app.reviews_count} reviews)</span>
          <span class="text-slate-600">•</span>
          <span class="text-slate-200 font-bold">${app.installs || '25K+'} Downloads</span>
        </div>
        
        <p class="text-slate-300 text-xs sm:text-sm leading-relaxed mb-4">${escapeHtml(app.description)}</p>
      </div>
    </div>

    ${app.screenshots && app.screenshots.length > 0 ? `
    <div class="mb-6">
      <h4 class="text-xs font-bold text-slate-300 uppercase tracking-wider mb-3">Official Google Play Screenshots</h4>
      <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-thin">
        ${app.screenshots.map(ss => `
          <img src="${ss}" alt="Screenshot" class="h-56 sm:h-64 rounded-xl object-cover border border-slate-700 bg-slate-800 shadow-lg flex-shrink-0">
        `).join('')}
      </div>
    </div>
    ` : ''}

    <div class="mb-6 bg-slate-800/60 p-4 rounded-2xl border border-slate-700">
      <h4 class="text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Core Features</h4>
      <div class="grid grid-cols-2 gap-2">
        ${(app.features || []).map(f => `
          <div class="flex items-center gap-1.5 text-xs text-slate-300">
            <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            ${escapeHtml(f)}
          </div>
        `).join('')}
      </div>
    </div>

    <div class="flex items-center gap-3 flex-wrap pt-2">
      <a href="${storeLink}" target="_blank" rel="noopener noreferrer" onclick="playHapticClick()" class="px-6 py-3.5 cw-play-btn text-white font-extrabold text-sm rounded-xl flex items-center gap-2 shadow-xl shadow-emerald-950/40">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M3.609 1.814L13.792 12 3.61 22.186a2.38 2.38 0 01-.61-1.614V3.428c0-.627.228-1.206.61-1.614zm11.602 11.602l2.36 2.36-11.492 6.64 9.132-9zm2.36-2.36l-2.36 2.36-9.132-9 11.492 6.64zm1.442 1.442l3.412 1.972a1.763 1.763 0 010 3.06l-3.412 1.972-2.36-2.36 2.36-2.644z"/></svg>
        <span>Install on Official Google Play Store</span>
      </a>
      <a href="${app.privacy_policy_url || 'https://consultways.com/privacy-policy/'}" target="_blank" rel="noopener" class="px-4 py-3.5 border border-slate-700 text-slate-300 hover:bg-slate-800 text-xs font-semibold rounded-xl transition-colors">
        Privacy Policy
      </a>
    </div>
  `;

  modal.classList.remove('hidden');
}

function closeModal() {
  const modal = document.getElementById('cwDetailModal');
  if (modal) modal.classList.add('hidden');
}

function simulateCheckout(itemId) {
  closeModal();
  showToast('Redirecting to SSL-encrypted checkout gateway...', 'info');
  setTimeout(() => {
    alert('Payment Gateway Integration Ready!\nCompatible with PayFast, Stripe, and Lemon Squeezy merchant onboarding on hub.consultways.com.');
  }, 600);
}

/* -------------------------------------------------------------
 * 5. WISHLIST & HELPERS
 * ------------------------------------------------------------- */
function toggleWishlist(id) {
  const list = window.CW_STATE.wishlist;
  const idx = list.indexOf(id);
  if (idx > -1) {
    list.splice(idx, 1);
    showToast('Removed from saved wishlist', 'info');
  } else {
    list.push(id);
    showToast('❤️ Saved to wishlist!', 'success');
  }
  localStorage.setItem('cw_wishlist', JSON.stringify(list));
  renderFilteredGrid();
}

function isWishlisted(id) {
  return (window.CW_STATE.wishlist || []).includes(id);
}

function copyToClipboard(text) {
  navigator.clipboard.writeText(text).then(() => {
    showToast('Link copied to clipboard!', 'success');
  }).catch(() => {
    prompt('Copy link:', text);
  });
}

function showToast(msg, type = 'info') {
  let toastContainer = document.getElementById('cwToastContainer');
  if (!toastContainer) {
    toastContainer = document.createElement('div');
    toastContainer.id = 'cwToastContainer';
    toastContainer.className = 'fixed bottom-5 right-5 z-50 flex flex-col gap-2 pointer-events-none';
    document.body.appendChild(toastContainer);
  }

  const toast = document.createElement('div');
  const bg = type === 'success' ? 'bg-emerald-600' : (type === 'error' ? 'bg-red-600' : 'bg-slate-900');
  toast.className = `${bg} text-white px-4 py-3 rounded-xl shadow-2xl text-sm font-medium flex items-center gap-2 pointer-events-auto transition-all duration-300 transform translate-y-2 opacity-0`;
  toast.innerHTML = `<span>${escapeHtml(msg)}</span>`;
  toastContainer.appendChild(toast);

  requestAnimationFrame(() => {
    toast.classList.remove('translate-y-2', 'opacity-0');
  });

  setTimeout(() => {
    toast.classList.add('opacity-0', 'translate-y-2');
    setTimeout(() => toast.remove(), 300);
  }, 3500);
}

function escapeHtml(str) {
  if (!str) return '';
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}


/* -------------------------------------------------------------
 * 6. WEB AUDIO API HAPTIC SYNTHESIZER & 3D CARD TILT
 * ------------------------------------------------------------- */
let audioCtx = null;
let soundEnabled = localStorage.getItem('cw_sound_enabled') !== 'false';

function getAudioContext() {
  if (!audioCtx) {
    const AudioContextClass = window.AudioContext || window.webkitAudioContext;
    if (AudioContextClass) audioCtx = new AudioContextClass();
  }
  if (audioCtx && audioCtx.state === 'suspended') {
    audioCtx.resume();
  }
  return audioCtx;
}

function playHapticHover() {
  if (!soundEnabled) return;
  try {
    const ctx = getAudioContext();
    if (!ctx) return;
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();
    osc.type = 'sine';
    const now = ctx.currentTime;
    osc.frequency.setValueAtTime(800, now);
    osc.frequency.exponentialRampToValueAtTime(1200, now + 0.03);
    gain.gain.setValueAtTime(0.04, now);
    gain.gain.exponentialRampToValueAtTime(0.001, now + 0.035);
    osc.connect(gain);
    gain.connect(ctx.destination);
    osc.start(now);
    osc.stop(now + 0.04);
  } catch(e) {}
}

function playHapticClick() {
  if (!soundEnabled) return;
  try {
    const ctx = getAudioContext();
    if (!ctx) return;
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();
    osc.type = 'triangle';
    const now = ctx.currentTime;
    osc.frequency.setValueAtTime(520, now);
    osc.frequency.exponentialRampToValueAtTime(880, now + 0.06);
    gain.gain.setValueAtTime(0.08, now);
    gain.gain.exponentialRampToValueAtTime(0.001, now + 0.08);
    osc.connect(gain);
    gain.connect(ctx.destination);
    osc.start(now);
    osc.stop(now + 0.08);
  } catch(e) {}
}

function toggleSound() {
  soundEnabled = !soundEnabled;
  localStorage.setItem('cw_sound_enabled', soundEnabled ? 'true' : 'false');
  updateSoundButtonUI();
  if (soundEnabled) playHapticClick();
  showToast(soundEnabled ? '🔊 Futuristic Haptic Sound ON' : '🔇 Haptic Sound Muted', 'info');
}

function updateSoundButtonUI() {
  const btn = document.getElementById('soundToggleFloatingBtn');
  if (btn) {
    btn.innerHTML = soundEnabled ? `
      <svg class="w-4 h-4 text-emerald-400 animate-pulse" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.415z" clip-rule="evenodd"/></svg>
      <span class="text-xs font-bold text-emerald-300">SFX ON</span>
    ` : `
      <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM12.293 7.293a1 1 0 011.414 0L15 8.586l1.293-1.293a1 1 0 111.414 1.414L16.414 10l1.293 1.293a1 1 0 01-1.414 1.414L15 11.414l-1.293 1.293a1 1 0 01-1.414-1.414L13.586 10l-1.293-1.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
      <span class="text-xs font-bold text-slate-400">SFX OFF</span>
    `;
  }
}

function init3DTiltCards() {
  const cards = document.querySelectorAll('.app-haptic-card, .app-haptic-card-dark, .neon-border-box');
  cards.forEach(card => {
    card.addEventListener('mouseenter', () => {
      playHapticHover();
    });
    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      const centerX = rect.width / 2;
      const centerY = rect.height / 2;
      const rotateX = ((y - centerY) / centerY) * -6;
      const rotateY = ((x - centerX) / centerX) * 6;
      card.style.transform = `perspective(800px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.06, 1.06, 1.06)`;
    });
    card.addEventListener('mouseleave', () => {
      card.style.transform = '';
    });
    card.addEventListener('click', () => {
      playHapticClick();
    });
  });

  document.querySelectorAll('a, button').forEach(el => {
    el.addEventListener('mouseenter', () => {
      if (el.matches(':hover')) playHapticHover();
    });
  });
}
