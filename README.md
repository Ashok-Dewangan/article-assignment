# Article Assignment API

This is a Laravel-based API project for managing articles with user authentication using Laravel Sanctum. All API requests and responses are encrypted and decrypted based on the user's API key.

## Features

-   User Registration
-   User Login
-   User Logout
-   Create, Read, Update, Delete (CRUD) operations for articles
-   Encrypted API requests and responses

## Requirements

-   PHP >= 8.0
-   Composer
-   Laravel >= 9.x
-   MySQL or any other supported database

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/your-username/article-assignment.git
    cd article-assignment
    ```

2. Install dependencies:

    composer install

3. Copy the [.env.example] file to [.env] and configure your environment variables:

    ```bash
    cp .env.example .env
    ```

4. Generate the application key:

    ```bash
    php artisan key:generate
    ```

5. Run the database migrations:

    ```bash
    php artisan migrate
    ```

6. Start the development server:

    ```bash
    php artisan serve
    ```

## API Endpoints

### Authentication

-   **Register**: `POST /api/register`

    -   Request Body:
        ```json
        {
            "name": "encrypted_name",
            "email": "encrypted_email",
            "password": "encrypted_password"
        }
        ```

-   **Login**: `POST /api/login`

    -   Request Body:
        ```json
        {
            "email": "encrypted_email",
            "password": "encrypted_password"
        }
        ```

-   **Logout**: `POST /api/logout`
    -   Headers:
        Authorization-: Bearer `your_generated_token`

### Articles

-   **Get All Articles**: `GET /api/articles`

    -   Headers:
        -   Authorization: Bearer `your_generated_token`

-   **Create Article**: `POST /api/articles`

    -   Headers:
        -   Authorization: Bearer `your_generated_token`
    -   Request Body:
        ```json
        {
            "title": "encrypted_title",
            "content": "encrypted_content"
        }
        ```

-   **Get Single Article**: `GET /api/articles/{id}`

    -   Headers:
        -   Authorization: Bearer `your_generated_token`

-   **Update Article**: `PUT /api/articles/{id}`

    -   Headers:
        -   Authorization: Bearer `your_generated_token`
    -   Request Body:
        ```json
        {
            "title": "encrypted_title",
            "content": "encrypted_content"
        }
        ```

-   **Delete Article**: `DELETE /api/articles/{id}`
    -   Headers:
        -   Authorization: Bearer `your_generated_token`

## Middleware

The `EncryptDecryptMiddleware` is used to encrypt and decrypt API requests and responses. It is applied to the routes in [api.php].

```php
// filepath: routes/api.php
Route::post('register', [AuthController::class, 'register'])->middleware('encrypt.decrypt');
Route::post('login', [AuthController::class, 'login'])->middleware('encrypt.decrypt');

Route::middleware(['auth:sanctum', 'encrypt.decrypt'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::apiResource('articles', ArticleController::class);
});
```

# Here is an example of how to encrypt data in Postman:

# Encrypt Request Data:

Use Laravel's `Crypt::encryptString()` method to encrypt the request data before sending it.

# Decrypt Response Data:

Use Laravel's `Crypt::decryptString()` method to decrypt the response data after receiving it.
