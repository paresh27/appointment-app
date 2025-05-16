# Appointment Booking Application

## Getting Started

Follow the steps below to set up the project locally:

### 1. Clone the Repository

```bash
git clone <repository-url>
cd <repository-directory>
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Create Databases

Create two MySQL databases:

- `appointment_app`
- `appointment_application_test`

You can create them via MySQL CLI or a database manager like phpMyAdmin or TablePlus.

### 4. Configure Environment

Copy the `.env.example` file to `.env` and update your database credentials:

```bash
cp .env.example .env
```

Then generate the application key:

```bash
php artisan key:generate
```

### 5. Run Migrations and Seeders

```bash
php artisan migrate
php artisan db:seed
```

### 6. Start the Local Development Server

```bash
php artisan serve
```

### 7. Run Tests

To run the test suite using Pest:

```bash
composer test
```

---