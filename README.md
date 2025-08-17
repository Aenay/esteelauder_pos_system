Getting Started
To get a local copy up and running, follow these simple steps.

Prerequisites
Make sure you have the following installed on your local machine:

PHP (>= 8.1)

Composer

Node.js & NPM

A local database server (e.g., MySQL)

Installation
Clone the repository

git clone https://github.com/your_username/your_project_name.git
cd your_project_name

Install PHP dependencies

composer install

Install JavaScript dependencies

npm install

Set up your environment file

Copy the .env.example file to a new file named .env.

cp .env.example .env

Open the .env file and update your database credentials (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

Generate an application key

php artisan key:generate

Run database migrations
This will create all the necessary tables in your database.

php artisan migrate

(Optional) Seed the database
This will populate your database with some sample data (e.g., admin user, sample products).

php artisan db:seed

Compile front-end assets

npm run dev

Start the development server

php artisan serve

Your application will be available at http://127.0.0.1:8000.
