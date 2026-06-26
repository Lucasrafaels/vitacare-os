FROM php:8.3-fpm

# Dependências de sistema e extensões PHP exigidas pelo Laravel + MySQL
RUN apt-get update && apt-get install -y \
        git \
        unzip \
        libzip-dev \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 1) Cria um projeto Laravel real (framework completo, vendor/ etc).
#    Isso garante Eloquent, Blade, roteador e tudo mais "de verdade".
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_DISABLE_AUDIT=1
ENV COMPOSER_ALLOW_ALL_PLUGINS=1
RUN composer config --global allow-plugins true
RUN composer config --global policy.advisories.block false
RUN composer create-project laravel/laravel:^11.0 . \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --no-audit

# 2) Sobrepõe o esqueleto da aplicação VitaCare (controllers, models,
#    migrations, seeders, rotas e views) por cima do Laravel recém-criado.
COPY app_skeleton/app/Http/Controllers/. /var/www/html/app/Http/Controllers/
COPY app_skeleton/app/Http/Middleware/. /var/www/html/app/Http/Middleware/
COPY app_skeleton/app/Models/. /var/www/html/app/Models/
COPY app_skeleton/database/migrations/. /var/www/html/database/migrations/
COPY app_skeleton/database/seeders/. /var/www/html/database/seeders/
COPY app_skeleton/routes/web.php /var/www/html/routes/web.php
COPY app_skeleton/routes/console.php /var/www/html/routes/console.php
COPY app_skeleton/bootstrap/app.php /var/www/html/bootstrap/app.php
COPY app_skeleton/resources/views/. /var/www/html/resources/views/
COPY app_skeleton/config/auth.php /var/www/html/config/auth.php
COPY app_skeleton/.env.example /var/www/html/.env.example

# 3) Remove a migration default de "users" do Laravel (usamos "profissionais").
RUN rm -f /var/www/html/database/migrations/*_create_users_table.php \
    && rm -f /var/www/html/database/migrations/*_create_password_reset_tokens_table.php

# 4) Prepara .env, gera APP_KEY e ajusta permissões.
RUN cp /var/www/html/.env.example /var/www/html/.env \
    && composer require barryvdh/laravel-dompdf --no-interaction \
    && composer install --no-interaction --optimize-autoloader \
    && php artisan key:generate \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# 5) Preserva uma cópia completa do projeto montado em /opt/app-build.
#    Isso é necessário porque em produção o diretório /var/www/html será
#    substituído por um volume Docker nomeado (vazio na primeira execução),
#    para que o container do Nginx também tenha acesso ao código (public/).
#    O entrypoint.sh copia esse conteúdo para o volume no primeiro boot.
RUN cp -a /var/www/html /opt/app-build

COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
