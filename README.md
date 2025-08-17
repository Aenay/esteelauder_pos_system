Perfect 🚀 I’ll turn your installation guide into a nice **README.md section** with proper formatting, including your repo link and bash code blocks.

Here’s how it would look:

````markdown
# Estee Lauder POS System

Getting Started  
To get a local copy up and running, follow these simple steps.

---

## 📋 Prerequisites  
Make sure you have the following installed on your local machine:

- PHP (>= 8.1)  
- Composer  
- Node.js & NPM  
- A local database server (e.g., MySQL)  

---

## ⚙️ Installation  

### 1️⃣ Clone the repository  
```bash
git clone https://github.com/Aenay/esteelauder_pos_system.git
cd esteelauder_pos_system
````

### 2️⃣ Install PHP dependencies

```bash
composer install
```

### 3️⃣ Install JavaScript dependencies

```bash
npm install
```

### 4️⃣ Set up your environment file

```bash
cp .env.example .env
```

Open the `.env` file and update your database credentials (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

### 5️⃣ Generate an application key

```bash
php artisan key:generate
```

### 6️⃣ Run database migrations

This will create all the necessary tables in your database.

```bash
php artisan migrate
```

### 7️⃣ (Optional) Seed the database

This will populate your database with some sample data (e.g., admin user, sample products).

```bash
php artisan db:seed
```

### 8️⃣ Compile front-end assets

```bash
npm run dev
```

### 9️⃣ Start the development server

```bash
php artisan serve
```

Your application will be available at:
👉 [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 📌 Repository

[Estee Lauder POS System](https://github.com/Aenay/esteelauder_pos_system.git)

```

