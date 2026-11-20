# ConsultWays Hub (`hub.consultways.com`) & Subdomain Ecosystem Revamp

**Production Deployment & Architecture Suite for ConsultWays Limited**

---

## 🌟 Executive Summary

This repository delivers the complete solution for:
1. **Brand-New Viral Hybrid Subdomain: `hub.consultways.com`**
   - Pre-loaded with **all 49 posts and products** harvested directly from `consultways.com` with authentic high-resolution photos and pricing strictly mapped between **$20.00 and $200.00 USD**.
   - 5 Viral Pillars: Official Play Store apps showcase (21 apps/tools), Alibaba/Temu-style Free Listing Engine, AI Auto-Approval pipeline (Gemini/OpenAI), Buyer Command Center (`/dashboard`), and Google AdSense readiness (`ads.txt`).
   - Ultra-low footprint: **~260 KB total size** (strictly under the **150 MB** cPanel quota limit).
   - Root domain `consultways.com` remains 100% untouched and safe.
2. **Diagnosis & Repair Package for Broken Node.js Subdomains:**
   - Detailed diagnosis and 1-click repair scripts for `contracts.consultways.com` (fixed missing CSS/Vite bundles and FOUC) and `shop.consultways.com`.
3. **WordPress Alternative Suite:**
   - Astra Child Theme & custom `consultways-hub-core` plugin for WordPress implementations.

---

## 📁 Repository Directory Structure

```text
├── hub.consultways.com/               # The Complete Hybrid Web Platform (Direct cPanel upload)
│   ├── .htaccess                     # High-performance gzip, security headers, clean rewrites
│   ├── ads.txt                       # AdSense direct publisher verification (pub-8661538987218500)
│   ├── robots.txt                    # Googlebot, AdSense crawler & sitemap directives
│   ├── sitemap.xml                   # Auto-indexed XML sitemap
│   ├── manifest.json & sw.js         # Full PWA offline & home screen support
│   ├── config.php                    # Central configuration & API key bindings
│   ├── index.php                     # Ultra-fast router & dynamic OpenGraph SEO
│   ├── api/
│   │   ├── listings.php              # Real-time search, filter, and pagination
│   │   ├── apps.php                  # Official Play Store apps catalog
│   │   ├── moderate.php              # Gemini API auto-approval & policy verification
│   │   ├── orders.php                # Live carrier tracking & order status
│   │   └── upload.php                # Auto-WebP server compressor
│   ├── assets/
│   │   ├── css/tailwind.min.css      # Self-contained Tailwind CSS bundle (zero CDN failure risk)
│   │   ├── css/app.css               # Glassmorphism, badges, and hover polish
│   │   └── js/app.js                 # Unified reactive logic, WebP canvas compressor, share links
│   ├── data/
│   │   ├── listings.json             # 49 pre-loaded consultways.com items ($20 - $200 USD)
│   │   ├── apps.json                 # 21 ConsultWays & Twinkling Bytes apps/tools
│   │   ├── orders.json               # Seed orders & DHL step tracking
│   │   └── moderation_queue.json     # Flagged items review queue
│   └── views/
│       ├── header.php & footer.php   # Responsive layout, social meta & AdSense slots
│       ├── home.php                  # Hero banner & featured showcase
│       ├── apps.php                  # Play Store apps & SaaS tools directory
│       ├── marketplace.php           # Multi-vendor free listing feed
│       ├── post-listing.php          # 10s listing submission with Auto-WebP
│       ├── dashboard.php             # Unified Buyer/Seller Command Center
│       └── privacy.php, terms.php, refund.php, shipping.php  # Merchant legal policies
│
├── contracts-shop-fix/               # PART 1: Node.js Subdomain Repairs
│   ├── DIAGNOSIS_AND_FIX_GUIDE.md    # Detailed root cause explanation & steps
│   ├── contracts.consultways.com/    # Repaired index.html, vite.config.js, server.js, fix script
│   └── shop.consultways.com/         # Repaired next.config.js, .htaccess, fix script
│
└── wordpress-suite/                  # WordPress Alternative Suite
    ├── themes/astra-child/           # Astra Child theme with Dokan styles
    └── plugins/consultways-hub-core/ # Custom core plugin with Auto-WebP & price bounds
```

---

## 🚀 PART 1: Deploying the Fix for Existing Node.js Subdomains

### Why `contracts.consultways.com` was unstyled:
1. **Dynamic Tailwind JIT Blocker:** The old `index.html` used `<style>body { visibility: hidden; }</style>` waiting for a client-side `cdn.tailwindcss.com` script to load inside `DOMContentLoaded`.
2. **Cloudflare Rocket Loader Conflict:** Rocket Loader replaced the script tags with deferred scripts, preventing Tailwind JIT from compiling and leaving the page naked or permanently invisible.
3. **Mismatched Vite Base:** Vite static assets were not linked with `base: '/'` and were returning 404.

### 1-Click Fix via cPanel Terminal / SSH:
```bash
# 1. Navigate to contracts directory
cd /home/consultw/contracts.consultways.com

# 2. Upload and run the repair script
bash contracts-shop-fix/contracts.consultways.com/fix-contracts.sh
```
*Alternatively, copy `contracts-shop-fix/contracts.consultways.com/index.html` to `/home/consultw/contracts.consultways.com/dist/index.html`.*

---

## ⚡ PART 2: Deploying `hub.consultways.com` to cPanel

### Method A: Direct Upload via cPanel File Manager (Simplest)
1. In cPanel, navigate to **Subdomains** and ensure `hub.consultways.com` has its document root set to:
   `/home/consultw/hub.consultways.com`
2. Compress the contents of the `hub.consultways.com` folder in this repo into a `.zip` file.
3. Open cPanel **File Manager**, enter `/home/consultw/hub.consultways.com/`, and upload + extract the zip.
4. Set permissions on `/uploads` and `/data` to `755` (writable by PHP).

### Method B: Git Version Control in cPanel
1. In cPanel, click **Git™ Version Control** -> **Create**.
2. Clone your GitHub repository URL into `/home/consultw/hub.consultways.com`.

---

## 🛡️ Configurable Settings (`config.php`)

Open `/home/consultw/hub.consultways.com/config.php`:
- **Google AdSense:** Configured with client `ca-pub-8661538987218500`. (Enabled across App Details and Market feeds; strictly excluded on `/dashboard`).
- **Gemini AI API Key:** Add your Gemini API key in `define('GEMINI_API_KEY', 'YOUR_KEY_HERE');` to enable real-time content safety scanning.
- **Support Email:** `support@consultways.com` (Compliant with Stripe, PayFast, Lemon Squeezy).

---

## 📊 Pre-Loaded Listings & Prices ($20.00 – $200.00 USD)

All 49 posts and products harvested from `consultways.com` are pre-enlisted in `hub.consultways.com/data/listings.json`:
- Combined Switch Socket Universal 20A -> **$24.99 USD**
- Samsung Galaxy A26/A36/A56 Accessories -> **$39.99 USD**
- Unlock Success with Expert Consultancy Services -> **$189.00 USD**
- Realme Buds Air 5 Pro ANC Headphones -> **$79.99 USD**
- 8-in-1 Type-C Hub USB-C 4K HDMI Adapter -> **$45.00 USD**
- Tayyab SEO Store Audit -> **$149.00 USD**
- Beauty & Skincare Products Upto 90% Off -> **$29.99 USD**
- Quran Learning Tutoring -> **$49.00 USD**
- Computer Hardware Classes -> **$89.00 USD**
- Textile Industry Paper Cones -> **$65.00 USD**
- Elevate Your Consultancy Venture -> **$190.50 USD**
- Finland Visa Sponsorship Consultancy -> **$175.00 USD**
- Light Barrier with Remote Function -> **$129.00 USD**
- ConsultWays Advisory Guides (Blogs 1-4) -> **$25.00 – $130.00 USD**

---

© 2026 ConsultWays Limited. All Rights Reserved.
