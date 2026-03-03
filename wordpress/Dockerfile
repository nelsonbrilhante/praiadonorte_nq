FROM wordpress:6.8-php8.3-apache

RUN curl -o /usr/local/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x /usr/local/bin/wp

ENV WP_CLI_CACHE_DIR=/tmp/wp-cli-cache
RUN mkdir -p /tmp/wp-cli-cache && chown www-data:www-data /tmp/wp-cli-cache
