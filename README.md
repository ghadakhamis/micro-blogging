<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Microblogging

Microblogging is a social network service. Microblogging App allows  users post and interact with messages known as "tweets".

# Requirements
  
- PHP 8.2
- Mysql
- Apache
- Composer

# Installation Steps:

a. Clone the repository 

```bash
    git clone https://github.com/ghadakhamis/micro-blogging.git
  ```
- Branches: main

b. In project root copy ".env.example" to ".env", and fill in all requirements to match your need and host settings.

```bash
  cp .env.example .env
  ```
- Edit the new .env file and change a lot of variables

c. Install all the dependencies using composer

```bash
  composer install
  ```
d. Generate a new application key

```bash
  php artisan key:generate
  ```
e. Run the database migrations (Set the database connection in .env before migrating)

```bash
  php artisan migrate
  ```    

f. For local development server
```bash
  php artisan serve
  ```                                                                                                  
You can now access the server at http://localhost:8000     

You can access the API documentation at https://documenter.getpostman.com/view/5872734/2sA35JzL1K