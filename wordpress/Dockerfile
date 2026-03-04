FROM wordpress:6.8-php8.3-apache

RUN curl -o /usr/local/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x /usr/local/bin/wp

ENV WP_CLI_CACHE_DIR=/tmp/wp-cli-cache
RUN mkdir -p /tmp/wp-cli-cache && chown www-data:www-data /tmp/wp-cli-cache

# Copy child theme into the image
COPY coolify/themes/kadence-praia-do-norte /usr/src/wordpress/wp-content/themes/kadence-praia-do-norte

# Copy custom shipping plugin into the image
COPY coolify/plugins/pn-table-rate-shipping /usr/src/wordpress/wp-content/plugins/pn-table-rate-shipping
