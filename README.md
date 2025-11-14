# 📦 Orders Processing System – README

This project is a backend-focused Laravel application designed to process orders asynchronously using queued jobs, generate KPIs using Redis, send notifications, and handle refunds with full idempotency.

------------------------------------------------------------------------

# 🛠️ Setup Instructions

## 1. Clone

    git clone <repository-url>
    cd nextventures

## 2. Install

    composer install
    cp .env.example .env
    php artisan key:generate

## 3. Environment

Configure DB + Redis in `.env`.

## 4. Migrate

    php artisan migrate --seed


## 5. Or Simple Run Init Command

    php artisan app:init --force

------------------------------------------------------------------------

# 📥 CSV Import

    php artisan orders:import orders.csv

------------------------------------------------------------------------

# 📊 Horizon

    php artisan horizon

Visit: `/horizon`

------------------------------------------------------------------------

# 🧵 Queues

    php artisan queue:work

------------------------------------------------------------------------

# 🧾 Refund Testing

    POST {{BASE_URL}}/api/v1/orders/refund/request

    { 
        "amount": 50.0,
        "order": 5,
        "user": 1
    }

------------------------------------------------------------------------

# 📂 Structure

    app/
      Console/
      Models/
    routes/
    database/
    src/
        domain/
            Order/
                Actions/
                Controllers/
                Data/
                Factories/
                Jobs/
                Models/
                Requests/
                Rules/
                Seeders/
            Product/
            Refund/
            Payment/
        support/
------------------------------------------------------------------------
