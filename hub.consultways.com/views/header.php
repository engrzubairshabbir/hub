<?php
/**
 * ConsultWays Hub - Header Component
 * Subdomain: hub.consultways.com
 */

$currentView = $currentView ?? 'home';
$pageTitle = $pageTitle ?? 'ConsultWays Hub — Apps, Tools & Viral Marketplace';
$pageDesc = $pageDesc ?? 'Official Play Store apps, SaaS productivity tools, and free multi-vendor marketplace on hub.consultways.com. Certified global digital commerce.';
$ogImage = $ogImage ?? 'https://consultways.com/wp-content/uploads/2025/01/Discover-the-Future-of-Online-Consultancy-with-Consultways.com-in-UK-USA-and-EU.webp';
$canonicalUrl = APP_URL . ($currentView === 'home' ? '/' : '/' . $currentView);
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  
  <title><?= e($pageTitle) ?></title>
  <meta name="description" content="<?= e($pageDesc) ?>">
  <meta name="keywords" content="ConsultWays Hub, Play Store Apps, Free Listings, Multi Vendor Marketplace, SaaS Tools, Digital Downloads, Pakistan Freelance Services, E-Commerce Deals">
  <link rel="canonical" href="<?= e($canonicalUrl) ?>">
  <meta name="robots" content="index, follow">

  <!-- OpenGraph Social Cards -->
  <meta property="og:type" content="website">
  <meta property="og:title" content="<?= e($pageTitle) ?>">
  <meta property="og:description" content="<?= e($pageDesc) ?>">
  <meta property="og:url" content="<?= e($canonicalUrl) ?>">
  <meta property="og:image" content="<?= e($ogImage) ?>">
  <meta property="og:site_name" content="ConsultWays Hub">

  <!-- Twitter Meta -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= e($pageTitle) ?>">
  <meta name="twitter:description" content="<?= e($pageDesc) ?>">
  <meta name="twitter:image" content="<?= e($ogImage) ?>">

  <!-- PWA & Mobile Web App -->
  <link rel="manifest" href="/manifest.json">
  <meta name="theme-color" content="#2563eb">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="CW Hub">

  <!-- Self-Contained Stylesheets (Zero CDN Dependency - No FOUC) -->
  <link rel="stylesheet" href="/assets/css/tailwind.min.css">
  <link rel="stylesheet" href="/assets/css/app.css">

  <!-- Google AdSense Readiness (Auto-disabled on dashboard & checkout) -->
  <?php if (ADSENSE_ENABLED && !in_array($currentView, ['dashboard', 'checkout'])): ?>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=<?= e(ADSENSE_CLIENT_ID) ?>" crossorigin="anonymous"></script>
  <?php endif; ?>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col justify-between">

  <!-- Top Announcement Bar (Temu / Alibaba Viral Urgency) -->
  <aside aria-label="Announcement" class="bg-slate-900 text-white py-2 px-4 text-xs font-semibold text-center border-b border-slate-800">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
      <div class="hidden md:flex items-center gap-2">
        <span class="badge-temu px-2 py-0.5 rounded-full text-[10px]">NEW SUBDOMAIN</span>
        <span class="text-slate-300">hub.consultways.com is live! Verified listings between $20.00 – $200.00 USD</span>
      </div>
      <div class="mx-auto md:mx-0 flex items-center gap-4">
        <a href="/post-listing" class="text-amber-400 hover:text-amber-300 font-bold flex items-center gap-1">
          <span>🚀 Open Your Free Store & Post Listings in 10s</span>
          <span>→</span>
        </a>
        <span class="text-slate-600 hidden sm:inline">|</span>
        <a href="https://consultways.com" target="_blank" rel="noopener" class="text-slate-400 hover:text-white hidden sm:inline">
          Main Domain (consultways.com)
        </a>
      </div>
    </div>
  </aside>

  <!-- Main Navigation Header -->
  <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
      
      <!-- Brand Logo -->
      <a href="/" class="flex items-center gap-3">
        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-blue-700 to-indigo-600 flex items-center justify-center text-white font-black text-xl shadow-md">
          CW
        </div>
        <div>
          <div class="flex items-center gap-2">
            <span class="font-black text-xl text-slate-900 tracking-tight">ConsultWays</span>
            <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase rounded-md bg-blue-100 text-blue-700 tracking-wider">HUB</span>
          </div>
          <span class="text-[11px] text-slate-500 font-medium block leading-none">Apps, Tools & Free Marketplace</span>
        </div>
      </a>

      <!-- Desktop Nav Links -->
      <nav class="hidden lg:flex items-center gap-1 font-semibold text-sm text-slate-600">
        <a href="/" class="px-3.5 py-2 rounded-xl hover:text-blue-600 hover:bg-slate-100 <?= $currentView === 'home' ? 'text-blue-600 bg-blue-50 font-bold' : '' ?>">
          Home
        </a>
        <a href="/apps" class="px-3.5 py-2 rounded-xl hover:text-blue-600 hover:bg-slate-100 <?= $currentView === 'apps' ? 'text-blue-600 bg-blue-50 font-bold' : '' ?>">
          Play Store Apps
        </a>
        <a href="/marketplace" class="px-3.5 py-2 rounded-xl hover:text-blue-600 hover:bg-slate-100 <?= $currentView === 'marketplace' ? 'text-blue-600 bg-blue-50 font-bold' : '' ?>">
          Free Marketplace
        </a>
        <a href="/dashboard" class="px-3.5 py-2 rounded-xl hover:text-blue-600 hover:bg-slate-100 <?= $currentView === 'dashboard' ? 'text-blue-600 bg-blue-50 font-bold' : '' ?>">
          Buyer Dashboard
        </a>
      </nav>

      <!-- Action CTAs -->
      <div class="flex items-center gap-3">
        <a href="/post-listing" class="px-4 py-2.5 rounded-xl badge-temu text-white text-xs font-bold shadow-md hover:brightness-105 transition-all flex items-center gap-1.5">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
          <span>Post Free Listing</span>
        </a>
        <a href="/dashboard" class="hidden sm:flex p-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-100 transition-colors" title="My Account & Tracking">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </a>
      </div>

    </div>
  </header>

  <!-- Main View Container -->
  <main class="flex-1">
