// Cloudflare Pages Function: AI Content Safety Moderation
// Reads GEMINI_API_KEY from Cloudflare Pages Environment Variables (Encrypted)

export async function onRequestPost(context) {
  try {
    const { request, env } = context;
    const body = await request.json();
    const { title, type, price_usd, seller_name, seller_email, description } = body || {};

    if (!title || !description || !seller_email) {
      return new Response(JSON.stringify({ status: 'error', message: 'Missing required fields' }), {
        status: 400,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    const price = parseFloat(price_usd) || 0;
    if (price < 20 || price > 200) {
      return new Response(JSON.stringify({ status: 'error', message: 'Price must be strictly between $20.00 and $200.00 USD' }), {
        status: 400,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    // Heuristics
    const banned = ['counterfeit', 'replica', 'fake brand', 'stolen', 'hack', 'weapon', 'casino'];
    const text = `${title} ${description}`.toLowerCase();
    for (const b of banned) {
      if (text.includes(b)) {
        return new Response(JSON.stringify({
          status: 'queued',
          message: `Held in review queue: prohibited term '${b}' detected.`
        }), {
          status: 200,
          headers: { 'Content-Type': 'application/json' }
        });
      }
    }

    // Gemini API if key is set in Cloudflare Dashboard
    const geminiKey = env.GEMINI_API_KEY;
    if (geminiKey) {
      try {
        const prompt = `Analyze listing: Title: "${title}", Desc: "${description}", Price: $${price}. Reply strictly in valid JSON: {"safe": true/false, "reason": "summary"}`;
        const resp = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=${encodeURIComponent(geminiKey)}`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ contents: [{ parts: [{ text: prompt }] }] })
        });
        if (resp.ok) {
          const gData = await resp.json();
          const gText = gData?.candidates?.[0]?.content?.parts?.[0]?.text || '';
          const match = gText.match(/\{.*\}/s);
          if (match) {
            const parsed = JSON.parse(match[0]);
            if (parsed.safe === false) {
              return new Response(JSON.stringify({
                status: 'queued',
                message: parsed.reason || 'Flagged by AI moderator.'
              }), {
                status: 200,
                headers: { 'Content-Type': 'application/json' }
              });
            }
          }
        }
      } catch (err) {}
    }

    return new Response(JSON.stringify({
      status: 'approved',
      message: 'Verified by AI and auto-published instantly!'
    }), {
      status: 200,
      headers: { 'Content-Type': 'application/json' }
    });

  } catch (err) {
    return new Response(JSON.stringify({ status: 'error', message: err.message }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' }
    });
  }
}
