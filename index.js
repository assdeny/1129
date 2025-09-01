const express = require("express");
const admin = require("firebase-admin");
const cors = require("cors");

const app = express();
app.use(cors());
app.use(express.json());

// Ambil service account dari ENV di Railway
const serviceAccount = JSON.parse(process.env.FIREBASE_CONFIG);
admin.initializeApp({
  credential: admin.credential.cert(serviceAccount)
});

// Endpoint untuk kirim notifikasi promo produk
app.post("/sendProductPromo", async (req, res) => {
  const { umkmName, productName, promoPrice, productId, umkmId, topic = "global" } = req.body;

  if (!umkmName || !productName || !productId) {
    return res.status(400).json({ ok: false, error: "Missing required fields" });
  }

  const title = `${umkmName} - Promo Produk!`;
  let body = productName;
  if (promoPrice) body += ` - Harga promo: Rp ${promoPrice}`;

  const message = {
    notification: { title, body },
    data: {
      productId: String(productId),
      umkmId: String(umkmId || ""),
      promoPrice: String(promoPrice || "")
    },
    topic
  };

  try {
    const response = await admin.messaging().send(message);
    return res.json({ ok: true, result: response });
  } catch (err) {
    console.error("Error sending message:", err);
    return res.status(500).json({ ok: false, error: err.message });
  }
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => console.log(`🚀 Server running on port ${PORT}`));
