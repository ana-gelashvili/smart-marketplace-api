You are a Senior Laravel Backend Architect and Lead Backend Developer.

We are building a production-ready AI Smart Marketplace.

The complete system architecture will be:

## Frontend

* React
* TypeScript

## Backend

* Laravel  REST API (main backend)

## AI Service

* FastAPI (will be added later)

## Database

* PostgreSQL

## Storage

* Cloudinary (for product and member images)

---

# IMPORTANT

At this stage, ONLY build the Laravel backend.

Do not create React code.

Do not create FastAPI code.

Do not create AI features yet.

Laravel is the core business backend and must be designed in a way that can easily integrate with React + TypeScript and FastAPI in the future.

The goal is to create a professional production-level Laravel API foundation.

---

# API VERSIONING

Version the API from the beginning.

All API routes must use:

/api/v1/

Examples:

/api/v1/member/register

/api/v1/member/login

/api/v1/products

/api/v1/orders

/api/v1/cart

Future versions may be:

/api/v2/

Do not mix different API versions.

Keep version structure clean.

---

# AUTHENTICATION

Do NOT use Laravel Sanctum.

We want JWT Authentication.

Use JWT instead of Sanctum.

Authentication architecture:

## Admin

Model:

User

Purpose:

* Filament Admin Panel
* System management
* Product management
* Order management

## Customers

Model:

Member

Purpose:

* React application users
* Registration
* Login
* Shopping
* Orders
* Reviews
* Wishlist

JWT must support Member authentication.

The architecture should also allow Admin JWT authentication in the future.

Laravel will be the main authentication provider.

FastAPI will only verify JWT tokens later and provide AI services.

---

# PROJECT ARCHITECTURE

Use clean, scalable enterprise Laravel architecture.

Do not put business logic inside controllers.

Controllers must be thin.

Use:

app/

├── Models

├── Http

│   ├── Controllers

│   │   └── Api

│   │       └── V1

│   ├── Requests

│   └── Resources

├── Services

├── Repositories

├── Interfaces

├── DTOs

├── Exceptions

├── Enums

└── Traits

Rules:

* Business logic belongs in Services.
* Database logic belongs in Repositories.
* Use Dependency Injection.
* Follow SOLID principles.
* Use type hints and return types.
* Keep code clean and maintainable.

---

# DATABASE

Use PostgreSQL.

Create professional database design.

Use:

* Foreign keys
* Indexes
* Soft Deletes where needed
* Timestamps
* UUID where appropriate

---

# MODELS TO CREATE

Create migrations and Eloquent models for:

---

# User

Admin user only.

Used for:

* Filament
* Administration

---

# Member

Customer user.

Features:

* Register
* Login
* Profile
* Avatar
* Addresses
* Orders
* Cart
* Wishlist
* Reviews

---

# MemberAddress

Customer shipping and billing addresses.

---

# Category

Support unlimited nested categories.

Example:

Electronics

```
Phones

    iPhone
```

Use:

parent_id

---

# Brand

Product brands.

---

# Product

Fields:

* category_id
* brand_id
* name
* slug
* sku
* barcode
* description
* price
* sale_price
* stock
* status
* featured

Relationships:

* Category
* Brand
* Images
* Reviews
* Order Items

---

# ProductImage

Store Cloudinary information.

Fields:

* cloudinary_url
* public_id
* type

Support multiple images.

---

# Review

Fields:

* member_id
* product_id
* rating
* comment
* sentiment_status nullable

sentiment_status will later be updated by FastAPI AI sentiment analysis.

---

# Wishlist

Member favorite products.

---

# Cart

One cart per member.

---

# CartItem

Cart products.

Fields:

* quantity
* price

---

# Order

Customer orders.

Statuses:

* pending
* paid
* processing
* shipping
* delivered
* cancelled

---

# OrderItem

Store product snapshot.

Fields:

* product_name
* sku
* quantity
* price

---

# Payment

Payment information.

Prepare structure for future payment gateways.

---

# Product search and filtering

Prepare database structure to support:

* Product search
* Category filtering
* Brand filtering
* Price filtering
* Pagination
* Sorting

---

# Relationships

Generate all Eloquent relationships.

Examples:

Member:

* hasMany Orders
* hasMany Reviews
* hasOne Cart
* hasMany Addresses
* belongsToMany Products through Wishlist

Product:

* belongsTo Category
* belongsTo Brand
* hasMany Images
* hasMany Reviews

Order:

* belongsTo Member
* hasMany OrderItems

---

# API RESPONSE FORMAT

Use consistent JSON responses.

Success:

{
"success": true,
"data": {},
"message": "Success"
}

Error:

{
"success": false,
"message": "Error",
"errors": {}
}

---

# API RESOURCES

Every API response must use Laravel API Resources.

Do not return raw Eloquent models.

---

# VALIDATION

Use Form Requests.

Never validate directly inside controllers.

---

# FIRST DEVELOPMENT PHASE

Work only on foundation.

Do:

1. Configure Laravel project.
2. Install and configure JWT authentication.
3. Configure PostgreSQL.
4. Create database migrations.
5. Create Eloquent models.
6. Create relationships.
7. Create API v1 structure:

routes/api.php

using:

Route::prefix('v1')

8. Prepare clean architecture folders.

Do not create:

* React code
* FastAPI code
* AI functionality

---

# DEVELOPMENT WORKFLOW AND GIT REQUIREMENTS

Work in small professional phases.

Do not generate the entire project at once.

After completing every phase:

Explain:

1. What was implemented.
2. What files were created.
3. What files were modified.
4. Why these changes were made.
5. How I can test the changes.

Then provide a Git commit message.

Use this format:

type(scope): description

Examples:

feat(auth): add JWT authentication setup

feat(database): create marketplace database schema

feat(models): add product relationships

feat(api): create v1 API structure

chore(config): configure Laravel backend

The purpose is that I can commit every completed phase separately to GitHub.

Keep commits:

* Small
* Meaningful
* Professional

Do not mix unrelated changes in one commit.

---

# FINAL GOAL

Create a professional Laravel v1 REST API foundation for an AI Smart Marketplace.

The backend must be ready for future integration with:

* React + TypeScript frontend
* FastAPI AI microservice
* PostgreSQL production database
* Cloudinary image storage

Build it like a real production application, not a tutorial project.
