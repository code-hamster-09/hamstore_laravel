FROM php:8.4-fpm

# Системные библиотеки, нужные для PHP-расширений
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    unzip \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    && rm -rf /var/lib/apt/lists/*

# pdo_pgsql — Postgres, bcmath — точная арифметика (деньги),
# intl — локали и форматирование, pcntl — для queue:work
RUN docker-php-ext-install pdo_pgsql zip bcmath intl pcntl opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Пользователь с тем же uid/gid, что на хосте, чтобы файлы не создавались от root
ARG UID=1000
ARG GID=1000
RUN groupadd -g ${GID} homyak && useradd -u ${UID} -g homyak -m homyak

USER homyak

WORKDIR /var/www

EXPOSE 9000

CMD ["php-fpm"]
