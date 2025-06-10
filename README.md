# Task Manager

This is a simple web application built with **Laravel** and **Vite** as the frontend asset bundler. It serves a management of task of a group or departments.

## Features

-   User Authentication (Login/Register)
-   Task CRUD operations
-   Task Categories
-   Task Priority Levels
-   Task Status Management
-   Task Assignment to Users
-   Task Comments
-   Basic Dashboard with Statistics

## Requirements

-   PHP 8.1 or higher
-   Laravel 10.x
-   MySQL 8.0 or higher
-   Composer

## Installation

Follow these steps to get your application up and running:

### Clone the repository

```bash
git clone https://github.com/jnmagat/TaskManager.git
cd your-project
```

### Install PHP dependencies

```bash
composer install
```

### Install frontend dependencies

```bash
npm install
```

### Then, configure your .env file with the correct database credentials:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

### Migrate the database

```bash
php artisan migrate:fresh --seed
```

### Serve the application

```bash
php artisan serve
```

### Then, in a separate terminal, run the Vite development server:

```bash
npm run dev
```

The application will be available at http://127.0.0.1:8000
