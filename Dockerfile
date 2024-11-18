# Usar una imagen base oficial con PHP y Nginx
FROM php:8.3-fpm

# Instalar Nginx
RUN apt-get update && apt-get install -y nginx && \
    apt-get clean

# Copiar los archivos de configuración de Nginx
COPY default.conf /etc/nginx/conf.d/

# Copiar el código de la aplicación al contenedor
COPY . /var/www/html

# Asignar permisos adecuados
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Exponer el puerto 80
EXPOSE 80

# Comando para iniciar Nginx y PHP-FPM juntos
CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]

# Redirigir logs de PHP a un archivo
RUN echo "error_log = /var/log/php_errors.log" >> /usr/local/etc/php/php.ini

# Asegurar directorio de logs
RUN mkdir -p /var/log/php && chown -R www-data:www-data /var/log/php

# Volumen para logs
VOLUME ["/var/log/nginx", "/var/log/php"]
