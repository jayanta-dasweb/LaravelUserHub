## Introduction

This Laravel project is built with Laravel 10 and incorporates various libraries and packages to enhance functionality. The main technologies used include jQuery, Select2, SweetAlert2, DataTables, Spatie for role management, Swagger for API documentation, Bootstrap 5, and PhpSpreadsheet for reading Excel files.

## Prerequisites

- PHP 8.1 or higher
- Composer 2.2.0 or higher
- PostgreSQL

## Installation

1. **Clone the repository:**
    ```bash
    git clone <repository_url>
    cd <repository_directory>
    ```

2. **Install dependencies:**
    ```bash
    composer install
    ```

3. **Copy the example environment file and configure your environment:**
    ```bash
    cp .env.example .env
    ```

4. **Generate an application key:**
    ```bash
    php artisan key:generate
    ```

5. **Configure your database settings in the `.env` file:**
    ```
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=nic_assessment_db
    DB_USERNAME=your_database_username
    DB_PASSWORD=your_database_password
    ```

6. **Run database migrations and seed the database:**
    ```bash
    php artisan migrate --seed
    ```

## Super Admin Credentials

- **Email:** jayantadas.dev@gmail.com
- **Password:** 123456789

## Main Functionalities

- **User Registration and Login:** Users can register and log in. If a user does not have any assigned roles, a Bootstrap alert will notify them.
- **Role Management:** The super admin can assign roles to users, create new roles, and assign permissions to roles.
- **User Management:** The super admin can view users without roles, assign roles, and manage user details.
- **Bulk User Data Import:** Users can upload Excel files (.xls, .xlsx, or .csv) to import user data in bulk. The system will validate the data and provide feedback on any issues.
- **Open REST API:** Two APIs are available for fetching user details and roles. API documentation can be accessed at [http://127.0.0.1:8000/api/documentation](http://127.0.0.1:8000/api/documentation).

## Process Flow

1. **User Registration:**
    - Users can register themselves.
    - After registration, users can log in using their email and password.
    - If the user does not have an assigned role, a Bootstrap alert will be displayed.

2. **Super Admin Management:**
    - Log in using super admin credentials.
    - Access the "New User" sidebar menu to view users without roles.
    - Assign roles to users from this page. Once a role is assigned, the user will move to the "All Users" page.

3. **Roles Management:**
    - Create new roles and assign permissions.
    - Manage existing roles and permissions.

4. **Bulk User Data Import:**
    - Upload Excel files to import user data.
    - The system will validate the data, remove any rows with issues, and provide a detailed alert with the reasons for removal.
    - Update or remove data before final submission.
    - Import bulk user data with roles.

5. **Open REST API:**
    - Two APIs are available:
        - **Get all user details, roles, and permissions.**
        - **Get specific user details with role and permissions.**
    - Use the Swagger documentation to try the APIs: [http://127.0.0.1:8000/api/documentation](http://127.0.0.1:8000/api/documentation).

## Necessary Commands

- **Run the application:**
    ```bash
    php artisan serve
    ```

- **Migrate the database:**
    ```bash
    php artisan migrate
    ```

- **Seed the database:**
    ```bash
    php artisan db:seed
    ```

## Additional Information

- **Libraries and Packages Used:**
    - Laravel 10
    - jQuery
    - Select2
    - SweetAlert2
    - DataTables
    - Spatie for role management
    - Swagger for API documentation
    - Bootstrap 5
    - PhpSpreadsheet for reading Excel files