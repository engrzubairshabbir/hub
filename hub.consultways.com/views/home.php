<?php
/**
 * ConsultWays Hub - Home View
 * Subdomain: hub.consultways.com
 */

$listingsFile = DATA_PATH . '/listings.json';
$appsFile = DATA_PATH . '/apps.json';

$allListings = file_exists($listingsFile) ? (json_decode(file_get_contents($listingsFile), true) ?: []) : [];
$allApps = file_exists($appsFile) ? (json_decode(file_get_contents($appsFile), true) ?: []) : [];

$featuredListings = array_slice($allListings, 0, 8);
$featuredApps = array_slice($allApps, 0, 6);
?>

<!-- Pass Data to Frontend JS State -->
<script>
  window.CW_INITIAL_LISTINGS = <?= json_encode($allListings, JSON_UNESCAPED_SLASHES) ?>;
  window.CW_INITIAL_APPS = <?= json_encode($allApps, JSON_UNESCAPED_SLASHES) ?>;
</script>

<!-- Hero Section (Alibaba / Temu Viral Theme) -->
<section class="hero-gradient py-16 md:py-24 border-b border-slate-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    
    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 border border-blue-200 text-blue-700 text-xs font-bold uppercase tracking-wider mb-6 animate-pulse">
      <span class="w-2 h-2 rounded-full bg-blue-600"></span>
      Official Subdomain: hub.consultways.com
    </div>

    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-tight max-w-4xl mx-auto mb-6">
      The Global Ecosystem for <span class="text-blue-600">Apps, Tools</span> & <span class="text-orange-500">Free Listings</span>
    </h1>

    <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto mb-10 leading-relaxed">
      Explore official Google Play Store Android apps, high-performance SaaS utilities, and our viral Alibaba-style multi-vendor marketplace with verified listings from <strong class="text-slate-900">$20.00 to $200.00 USD</strong>.
    </p>

    <!-- Hero CTA Buttons -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 max-w-md mx-auto mb-12">
      <a href="/post-listing" class="w-full sm:w-auto px-8 py-4 rounded-2xl badge-temu text-white font-extrabold text-base shadow-xl hover:brightness-105 transition-transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        <span>Post Free Listing in 10s</span>
      </a>
      <a href="/marketplace" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-white border border-slate-300 text-slate-800 hover:bg-slate-100 font-bold text-base shadow-sm transition-colors">
        Browse Marketplace
      </a>
    </div>

    <!-- Quick Trust Badges -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-3xl mx-auto text-xs text-slate-500 font-medium pt-8 border-t border-slate-200">
      <div class="flex items-center justify-center gap-1.5">
        <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>Zero Listing Fees</span>
      </div>
      <div class="flex items-center justify-center gap-1.5">
        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>AI Auto-Approval</span>
      </div>
      <div class="flex items-center justify-center gap-1.5">
        <svg class="w-4 h-4 text-purple-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>Auto-WebP Cloud &lt;100KB</span>
      </div>
      <div class="flex items-center justify-center gap-1.5">
        <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>$20 – $200 USD Guarantee</span>
      </div>
    </div>

  </div>
</section>

<!-- 3 Viral Pillars of Free Listings -->
<section class="py-16 bg-white border-b border-slate-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-3xl mx-auto mb-12">
      <h2 class="text-xs font-bold uppercase tracking-widest text-blue-600 mb-2">Multi-Vendor Marketplace Pillars</h2>
      <p class="text-3xl font-extrabold text-slate-900">What Can You List & Sell for Free?</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      
      <!-- Pillar 1 -->
      <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 hover-lift">
        <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-2xl mb-6 shadow-sm">
          📦
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-2">1. Physical Products</h3>
        <p class="text-sm text-slate-600 leading-relaxed mb-4">
          Consumer electronics, smart adapters, accessories, apparel, textiles, and home essentials. Sell directly to global buyers with tracking integration.
        </p>
        <span class="text-xs font-semibold text-amber-700 bg-amber-50 px-3 py-1 rounded-full border border-amber-200">
          E-Commerce Merchants
        </span>
      </div>

      <!-- Pillar 2 -->
      <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 hover-lift">
        <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-2xl mb-6 shadow-sm">
          ⚡
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-2">2. Digital Downloads</h3>
        <p class="text-sm text-slate-600 leading-relaxed mb-4">
          Software licenses, developer templates, code scripts, ebooks, graphics, and productivity presets. Instant vault delivery upon purchase.
        </p>
        <span class="text-xs font-semibold text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-200">
          Software & Creators
        </span>
      </div>

      <!-- Pillar 3 -->
      <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 hover-lift">
        <div class="w-14 h-14 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-2xl mb-6 shadow-sm">
          💼
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-2">3. Freelance & Business Services</h3>
        <p class="text-sm text-slate-600 leading-relaxed mb-4">
          Professional SEO audits, digital consultation, software architecture advisory, online tutoring, and corporate strategy support.
        </p>
        <span class="text-xs font-semibold text-purple-700 bg-purple-50 px-3 py-1 rounded-full border border-purple-200">
          Freelancers & Consultants
        </span>
      </div>

    </div>
  </div>
</section>

<!-- Official Play Store Showcase Preview -->
<section class="py-16 bg-slate-50 border-b border-slate-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
      <div>
        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-blue-600 mb-1">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3.609 1.814L13.792 12 3.61 22.186a2.38 2.38 0 01-.61-1.614V3.428c0-.627.228-1.206.61-1.614zm11.602 11.602l2.36 2.36-11.492 6.64 9.132-9zm2.36-2.36l-2.36 2.36-9.132-9 11.492 6.64zm1.442 1.442l3.412 1.972a1.763 1.763 0 010 3.06l-3.412 1.972-2.36-2.36 2.36-2.644z"/></svg>
          Official Play Store Ecosystem
        </div>
        <h2 class="text-3xl font-extrabold text-slate-900">Featured Mobile Apps & SaaS Tools</h2>
      </div>
      <a href="/apps" class="text-blue-600 hover:text-blue-700 font-bold text-sm flex items-center gap-1">
        <span>View All 21 Ecosystem Apps</span>
        <span>→</span>
      </a>
    </div>

    <!-- Apps Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($featuredApps as $app): ?>
        <div class="bg-white rounded-2xl p-6 border border-slate-200 hover-lift flex flex-col justify-between">
          <div>
            <div class="flex items-start gap-4 mb-4">
              <img src="<?= e($app['icon']) ?>" alt="<?= e($app['name']) ?>" class="w-16 h-16 rounded-2xl object-cover bg-slate-100 shadow-sm flex-shrink-0">
              <div class="min-w-0">
                <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded-md bg-blue-50 text-blue-700 mb-1">
                  <?= e($app['category']) ?>
                </span>
                <h4 class="font-bold text-slate-900 text-base leading-tight truncate">
                  <?= e($app['name']) ?>
                </h4>
                <div class="flex items-center gap-2 text-xs text-slate-500 mt-1">
                  <span class="text-amber-500 font-bold">★ <?= e($app['rating']) ?></span>
                  <span>•</span>
                  <span><?= e($app['installs'] ?? '50k+') ?></span>
                </div>
              </div>
            </div>
            <p class="text-slate-600 text-xs line-clamp-2 leading-relaxed mb-4">
              <?= e($app['description']) ?>
            </p>
          </div>

          <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            <button onclick="openAppDetailModal('<?= e($app['id']) ?>')" class="text-xs font-semibold text-slate-600 hover:text-blue-600">
              Read Details
            </button>
            <a href="<?= e($app['play_store_url'] ?? $app['web_url'] ?? '#') ?>" target="_blank" rel="noopener noreferrer" class="px-3.5 py-1.5 rounded-xl bg-slate-900 hover:bg-blue-600 text-white text-xs font-semibold flex items-center gap-1.5 transition-colors">
              <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M3.609 1.814L13.792 12 3.61 22.186a2.38 2.38 0 01-.61-1.614V3.428c0-.627.228-1.206.61-1.614zm11.602 11.602l2.36 2.36-11.492 6.64 9.132-9zm2.36-2.36l-2.36 2.36-9.132-9 11.492 6.64zm1.442 1.442l3.412 1.972a1.763 1.763 0 010 3.06l-3.412 1.972-2.36-2.36 2.36-2.644z"/></svg>
              <span>Install App</span>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Featured Free Listings Feed (from consultways.com with $20 - $200 pricing) -->
<section class="py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
      <div>
        <div class="text-xs font-bold uppercase tracking-wider text-orange-600 mb-1">
          Alibaba / Temu Multi-Vendor Feed
        </div>
        <h2 class="text-3xl font-extrabold text-slate-900">Verified Marketplace Listings</h2>
        <p class="text-slate-500 text-xs mt-1">Pre-enlisted from consultways.com with authentic photos & standard pricing ($20 – $200 USD)</p>
      </div>
      <a href="/marketplace" class="px-5 py-2.5 rounded-xl bg-blue-600 text-white font-bold text-xs hover:bg-blue-700 shadow-md transition-colors">
        Explore All <?= count($allListings) ?> Listings →
      </a>
    </div>

    <!-- Listings Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($featuredListings as $item): ?>
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover-lift flex flex-col justify-between">
          <div class="relative">
            <img src="<?= e($item['image_url']) ?>" alt="<?= e($item['title']) ?>" loading="lazy" class="w-full h-48 object-cover bg-slate-100" onerror="this.src='https://consultways.com/wp-content/uploads/2025/01/Discover-the-Future-of-Online-Consultancy-with-Consultways.com-in-UK-USA-and-EU.webp'">
            <span class="absolute top-3 left-3 px-2 py-0.5 text-[10px] font-bold rounded-full bg-slate-900/80 text-white backdrop-blur-sm">
              <?= e($item['type']) ?>
            </span>
          </div>

          <div class="p-5 flex-1 flex flex-col justify-between">
            <div>
              <div class="flex items-center gap-1.5 mb-1.5">
                <span class="text-amber-500 font-bold text-xs">★ <?= e($item['rating'] ?? '4.8') ?></span>
                <span class="text-slate-400 text-[11px]">(<?= e($item['reviews_count'] ?? '24') ?>)</span>
                <span class="text-slate-300">•</span>
                <span class="text-slate-500 text-[11px] truncate max-w-[120px]"><?= e($item['seller_name'] ?? 'ConsultWays') ?></span>
              </div>
              <h4 class="font-bold text-slate-900 text-sm mb-2 line-clamp-2 hover:text-blue-600 cursor-pointer" onclick="openDetailModal('<?= e($item['id']) ?>')">
                <?= e($item['title']) ?>
              </h4>
              <p class="text-slate-500 text-xs line-clamp-2 mb-4 leading-relaxed">
                <?= e($item['description']) ?>
              </p>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
              <div>
                <div class="text-[10px] text-slate-400 font-medium">Standard Price</div>
                <div class="text-lg font-black text-blue-600"><?= e($item['price_display']) ?></div>
              </div>
              <button onclick="openDetailModal('<?= e($item['id']) ?>')" class="px-3 py-1.5 bg-slate-900 hover:bg-blue-600 text-white text-xs font-semibold rounded-xl transition-colors">
                View & Share
              </button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Viral CTA Strip -->
    <div class="mt-16 p-8 md:p-12 rounded-3xl bg-gradient-to-r from-blue-700 to-indigo-800 text-white text-center shadow-xl">
      <h3 class="text-2xl sm:text-3xl font-black mb-3">Sell Your Products, Software or Freelance Services</h3>
      <p class="text-blue-100 text-sm max-w-xl mx-auto mb-8">
        Join over 1,500 merchants on ConsultWays Hub. Auto-WebP compression, AI title verification, and 1-click WhatsApp customer loops included for free.
      </p>
      <a href="/post-listing" class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl bg-white text-blue-700 hover:bg-blue-50 font-extrabold text-sm shadow-lg transition-transform hover:-translate-y-0.5">
        <span>Open Your Free Store Now</span>
        <span>→</span>
      </a>
    </div>

  </div>
</section>
