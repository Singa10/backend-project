# Book Store Backend 
| Name              | ID           | Username  |
|-------------------|--------------|-----------|
| Singitan Niguse   | ETS1290/16   | Singa10   |
| Seyfadin          | ETS1273/16   | webseyf   |
| Abdissa Habtamu   | EST1668/16   | abdissa21 |
| Sofonias Mitiku   | ETS1297/16   | sofonisiah|
| Selman Awel       | ETS1263/16   | Lesnam675 |

## ✨ To Be Added Backend Features
- **User Authentication** — Registration, login, logout with secure password hashing (bcrypt) and session management
- **Email Verification** — Real email validation using AbstractAPI to block fake/disposable emails
- **Dynamic Product Catalog** — Books fetched from MySQL with search, filters, sorting, and pagination
- **Category System** — 8 book categories stored in database
- **Shopping Cart** — Database-stored cart with add, update, remove operations for logged-in users
- **Checkout & Orders** — Order placement with shipping info, saved in MySQL with status tracking
- **Payment Gateway** — PayPal, Telebirr, and CBE payment integration with transaction tracking
- **Admin Dashboard** — Real-time stats, sales charts, category charts powered by live database queries
- **Admin CRUD** — Add, edit, delete books | View and manage orders | View and delete users
- **Contact System** — AJAX contact form saved to database with auto-reply email
- **Newsletter** — Email subscription with duplicate detection
- **Message Management** — Admin can view full messages, mark as read
- **Email Notifications** — Welcome email on registration, order confirmation after payment, contact auto-reply
- **Security** — Prepared statements (SQL injection prevention), input sanitization, role-based access control


## 📅 8-Week Development Plan

### Week 1 — Project Setup & Planning
- Finalize folder structure and file organization
- Create GitHub repo and add all members
- Design database schema (tables, relationships)
- Create bookstore.sql with all tables and seed data
- Agree on naming conventions (variables, functions, files)

### Week 2 — Database & Authentication
- Build database connection (config/db.php)
- Build helper functions (includes/functions.php)
- Build session management (session.php)
- Build registration and login handlers
- Update login page forms with name, action, method attributes
- Cross-check: Test registration and login end-to-end

### Week 3 — Shop & Book Catalog
- Build get_books.php with filters, search, sort, pagination
- Build get_categories.php and get_book_detail.php
- Connect shop page JavaScript to fetch from PHP backend
- Build global auth-check for navbar updates on all pages
- Cross-check: Verify books load from database, test all filter combinations

### Week 4 — Shopping Cart & Checkout
- Build add_to_cart, view_cart, update_cart, remove_from_cart
- Build place_order.php to create orders in database
- Build checkout page with shipping form
- Connect cart dropdown to database instead of localStorage
- Cross-check: Test full flow from add to cart to order creation

### Week 5 — Payment Gateway & Admin Dashboard
- Add payment method columns to orders table
- Build process_payment.php for PayPal, Telebirr, CBE
- Build checkout payment UI with real logos
- Build dashboard_stats.php with real database queries
- Build admin page with stats cards and charts
- Cross-check: Test all 3 payment methods, verify admin stats match database

### Week 6 — Admin CRUD & Contact System
- Build admin book management (add, edit, delete)
- Build admin order management (view, update status)
- Build admin user management (view, delete)
- Build contact form handler with AJAX
- Build newsletter subscription handler
- Build admin message viewer with mark as read
- Cross-check: Test all CRUD operations, verify contact saves to database

### Week 7 — Email System & Security
- Set up PHPMailer with Gmail SMTP
- Build email templates (welcome, order confirmation, auto-reply)
- Integrate AbstractAPI for real email verification
- Build user profile management (get/update)
- Security audit on all PHP files (prepared statements, sanitization)
- Cross-check: Test emails send correctly, test fake email rejection, run SQL injection tests

### Week 8 — Testing & Final Review
- Full end-to-end testing (30-point checklist)
- Fix all bugs found during testing
- Cross-browser testing (Chrome, Firefox, Edge)
- Mobile responsiveness testing
- Final code review and README update
- Cross-check: All 30 tests passing, demo walkthrough on fresh XAMPP install
