<?php
/**
 * ConsultWays Hub - Marketplace View
 * Subdomain: hub.consultways.com
 */

$listingsFile = DATA_PATH . '/listings.json';
$allListings = file_exists($listingsFile) ? (json_decode(file_get_contents($listingsFile), true) ?: []) : [];

$initialCategory = $_GET['cat'] ?? 'All';
?>

<script>
  window.CW_INITIAL_LISTINGS = <?= json_encode($allListings, JSON_UNESCAPED_SLASHES) ?>;
  window.CW_ACTIVE_CAT = "<?= e($initialCategory) ?>";
</script>

<div class="bg-white border-b border-slate-200 py-10">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-50 text-orange-700 text-xs font-bold uppercase tracking-wider mb-2">
          Alibaba / Temu Free Listing Feed
        </div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">
          Multi-Vendor Free Marketplace
        </h1>
        <p class="text-xs text-slate-500 mt-1">
          All listings guaranteed verified between <strong class="text-slate-800">$20.00 – $200.00 USD</strong>. Zero transaction listing fee.
        </p>
      </div>

      <div class="flex items-center gap-3">
        <a href="/post-listing" class="px-5 py-3 rounded-xl badge-temu text-white text-xs font-bold shadow-md hover:brightness-105 flex items-center gap-2 transition-transform hover:-translate-y-0.5">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
          <span>Post Your Free Listing</span>
        </a>
      </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
      
      <!-- Category Tabs -->
      <div class="flex items-center gap-2 overflow-x-auto pb-2 lg:pb-0">
        <?php 
          $cats = ['All', 'Physical Products', 'Digital Downloads', 'Freelance & Business Services'];
          foreach ($cats as $c):
            $isActive = ($c === $initialCategory);
        ?>
          <button data-cat="<?= e($c) ?>" class="cat-filter-btn px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all <?= $isActive ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-100' ?>">
            <?= e($c) ?>
          </button>
        <?php endforeach; ?>
      </div>

      <!-- Keyword Search & Price Slider -->
      <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
        
        <!-- Search Input -->
        <div class="relative flex-1 sm:w-64">
          <input type="text" id="globalSearchInput" placeholder="Search 49+ verified items..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-blue-500 transition-colors">
          <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>

        <!-- Price Range Slider ($20 to $200 USD) -->
        <div class="bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-xl flex items-center gap-2 text-xs">
          <span class="text-slate-500 font-medium whitespace-nowrap">Max:</span>
          <input type="range" id="priceRangeSlider" min="20" max="200" step="5" value="200" class="w-24 accent-blue-600 cursor-pointer">
          <span id="priceDisplayVal" class="font-extrabold text-blue-600 whitespace-nowrap">$200</span>
        </div>

      </div>

    </div>
  </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
  <div class="flex items-center justify-between mb-6">
    <div id="listingCountBadge" class="text-xs font-bold text-slate-500 uppercase tracking-wider">
      <?= count($allListings) ?> Listings Available
    </div>
    <div class="text-xs text-slate-400">
      Auto-Optimized WebP & 1-Click WhatsApp Sharing
    </div>
  </div>

  <!-- Listings Responsive Grid -->
  <div id="marketplaceGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Populated via JavaScript for instantaneous reactive filtering -->
  </div>
</div>

<script>
  // Trigger initial render with passed category if any
  document.addEventListener('DOMContentLoaded', () => {
    if (window.CW_ACTIVE_CAT && window.CW_ACTIVE_CAT !== 'All') {
      window.CW_STATE.activeCategory = window.CW_ACTIVE_CAT;
    }
    renderFilteredGrid();
  });
</script>
