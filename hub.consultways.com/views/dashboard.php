<?php
/**
 * ConsultWays Hub - Buyer & Seller Command Center
 * Subdomain: hub.consultways.com/dashboard
 */

$ordersFile = DATA_PATH . '/orders.json';
$orders = file_exists($ordersFile) ? (json_decode(file_get_contents($ordersFile), true) ?: []) : [];

$listingsFile = DATA_PATH . '/listings.json';
$allListings = file_exists($listingsFile) ? (json_decode(file_get_contents($listingsFile), true) ?: []) : [];

$userStoreListings = array_slice($allListings, 0, 5);
?>

<div class="bg-white border-b border-slate-200 py-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-black text-xl shadow-md">
          U
        </div>
        <div>
          <div class="flex items-center gap-2">
            <h1 class="text-2xl font-black text-slate-900">User Command Center</h1>
            <span class="badge-verified px-2 py-0.5 text-[11px] font-bold rounded-full">Active Account</span>
          </div>
          <p class="text-xs text-slate-500 mt-0.5">Alibaba/Temu Style Unified Buyer & Seller Center • hub.consultways.com</p>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <a href="/post-listing" class="px-4 py-2.5 rounded-xl badge-temu text-white text-xs font-bold shadow-md hover:brightness-105 flex items-center gap-1.5">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
          <span>Create New Listing</span>
        </a>
      </div>
    </div>
  </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Sidebar Navigation -->
    <div class="lg:col-span-3">
      <div class="bg-white rounded-3xl p-4 border border-slate-200 shadow-sm sticky top-28 space-y-1">
        
        <a href="#orders" data-target="dashOrdersSection" class="dash-nav-tab flex items-center justify-between px-4 py-3 rounded-2xl bg-blue-600 text-white font-bold text-xs shadow transition-all">
          <div class="flex items-center gap-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span>My Orders & Tracking</span>
          </div>
          <span class="px-2 py-0.5 rounded-full bg-blue-500 text-[10px]"><?= count($orders) ?></span>
        </a>

        <a href="#vault" data-target="dashVaultSection" class="dash-nav-tab flex items-center justify-between px-4 py-3 rounded-2xl text-slate-600 font-bold text-xs hover:bg-slate-100 transition-all">
          <div class="flex items-center gap-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            <span>Digital Downloads Vault</span>
          </div>
          <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px]">1 Active</span>
        </a>

        <a href="#listings" data-target="dashListingsSection" class="dash-nav-tab flex items-center justify-between px-4 py-3 rounded-2xl text-slate-600 font-bold text-xs hover:bg-slate-100 transition-all">
          <div class="flex items-center gap-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <span>My Free Store Manager</span>
          </div>
          <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px]">5 Stores</span>
        </a>

        <a href="#messages" data-target="dashMessagesSection" class="dash-nav-tab flex items-center justify-between px-4 py-3 rounded-2xl text-slate-600 font-bold text-xs hover:bg-slate-100 transition-all">
          <div class="flex items-center gap-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            <span>Buyer-Seller Messages</span>
          </div>
          <span class="w-2 h-2 rounded-full bg-green-500"></span>
        </a>

        <a href="#wishlist" data-target="dashWishlistSection" class="dash-nav-tab flex items-center justify-between px-4 py-3 rounded-2xl text-slate-600 font-bold text-xs hover:bg-slate-100 transition-all">
          <div class="flex items-center gap-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            <span>Saved Wishlist</span>
          </div>
        </a>

        <a href="#disputes" data-target="dashDisputesSection" class="dash-nav-tab flex items-center justify-between px-4 py-3 rounded-2xl text-slate-600 font-bold text-xs hover:bg-slate-100 transition-all">
          <div class="flex items-center gap-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span>Disputes & Refunds</span>
          </div>
          <span class="text-[10px] text-emerald-600 font-semibold">0 Active</span>
        </a>

      </div>
    </div>

    <!-- Content Sections -->
    <div class="lg:col-span-9">

      <!-- Section 1: My Orders & Live Tracking -->
      <div id="dashOrdersSection" class="dash-content-section space-y-6">
        <div class="bg-white rounded-3xl p-6 border border-slate-200">
          <h2 class="text-xl font-black text-slate-900 mb-1">My Orders & Live Carrier Tracking</h2>
          <p class="text-xs text-slate-500 mb-6">Real-time status updates and carrier tracking for all items purchased on ConsultWays Hub</p>

          <div class="space-y-6">
            <?php foreach ($orders as $order): ?>
              <div class="border border-slate-200 rounded-2xl p-6 bg-slate-50/50">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-200 mb-4 gap-2">
                  <div>
                    <span class="text-xs font-bold text-slate-500">Order ID:</span>
                    <strong class="text-slate-900 font-mono text-sm ml-1"><?= e($order['order_id']) ?></strong>
                    <span class="text-slate-300 mx-2">•</span>
                    <span class="text-xs text-slate-400"><?= e($order['date']) ?></span>
                  </div>
                  <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 text-xs font-bold rounded-full <?= $order['status'] === 'Delivered' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' ?>">
                      ● <?= e($order['status']) ?>
                    </span>
                    <span class="text-sm font-black text-slate-900 ml-2">$<?= number_format($order['total'], 2) ?> USD</span>
                  </div>
                </div>

                <!-- Item Details -->
                <div class="mb-6 space-y-2">
                  <?php foreach ($order['items'] as $item): ?>
                    <div class="flex items-center justify-between text-xs">
                      <span class="font-bold text-slate-800"><?= e($item['title']) ?> (x<?= $item['quantity'] ?>)</span>
                      <span class="text-slate-600 font-medium">$<?= number_format($item['price'], 2) ?></span>
                    </div>
                  <?php endforeach; ?>
                </div>

                <!-- Carrier Tracking Stepper -->
                <div class="pt-4 border-t border-slate-200">
                  <div class="flex items-center justify-between text-xs mb-3">
                    <span class="text-slate-500 font-medium">Carrier: <strong class="text-slate-800"><?= e($order['carrier'] ?? 'Direct Digital Delivery') ?></strong></span>
                    <span class="font-mono text-blue-600 font-bold"><?= e($order['tracking_number'] ?? 'N/A') ?></span>
                  </div>

                  <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <?php foreach ($order['steps'] as $step): ?>
                      <div class="p-3 rounded-xl border <?= $step['completed'] ? 'border-emerald-200 bg-emerald-50/50' : 'border-slate-200 bg-white' ?>">
                        <div class="flex items-center gap-1.5 mb-1">
                          <?php if ($step['completed']): ?>
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                          <?php else: ?>
                            <span class="w-3.5 h-3.5 rounded-full border-2 border-slate-300"></span>
                          <?php endif; ?>
                          <span class="text-xs font-bold <?= $step['completed'] ? 'text-emerald-900' : 'text-slate-500' ?>"><?= e($step['label']) ?></span>
                        </div>
                        <span class="text-[10px] text-slate-400 block"><?= e($step['date']) ?></span>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>

              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Section 2: Digital Downloads Vault -->
      <div id="dashVaultSection" class="dash-content-section space-y-6 hidden">
        <div class="bg-white rounded-3xl p-6 border border-slate-200">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h2 class="text-xl font-black text-slate-900 mb-1">Digital Downloads Vault</h2>
              <p class="text-xs text-slate-500">Secure, encrypted access to purchased desktop software, templates & license keys</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">1 Item Stored</span>
          </div>

          <div class="border border-slate-200 rounded-2xl p-6 bg-slate-50">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <div>
                <span class="inline-block px-2.5 py-0.5 rounded-md bg-blue-100 text-blue-800 text-[10px] font-bold mb-1">Desktop License</span>
                <h3 class="font-bold text-slate-900 text-base">Digital Scrolling Bar 360 Pro / Flash Pro</h3>
                <p class="text-xs text-slate-500 mt-1">Multi-monitor stock, crypto & news ticker license (Lifetime Version)</p>
              </div>
              <button onclick="showToast('Starting secure vault download...', 'success')" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Download Installer (.exe / .dmg)</span>
              </button>
            </div>

            <!-- License Key Reveal -->
            <div class="mt-6 pt-4 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
              <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-600">Product Key:</span>
                <code id="vaultKey1" data-full-key="CW-DSB360-PRO-9844-2026-ACTIVE" data-masked="true" class="bg-slate-200 text-slate-800 px-3 py-1 rounded-lg text-xs font-mono font-bold">
                  CW-DSB360-••••-••••-ACTIVE
                </code>
              </div>
              <button onclick="revealKey('vaultKey1')" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                Reveal & Copy Key
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Section 3: My Free Store Manager -->
      <div id="dashListingsSection" class="dash-content-section space-y-6 hidden">
        <div class="bg-white rounded-3xl p-6 border border-slate-200">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
            <div>
              <h2 class="text-xl font-black text-slate-900 mb-1">My Free Listings & Store Manager</h2>
              <p class="text-xs text-slate-500">Manage your active products, edit prices ($20 - $200 USD), and review AI moderation scores</p>
            </div>
            <a href="/post-listing" class="px-4 py-2 rounded-xl badge-temu text-white text-xs font-bold shadow">
              + New Free Listing
            </a>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead>
                <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider">
                  <th class="py-3 px-4">Item</th>
                  <th class="py-3 px-4">Type</th>
                  <th class="py-3 px-4">Price</th>
                  <th class="py-3 px-4">Status</th>
                  <th class="py-3 px-4 text-right">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <?php foreach ($userStoreListings as $listing): ?>
                  <tr class="hover:bg-slate-50">
                    <td class="py-3 px-4 flex items-center gap-3">
                      <img src="<?= e($listing['image_url']) ?>" alt="" class="w-10 h-10 rounded-lg object-cover bg-slate-100">
                      <div>
                        <div class="font-bold text-slate-900 line-clamp-1 max-w-[200px]"><?= e($listing['title']) ?></div>
                        <span class="text-[10px] text-slate-400">Created <?= e($listing['created_at']) ?></span>
                      </div>
                    </td>
                    <td class="py-3 px-4 text-slate-600 font-medium"><?= e($listing['type']) ?></td>
                    <td class="py-3 px-4 font-bold text-blue-600"><?= e($listing['price_display']) ?></td>
                    <td class="py-3 px-4">
                      <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800">
                        Active & Indexed
                      </span>
                    </td>
                    <td class="py-3 px-4 text-right">
                      <button onclick="openDetailModal('<?= e($listing['id']) ?>')" class="text-blue-600 font-bold hover:underline mr-2">Preview</button>
                      <button onclick="showToast('Listing updated successfully', 'success')" class="text-slate-500 font-semibold hover:text-slate-800">Edit</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Section 4: Direct Buyer-Seller Messages -->
      <div id="dashMessagesSection" class="dash-content-section space-y-6 hidden">
        <div class="bg-white rounded-3xl p-6 border border-slate-200 flex flex-col h-[520px]">
          <div class="pb-4 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 font-bold flex items-center justify-center">
                CS
              </div>
              <div>
                <h3 class="font-bold text-slate-900 text-sm">ConsultWays Verified Store Support</h3>
                <span class="text-[10px] text-emerald-600 flex items-center gap-1 font-semibold">
                  <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Online • Instant Inquiries
                </span>
              </div>
            </div>
            <span class="text-xs text-slate-400">Order #CW-ORD-98214</span>
          </div>

          <!-- Chat Messages Body -->
          <div id="chatMessagesContainer" class="flex-1 overflow-y-auto py-4 space-y-3">
            <div class="flex justify-start">
              <div class="bg-slate-100 text-slate-800 rounded-2xl rounded-tl-none py-2.5 px-4 max-w-[80%] text-sm border border-slate-200">
                <p class="font-semibold text-xs text-blue-600 mb-0.5">Verified Seller</p>
                <p>Hello! Your order for the Combined Switch Socket has been verified and handed to DHL Express. How can we assist you today?</p>
                <span class="text-[10px] text-slate-400 block mt-1">10:32 AM</span>
              </div>
            </div>
          </div>

          <!-- Chat Input -->
          <div class="pt-4 border-t border-slate-200 flex items-center gap-2">
            <input type="text" id="chatMsgInput" placeholder="Write a message to the seller..." class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-blue-500" onkeydown="if(event.key === 'Enter') sendSellerMessage()">
            <button onclick="sendSellerMessage()" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow transition-colors">
              Send
            </button>
          </div>
        </div>
      </div>

      <!-- Section 5: Saved Wishlist -->
      <div id="dashWishlistSection" class="dash-content-section space-y-6 hidden">
        <div class="bg-white rounded-3xl p-6 border border-slate-200">
          <h2 class="text-xl font-black text-slate-900 mb-1">My Saved Wishlist</h2>
          <p class="text-xs text-slate-500 mb-6">Saved items from ConsultWays Hub with price tracking</p>

          <div class="p-8 text-center bg-slate-50 rounded-2xl border border-slate-200">
            <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            <h4 class="font-bold text-slate-800 text-sm mb-1">Your wishlist is active!</h4>
            <p class="text-xs text-slate-500 mb-4">Click the heart icon on any marketplace item to save it for later.</p>
            <a href="/marketplace" class="px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700">
              Browse Marketplace Items
            </a>
          </div>
        </div>
      </div>

      <!-- Section 6: Disputes & Refunds -->
      <div id="dashDisputesSection" class="dash-content-section space-y-6 hidden">
        <div class="bg-white rounded-3xl p-6 border border-slate-200">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h2 class="text-xl font-black text-slate-900 mb-1">Disputes & Refund Center</h2>
              <p class="text-xs text-slate-500">100% Buyer Protection backed by ConsultWays dispute resolution team</p>
            </div>
            <button onclick="showToast('Dispute ticket form opened', 'info')" class="px-3.5 py-1.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50">
              Open New Dispute
            </button>
          </div>

          <div class="p-6 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-4">
            <svg class="w-8 h-8 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <div>
              <h4 class="font-bold text-emerald-900 text-sm">Account in Excellent Standing</h4>
              <p class="text-xs text-emerald-700 mt-0.5">You have 0 open disputes or pending chargebacks. All payments are verified under our Global Commerce Policy.</p>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
</div>
