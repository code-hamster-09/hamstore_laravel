# HamStore — Laravel API

Бэкенд интернет-магазина. Пет-проект, на котором я разбираю реальные задачи e-commerce: авторизацию, работу с заказами и приём оплаты.

- Фронтенд (Next.js): [code-hamster-09/ham-store](https://github.com/code-hamster-09/ham-store) · демо: [ham-store.vercel.app](https://ham-store.vercel.app)

## Стек

- PHP 8.3, Laravel 13, Laravel Sanctum
- PostgreSQL 16
- Docker (php-fpm, nginx, postgres)

## Реализовано

- **Авторизация** через Sanctum-токены: регистрация, логин, выход с текущего или со всех устройств.
- **Товары**: CRUD с бренд-, категорийными и цветовыми связями. Изменять и удалять товар может только его продавец. Удаление мягкое (soft delete), поэтому старые заказы не ломаются.
- **Корзина**: CRUD позиций с проверкой владельца. Уникальность позиции задаётся связкой товар + цвет.
- **Заказы**: модели заказа и позиций. В позиции сохраняется снимок цены и названия на момент покупки.
- **Оплата**:
  - провайдер подключён через интерфейс `PaymentGateway` и DI-контейнер, новый провайдер добавляется без правок бизнес-логики;
  - вебхук проверяет HMAC-подпись через `hash_equals`;
  - идемпотентность: строка платежа блокируется `lockForUpdate`, повторно проверяется статус, сверяется сумма;
  - деньги хранятся целым числом в минимальных единицах.

## В планах

- Оформление заказа (checkout) с блокировкой остатков
- Сверка зависших платежей по расписанию (Scheduler)
- Очереди для уведомлений
- Feature-тесты вебхука и корзины

## Запуск

```bash
git clone https://github.com/code-hamster-09/hamstore_laravel.git
cd hamstore_laravel
cp .env.example .env   # БД уже настроена под docker, задай FAKEPAY_SECRET

docker compose -f docker/docker-compose.yml up -d --build
docker compose -f docker/docker-compose.yml exec app composer install
docker compose -f docker/docker-compose.yml exec app php artisan key:generate
docker compose -f docker/docker-compose.yml exec app php artisan migrate --seed
```

API доступно на `http://localhost:8080/api`.

## Основные эндпоинты

| Метод | Путь | Доступ |
|---|---|---|
| POST | `/api/auth/register`, `/api/auth/login` | публичный |
| POST | `/api/auth/logout`, `/api/auth/logoutAll` | токен |
| GET | `/api/products`, `/api/products/{id}` | публичный |
| POST / PUT / DELETE | `/api/products`, `/api/products/{id}` | токен, только продавец |
| GET / POST / PATCH / DELETE | `/api/cart-items` | токен, только владелец |
| POST | `/api/webhooks/fakepay` | подпись HMAC |
