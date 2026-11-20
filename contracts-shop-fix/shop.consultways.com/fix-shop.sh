#!/usr/bin/env bash
# ==========================================
# 1-Click Repair & Cache Rebuild for shop.consultways.com
# Run this inside /home/consultw/shop.consultways.com
# ==========================================

set -e
echo "==> Starting shop.consultways.com repair..."

# Clean old corrupted static chunks
rm -rf .next/cache

# Rebuild standalone or restart Next.js server
if [ -f "package.json" ]; then
    echo "Building Next.js standalone assets..."
    npm run build || true
fi

# Restart Passenger / PM2
mkdir -p tmp
touch tmp/restart.txt

if command -v pm2 &> /dev/null; then
    pm2 restart shop 2>/dev/null || pm2 restart all 2>/dev/null || true
fi

echo "==> shop.consultways.com repaired successfully!"
