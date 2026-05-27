# Kominhoo Beauty — Session Notes

## What Was Done

### Architecture Decision
Converted the existing HTML/CSS/JS prototype into a two-app Laravel architecture:
- **`kominhoo/backend/`** — Laravel API-only app (MySQL + Sanctum), serves both the frontend and future mobile apps
- **`kominhoo/frontend/`** — Separate Laravel app that consumes the backend via HTTP, renders Blade views

---

### Backend (`kominhoo/backend/`)

#### Setup
- Laravel installed with `composer install --no-dev` (workaround for corrupted `laravel/pint` zip)
- `.env` configured for XAMPP MySQL via Unix socket (`DB_SOCKET=C:/xampp/mysql/mysql.sock`)
- Laravel Sanctum installed and configured for both SPA sessions and mobile Bearer tokens
- CORS set to `allowed_origins: ['*']` — open for mobile app connections

#### Database — 12 Migrations
| Migration | Table |
|---|---|
| `create_users_table` | Extended with `phone`, `role`, `skin_type`, `loyalty_points`, `tier`, `avatar` |
| `create_products_table` | `skin_types` and `images` stored as JSON |
| `create_bundles_table` | Skincare bundle sets |
| `create_bundle_product_table` | Pivot with `quantity` |
| `create_orders_table` | `shipping_address` as JSON, status enum |
| `create_order_items_table` | Polymorphic (`morphs`) — handles both products and bundles |
| `create_reviews_table` | Status enum: `pending/approved/rejected` |
| `create_community_posts_table` | Tags as JSON, status enum |
| `create_quiz_results_table` | Answers + recommended product IDs as JSON |
| `create_promotions_table` | Percentage and fixed discount types |
| `create_subscribers_table` | Newsletter subscribers |
| `create_guides_table` | Skincare blog/guide articles |

#### Models — 11 Models
`User`, `Product`, `Bundle`, `Order`, `OrderItem` (morphTo), `Review`, `CommunityPost`, `QuizResult`, `Promotion`, `Subscriber`, `Guide`

#### API Controllers — 11 Controllers (`app/Http/Controllers/Api/V1/`)
All controllers follow a consistent `apiResponse()` format: `{ success, data, message, errors }`

| Controller | Key Methods |
|---|---|
| `AuthController` | `register`, `login`, `logout`, `me` |
| `ProductController` | Index with filters (category, brand, skin_type, search, featured) |
| `BundleController` | CRUD + product sync |
| `OrderController` | Store creates polymorphic order items |
| `ReviewController` | Approve/reject recalculates product rating |
| `CommunityController` | Public approved posts, auth post creation |
| `QuizController` | Submit stores result, returns recommended products by skin type |
| `PromotionController` | `applyCode()` validates coupon + calculates discount |
| `SubscriberController` | Subscribe / unsubscribe |
| `GuideController` | CRUD with category/search filters |
| `UserController` | CRUD (create via `/auth/register`) |

#### Routes — 47 routes at `/api/v1/`
- **Public:** register, login, GET products/bundles/guides/community, POST quiz, apply promo, subscribe
- **Authenticated (Sanctum):** logout, me, orders, POST reviews, POST community posts, quiz history
- **Admin only:** CRUD products/bundles/guides, manage reviews/community, CRUD promotions, view subscribers, CRUD users

#### Middleware
- `AdminMiddleware` — checks `user->role === 'admin'`, registered as `'admin'` in Kernel

---

### Frontend (`kominhoo/frontend/`)

#### Setup
- Separate Laravel install consuming the backend API via `Http::` facade
- `API_BASE_URL` in `.env`, exposed via `config('app.api_base_url')`
- `AuthenticateUser` middleware — checks `session('api_token')`, redirects to login if missing
- Session stores `api_token` and `user` array on login

#### Controllers — 7 Controllers
| Controller | Responsibility |
|---|---|
| `AuthController` | Login/register via API, session management |
| `PageController` | Home, community gallery, quiz results |
| `ShopController` | Product listing with filters, product detail, review submit |
| `CheckoutController` | Apply promo (JSON), place order (JSON) |
| `DashboardController` | Overview, orders list, profile update |
| `QuizController` | Quiz page, submit to API, store result in session |
| `CommunityController` | Submit post, newsletter subscribe |

#### Blade Views — 10 Views Created
| View | Description |
|---|---|
| `layouts/app.blade.php` | Full layout with auth-aware nav, cart drawer, flash messages, footer |
| `pages/home.blade.php` | Hero, featured products/bundles from API, quiz CTA, community, guides |
| `pages/shop.blade.php` | Filter form, products grid, bundles section |
| `pages/product.blade.php` | Product detail, reviews list, review form |
| `pages/login.blade.php` | Login form |
| `pages/signup.blade.php` | Registration form |
| `pages/dashboard.blade.php` | Sidebar, stats, recent orders, profile update |
| `pages/quiz.blade.php` | 5-step multi-question quiz with JS step navigation |
| `pages/checkout.blade.php` | Cart from localStorage, shipping form, payment selector, coupon, AJAX order |
| `pages/community.blade.php` | Masonry post grid, post submission form |
| `pages/results.blade.php` | Skin type profile, care tips, recommended products, CTA to filtered shop |

#### Public Assets
- `public/css/style.css` — copied from original prototype
- `public/js/app.js` — copied from original prototype (cart logic, mini-cart drawer)

---

### Bugs Fixed During the Session
| Bug | Fix |
|---|---|
| DNS resolution failure (Composer couldn't reach packagist.org) | User changed DNS to 8.8.8.8 via Windows Network Settings |
| `laravel/pint` corrupt zip during install | Ran `composer install --no-dev` to skip all dev packages |
| MySQL connection refused on `127.0.0.1` | Added `DB_SOCKET=C:/xampp/mysql/mysql.sock` and changed host to `localhost` |
| `PromotionController::validate()` conflicted with base Controller | Renamed to `applyCode()` and updated route |
| `can:admin` middleware requires Gate/Policy setup | Created custom `AdminMiddleware`, registered as `'admin'` in Kernel |
| `CheckoutController::placeOrder` returned a redirect | Changed to return JSON so the fetch-based checkout JS could handle the response |
| `PageController::results` passed wrong session structure to view | Fixed to read `result.skin_type` and `result.answers` from nested API response |

---

## Next Steps

### 1. Run Database Migrations
```bash
cd c:/xampp/htdocs/kominhoo/backend
php artisan migrate
```
If it fails, check the MySQL socket path in `.env` and confirm XAMPP MySQL is running.

### 2. Seed Demo Data
Create and run seeders for:
- Admin user (`role = 'admin'`)
- Sample products (with skin_types JSON, images)
- Sample bundles
- Sample guides
- Sample community posts (status = approved)
- Sample promotions

```bash
php artisan make:seeder DatabaseSeeder
php artisan db:seed
```

### 3. Test the Backend API
Use Postman or curl to verify key endpoints before touching the frontend:
- `POST /api/v1/auth/register`
- `POST /api/v1/auth/login` → save the token
- `GET /api/v1/products`
- `POST /api/v1/quiz` with `Bearer <token>`
- `POST /api/v1/promotions/apply`

### 4. Test the Frontend End-to-End Flow
Visit `http://localhost/kominhoo/frontend/public/` and walk through:
- [ ] Home page loads featured products/bundles from API
- [ ] Shop page filters work
- [ ] Product detail page and review submission
- [ ] Register → Login → Session stored correctly
- [ ] Quiz → Results page shows skin type + recommended products
- [ ] Checkout: add item to cart, apply coupon, place order
- [ ] Dashboard: orders list, profile update
- [ ] Community: view posts, submit a post
- [ ] Sign out clears session

### 5. Handle Image Uploads (Community Posts)
Currently community posts accept an image URL string. If you want actual file uploads:
- Add `enctype="multipart/form-data"` to the community form (already there)
- Store uploads on the backend via Laravel's `Storage` facade
- Update `CommunityController` on both backend and frontend to handle file uploads

### 6. Admin Panel
The original prototype had an `admin.html`. Decide whether to:
- **Option A:** Keep it as a standalone HTML file pointing to the backend API (quick)
- **Option B:** Build a proper `/admin` section in the frontend as Blade views behind the `auth.user` + admin role check (complete)

If Option B, create an `AdminController` with views for: product management, order management, review moderation, community moderation, user list, promo codes.

### 7. Set Up XAMPP Virtual Hosts (Optional but Recommended)
Clean up URLs from `/kominhoo/backend/public/` to `api.kominhoo.test`:

In `C:/xampp/apache/conf/extra/httpd-vhosts.conf`:
```apache
<VirtualHost *:80>
    ServerName api.kominhoo.test
    DocumentRoot "C:/xampp/htdocs/kominhoo/backend/public"
</VirtualHost>
<VirtualHost *:80>
    ServerName kominhoo.test
    DocumentRoot "C:/xampp/htdocs/kominhoo/frontend/public"
</VirtualHost>
```
Then add both to `C:/Windows/System32/drivers/etc/hosts`:
```
127.0.0.1   kominhoo.test
127.0.0.1   api.kominhoo.test
```
Update `.env` files on both apps to use the new URLs, then update `API_BASE_URL` in the frontend.

### 8. Mobile App Integration
The backend is already mobile-ready (open CORS, Sanctum Bearer tokens). When building the mobile app:
- `POST /api/v1/auth/login` → receive token
- Include `Authorization: Bearer <token>` header on all authenticated requests
- Base URL: `http://api.kominhoo.test/api/v1` (or the hosted domain)

### 9. Deploy to Production
When ready:
- Backend: shared hosting or VPS with PHP 8.0+, MySQL, set `APP_ENV=production`
- Frontend: same server or separate, update `API_BASE_URL` to the production API domain
- Set all `APP_DEBUG=false`, generate fresh `APP_KEY`, configure proper CORS origins (replace `*` with the frontend domain)
