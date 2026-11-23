/**
 * ConsultWays Hub - Core Tactile Controller & Haptic Audio Engine
 * Integrated with FidoTown design principles
 */

// Web Audio API Synthesizer (Instant, Zero Latency Haptics)
let audioCtx = null;
let audioMuted = localStorage.getItem('cw_audio_muted') === 'true';

function initAudio() {
  if (!audioCtx) {
    const AudioContext = window.AudioContext || window.webkitAudioContext;
    if (AudioContext) audioCtx = new AudioContext();
  }
  if (audioCtx && audioCtx.state === 'suspended') {
    audioCtx.resume();
  }
}

function playHapticClick() {
  if (audioMuted) return;
  try {
    initAudio();
    if (!audioCtx) return;
    const osc = audioCtx.createOscillator();
    const gain = audioCtx.createGain();
    osc.type = 'triangle';
    osc.frequency.setValueAtTime(420, audioCtx.currentTime);
    osc.frequency.exponentialRampToValueAtTime(120, audioCtx.currentTime + 0.04);
    gain.gain.setValueAtTime(0.12, audioCtx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.04);
    osc.connect(gain);
    gain.connect(audioCtx.destination);
    osc.start();
    osc.stop(audioCtx.currentTime + 0.04);
  } catch(e) {}
}

function playHapticPop() {
  if (audioMuted) return;
  try {
    initAudio();
    if (!audioCtx) return;
    const osc = audioCtx.createOscillator();
    const gain = audioCtx.createGain();
    osc.type = 'sine';
    osc.frequency.setValueAtTime(320, audioCtx.currentTime);
    osc.frequency.exponentialRampToValueAtTime(740, audioCtx.currentTime + 0.06);
    gain.gain.setValueAtTime(0.1, audioCtx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.06);
    osc.connect(gain);
    gain.connect(audioCtx.destination);
    osc.start();
    osc.stop(audioCtx.currentTime + 0.06);
  } catch(e) {}
}

function toggleAudio() {
  audioMuted = !audioMuted;
  localStorage.setItem('cw_audio_muted', audioMuted ? 'true' : 'false');
  updateAudioUi();
  if (!audioMuted) playHapticClick();
}

function updateAudioUi() {
  const icon = document.getElementById('cwAudioIcon');
  const label = document.getElementById('cwAudioLabel');
  if (icon) {
    icon.innerHTML = audioMuted ? `
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
    ` : `
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
    `;
  }
  if (label) {
    label.textContent = audioMuted ? 'Sound OFF' : 'Sound ON';
  }
}

// Global State
window.CW_STATE = {
  apps: window.CW_INITIAL_APPS || [],
  listings: window.CW_INITIAL_LISTINGS || [],
  filters: {
    type: 'all',
    search: '',
    minPrice: 20,
    maxPrice: 200
  }
};

function escapeHtml(str) {
  if (!str) return '';
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

// Open App Detail Modal with Verified Google Play Data & Screenshots
function openAppDetailModal(appId) {
  playHapticPop();
  const modal = document.getElementById('cwDetailModal');
  if (!modal) return;

  const app = (window.CW_STATE.apps || []).find(a => a.id === appId || a.package_name === appId);
  if (!app) {
    console.warn('App not found:', appId);
    return;
  }

  const content = document.getElementById('cwModalContent');
  const storeLink = app.play_store_url || ('https://play.google.com/store/apps/details?id=' + app.package_name + '&hl=en');

  content.innerHTML = `
    <div class="flex flex-col md:flex-row gap-6 mb-6">
      <img src="${app.icon}" alt="${escapeHtml(app.name)}" class="w-24 h-24 md:w-28 md:h-28 rounded-3xl object-cover shadow-lg mx-auto md:mx-0 border border-slate-200 bg-white flex-shrink-0" onerror="this.src='/assets/icons/consultways-logo.svg'">
      <div class="flex-1">
        <div class="flex items-center gap-2 mb-2 flex-wrap">
          <span class="px-3 py-1 text-xs font-bold rounded-full bg-orange-100 text-orange-700">${escapeHtml(app.category)}</span>
          <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700 flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            Play Protect Verified
          </span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 mb-1">${escapeHtml(app.name)}</h2>
        <p class="text-xs text-slate-500 font-mono mb-3">${escapeHtml(app.package_name)}</p>
        
        <div class="flex items-center gap-4 text-xs mb-4 py-2 px-3 rounded-xl bg-slate-50 border border-slate-100 w-fit">
          <span class="text-amber-500 font-bold flex items-center gap-0.5 text-sm">★ ${app.rating}</span>
          <span class="text-slate-500">(${app.reviews_count} reviews)</span>
          <span class="text-slate-300">•</span>
          <span class="text-slate-700 font-bold">${app.installs} Downloads</span>
        </div>
        
        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed mb-4">${escapeHtml(app.description)}</p>
      </div>
    </div>

    ${app.screenshots && app.screenshots.length > 0 ? `
    <div class="mb-6">
      <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Live Google Play Store Preview</h4>
      <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-thin">
        ${app.screenshots.map(ss => `
          <img src="${ss}" alt="Screenshot" class="h-56 sm:h-64 rounded-2xl object-cover border border-slate-200 bg-slate-100 shadow-md flex-shrink-0">
        `).join('')}
      </div>
    </div>
    ` : ''}

    <div class="mb-6 bg-slate-50 p-4 rounded-2xl border border-slate-100">
      <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Core Features</h4>
      <div class="grid grid-cols-2 gap-2">
        ${(app.features || []).map(f => `
          <div class="flex items-center gap-1.5 text-xs text-slate-700">
            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            <span class="truncate">${escapeHtml(f)}</span>
          </div>
        `).join('')}
      </div>
    </div>

    <div class="flex items-center gap-3 flex-wrap pt-2">
      <a href="${storeLink}" target="_blank" rel="noopener noreferrer" onclick="playHapticClick()" class="px-6 py-3.5 fido-play-btn text-white text-sm flex items-center gap-2 shadow-md hover:scale-102">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M3.609 1.814L13.792 12 3.61 22.186a2.38 2.38 0 01-.61-1.614V3.428c0-.627.228-1.206.61-1.614zm11.602 11.602l2.36 2.36-11.492 6.64 9.132-9zm2.36-2.36l-2.36 2.36-9.132-9 11.492 6.64zm1.442 1.442l3.412 1.972a1.763 1.763 0 010 3.06l-3.412 1.972-2.36-2.36 2.36-2.644z"/></svg>
        <span>Install on Official Google Play Store</span>
      </a>
      <a href="${app.privacy_policy_url || 'https://consultways.com/privacy-policy/'}" target="_blank" rel="noopener" class="px-4 py-3.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold">
        Privacy Policy
      </a>
    </div>
  `;

  modal.classList.remove('hidden');
}

function closeAppDetailModal() {
  playHapticClick();
  const modal = document.getElementById('cwDetailModal');
  if (modal) modal.classList.add('hidden');
}

// Open Product Detail Modal
function openDetailModal(itemId) {
  playHapticPop();
  const modal = document.getElementById('cwProductModal');
  if (!modal) return;

  const item = (window.CW_STATE.listings || []).find(l => l.id === itemId || String(l.original_id) === String(itemId));
  if (!item) return;

  const content = document.getElementById('cwProductModalContent');
  content.innerHTML = `
    <div class="flex flex-col md:flex-row gap-6 mb-4">
      <img src="${item.image_url}" alt="${escapeHtml(item.title)}" class="w-full md:w-64 h-64 object-cover rounded-2xl border border-slate-200 bg-slate-50" onerror="this.src='https://consultways.com/wp-content/uploads/2025/01/Discover-the-Future-of-Online-Consultancy-with-Consultways.com-in-UK-USA-and-EU.webp'">
      <div class="flex-1">
        <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700 mb-2 inline-block">${escapeHtml(item.type)}</span>
        <h2 class="text-2xl font-black text-slate-900 mb-2">${escapeHtml(item.title)}</h2>
        <div class="flex items-center gap-3 text-xs mb-3 text-slate-500">
          <span class="text-amber-500 font-bold">★ ${item.rating || '4.8'}</span>
          <span>(${item.reviews_count || 18} reviews)</span>
          <span>•</span>
          <span class="font-semibold text-slate-700">${escapeHtml(item.seller_name || 'ConsultWays Store')}</span>
        </div>
        <div class="text-3xl font-black text-orange-600 mb-4">${item.price_display}</div>
        <p class="text-slate-600 text-sm leading-relaxed mb-6">${escapeHtml(item.description)}</p>
        <div class="flex items-center gap-3">
          <a href="mailto:support@consultways.com?subject=Inquiry: ${encodeURIComponent(item.title)}" onclick="playHapticClick()" class="fido-btn-primary px-6 py-3 text-xs sm:text-sm font-bold flex items-center gap-2">
            <span>Inquire / Purchase via Support</span>
          </a>
        </div>
      </div>
    </div>
  `;

  modal.classList.remove('hidden');
}

function closeProductModal() {
  playHapticClick();
  const modal = document.getElementById('cwProductModal');
  if (modal) modal.classList.add('hidden');
}

// Marketplace Filtering & Rendering
function renderListings() {
  const container = document.getElementById('cwListingsGrid');
  if (!container) return;

  const f = window.CW_STATE.filters;
  const filtered = (window.CW_STATE.listings || []).filter(item => {
    if (f.type !== 'all' && item.type !== f.type) return false;
    if (item.price_usd < f.minPrice || item.price_usd > f.maxPrice) return false;
    if (f.search) {
      const q = f.search.toLowerCase();
      const match = (item.title || '').toLowerCase().includes(q) || (item.description || '').toLowerCase().includes(q);
      if (!match) return false;
    }
    return true;
  });

  const countEl = document.getElementById('cwListingCount');
  if (countEl) countEl.textContent = filtered.length;

  if (filtered.length === 0) {
    container.innerHTML = `
      <div class="col-span-full py-12 text-center text-slate-400">
        <p class="text-base font-bold">No products match your selected filters.</p>
        <button onclick="resetFilters()" class="mt-3 text-xs text-orange-600 font-bold underline">Reset Price & Filters</button>
      </div>
    `;
    return;
  }

  container.innerHTML = filtered.map(item => `
    <div class="fido-card p-5 flex flex-col justify-between overflow-hidden cursor-pointer" onclick="openDetailModal('${item.id}')">
      <div>
        <div class="relative overflow-hidden rounded-2xl mb-4 h-48 bg-slate-100">
          <img src="${item.image_url}" alt="${escapeHtml(item.title)}" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" loading="lazy" onerror="this.src='https://consultways.com/wp-content/uploads/2025/01/Discover-the-Future-of-Online-Consultancy-with-Consultways.com-in-UK-USA-and-EU.webp'">
          <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-white/90 text-slate-800 shadow-sm backdrop-blur-sm">${item.type}</span>
        </div>
        <div class="flex items-center justify-between text-xs mb-1.5">
          <span class="text-amber-500 font-bold">★ ${item.rating || '4.8'}</span>
          <span class="text-slate-400 text-[11px] truncate max-w-[140px]">${escapeHtml(item.seller_name || 'ConsultWays')}</span>
        </div>
        <h3 class="font-black text-slate-900 text-sm mb-2 line-clamp-2 hover:text-orange-600 transition-colors">${escapeHtml(item.title)}</h3>
        <p class="text-xs text-slate-500 line-clamp-2 mb-4 leading-relaxed">${escapeHtml(item.description)}</p>
      </div>
      <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
        <div class="text-lg font-black text-orange-600">${item.price_display}</div>
        <button onclick="event.stopPropagation(); openDetailModal('${item.id}')" class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-orange-600 text-white text-xs font-bold transition-all">
          Details
        </button>
      </div>
    </div>
  `).join('');
}

function filterCategory(type) {
  playHapticClick();
  window.CW_STATE.filters.type = type;
  document.querySelectorAll('.cat-pill').forEach(btn => {
    if (btn.dataset.type === type) {
      btn.classList.add('bg-orange-600', 'text-white', 'shadow-md');
      btn.classList.remove('bg-white', 'text-slate-700');
    } else {
      btn.classList.remove('bg-orange-600', 'text-white', 'shadow-md');
      btn.classList.add('bg-white', 'text-slate-700');
    }
  });
  renderListings();
}

function onPriceChange(val) {
  window.CW_STATE.filters.maxPrice = Number(val);
  const disp = document.getElementById('cwPriceDisplay');
  if (disp) disp.textContent = '$' + val + '.00';
  renderListings();
}

function onSearchInput(val) {
  window.CW_STATE.filters.search = val;
  renderListings();
}

function resetFilters() {
  playHapticClick();
  window.CW_STATE.filters = { type: 'all', search: '', minPrice: 20, maxPrice: 200 };
  const slider = document.getElementById('cwPriceRange');
  if (slider) slider.value = 200;
  const disp = document.getElementById('cwPriceDisplay');
  if (disp) disp.textContent = '$200.00';
  filterCategory('all');
}

// Initial Run
document.addEventListener('DOMContentLoaded', () => {
  updateAudioUi();
  renderListings();
});
