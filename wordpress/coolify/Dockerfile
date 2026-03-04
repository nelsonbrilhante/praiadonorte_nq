FROM wordpress:6.8-php8.3-apache

# WP-CLI for automation
RUN curl -o /usr/local/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x /usr/local/bin/wp
ENV WP_CLI_CACHE_DIR=/tmp/wp-cli-cache
RUN mkdir -p /tmp/wp-cli-cache && chown www-data:www-data /tmp/wp-cli-cache

# PHP production tuning
RUN { \
    echo 'memory_limit = 256M'; \
    echo 'max_execution_time = 120'; \
    echo 'post_max_size = 64M'; \
    echo 'upload_max_filesize = 32M'; \
} > /usr/local/etc/php/conf.d/custom.ini

# Copy child theme into the image
COPY themes/kadence-praia-do-norte /usr/src/wordpress/wp-content/themes/kadence-praia-do-norte

# Copy custom shipping plugin into the image
COPY plugins/pn-table-rate-shipping /usr/src/wordpress/wp-content/plugins/pn-table-rate-shipping

COPY entrypoint.sh /usr/local/bin/wp-entrypoint.sh
RUN chmod +x /usr/local/bin/wp-entrypoint.sh

ENTRYPOINT ["wp-entrypoint.sh"]
CMD ["apache2-foreground"]
