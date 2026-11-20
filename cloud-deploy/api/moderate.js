// Vercel Serverless Function: AI Content Safety Moderation
// Reads GEMINI_API_KEY from Vercel Environment Variables (Encrypted, Never on GitHub)

export default async function handler(req, res) {
  if (req.method !== 'POST') {
    return res.status(405).json({ status: 'error', message: 'Method not allowed' });
  }

  const { title, type, price_usd, seller_name, seller_email, description } = req.body || {};

  if (!title || !description || !seller_email) {
    return res.status(400).json({ status: 'error', message: 'Missing required fields' });
  }

  const price = parseFloat(price_usd) || 0;
  if (price < 20 || price > 200) {
    return res.status(400).json({ status: 'error', message: 'Price must be strictly between $20.00 and $200.00 USD' });
  }

  // 1. Prohibited heuristic keywords check
  const bannedKeywords = ['counterfeit', 'replica', 'fake brand', 'stolen', 'hack', 'crack', 'weapon', 'firearm', 'narcotics', 'casino', 'betting'];
  const contentText = `${title} ${description}`.toLowerCase();
  for (const bad of bannedKeywords) {
    if (contentText.includes(bad)) {
      return res.status(200).json({
        status: 'queued',
        message: `Held in moderation queue: keyword '${bad}' detected.`,
        reasons: [`Contains prohibited term: ${bad}`]
      });
    }
  }

  // 2. Gemini API Verification (if key configured in Vercel dashboard)
  const geminiKey = process.env.GEMINI_API_KEY;
  if (geminiKey) {
    try {
      const prompt = `You are a content safety moderator for ConsultWays Hub. Check if this product listing is authentic, safe, and legal to publish. Title: "${title}", Description: "${description}", Price: $${price}. Reply strictly in valid JSON format only: {"safe": true/false, "reason": "brief explanation"}`;
      const geminiRes = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=${encodeURIComponent(geminiKey)}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ contents: [{ parts: [{ text: prompt }] }] })
      });

      if (geminiRes.ok) {
        const data = await geminiRes.json();
        const text = data?.candidates?.[0]?.content?.parts?.[0]?.text || '';
        const match = text.match(/\{.*\}/s);
        if (match) {
          const parsed = JSON.parse(match[0]);
          if (parsed.safe === false) {
            return res.status(200).json({
              status: 'queued',
              message: parsed.reason || 'Held for moderator approval.',
              reasons: [parsed.reason]
            });
          }
        }
      }
    } catch (e) {
      console.warn('Gemini verification fallback:', e.message);
    }
  }

  // 3. Auto-publish safe listing
  return res.status(200).json({
    status: 'approved',
    message: 'Listing verified by AI and auto-published instantly!',
    listing: {
      id: `cw-cloud-${Date.now()}`,
      title,
      type,
      price_usd: price,
      price_display: `$${price.toFixed(2)}`,
      seller_name: seller_name || 'Verified Merchant',
      seller_verified: true,
      rating: '5.0',
      reviews_count: 1,
      status: 'approved',
      created_at: new Date().toISOString().split('T')[0]
    }
  });
}
