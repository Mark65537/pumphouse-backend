<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

<h1 align="center">Это API для проекта <a href="https://github.com/Mark65537/pumphouse-frontend">Водокачка+</a></h1>

## Описание

## Инструкция по запуску

Для запуска локального сервера используйте файл "ZZ) RUN.bat" или команду

```bash
php artisan serve
```
# Authentication API

## Login

The login endpoint is used to authenticate a user by their `name` and `password`. Upon successful authentication, it returns the user's name and a personal access token that can be used for subsequent API requests that require authentication.

### HTTP Request

`POST /api/login`

### Request Headers

| Header | Value |
| ------ | ----- |
| Content-Type | application/json |

### Request Body

The request body should include a JSON object with the following properties:

```json
{
    "name": "your_username",
    "password": "your_password"
}
```
### Response Body

The response will be a JSON object containing the user's name and the authentication token.

### Successful Response

Code: 200 OK

Content:
```json
{
    "user": "your_username",
    "token": "your_auth_token"
}
```

### Example

Here is an example of how to login using curl:

```bat
curl -X POST -H "Content-Type: application/json" \
-d '{"name": "your_username", "password": "your_password"}' \
http://your-api-domain.com/api/login
```