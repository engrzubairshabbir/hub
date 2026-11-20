<?php
/**
 * ConsultWays Hub - Post Free Listing View
 * Subdomain: hub.consultways.com
 */
?>

<div class="bg-white border-b border-slate-200 py-12">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full badge-temu text-white text-xs font-bold uppercase tracking-wider mb-4">
      🔥 100% Free Seller Onboarding
    </div>
    <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-3">
      Post Free Listing / Open Your Free Store
    </h1>
    <p class="text-slate-600 text-sm max-w-xl mx-auto leading-relaxed">
      List physical goods, digital software downloads, or freelance consultancy in under 10 seconds. Enjoy <strong class="text-slate-800">Auto-WebP image compression (&lt;100 KB)</strong> and real-time AI title verification.
    </p>
  </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
  <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-xl">
    
    <!-- AI Moderation Status Alert (Injected by JS) -->
    <div id="aiStatusAlert" class="mb-8 hidden"></div>

    <form id="freeListingForm" class="space-y-6">
      
      <!-- Field 1: Listing Category & Type -->
      <div>
        <label for="listingType" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
          Listing Pillar & Category *
        </label>
        <select id="listingType" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:border-blue-500">
          <option value="Physical Products">📦 Physical Products (E-Commerce Merchandise, Accessories, Tech)</option>
          <option value="Digital Downloads">⚡ Digital Downloads (Software, Code Scripts, Templates, Ebooks)</option>
          <option value="Freelance & Business Services">💼 Freelance & Business Services (Advisory, SEO, Tutoring)</option>
        </select>
      </div>

      <!-- Field 2: Listing Title -->
      <div>
        <label for="listingTitle" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
          Product / Service Title *
        </label>
        <input type="text" id="listingTitle" required placeholder="e.g. 8-in-1 Type-C Hub Fast Adapter or Professional SEO Audit" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-blue-500">
        <span class="text-[11px] text-slate-400 mt-1 block">Titles are automatically screened by Gemini AI for copyright and spam prevention.</span>
      </div>

      <!-- Field 3: Price in USD ($20 - $200 USD strict standard) -->
      <div>
        <div class="flex items-center justify-between mb-2">
          <label for="listingPrice" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
            Price (USD) *
          </label>
          <span class="text-xs text-blue-600 font-bold">Standard Hub Range: $20.00 – $200.00 USD</span>
        </div>
        <div class="relative">
          <span class="absolute left-4 top-3 text-slate-400 font-bold">$</span>
          <input type="number" id="listingPrice" required min="20" max="200" step="0.5" value="49.99" class="w-full pl-8 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-extrabold text-blue-600 focus:bg-white focus:outline-none focus:border-blue-500">
        </div>
      </div>

      <!-- Field 4: Auto-WebP Image Optimizer -->
      <div>
        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
          Product Photo (Auto-WebP Cloud Compressor) *
        </label>
        
        <div id="listingDropzone" class="dropzone-box p-8 text-center cursor-pointer">
          <input type="file" id="listingImageInput" accept="image/*" class="hidden">
          <div onclick="document.getElementById('listingImageInput').click()">
            <svg class="w-10 h-10 mx-auto text-blue-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <div class="text-sm font-bold text-slate-800 mb-1">Click to upload photo or drag & drop</div>
            <p class="text-xs text-slate-400">Client-side canvas auto-compresses to WebP (&lt;100 KB, max 1000px) so cPanel storage stays clean</p>
          </div>
        </div>

        <!-- Compressed Preview Box -->
        <div id="imagePreviewContainer" class="mt-4 p-4 bg-slate-50 border border-slate-200 rounded-2xl hidden flex items-center gap-4">
          <img id="imageCompressedPreview" src="" alt="Compressed WebP" class="w-20 h-20 rounded-xl object-cover bg-slate-200">
          <div>
            <div id="compressionSizeBadge" class="text-xs font-semibold mb-1"></div>
            <div class="text-[11px] text-slate-500">Ready for instant AI verification and CDN delivery.</div>
          </div>
        </div>
      </div>

      <!-- Field 5: Seller Info -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="sellerName" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Store / Seller Name *</label>
          <input type="text" id="sellerName" required placeholder="e.g. Apex Global Tech" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-blue-500">
        </div>
        <div>
          <label for="sellerEmail" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Seller Email (For Order Alerts) *</label>
          <input type="email" id="sellerEmail" required placeholder="orders@yourstore.com" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-blue-500">
        </div>
      </div>

      <!-- Field 6: Description -->
      <div>
        <label for="listingDesc" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
          Detailed Description & Specifications *
        </label>
        <textarea id="listingDesc" rows="4" required placeholder="Detail key specifications, warranty, included accessories, or digital download details..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-blue-500"></textarea>
      </div>

      <!-- Submit CTA -->
      <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="text-xs text-slate-500 text-center sm:text-left">
          By publishing, you agree to ConsultWays Hub compliance policies.
        </div>
        <button type="submit" id="submitListingBtn" class="w-full sm:w-auto px-8 py-3.5 rounded-xl badge-temu text-white font-extrabold text-sm shadow-lg hover:brightness-105 transition-all">
          Publish Free Listing Now
        </button>
      </div>

    </form>
  </div>
</div>
