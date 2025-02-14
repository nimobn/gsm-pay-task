# Laravel Application with MySQL and Swagger (Non-Dockerized Local Setup)

This README provides basic steps to run the Laravel app locally with MySQL and Swagger.

## Prerequisites

*   PHP (>= 8.2) with required extensions (BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, GD, MySQLi)
*   Composer
*   MySQL
*   Git

## Installation and Setup

1.  **Clone:**

    ```bash
    git clone <https://github.com/nimobn/gsm-pay-task.git>
    cd <your-laravel-app-directory>
    ```

2.  **Install PHP Dependencies:**

    ```bash
    composer install
    ```

3.  **Environment Configuration:**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    Configure `.env` with your MySQL details:

    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password
    ```
    
4.  **Install Passport:**

    ```bash
    php artisan passport:install
    ```
    
5.  **Database Migrations:**

    ```bash
    php artisan migrate
    ```

6.  **Database Seeders:**

    ```bash
    php artisan db:seed
    ```

7.  **Generate Swagger Doc:**

    ```bash
    php artisan l5-swagger:generate
    ```

## Running the Application

```bash
php artisan serve
```
All apis are available here: http://localhost:8000/api/documentation
