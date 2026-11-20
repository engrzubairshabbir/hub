const express = require('express');
const path = require('path');
const compression = require('compression');

const app = express();
const PORT = process.env.PORT || 3000;

// Enable gzip compression
app.use(compression());

// Serve static assets with explicit MIME types and cache headers
app.use(express.static(path.join(__dirname, 'dist'), {
  maxAge: '30d',
  setHeaders: (res, filePath) => {
    if (filePath.endsWith('.css')) {
      res.setHeader('Content-Type', 'text/css');
    } else if (filePath.endsWith('.js')) {
      res.setHeader('Content-Type', 'application/javascript');
    }
  }
}));

// Forward API requests to contracts-backend-new if running locally
const BACKEND_URL = process.env.BACKEND_URL || 'http://127.0.0.1:5000';
app.use('/api', (req, res) => {
  // Simple proxy fallback
  res.status(502).json({ error: 'Backend service unavailable. Check contracts-backend-new process.' });
});

// SPA fallback: return index.html for all non-file routes
app.get('*', (req, res) => {
  res.sendFile(path.join(__dirname, 'dist', 'index.html'));
});

app.listen(PORT, () => {
  console.log(`Contracts app running on port ${PORT}`);
});
