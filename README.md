# E-Learning Laravel API

API RESTful untuk platform e-learning dengan sistem flashcard dan manajemen kategori, dibangun menggunakan Laravel dan Sanctum untuk autentikasi.

## 📋 Daftar Isi

- [Fitur](#fitur)
- [Teknologi](#teknologi)
- [Prasyarat](#prasyarat)
- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [API Documentation](#api-documentation)
- [Testing](#testing)
- [Kontribusi](#kontribusi)

## ✨ Fitur

### Autentikasi & Manajemen User
- ✅ Register user baru
- ✅ Login dengan email & password
- ✅ Logout
- ✅ Forgot password
- ✅ Reset password dengan token
- ✅ Get user profile

### Manajemen Kategori
- ✅ CRUD kategori pembelajaran
- ✅ List semua kategori (public)
- ✅ Detail kategori
- ✅ Tracking progress user per kategori

### Sistem Flashcard
- ✅ CRUD flashcard
- ✅ Get flashcard berdasarkan kategori
- ✅ Random flashcard untuk belajar
- ✅ Submit attempt/jawaban flashcard
- ✅ History attempt user per flashcard

## 🚀 Teknologi

![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Sanctum](https://img.shields.io/badge/Sanctum-Auth-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Composer](https://img.shields.io/badge/Composer-885630?style=for-the-badge&logo=composer&logoColor=white)

## 📦 Prasyarat

Pastikan sistem Anda sudah terinstall:

- PHP >= 8.1
- Composer
- MySQL >= 5.7 atau MariaDB
- Git

## 🔧 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/rosyiddd666999/e_learning_laravel_api.git
cd e_learning_laravel_api
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Setup Environment

```bash
cp .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

## ⚙️ Konfigurasi

### Database Configuration

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e_learning_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Mail Configuration (untuk Reset Password)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@elearning.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Sanctum Configuration

```env
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
SESSION_DRIVER=cookie
```

### Jalankan Migration & Seeder

```bash
php artisan migrate
php artisan db:seed  # (optional) jika ada seeder
```

## 🏃 Menjalankan Aplikasi

### Development Server

```bash
php artisan serve
```

API akan berjalan di: `http://127.0.0.1:8000`

### Queue Worker (jika menggunakan jobs)

```bash
php artisan queue:work
```

## 📚 API Documentation

Base URL: `http://127.0.0.1:8000/api`

### Authentication Endpoints

#### Register
```http
POST /register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### Login
```http
POST /login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}

Response:
{
  "success": true,
  "token": "your_sanctum_token_here",
  "user": { ... }
}
```

#### Logout
```http
POST /logout
Authorization: Bearer {token}
```

#### Forgot Password
```http
POST /forgot-password
Content-Type: application/json

{
  "email": "john@example.com"
}
```

#### Reset Password
```http
POST /reset-password
Content-Type: application/json

{
  "token": "reset_token_from_email",
  "email": "john@example.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

### Category Endpoints

#### Get All Categories (Public)
```http
GET /categories
```

#### Get Category Detail (Public)
```http
GET /categories/{category_id}
```

#### Create Category (Protected)
```http
POST /categories
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Programming",
  "description": "Learn programming fundamentals"
}
```

#### Update Category (Protected)
```http
PUT /categories/{category_id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Updated Category Name",
  "description": "Updated description"
}
```

#### Delete Category (Protected)
```http
DELETE /categories/{category_id}
Authorization: Bearer {token}
```

#### Get User Progress in Category (Protected)
```http
GET /categories/{category_id}/progress
Authorization: Bearer {token}
```

### Flashcard Endpoints

#### Get All Flashcards (Protected)
```http
GET /flashcards
Authorization: Bearer {token}
```

#### Get Flashcard Detail (Protected)
```http
GET /flashcards/{flashcard_id}
Authorization: Bearer {token}
```

#### Get Flashcards by Category (Protected)
```http
GET /flashcards/{category_id}
Authorization: Bearer {token}
```

#### Get Random Flashcards (Protected)
```http
GET /flashcards/random?limit=10
Authorization: Bearer {token}
```

#### Create Flashcard (Protected)
```http
POST /flashcards
Authorization: Bearer {token}
Content-Type: application/json

{
  "category_id": 1,
  "question": "What is Laravel?",
  "answer": "Laravel is a PHP web framework"
}
```

#### Update Flashcard (Protected)
```http
PUT /flashcards/{flashcard_id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "question": "Updated question",
  "answer": "Updated answer"
}
```

#### Delete Flashcard (Protected)
```http
DELETE /flashcards/{flashcard_id}
Authorization: Bearer {token}
```

#### Submit Flashcard Attempt (Protected)
```http
POST /flashcards/attempt/{flashcard_id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "answer": "User's answer",
  "is_correct": true
}
```

#### Get User Attempts (Protected)
```http
GET /flashcards/attempt/{flashcard_id}
Authorization: Bearer {token}
```

### User Endpoint

#### Get Current User (Protected)
```http
GET /user
Authorization: Bearer {token}
```

## 🧪 Testing

### Run Tests

```bash
php artisan test
```

### Run Specific Test

```bash
php artisan test --filter TestName
```

## 📁 Struktur Folder

```
e_learning_laravel_api/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Auth/
│   │       │   ├── LoginController.php
│   │       │   ├── RegisterController.php
│   │       │   ├── LogoutController.php
│   │       │   ├── ForgotPasswordController.php
│   │       │   └── ResetPasswordController.php
│   │       ├── Category/
│   │       │   ├── GetCategoryController.php
│   │       │   ├── CreateCategoryController.php
│   │       │   ├── UpdateCategoryController.php
│   │       │   └── DeleteCategoryController.php
│   │       ├── Flashcard/
│   │       │   ├── GetFlashcardController.php
│   │       │   ├── CreateFlashcardController.php
│   │       │   ├── UpdateFlashcardController.php
│   │       │   └── DeleteFlashcardController.php
│   │       └── Users/
│   │           └── GetUserController.php
│   └── Models/
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   ├── api.php
│   └── web.php
├── tests/
├── .env.example
├── composer.json
└── README.md
```

## 🔒 Security

- Password di-hash menggunakan bcrypt
- API dilindungi dengan Laravel Sanctum
- CSRF protection untuk form
- Rate limiting untuk API endpoints
- Validasi input pada semua endpoints

## 📝 Response Format

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["Error detail"]
  }
}
```

### HTTP Status Codes
- `200` - OK
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## 🤝 Kontribusi

Kontribusi selalu diterima! Silakan fork repository ini dan buat pull request.

1. Fork Project
2. Create Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to Branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

Project ini menggunakan MIT License.

## 👨‍💻 Author

**Rosyid**
- GitHub: [@rosyiddd666999](https://github.com/rosyiddd666999)

## 📞 Support

Jika ada pertanyaan atau issue, silakan buat issue di GitHub repository atau hubungi melalui email.

---

⭐️ Jangan lupa berikan star jika project ini membantu Anda!
