# Estee Lauder POS System

## Getting Started
To get a local copy up and running, follow these simple steps.

### Prerequisites
Make sure you have the following installed on your local machine:
- PHP (>= 8.1)
- Composer
- Node.js & NPM
- A local database server (e.g., MySQL)

### Installation
Clone the repository:
```bash
git clone https://github.com/Aenay/esteelauder_pos_system.git
cd esteelauder_pos_system
Install PHP dependencies:

bash
Copy
Edit
composer install
Install JavaScript dependencies:

bash
Copy
Edit
npm install
Set up your environment file:

bash
Copy
Edit
cp .env.example .env
Open the .env file and update your database credentials (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

Generate an application key:

bash
Copy
Edit
php artisan key:generate
Run database migrations (this will create all the necessary tables in your database):

bash
Copy
Edit
php artisan migrate
(Optional) Seed the database with sample data:

bash
Copy
Edit
php artisan db:seed
Compile front-end assets:

bash
Copy
Edit
npm run dev
Start the development server:

bash
Copy
Edit
php artisan serve
Your application will be available at http://127.0.0.1:8000.
