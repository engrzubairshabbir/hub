<?php
/**
 * ConsultWays Hub - Footer Component
 * Subdomain: hub.consultways.com
 */
$currentView = $currentView ?? 'home';
?>
  </main>

  <!-- Google AdSense Responsive Slot (Category & Content Feeds Only) -->
  <?php if (ADSENSE_ENABLED && !in_array($currentView, ['dashboard', 'checkout', 'post-listing'])): ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-8">
      <div class="adsense-wrapper">
        <div class="adsense-label">Advertisement</div>
        <!-- Responsive Ad Unit -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="<?= e(ADSENSE_CLIENT_ID) ?>"
             data-ad-slot="9842103551"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
        <script>
             (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
      </div>
    </div>
  <?php endif; ?>

  <!-- Global Brand Footer -->
  <footer class="bg-slate-900 text-slate-400 pt-16 pb-12 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-slate-800">
        
        <!-- Col 1: Brand Info -->
        <div class="lg:col-span-2">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-lg">
              CW
            </div>
            <span class="font-black text-xl text-white tracking-tight">ConsultWays Hub</span>
          </div>
          <p class="text-sm text-slate-400 mb-6 leading-relaxed max-w-sm">
            ConsultWays — Global Software Studio & Digital Commerce Hub. Empowering millions through Android Play Store apps, high-speed desktop utilities, and viral multi-vendor free listings.
          </p>
          <div class="flex items-center gap-2 text-xs text-slate-400">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span>Official Support:</span>
            <a href="mailto:support@consultways.com" class="text-white hover:text-blue-400 font-semibold underline">support@consultways.com</a>
          </div>
          <div class="mt-2 text-xs text-slate-500">
            <span>Root Domain: </span>
            <a href="https://consultways.com" target="_blank" rel="noopener" class="text-slate-400 hover:text-white underline">consultways.com</a>
          </div>
        </div>

        <!-- Col 2: Play Store Showcase -->
        <div>
          <h4 class="text-white text-xs font-bold uppercase tracking-wider mb-4">Ecosystem Apps</h4>
          <ul class="space-y-2 text-xs">
            <li><a href="/apps" class="hover:text-white transition-colors">DocuSnap AI: Bill & Rx Finder</a></li>
            <li><a href="/apps" class="hover:text-white transition-colors">StudyPal — Parental Control</a></li>
            <li><a href="/apps" class="hover:text-white transition-colors">PocketDev AI</a></li>
            <li><a href="/apps" class="hover:text-white transition-colors">AlarmReminder Pro</a></li>
            <li><a href="/apps" class="hover:text-white transition-colors">TunnelSurf: Neon Bike Racing</a></li>
            <li><a href="/apps" class="hover:text-white transition-colors">Clipboard Saver Pro</a></li>
            <li><a href="/apps" class="hover:text-white transition-colors">NeuroSilence: Calm Tech</a></li>
          </ul>
        </div>

        <!-- Col 3: Free Marketplace -->
        <div>
          <h4 class="text-white text-xs font-bold uppercase tracking-wider mb-4">Free Marketplace</h4>
          <ul class="space-y-2 text-xs">
            <li><a href="/marketplace?cat=Physical+Products" class="hover:text-white transition-colors">Physical Products</a></li>
            <li><a href="/marketplace?cat=Digital+Downloads" class="hover:text-white transition-colors">Digital Downloads & Software</a></li>
            <li><a href="/marketplace?cat=Freelance+%26+Business+Services" class="hover:text-white transition-colors">Freelance & Business Services</a></li>
            <li><a href="/post-listing" class="text-amber-400 font-bold hover:text-amber-300">Open Free Store in 10s</a></li>
            <li><a href="/dashboard" class="hover:text-white transition-colors">Live Order Tracking</a></li>
            <li><a href="/dashboard" class="hover:text-white transition-colors">Digital Downloads Vault</a></li>
          </ul>
        </div>

        <!-- Col 4: Compliance & Legal -->
        <div>
          <h4 class="text-white text-xs font-bold uppercase tracking-wider mb-4">Compliance & Policies</h4>
          <ul class="space-y-2 text-xs">
            <li><a href="/privacy" class="hover:text-white transition-colors">Privacy Policy</a></li>
            <li><a href="/terms" class="hover:text-white transition-colors">Terms of Service</a></li>
            <li><a href="/refund" class="hover:text-white transition-colors">Refund & Return Policy</a></li>
            <li><a href="/shipping" class="hover:text-white transition-colors">Shipping & Digital Delivery</a></li>
            <li><a href="/ads.txt" target="_blank" class="hover:text-white transition-colors">ads.txt Verification</a></li>
          </ul>
        </div>

      </div>

      <!-- Bottom Credits & Compliance Note -->
      <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
        <div>
          © <?= date('Y') ?> ConsultWays Limited. All rights reserved. Registered software publisher & multi-vendor commerce hub.
        </div>
        <div class="flex items-center gap-3">
          <span class="px-2.5 py-1 rounded-md bg-slate-800 text-slate-300 font-medium">Standard Pricing: $20.00 – $200.00 USD</span>
          <span class="px-2.5 py-1 rounded-md bg-slate-800 text-slate-300 font-medium">Auto-WebP Cloud Compression</span>
        </div>
      </div>
    </div>
  </footer>

  <!-- Universal Interactive Detail Modal -->
  <div id="cwDetailModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden modal-overlay">
    <div id="cwModalBackdrop" class="absolute inset-0"></div>
    <div class="relative bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl modal-enter z-10 max-h-[90vh] overflow-y-auto">
      <button id="cwModalCloseBtn" class="absolute top-5 right-5 p-2 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-900 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
      <div id="cwModalContent">
        <!-- Injected via JavaScript -->
      </div>
    </div>
  </div>

  <!-- Toast Notifications Container -->
  <div id="cwToastContainer" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 pointer-events-none"></div>

  <!-- Core Reactive Scripts -->
  <script src="/assets/js/app.js"></script>
</body>
</html>
