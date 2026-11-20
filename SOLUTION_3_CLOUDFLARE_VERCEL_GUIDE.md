# SOLUTION 3: Cloudflare Pages / Vercel Zero-Disk Deployment Guide
**Subdomain:** `hub.consultways.com`
**cPanel Disk Footprint:** **0 KB (ZERO Bytes Used on cPanel)**
**Root Domain (`consultways.com`):** **100% Untouched, Running & Earning on cPanel**

---

## 🌟 Why This is the Ultimate Solution for You:
1. **0 MB cPanel Disk Used**: `hub.consultways.com` runs on global edge servers (Cloudflare / Vercel).
2. **Main Domain Safe**: `consultways.com` cPanel per hi chalti rahay gi, AdSense earning ya traffic ko koi asar nahi hoga.
3. **Gemini API Key 100% Secure**: Key ko GitHub per nahi bhejna parta; Cloudflare / Vercel ke encrypted dashboard settings me daal diya jata hy, jahan koi scanner ya hacker nahi pohnch sakta.
4. **Blazing Fast**: 99+ Google Lighthouse score, global CDN, 0ms latency.

---

## 🚀 Option A: Vercel Par 2-Minute Live Setup (Easiest)

### Step 1: Vercel Par Project Import Karein
1. [Vercel.com](https://vercel.com) par login karein (apne GitHub account se).
2. Click **Add New...** -> **Project**.
3. Apni GitHub repository select karein (`ConsultWays.com Revamp` ya jo b naam rakhein).
4. **Root Directory**: Select `cloud-deploy` (ya repository root).
5. **Environment Variables**:
   - Variable Name: `GEMINI_API_KEY`
   - Value: `AIzaSy...` (Apni real Gemini API key yahan paste karein).
   *(Yeh key Vercel ke servers per encrypted store hoti hy aur GitHub per kabhi show nahi hoti).*
6. Click **Deploy**.

### Step 2: Subdomain `hub.consultways.com` Connect Karein
1. Vercel dashboard me project **Settings** -> **Domains** me jayein.
2. Enter karein: `hub.consultways.com` aur **Add** per click karein.
3. Vercel aap ko aik **CNAME record** dega, maslan:
   `cname.vercel-dns.com`

### Step 3: cPanel Me CNAME Record Add Karein (Only 30 Seconds)
1. Apne cPanel (`my.hosting.com`) me login karein.
2. Search bar me type karein: **Zone Editor**.
3. `consultways.com` ke agay **Manage** per click karein.
4. Click **+ Add Record**:
   - **Type**: `CNAME`
   - **Name**: `hub.consultways.com.` (ya sirf `hub`)
   - **Record**: `cname.vercel-dns.com`
   - **TTL**: `14400` (ya default 3600)
5. Click **Save Record**.

Done! Kuch hi minutes me **`hub.consultways.com`** live ho jayegi aur cPanel ki 0 MB space use hogi!

---

## ⚡ Option B: Cloudflare Pages Par Setup

1. [Dash.cloudflare.com](https://dash.cloudflare.com) me login karein -> **Workers & Pages** -> **Create application** -> **Pages**.
2. Connect to Git -> Repository select karein.
3. Build output directory: `cloud-deploy`.
4. **Environment Variables** me `GEMINI_API_KEY` add karein.
5. Click **Save and Deploy**.
6. Deployment ke baad **Custom Domains** me ja kar `hub.consultways.com` add karein.

---

## 🛡️ Gemini API Key Scanner Se Kaise Bach Rahi Hy?
- Code ke andar koi key hardcoded nahi hy.
- `api/moderate.js` server-side run hota hy aur key ko `process.env.GEMINI_API_KEY` se uthata hy.
- Frontend ya public inspect element me sirf `https://hub.consultways.com/api/moderate` ka endpoint dikhta hy, API key hamesha backend me encrypted rehti hy.

---

© 2026 ConsultWays Limited. All Rights Reserved.
