# Thiết Kế Decor

Nền tảng mua bán file thiết kế, design và assets dành cho các nhà thiết kế và khách hàng Việt Nam.

## Tech Stack

- **Backend:** Laravel 12, PHP 8.2+
- **Frontend:** Blade, Tailwind CSS v3, DaisyUI v5, Alpine.js
- **Tìm kiếm:** Meilisearch (tích hợp qua Laravel Scout)
- **Build tool:** Vite
- **Testing:** Pest PHP
- **Deployment:** Docker

## Tính năng

- Duyệt và tìm kiếm file thiết kế theo danh mục, thẻ (tag)
- Tìm kiếm toàn văn nhanh chóng với Meilisearch
- Quản lý sản phẩm, danh mục và tags (admin)
- Xác thực người dùng (Laravel Breeze)
- Quản lý hồ sơ người dùng
- Form liên hệ

## Yêu cầu hệ thống

- PHP >= 8.2
- Composer
- Node.js & pnpm
- Meilisearch instance
- MySQL / PostgreSQL

## Cài đặt

### 1. Clone repository

```bash
git clone https://github.com/nhat191024/Design-Library.git
cd Design-Library
```

### 2. Cài đặt dependencies

```bash
composer install
pnpm install
```

### 3. Cấu hình môi trường

```bash
cp .env.example .env
php artisan key:generate
```

Cập nhật file `.env` với thông tin database và Meilisearch:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=design_library
DB_USERNAME=root
DB_PASSWORD=

SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://localhost:7700
MEILISEARCH_KEY=your_master_key
```

### 4. Migrate & Seed database

```bash
php artisan migrate --seed
```

### 5. Index dữ liệu vào Meilisearch

```bash
php artisan scout:import "App\Models\Product"
```

### 6. Build assets

```bash
pnpm run build
# hoặc để dev
pnpm run dev
```

### 7. Khởi động server

```bash
php artisan serve
```

## Chạy với Docker

```bash
docker compose up -d
```

Ứng dụng sẽ chạy tại `http://localhost:10013`.

## Chạy Tests

```bash
php artisan test
# hoặc
./vendor/bin/pest
```

## Cấu trúc thư mục chính

```
app/
├── Http/
│   ├── Controllers/     # Controllers cho client và admin
│   ├── Requests/        # Form Request validation
│   └── Services/        # SearchService (Meilisearch)
├── Models/              # Eloquent models (Product, Category, Tag, ...)
└── View/Components/     # Blade components
database/
├── migrations/
└── seeders/
resources/views/
├── admin/               # Giao diện quản trị
├── client/              # Giao diện người dùng
├── components/          # Blade components dùng chung
└── layouts/             # Layout chính
```

## License

[MIT](https://opensource.org/licenses/MIT)
