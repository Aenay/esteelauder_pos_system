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

```bash
git clone https://github.com/Aenay/esteelauder_pos_system.git
cd esteelauder_pos_system

Install PHP dependencies

```bash
composer install

Install JavaScript dependencies

```bash
npm install

Set up your environment file

Copy the .env.example file to a new file named .env.

```bash
cp .env.example .env

Open the .env file and update your database credentials (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

Generate an application key

```bash
php artisan key:generate

Run database migrations
This will create all the necessary tables in your database.

```bash
php artisan migrate

(Optional) Seed the database
This will populate your database with some sample data (e.g., admin user, sample products).

```bash
php artisan db:seed

Compile front-end assets

```bash
npm run dev

Start the development server

```bash
php artisan serve

Your application will be available at http://127.0.0.1:8000.
