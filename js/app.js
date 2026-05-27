/* ============================================================
   KOMINHOO BEAUTY — App Data & Shared Logic
   ============================================================ */

// ── Product Database ──────────────────────────────────────────
const PRODUCTS = [
  {
    id: 1, name: "Low pH Good Morning Cleanser", brand: "COSRX",
    category: "Cleanser", price: 12500, originalPrice: null,
    skinType: ["Oily","Combination"], concern: ["Acne","Pores","Texture"],
    sensitivity: "Sensitive", routineStep: "Cleanser", timeOfUse: "AM/PM",
    texture: "Gel", ingredients: ["Salicylic Acid","BHA"],
    priceTier: "Mid", climate: ["Humid"], rating: 4.7, reviews: 1243,
    image: "https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=400&h=400&fit=crop&q=80",
    badge: "Best Seller", inStock: true, isNew: false,
    desc: "A gentle, low-pH cleanser that removes impurities without stripping your skin barrier."
  },
  {
    id: 2, name: "Snail Mucin 96% Power Repairing Essence", brand: "COSRX",
    category: "Essence", price: 18000, originalPrice: 22000,
    skinType: ["Dry","Normal","Combination"], concern: ["Dehydration","Fine Lines","Texture"],
    sensitivity: "Low", routineStep: "Essence", timeOfUse: "AM/PM",
    texture: "Water", ingredients: ["Snail Mucin","Hyaluronic Acid"],
    priceTier: "Mid", climate: ["All"], rating: 4.9, reviews: 3587,
    image: "https://images.unsplash.com/photo-1620916566396-4c7aa9a87879?w=400&h=400&fit=crop&q=80",
    badge: "Fan Fave", inStock: true, isNew: false,
    desc: "96% snail secretion filtrate essence that repairs, hydrates and brightens skin overnight."
  },
  {
    id: 3, name: "Cream Skin Toner & Moisturizer", brand: "Laneige",
    category: "Toner", price: 28500, originalPrice: null,
    skinType: ["Dry","Normal"], concern: ["Dehydration","Fine Lines"],
    sensitivity: "Low", routineStep: "Toner", timeOfUse: "AM/PM",
    texture: "Cream", ingredients: ["Ceramides","White Leaf Water"],
    priceTier: "Premium", climate: ["Air-conditioned"], rating: 4.6, reviews: 876,
    image: "https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?w=400&h=400&fit=crop&q=80",
    badge: null, inStock: true, isNew: false,
    desc: "A creamy toner that delivers a surge of moisture while strengthening the skin barrier."
  },
  {
    id: 4, name: "AHA BHA PHA 30 Days Miracle Toner", brand: "Some By Mi",
    category: "Toner", price: 15500, originalPrice: 18000,
    skinType: ["Oily","Combination"], concern: ["Acne","Texture","Large Pores"],
    sensitivity: "Medium", routineStep: "Toner", timeOfUse: "PM",
    texture: "Water", ingredients: ["AHA","BHA","PHA","Centella Asiatica"],
    priceTier: "Mid", climate: ["Humid"], rating: 4.5, reviews: 2109,
    image: "https://images.unsplash.com/photo-1614289371518-722f2615943d?w=400&h=400&fit=crop&q=80",
    badge: "Sale", inStock: true, isNew: false,
    desc: "Triple acid toner that visibly reduces acne, blackheads and uneven texture in 30 days."
  },
  {
    id: 5, name: "10% Niacinamide + 1% Zinc Serum", brand: "The Ordinary",
    category: "Serum", price: 8500, originalPrice: null,
    skinType: ["Oily","Combination","Normal"], concern: ["Acne","Large Pores","Dullness","Hyperpigmentation"],
    sensitivity: "Low", routineStep: "Serum", timeOfUse: "AM/PM",
    texture: "Water", ingredients: ["Niacinamide","Zinc PCA"],
    priceTier: "Basic", climate: ["All"], rating: 4.4, reviews: 4821,
    image: "https://images.unsplash.com/photo-1631729371254-42c2892f0e6e?w=400&h=400&fit=crop&q=80",
    badge: "Best Value", inStock: true, isNew: false,
    desc: "High-strength vitamin B3 formula that targets excess sebum, blemishes and pore appearance."
  },
  {
    id: 6, name: "Tone Up Sun Serum SPF 50+ PA++++", brand: "Beauty of Joseon",
    category: "Sunscreen", price: 22000, originalPrice: null,
    skinType: ["All"], concern: ["Hyperpigmentation","Dullness","Fine Lines"],
    sensitivity: "Low", routineStep: "Sunscreen", timeOfUse: "AM",
    texture: "Lotion", ingredients: ["Niacinamide","Rice Extract","Propolis"],
    priceTier: "Mid", climate: ["Hot","Humid"], rating: 4.8, reviews: 2931,
    image: "https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=400&h=400&fit=crop&q=80",
    badge: "Staff Pick", inStock: true, isNew: true,
    desc: "Lightweight sun serum that protects, brightens and tones in one effortless step."
  },
  {
    id: 7, name: "Heartleaf Quercetinol Pore Concentrate Serum", brand: "Anua",
    category: "Serum", price: 24000, originalPrice: 29000,
    skinType: ["Oily","Combination","Sensitive"], concern: ["Acne","Redness","Large Pores"],
    sensitivity: "Sensitive", routineStep: "Serum", timeOfUse: "PM",
    texture: "Lotion", ingredients: ["Centella Asiatica","Heartleaf","Niacinamide"],
    priceTier: "Mid", climate: ["All"], rating: 4.7, reviews: 1654,
    image: "https://images.unsplash.com/photo-1570194065650-d99fb4b38f34?w=400&h=400&fit=crop&q=80",
    badge: "New", inStock: true, isNew: true,
    desc: "Heartleaf-powered serum that calms redness, tightens pores and controls excess oil."
  },
  {
    id: 8, name: "Freshly Juiced Vitamin C Serum", brand: "Klairs",
    category: "Serum", price: 32000, originalPrice: null,
    skinType: ["All"], concern: ["Hyperpigmentation","Dullness","Fine Lines"],
    sensitivity: "Low", routineStep: "Serum", timeOfUse: "PM",
    texture: "Water", ingredients: ["Vitamin C","Hyaluronic Acid","Peptides"],
    priceTier: "Premium", climate: ["All"], rating: 4.6, reviews: 987,
    image: "https://images.unsplash.com/photo-1556228720-195a672e8a03?w=400&h=400&fit=crop&q=80",
    badge: null, inStock: true, isNew: false,
    desc: "Gentle 5% vitamin C serum that brightens, evens tone and fades dark spots without irritation."
  },
  {
    id: 9, name: "Green Tea Seed Deep Cream", brand: "Innisfree",
    category: "Moisturizer", price: 19500, originalPrice: 23000,
    skinType: ["All"], concern: ["Dehydration","Fine Lines","Dullness"],
    sensitivity: "Low", routineStep: "Moisturizer", timeOfUse: "AM/PM",
    texture: "Cream", ingredients: ["Green Tea","Hyaluronic Acid","Ceramides"],
    priceTier: "Mid", climate: ["All"], rating: 4.5, reviews: 2345,
    image: "https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=400&h=400&fit=crop&q=80",
    badge: "Sale", inStock: true, isNew: false,
    desc: "Rich, antioxidant moisturizer that deeply hydrates and repairs the skin barrier overnight."
  },
  {
    id: 10, name: "Centella Unscented Toner", brand: "Purito",
    category: "Toner", price: 14500, originalPrice: null,
    skinType: ["Sensitive","Dry","Normal"], concern: ["Redness","Dehydration","Acne"],
    sensitivity: "Sensitive", routineStep: "Toner", timeOfUse: "AM/PM",
    texture: "Water", ingredients: ["Centella Asiatica","PHA","Hyaluronic Acid"],
    priceTier: "Mid", climate: ["All"], rating: 4.6, reviews: 1432,
    image: "https://images.unsplash.com/photo-1596564100009-9b6fcf02e2ff?w=400&h=400&fit=crop&q=80",
    badge: null, inStock: true, isNew: false,
    desc: "Fragrance-free centella toner that soothes, hydrates and gently exfoliates sensitive skin."
  },
  {
    id: 11, name: "Real Cica 144 Soothing Ampoule", brand: "Dr.Jart+",
    category: "Serum", price: 38500, originalPrice: null,
    skinType: ["Sensitive","Dry","Combination"], concern: ["Redness","Acne","Dehydration"],
    sensitivity: "Sensitive", routineStep: "Serum", timeOfUse: "AM/PM",
    texture: "Water", ingredients: ["Centella Asiatica","Salicornia","Madecassoside"],
    priceTier: "Premium", climate: ["All"], rating: 4.8, reviews: 734,
    image: "https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=400&h=400&fit=crop&q=80",
    badge: "Staff Pick", inStock: true, isNew: false,
    desc: "144-hour moisture retention ampoule packed with Cica actives for intensely calm, clear skin."
  },
  {
    id: 12, name: "Pure Vitamin C 21.5 Advanced Serum", brand: "Klairs",
    category: "Serum", price: 45000, originalPrice: 52000,
    skinType: ["Normal","Combination","Oily"], concern: ["Hyperpigmentation","Fine Lines","Dullness"],
    sensitivity: "Medium", routineStep: "Serum", timeOfUse: "PM",
    texture: "Water", ingredients: ["Vitamin C","Vitamin E","Hyaluronic Acid"],
    priceTier: "Premium", climate: ["All"], rating: 4.6, reviews: 512,
    image: "https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=400&h=400&fit=crop&q=80",
    badge: "Sale", inStock: true, isNew: false,
    desc: "High-potency 21.5% vitamin C serum for dramatic brightening and anti-aging results."
  },
  {
    id: 13, name: "Oil-Free Matte Gel Moisturizer", brand: "COSRX",
    category: "Moisturizer", price: 17000, originalPrice: null,
    skinType: ["Oily","Combination"], concern: ["Acne","Large Pores","Dullness"],
    sensitivity: "Low", routineStep: "Moisturizer", timeOfUse: "AM/PM",
    texture: "Gel", ingredients: ["Niacinamide","Hyaluronic Acid","Birch Sap"],
    priceTier: "Mid", climate: ["Hot","Humid"], rating: 4.5, reviews: 867,
    image: "https://images.unsplash.com/photo-1557053910-d9eadeed1c58?w=400&h=400&fit=crop&q=80",
    badge: null, inStock: true, isNew: false,
    desc: "Feather-light gel moisturizer that hydrates without clogging pores or leaving residue."
  },
  {
    id: 14, name: "Alpha Arbutin 2% + HA Serum", brand: "The Ordinary",
    category: "Serum", price: 9500, originalPrice: null,
    skinType: ["All"], concern: ["Hyperpigmentation","Dullness"],
    sensitivity: "Low", routineStep: "Serum", timeOfUse: "AM/PM",
    texture: "Water", ingredients: ["Arbutin","Hyaluronic Acid"],
    priceTier: "Basic", climate: ["All"], rating: 4.3, reviews: 3201,
    image: "https://images.unsplash.com/photo-1616616622481-0a10ae0e15e1?w=400&h=400&fit=crop&q=80",
    badge: "Best Value", inStock: true, isNew: false,
    desc: "Targeted brightening serum that fades dark spots and evens skin tone over time."
  },
  {
    id: 15, name: "Propolis Energy Ampule", brand: "Beauty of Joseon",
    category: "Serum", price: 26500, originalPrice: 32000,
    skinType: ["All"], concern: ["Dullness","Dehydration","Fine Lines"],
    sensitivity: "Low", routineStep: "Serum", timeOfUse: "AM/PM",
    texture: "Lotion", ingredients: ["Propolis","Niacinamide","Peptides"],
    priceTier: "Mid", climate: ["All"], rating: 4.7, reviews: 1876,
    image: "https://images.unsplash.com/photo-1582560475093-ba66accbc095?w=400&h=400&fit=crop&q=80",
    badge: "Sale", inStock: true, isNew: false,
    desc: "Golden propolis ampoule that glows up dull, tired skin with rich antioxidants and peptides."
  },
  {
    id: 16, name: "Water Bank Blue HA Serum", brand: "Laneige",
    category: "Serum", price: 42000, originalPrice: null,
    skinType: ["All"], concern: ["Dehydration","Fine Lines","Dullness"],
    sensitivity: "Low", routineStep: "Serum", timeOfUse: "AM/PM",
    texture: "Water", ingredients: ["Hyaluronic Acid","Blue Hyaluronic Acid","Niacinamide"],
    priceTier: "Premium", climate: ["All"], rating: 4.8, reviews: 643,
    image: "https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=400&h=400&fit=crop&q=80",
    badge: "New", inStock: true, isNew: true,
    desc: "Next-gen hydration serum with 5 types of hyaluronic acid that lock in moisture for 72 hours."
  },
  {
    id: 17, name: "Sleep Mask", brand: "Laneige",
    category: "Mask", price: 29000, originalPrice: 35000,
    skinType: ["Dry","Normal","Combination"], concern: ["Dehydration","Fine Lines","Dullness"],
    sensitivity: "Low", routineStep: "Mask", timeOfUse: "PM",
    texture: "Gel", ingredients: ["Hyaluronic Acid","Ceramides","Squalane"],
    priceTier: "Premium", climate: ["Air-conditioned"], rating: 4.9, reviews: 5218,
    image: "https://images.unsplash.com/photo-1597852074816-d933c7d2b988?w=400&h=400&fit=crop&q=80",
    badge: "Iconic", inStock: true, isNew: false,
    desc: "Cult-classic overnight sleep mask that transforms dry, dull skin into plump perfection by morning."
  },
  {
    id: 18, name: "Retinol 0.2% in Squalane", brand: "The Ordinary",
    category: "Treatment", price: 11500, originalPrice: null,
    skinType: ["Normal","Combination","Oily"], concern: ["Fine Lines","Wrinkles","Texture"],
    sensitivity: "Medium", routineStep: "Treatment", timeOfUse: "PM",
    texture: "Oil", ingredients: ["Retinol","Squalane"],
    priceTier: "Basic", climate: ["All"], rating: 4.4, reviews: 2987,
    image: "https://images.unsplash.com/photo-1555487505-8603a1a69755?w=400&h=400&fit=crop&q=80",
    badge: null, inStock: true, isNew: false,
    desc: "Entry-level retinol formula suspended in squalane for gentle anti-aging without over-drying."
  },
  {
    id: 19, name: "Pore Minimizing Toner", brand: "Innisfree",
    category: "Toner", price: 16500, originalPrice: null,
    skinType: ["Oily","Combination"], concern: ["Large Pores","Acne","Texture"],
    sensitivity: "Medium", routineStep: "Toner", timeOfUse: "AM/PM",
    texture: "Water", ingredients: ["BHA","Salicylic Acid","Green Tea"],
    priceTier: "Mid", climate: ["Humid"], rating: 4.3, reviews: 1109,
    image: "https://images.unsplash.com/photo-1545208935-9a7b23524f41?w=400&h=400&fit=crop&q=80",
    badge: null, inStock: true, isNew: false,
    desc: "BHA-powered toner that visibly minimizes pores, controls oil and refines skin texture."
  },
  {
    id: 20, name: "Moisture Surge 100H Auto-Replenishing Hydrator", brand: "Clinique",
    category: "Moisturizer", price: 52000, originalPrice: 65000,
    skinType: ["All"], concern: ["Dehydration","Fine Lines","Dullness"],
    sensitivity: "Low", routineStep: "Moisturizer", timeOfUse: "AM/PM",
    texture: "Gel", ingredients: ["Aloe Vera","Hyaluronic Acid","Activated Aloe Water"],
    priceTier: "Premium", climate: ["All"], rating: 4.7, reviews: 4322,
    image: "https://images.unsplash.com/photo-1586201375761-83865001e31c?w=400&h=400&fit=crop&q=80",
    badge: "Luxury Pick", inStock: true, isNew: false,
    desc: "100-hour continuous hydration gel that auto-replenishes moisture as the day goes on."
  }
];

// ── Bundle Kits ──────────────────────────────────────────────
const BUNDLES = [
  {
    id: 1, name: "Acne Starter Kit",
    concern: ["Acne"],
    products: [1, 4, 5, 13],
    price: 48500, originalPrice: 63500,
    image: "https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=600&h=800&fit=crop&q=80",
    tag: "Clear Skin",
    desc: "Everything you need to banish breakouts and control oil for good."
  },
  {
    id: 2, name: "Brightening Kit",
    concern: ["Hyperpigmentation","Dullness"],
    products: [8, 14, 6, 3],
    price: 67500, originalPrice: 85000,
    image: "https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=600&h=800&fit=crop&q=80",
    tag: "Glow Up",
    desc: "A Vitamin C-powered lineup to fade dark spots and achieve glass skin."
  },
  {
    id: 3, name: "Hydration Kit",
    concern: ["Dehydration"],
    products: [2, 3, 9, 17],
    price: 81500, originalPrice: 102000,
    image: "https://images.unsplash.com/photo-1614289371518-722f2615943d?w=600&h=800&fit=crop&q=80",
    tag: "Quench & Glow",
    desc: "Layer on lush hydration from cleanse to sleep with this moisture-boosting routine."
  },
  {
    id: 4, name: "Anti-Aging Starter Kit",
    concern: ["Fine Lines","Wrinkles"],
    products: [8, 18, 15, 20],
    price: 92000, originalPrice: 118000,
    image: "https://images.unsplash.com/photo-1556228720-195a672e8a03?w=600&h=800&fit=crop&q=80",
    tag: "Turn Back Time",
    desc: "A science-backed lineup to soften fine lines, firm skin, and restore youthful radiance."
  },
  {
    id: 5, name: "Sensitive Skin Rescue",
    concern: ["Redness","Sensitivity"],
    products: [10, 11, 2, 6],
    price: 74500, originalPrice: 95500,
    image: "https://images.unsplash.com/photo-1515377905703-c4788e51af15?w=600&h=800&fit=crop&q=80",
    tag: "Calm & Repair",
    desc: "Gentle, barrier-first formulas that soothe reactive skin without any irritation."
  },
  {
    id: 6, name: "Glass Skin Kit",
    concern: ["Dullness","Texture","Hyperpigmentation"],
    products: [5, 12, 7, 6],
    price: 97000, originalPrice: 124000,
    image: "https://images.unsplash.com/photo-1570194065650-d99fb4b38f34?w=600&h=800&fit=crop&q=80",
    tag: "K-Beauty Ritual",
    desc: "The full K-beauty layering routine for that iconic dewy, translucent glass skin finish."
  }
];

// ── Buying Guides ────────────────────────────────────────────
const GUIDES = [
  { id:1, icon:"🧴", title:"Acne Solutions", desc:"Clear breakouts fast with targeted ingredients", count:"14 products",
    image:"https://images.unsplash.com/photo-1515377905703-c4788e51af15?w=600&h=700&fit=crop&q=80" },
  { id:2, icon:"✨", title:"Glow Boosters", desc:"Achieve glass skin with brightening actives", count:"18 products",
    image:"https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=600&h=700&fit=crop&q=80" },
  { id:3, icon:"💧", title:"Hydration Heroes", desc:"Lock in moisture for plump, bouncy skin", count:"22 products",
    image:"https://images.unsplash.com/photo-1622618991746-fe6004db3a47?w=600&h=700&fit=crop&q=80" },
  { id:4, icon:"⏰", title:"Anti-Aging Routine", desc:"Fight fine lines with clinically-proven actives", count:"11 products",
    image:"https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?w=600&h=700&fit=crop&q=80" },
  { id:5, icon:"🛡️", title:"Sensitive Skin", desc:"Calm redness and strengthen your barrier", count:"16 products",
    image:"https://images.unsplash.com/photo-1576426863848-c21f53c60b19?w=600&h=700&fit=crop&q=80" },
  { id:6, icon:"🌟", title:"Pore Perfecting", desc:"Minimize pores and refine skin texture", count:"9 products",
    image:"https://images.unsplash.com/photo-1617897903246-719242758050?w=600&h=700&fit=crop&q=80" },
  { id:7, icon:"☀️", title:"Sun Protection", desc:"Find your perfect SPF for Nigerian weather", count:"8 products",
    image:"https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=600&h=700&fit=crop&q=80" },
  { id:8, icon:"🌙", title:"Night Repair", desc:"Maximize skin renewal while you sleep", count:"12 products",
    image:"https://images.unsplash.com/photo-1570194065650-d99fb4b38f34?w=600&h=700&fit=crop&q=80" }
];

// ── Gift Cards ───────────────────────────────────────────────
const GIFT_CARDS = [
  { id: 'gc-5k',  amount: 5000,  label: '₦5,000',  tag: 'Starter Gift',   popular: false, desc: 'Perfect for 1–2 products' },
  { id: 'gc-10k', amount: 10000, label: '₦10,000', tag: 'Most Popular',   popular: true,  desc: 'Covers a full mini-routine' },
  { id: 'gc-25k', amount: 25000, label: '₦25,000', tag: 'Best Value',     popular: false, desc: 'For the serious skincare fan' },
  { id: 'gc-50k', amount: 50000, label: '₦50,000', tag: 'Premium Bundle', popular: false, desc: 'The ultimate glow gift' }
];

let selectedGcAmount = 0;

function selectGiftCard(id) {
  const gc = GIFT_CARDS.find(g => g.id === id);
  if (!gc) return;
  selectedGcAmount = gc.amount;
  document.querySelectorAll('.gc-card').forEach(el => el.style.outline = 'none');
  const el = document.getElementById('gc-' + id);
  if (el) el.style.outline = '3px solid var(--lime)';
  const amtEl = document.getElementById('gc-selected-amount');
  if (amtEl) amtEl.textContent = gc.label;
  showToast('🎁', `<strong>${gc.label}</strong> gift card selected`);
}

function buyGiftCard() {
  const recipientName  = (document.getElementById('gc-recipient-name')  || {}).value || '';
  const recipientEmail = (document.getElementById('gc-recipient-email') || {}).value || '';
  const msg            = (document.getElementById('gc-message')         || {}).value || '';
  const customEl       = document.getElementById('gc-custom-amount');
  const customAmt      = customEl ? parseInt(customEl.value) : 0;
  const amount         = customAmt > 0 ? customAmt : selectedGcAmount;
  if (!amount || amount < 1000) { showToast('⚠️', 'Please select or enter a gift card amount'); return; }
  if (!recipientName)  { showToast('⚠️', 'Please enter the recipient\'s name'); return; }
  if (!recipientEmail) { showToast('⚠️', 'Please enter the recipient\'s email'); return; }
  // Add gift card as a cart item
  const existing = cart.find(i => i.id === 'giftcard-' + amount);
  if (existing) { existing.qty++; }
  else {
    cart.push({
      id: 'giftcard-' + amount,
      name: `Kominhoo Gift Card — ₦${amount.toLocaleString()}`,
      brand: 'Kominhoo',
      price: amount,
      image: 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=400&h=400&fit=crop&q=80',
      qty: 1,
      isGiftCard: true,
      giftTo: recipientName,
      giftEmail: recipientEmail,
      giftMsg: msg
    });
  }
  saveCart(); updateCartUI(); renderCartItems();
  showToast('🎁', `Gift card for <strong>${recipientName}</strong> added to cart! <a href="checkout.html" style="color:var(--lime);font-weight:700;text-decoration:underline;margin-left:6px">Checkout →</a>`);
  const drawer = document.getElementById('cart-drawer');
  const overlay = document.getElementById('cart-overlay');
  if (drawer && !drawer.classList.contains('open')) {
    drawer.classList.add('open');
    if (overlay) { overlay.style.opacity='1'; overlay.style.visibility='visible'; }
  }
}

// ── Cart State ──────────────────────────────────────────────
let cart = JSON.parse(localStorage.getItem('kominhoo_cart') || '[]');

function saveCart() { localStorage.setItem('kominhoo_cart', JSON.stringify(cart)); }

function addToCart(productId, fromCheckout) {
  const existing = cart.find(i => i.id === productId);
  if (existing) { existing.qty++; }
  else { const p = PRODUCTS.find(p => p.id === productId); if(p) cart.push({ id:p.id, name:p.name, brand:p.brand, price:p.price, image:p.image, qty:1 }); }
  saveCart();
  updateCartUI();
  renderCartItems();
  const p = PRODUCTS.find(pr => pr.id === productId);
  const name = p ? p.name.split(' ').slice(0,4).join(' ') : 'Item';
  showToast('🛒', `<strong>${name}</strong> added! <a href="checkout.html" style="color:var(--lime);font-weight:700;text-decoration:underline;margin-left:6px">Checkout →</a>`);
  // Flash the cart drawer open briefly on shop/home pages
  if (!fromCheckout) {
    const drawer = document.getElementById('cart-drawer');
    const overlay = document.getElementById('cart-overlay');
    if (drawer && !drawer.classList.contains('open')) {
      drawer.classList.add('open');
      if (overlay) { overlay.style.opacity='1'; overlay.style.visibility='visible'; overlay.classList && overlay.classList.add('open'); }
    }
  }
}

function removeFromCart(id) { cart = cart.filter(i => i.id !== id); saveCart(); updateCartUI(); renderCartItems(); }

function changeQty(id, delta) {
  const item = cart.find(i => i.id === id);
  if (!item) return;
  item.qty += delta;
  if (item.qty <= 0) removeFromCart(id);
  else { saveCart(); updateCartUI(); renderCartItems(); }
}

function updateCartUI() {
  const total = cart.reduce((s, i) => s + i.qty, 0);
  document.querySelectorAll('.cart-count').forEach(el => { el.textContent = total; el.style.display = total ? 'grid' : 'none'; });
}

function renderCartItems() {
  const wrap = document.querySelector('.cart-items');
  if (!wrap) return;
  if (!cart.length) { wrap.innerHTML = '<div style="text-align:center;padding:48px 24px;color:var(--text-muted)"><div style="font-size:3rem;margin-bottom:16px">🛒</div><p style="font-weight:600">Your cart is empty</p><p style="font-size:.88rem;margin-top:8px">Take the skin quiz to get personalized recommendations!</p></div>'; }
  else { wrap.innerHTML = cart.map(item => `
    <div class="cart-item">
      <img class="cart-item-img" src="${item.image}" alt="${item.name}">
      <div class="cart-item-info">
        <div class="cart-item-brand">${item.brand}</div>
        <div class="cart-item-name">${item.name}</div>
        <div class="cart-item-price">₦${item.price.toLocaleString()}</div>
        <div class="cart-item-qty">
          <button class="qty-btn" onclick="changeQty(${item.id},-1)">−</button>
          <span class="qty-num">${item.qty}</span>
          <button class="qty-btn" onclick="changeQty(${item.id},1)">+</button>
        </div>
        <div class="cart-item-remove" onclick="removeFromCart(${item.id})">Remove</div>
      </div>
    </div>`).join(''); }
  const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
  const shipping = subtotal > 50000 ? 0 : 3500;
  const el = document.querySelector('.cart-footer-totals');
  if (el) el.innerHTML = `
    <div class="cart-total-row"><span class="cart-total-label">Subtotal</span><span class="cart-total-value">₦${subtotal.toLocaleString()}</span></div>
    <div class="cart-total-row"><span class="cart-total-label">Shipping</span><span class="cart-total-value">${shipping===0?'<span style="color:var(--success)">FREE</span>':'₦'+shipping.toLocaleString()}</span></div>
    <div class="cart-total-row cart-grand-total"><span>Total</span><span>₦${(subtotal+shipping).toLocaleString()}</span></div>`;
}

// ── Toast ──────────────────────────────────────────────────
function showToast(icon, msg) {
  let t = document.getElementById('toast');
  if (!t) { t = document.createElement('div'); t.id = 'toast'; t.className = 'toast'; document.body.appendChild(t); }
  t.innerHTML = `<span class="toast-icon">${icon}</span><span>${msg}</span>`;
  t.classList.add('show');
  clearTimeout(t._timer);
  t._timer = setTimeout(() => t.classList.remove('show'), 3500);
}

// ── Product Card Builder ────────────────────────────────────
function buildProductCard(p, width = '280px') {
  const badgeHtml = p.badge ? `<span class="badge ${p.badge==='Sale'?'badge-red':p.badge==='New'?'badge-lime':'badge-dark'}">${p.badge}</span>` : '';
  const stars = '★'.repeat(Math.round(p.rating)) + '☆'.repeat(5 - Math.round(p.rating));
  const concernTags = p.concern.slice(0,3).map(c => `<span class="product-concern-tag">${c}</span>`).join('');
  const hasSale = p.originalPrice;
  return `
  <div class="product-card" style="width:${width}">
    <div class="product-img-wrap">
      <a href="product.html?id=${p.id}" style="display:block;height:100%">
        <img src="${p.image}" alt="${p.name}" loading="lazy">
      </a>
      <div class="product-badges">${badgeHtml}</div>
      <button class="product-save-btn" onclick="toggleSave(${p.id},this)" title="Save">♡</button>
      <div class="product-actions-overlay">
        <button class="btn btn-white btn-sm" style="flex:1" onclick="openQuickView(${p.id})">Quick View</button>
        <a href="product.html?id=${p.id}" class="btn btn-dark btn-sm" style="flex:1">Full Details</a>
      </div>
    </div>
    <div class="product-info">
      <div class="product-brand">${p.brand}</div>
      <a href="product.html?id=${p.id}" class="product-name" style="display:block;color:inherit">${p.name}</a>
      <div class="product-concern-tags">${concernTags}</div>
      <div class="product-stars">
        <span class="stars">${stars}</span>
        <span>${p.rating} (${p.reviews.toLocaleString()})</span>
      </div>
      <div class="product-price">
        <span class="price-current">₦${p.price.toLocaleString()}</span>
        ${hasSale ? `<span class="price-original">₦${p.originalPrice.toLocaleString()}</span><span class="price-save">${Math.round((1-p.price/p.originalPrice)*100)}% off</span>` : ''}
      </div>
      <button class="product-add-btn" onclick="addToCart(${p.id})">Add to Cart</button>
      <a href="product.html?id=${p.id}" class="product-explore-btn">Explore →</a>
    </div>
  </div>`;
}

// ── Save / Wishlist ─────────────────────────────────────────
let saved = JSON.parse(localStorage.getItem('kominhoo_saved') || '[]');
function toggleSave(id, btn) {
  if (saved.includes(id)) { saved = saved.filter(s => s !== id); btn.textContent = '♡'; showToast('♡', 'Removed from wishlist'); }
  else { saved.push(id); btn.textContent = '♥'; btn.style.color = 'var(--red)'; showToast('♥', 'Saved to wishlist!'); }
  localStorage.setItem('kominhoo_saved', JSON.stringify(saved));
}

// ── Quick View Modal ────────────────────────────────────────
function openQuickView(id) {
  const p = PRODUCTS.find(pr => pr.id === id);
  if (!p) return;
  const stars = '★'.repeat(Math.round(p.rating)) + '☆'.repeat(5 - Math.round(p.rating));
  const m = document.getElementById('quick-view-modal');
  document.getElementById('qv-content').innerHTML = `
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start">
      <div style="border-radius:var(--r-lg);overflow:hidden;aspect-ratio:1;background:var(--gray-100)">
        <img src="${p.image}" alt="${p.name}" style="width:100%;height:100%;object-fit:cover">
      </div>
      <div>
        <div style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px">${p.brand}</div>
        <h3 style="font-size:1.2rem;font-weight:800;margin-bottom:10px;line-height:1.3">${p.name}</h3>
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px">
          <span style="color:#F59E0B;font-size:1rem">${stars}</span>
          <span style="font-size:.82rem;color:var(--text-muted)">${p.rating} · ${p.reviews.toLocaleString()} reviews</span>
        </div>
        <p style="font-size:.9rem;color:var(--text-secondary);line-height:1.6;margin-bottom:20px">${p.desc}</p>
        <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:20px">
          ${p.ingredients.map(i => `<span class="tag">${i}</span>`).join('')}
        </div>
        <div style="font-size:1.5rem;font-weight:800;margin-bottom:20px">₦${p.price.toLocaleString()}</div>
        <div style="display:flex;gap:10px">
          <button class="btn btn-dark btn-lg" style="flex:1" onclick="addToCart(${p.id});closeModal('quick-view-modal')">Add to Cart</button>
          <a href="product.html?id=${p.id}" class="btn btn-outline btn-lg" style="flex:1;text-align:center">Full Details →</a>
        </div>
      </div>
    </div>`;
  m.classList.add('open');
}
function closeModal(id) { document.getElementById(id)?.classList.remove('open'); }

// ── Navigation ──────────────────────────────────────────────
function initNav() {
  const nav = document.querySelector('.nav');
  if (!nav) return;
  window.addEventListener('scroll', () => nav.classList.toggle('scrolled', window.scrollY > 20));

  // Cart drawer
  document.querySelectorAll('[data-open-cart]').forEach(btn => btn.addEventListener('click', () => {
    document.getElementById('cart-drawer')?.classList.add('open');
    document.getElementById('cart-overlay')?.classList.add('open');
  }));
  document.getElementById('cart-overlay')?.addEventListener('click', () => {
    document.getElementById('cart-drawer')?.classList.remove('open');
    document.getElementById('cart-overlay')?.classList.remove('open');
  });
  document.getElementById('close-cart')?.addEventListener('click', () => {
    document.getElementById('cart-drawer')?.classList.remove('open');
    document.getElementById('cart-overlay')?.classList.remove('open');
  });

  // Search overlay
  document.querySelectorAll('[data-open-search]').forEach(btn => btn.addEventListener('click', () => {
    document.getElementById('search-overlay')?.classList.add('open');
    setTimeout(() => document.querySelector('.search-input')?.focus(), 200);
  }));
  document.getElementById('close-search')?.addEventListener('click', () => document.getElementById('search-overlay')?.classList.remove('open'));
  document.querySelector('.search-input')?.addEventListener('input', handleSearch);

  // Mobile menu
  document.getElementById('nav-toggle')?.addEventListener('click', () => document.getElementById('mobile-menu')?.classList.toggle('open'));
}

function handleSearch(e) {
  const q = e.target.value.toLowerCase();
  const results = q.length < 2 ? [] : PRODUCTS.filter(p => p.name.toLowerCase().includes(q) || p.brand.toLowerCase().includes(q) || p.concern.some(c => c.toLowerCase().includes(q)));
  const wrap = document.getElementById('search-results');
  if (!wrap) return;
  wrap.innerHTML = results.length ? results.slice(0,6).map(p => `
    <div class="search-result-item" onclick="addToCart(${p.id})">
      <img class="search-result-img" src="${p.image}" alt="${p.name}">
      <div class="search-result-info">
        <div class="brand">${p.brand}</div>
        <div class="name">${p.name}</div>
        <div class="price">₦${p.price.toLocaleString()}</div>
      </div>
    </div>`).join('') : (q.length>=2 ? '<div style="padding:20px;text-align:center;color:var(--text-muted)">No results found</div>' : '');
}

// ── Scroll Reveal ───────────────────────────────────────────
function initScrollReveal() {
  const els = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
  if (!els.length) return;
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); } });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
  els.forEach(el => observer.observe(el));
}

// ── Countdown Timer ─────────────────────────────────────────
function initCountdown(targetHours = 23) {
  const end = new Date(); end.setHours(end.getHours() + targetHours);
  function update() {
    const diff = end - new Date();
    if (diff <= 0) return;
    const h = Math.floor(diff / 3600000).toString().padStart(2,'0');
    const m = Math.floor((diff % 3600000) / 60000).toString().padStart(2,'0');
    const s = Math.floor((diff % 60000) / 1000).toString().padStart(2,'0');
    const hEl = document.getElementById('count-h'); const mEl = document.getElementById('count-m'); const sEl = document.getElementById('count-s');
    if(hEl) hEl.textContent = h; if(mEl) mEl.textContent = m; if(sEl) sEl.textContent = s;
  }
  update(); setInterval(update, 1000);
}

// ── Horizontal scroll arrows ────────────────────────────────
function scrollTrack(trackId, dir) {
  const track = document.getElementById(trackId);
  if (!track) return;
  track.scrollBy({ left: dir * 300, behavior: 'smooth' });
}

// ── Dashboard tabs ──────────────────────────────────────────
function switchDashPanel(id) {
  document.querySelectorAll('.dashboard-panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.dash-nav-item').forEach(n => n.classList.remove('active'));
  document.getElementById(id)?.classList.add('active');
  document.querySelector(`[data-panel="${id}"]`)?.classList.add('active');
}

// ── Admin tabs ──────────────────────────────────────────────
function switchAdminPanel(id) {
  document.querySelectorAll('.admin-panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.admin-nav-item').forEach(n => n.classList.remove('active'));
  document.getElementById(id)?.classList.add('active');
  document.querySelector(`[data-admin="${id}"]`)?.classList.add('active');
  document.querySelector('.admin-page-title').textContent = document.querySelector(`[data-admin="${id}"]`)?.querySelector('.admin-nav-icon').nextSibling?.textContent?.trim() || 'Dashboard';
}

// ── Loyalty progress bar animate ───────────────────────────
function animateLoyaltyBar() {
  const fill = document.querySelector('.loyalty-progress-fill');
  if (!fill) return;
  setTimeout(() => { fill.style.width = fill.dataset.pct || '60%'; }, 400);
}

// ── Init ────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  initNav();
  updateCartUI();
  renderCartItems();
  initScrollReveal();
  initCountdown();
  animateLoyaltyBar();

  // Close modals on overlay click
  document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if(e.target === o) o.classList.remove('open'); });
  });

  // Floating quiz button hide on quiz pages
  const floatQuiz = document.getElementById('floating-quiz');
  if (floatQuiz) {
    window.addEventListener('scroll', () => {
      floatQuiz.style.display = window.scrollY > 400 ? 'flex' : 'none';
    });
    floatQuiz.style.display = 'none';
  }
});
