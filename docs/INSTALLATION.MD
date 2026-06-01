# Installation Guide

## Prerequisites

Sebelum menjalankan aplikasi, pastikan perangkat telah terpasang:

* PHP 8.2 atau lebih baru
* Composer
* Node.js dan NPM
* MySQL
* Git

## Clone Repository

```bash
git clone <repository-url>
cd singgalang-jaya-travel
```

## Install Dependency Backend

```bash
composer install
```

## Install Dependency Frontend

```bash
npm install
```

## Konfigurasi Environment

Salin file environment:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

## Konfigurasi Database

Atur konfigurasi database pada file `.env`.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=singgalang_travel
DB_USERNAME=root
DB_PASSWORD=
```

## Jalankan Migration

```bash
php artisan migrate
```

## Menjalankan Aplikasi

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

Aplikasi dapat diakses melalui:

```
http://127.0.0.1:8000
```
