FROM php:7.4-cli
LABEL org.opencontainers.image.title="PHP Consul API development container"
LABEL org.opencontainers.image.description="PHP Consul API development container"
LABEL org.opencontainers.image.authors="Daniel Carbone<daniel.p.carbone@gmail.com>"

# set for php-cs-fixer
ENV WORKDIR /app

# copy in php-extension-installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# install extensions
RUN install-php-extensions \
      xdebug

# install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/bin --filename=composer \
    && rm 'composer-setup.php'

# install php-cs tools (thanks, cytopia!)
COPY --from=cytopia/phpcs:3-php7.4 /usr/bin/phpcs /usr/bin/phpcs
COPY --from=dancarbone/cytopia-phpcbf:7.4 /usr/bin/phpcbf /usr/bin/phpcbf
COPY --from=cytopia/php-cs-fixer:3-php7.4 /usr/bin/php-cs-fixer /usr/bin/php-cs-fixer
