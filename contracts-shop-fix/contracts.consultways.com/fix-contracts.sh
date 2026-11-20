#!/usr/bin/env bash
# ==========================================
# 1-Click Fix Script for contracts.consultways.com
# Run this inside /home/consultw/contracts.consultways.com
# ==========================================

set -e
echo "==> Starting contracts.consultways.com repair..."

# 1. Ensure Vite base is set to '/'
if [ -f "vite.config.js" ]; then
    echo "Updating vite.config.js..."
    sed -i "s|base:.*|base: '/',|g" vite.config.js || true
fi

# 2. Rebuild Frontend
if [ -f "package.json" ]; then
    echo "Building production bundle..."
    npm run build || npx vite build
fi

# 3. Copy dist assets to public webroot
echo "Syncing static assets to web directory..."
mkdir -p dist/assets
cp -r dist/* . 2>/dev/null || true

# 4. Restart Passenger / Node instance
if command -v passenger-config &> /dev/null; then
    echo "Restarting Phusion Passenger..."
    passenger-config restart-app /home/consultw/contracts.consultways.com || true
fi

mkdir -p tmp
touch tmp/restart.txt

echo "==> contracts.consultways.com repaired successfully!"
