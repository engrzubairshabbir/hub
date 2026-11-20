# DIAGNOSIS & REPAIR GUIDE: Node.js Subdomains
**Targets:**
- `/home/consultw/contracts.consultways.com`
- `/home/consultw/contracts-backend-new`
- `/home/consultw/shop.consultways.com`

---

## 1. Root Cause Analysis: Why `contracts.consultways.com` Was Unstyled

### Issue A: Deprecated Client-Side Tailwind JIT Script & FOUC Blocker
In the existing `index.html`:
```css
/* Prevent FOUC */
body { visibility: hidden; }
body.loaded { visibility: visible; }
```
And Tailwind was injected dynamically after page load:
```javascript
window.addEventListener('DOMContentLoaded', () => {
  const script = document.createElement('script');
  script.src = 'https://cdn.tailwindcss.com';
  ...
```
- `cdn.tailwindcss.com` is an experimental in-browser compiler **not meant for production**.
- When Cloudflare **Rocket Loader** runs, it converts scripts into:
  `<script type="...-text/javascript">` which stops or delays execution.
- Result: The dynamic script injection fails or delays, the `body.loaded` event never fires or fires without styles, leaving the user with an unstyled or invisible page!

### Issue B: Vite Asset Path Mismatch & Missing Express Static MIME Handling
In Vite builds, if `base` is not explicitly set to `'/'` or if the Express server in `/home/consultw/contracts.consultways.com` or `contracts-backend-new` does not serve the `/dist` or `/build` directory with proper MIME types (`text/css`, `application/javascript`), the browser refuses to load assets due to strict MIME type enforcement.

---

## 2. Step-by-Step Fix for `contracts.consultways.com`

### Step 1: Replace `index.html`
Copy the repaired `index.html` from `contracts-shop-fix/contracts.consultways.com/index.html` into:
`/home/consultw/contracts.consultways.com/index.html` (and `/home/consultw/contracts.consultways.com/dist/index.html`).

**What is fixed in this file:**
1. Inlined critical Tailwind CSS bundle (zero external JIT CDN dependency).
2. Added `data-cfasync="false"` so Cloudflare Rocket Loader does not alter critical bundle scripts.
3. Removed `body { visibility: hidden }` so HTML content renders instantaneously without delay.
4. Corrected `<base href="/">` and relative script links.

### Step 2: Update Vite Configuration
Ensure `vite.config.js` in `/home/consultw/contracts.consultways.com/` contains:
```javascript
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
  plugins: [react()],
  base: '/',
  build: {
    outDir: 'dist',
    assetsDir: 'assets',
    sourcemap: false
  }
});
```

### Step 3: Rebuild Vite Bundle & Link Server
Run the provided automated script:
```bash
cd /home/consultw/contracts.consultways.com
bash fix-contracts.sh
```
Or manually run:
```bash
npm run build
cp -r dist/* public_html/ 2>/dev/null || true
```

---

## 3. Step-by-Step Fix for `shop.consultways.com`

### Issue: Next.js Passenger Socket Disconnect & Static CSS 404s
`shop.consultways.com` is a Next.js application (`/_next/static/css/...`). On cPanel hosting, Next.js Passenger processes often exit due to memory limitations, causing `/_next/` static assets to return 502/504 errors.

### Fix:
1. Update `/home/consultw/shop.consultways.com/.htaccess` to serve `/_next/static/` directly through Apache without waking up Node.js.
2. Restart the PM2/Passenger application:
```bash
cd /home/consultw/shop.consultways.com
bash fix-shop.sh
```
