<?php
/**
 * ConsultWays Hub - Play Store Apps & SaaS Ecosystem View
 * Subdomain: hub.consultways.com
 */

$appsFile = DATA_PATH . '/apps.json';
$allApps = file_exists($appsFile) ? (json_decode(file_get_contents($appsFile), true) ?: []) : [];

$consultWaysApps = array_filter($allApps, fn($a) => ($a['dev_id'] ?? '') === PLAYSTORE_DEV_CONSULTWAYS);
$twinklingApps = array_filter($allApps, fn($a) => ($a['dev_id'] ?? '') === PLAYSTORE_DEV_TWINKLINGBYTES);
$saasTools = array_filter($allApps, fn($a) => empty($a['dev_id']));
?>

<script>
  window.CW_INITIAL_APPS = <?= json_encode($allApps, JSON_UNESCAPED_SLASHES) ?>;
</script>

<div class="bg-white border-b border-slate-200 py-12">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-wider mb-4">
        Official Store Directory
      </div>
      <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-4">
        Official Play Store Apps & SaaS Ecosystem
      </h1>
      <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
        Discover verified production apps by <strong class="text-slate-900">ConsultWays Limited</strong> and <strong class="text-slate-900">Twinkling Bytes Innovations</strong>. All links direct securely to the official Google Play Store or verified platforms (strictly no direct APK hosting).
      </p>
    </div>
  </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

  <!-- Section 1: ConsultWays Play Store Apps -->
  <div class="mb-16">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-200 mb-8 gap-4">
      <div>
        <div class="flex items-center gap-2">
          <h2 class="text-2xl font-black text-slate-900">ConsultWays Play Store Apps</h2>
          <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
            dev?id=<?= e(PLAYSTORE_DEV_CONSULTWAYS) ?>
          </span>
        </div>
        <p class="text-xs text-slate-500 mt-1">Utility, education, enterprise and fashion apps published under ConsultWays developer account</p>
      </div>
      <a href="https://play.google.com/store/apps/developer?id=<?= e(PLAYSTORE_DEV_CONSULTWAYS) ?>" target="_blank" rel="noopener noreferrer" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1">
        <span>View Full Play Console Profile</span>
        <span>→</span>
      </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($consultWaysApps as $app): ?>
        <div class="bg-white rounded-2xl p-6 border border-slate-200 hover-lift flex flex-col justify-between">
          <div>
            <div class="flex items-start gap-4 mb-4">
              <img src="<?= e($app['icon']) ?>" alt="<?= e($app['name']) ?>" class="w-16 h-16 rounded-2xl object-cover bg-slate-100 shadow-sm flex-shrink-0">
              <div class="min-w-0">
                <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded-md bg-blue-50 text-blue-700 mb-1">
                  <?= e($app['category']) ?>
                </span>
                <h3 class="font-bold text-slate-900 text-base leading-tight truncate">
                  <?= e($app['name']) ?>
                </h3>
                <div class="flex items-center gap-2 text-xs text-slate-500 mt-1">
                  <span class="text-amber-500 font-bold">★ <?= e($app['rating']) ?></span>
                  <span>•</span>
                  <span><?= e($app['installs'] ?? '25k+') ?></span>
                </div>
              </div>
            </div>
            <p class="text-slate-600 text-xs line-clamp-2 leading-relaxed mb-4">
              <?= e($app['description']) ?>
            </p>
          </div>

          <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            <button onclick="openAppDetailModal('<?= e($app['id']) ?>')" class="text-xs font-semibold text-slate-600 hover:text-blue-600">
              Tech Specs
            </button>
            <a href="<?= e($app['play_store_url'] ?? '#') ?>" target="_blank" rel="noopener noreferrer" class="px-3.5 py-1.5 rounded-xl bg-slate-900 hover:bg-blue-600 text-white text-xs font-semibold flex items-center gap-1.5 transition-colors">
              <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M3.609 1.814L13.792 12 3.61 22.186a2.38 2.38 0 01-.61-1.614V3.428c0-.627.228-1.206.61-1.614zm11.602 11.602l2.36 2.36-11.492 6.64 9.132-9zm2.36-2.36l-2.36 2.36-9.132-9 11.492 6.64zm1.442 1.442l3.412 1.972a1.763 1.763 0 010 3.06l-3.412 1.972-2.36-2.36 2.36-2.644z"/></svg>
              <span>Install on Play Store</span>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Section 2: Twinkling Bytes Innovations Play Store Apps -->
  <div class="mb-16">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-200 mb-8 gap-4">
      <div>
        <div class="flex items-center gap-2">
          <h2 class="text-2xl font-black text-slate-900">Twinkling Bytes Innovations</h2>
          <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800">
            dev?id=<?= e(PLAYSTORE_DEV_TWINKLINGBYTES) ?>
          </span>
        </div>
        <p class="text-xs text-slate-500 mt-1">Arcade games, clipboard tools, calm audio and lifestyle applications</p>
      </div>
      <a href="https://play.google.com/store/apps/developer?id=<?= e(PLAYSTORE_DEV_TWINKLINGBYTES) ?>" target="_blank" rel="noopener noreferrer" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
        <span>View Full Play Console Profile</span>
        <span>→</span>
      </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($twinklingApps as $app): ?>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 hover-lift flex flex-col justify-between">
          <div>
            <img src="<?= e($app['icon']) ?>" alt="<?= e($app['name']) ?>" class="w-14 h-14 rounded-2xl object-cover bg-slate-100 shadow-sm mb-3">
            <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded-md bg-indigo-50 text-indigo-700 mb-1">
              <?= e($app['category']) ?>
            </span>
            <h3 class="font-bold text-slate-900 text-sm leading-tight truncate mb-1">
              <?= e($app['name']) ?>
            </h3>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-2">
              <span class="text-amber-500 font-bold">★ <?= e($app['rating']) ?></span>
              <span>(<?= e($app['reviews_count']) ?>)</span>
            </div>
            <p class="text-slate-600 text-xs line-clamp-2 leading-relaxed mb-4">
              <?= e($app['description']) ?>
            </p>
          </div>

          <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
            <button onclick="openAppDetailModal('<?= e($app['id']) ?>')" class="text-xs font-semibold text-slate-600 hover:text-indigo-600">
              Details
            </button>
            <a href="<?= e($app['play_store_url'] ?? '#') ?>" target="_blank" rel="noopener noreferrer" class="px-3 py-1 rounded-xl bg-slate-900 hover:bg-indigo-600 text-white text-xs font-semibold flex items-center gap-1 transition-colors">
              <span>Install</span>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Section 3: Web, Desktop & Shopify Tools -->
  <div id="tools" class="mb-16">
    <div class="pb-4 border-b border-slate-200 mb-8">
      <h2 class="text-2xl font-black text-slate-900">Web, Desktop & Shopify Tools</h2>
      <p class="text-xs text-slate-500 mt-1">Specialized merchant software, Windows/macOS desktop tickers and Chrome extensions</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php foreach ($saasTools as $tool): ?>
        <div class="bg-white rounded-3xl p-8 border border-slate-200 hover-lift flex flex-col justify-between">
          <div>
            <div class="flex items-center justify-between mb-6">
              <img src="<?= e($tool['icon']) ?>" alt="<?= e($tool['name']) ?>" class="w-16 h-16 rounded-2xl object-cover bg-slate-100 shadow-sm">
              <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                <?= e($tool['platform']) ?>
              </span>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2"><?= e($tool['name']) ?></h3>
            <p class="text-xs text-slate-500 font-medium mb-4">By <?= e($tool['developer']) ?></p>
            <p class="text-sm text-slate-600 leading-relaxed mb-6"><?= e($tool['description']) ?></p>

            <ul class="space-y-2 mb-6">
              <?php foreach (($tool['features'] ?? []) as $feat): ?>
                <li class="flex items-center gap-2 text-xs text-slate-600">
                  <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                  <span><?= e($feat) ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>

          <div class="pt-6 border-t border-slate-100">
            <button onclick="openAppDetailModal('<?= e($tool['id']) ?>')" class="w-full py-3 rounded-xl bg-slate-900 hover:bg-blue-600 text-white font-bold text-sm text-center block transition-colors">
              Access Tool Suite
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

</div>
