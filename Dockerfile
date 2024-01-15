# ┏━━━━━━━━━━━━━━━━┓
# ┃ Belum Berhasil ┃
# ┗━━━━━━━━━━━━━━━━┛

FROM dunglas/frankenphp

# Be sure to replace "your-domain-name.example.com" by your domain name
# ENV SERVER_NAME=localhost

RUN install-php-extensions pcntl

COPY . /app


# If you want to disable HTTPS, use this value instead:
# ENV SERVER_NAME=localhost:80

# Enable PHP production settings
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
