/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  poweredByHeader: false,
  compress: true,
  assetPrefix: '',
  images: {
    unoptimized: true,
    domains: ['consultways.com', 'shop.consultways.com']
  },
  // Ensure standalone output for low memory cPanel footprint
  output: 'standalone'
};

module.exports = nextConfig;
