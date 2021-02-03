FROM php:7.4-cli

LABEL maintainer="Vagif Rufullazada"

RUN apt-get update && apt-get install -y libpq-dev zlib1g-dev libicu-dev libzip-dev procps \
zip git && rm -rf /var/lib/apt/lists/*

# Installing php extensions.
RUN docker-php-ext-install zip

# Installing composer.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --quiet

# Creating user for container.
RUN groupadd -g 1000 appuser && useradd -r -u 1000 -g appuser appuser

# Creating user && composer-cache directories.
RUN mkdir -p /home/appuser/.composer/cache/repo/https---asset-packagist.org \
    && mkdir /home/appuser/.composer/cache/files

# Giving proper permissions to user.
RUN chown -R appuser:appuser /home/appuser && chmod -R 775 /home/appuser

# Running container as user
USER appuser

ENV COMPOSER_NO_INTERACTION=1

WORKDIR /app