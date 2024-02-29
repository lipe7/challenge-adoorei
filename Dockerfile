# Usa a imagem oficial do PHP como base
FROM php:8.1-fpm

# Instala dependências
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && docker-php-ext-install pdo_mysql

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia o código fonte da aplicação para o container
COPY . /var/www/html

# Copia o arquivo composer.json e composer.lock para o diretório de trabalho
COPY composer.json composer.lock ./

# Instala as dependências do Composer
RUN composer install

# Adiciona o script wait-for-it.sh
ADD https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh /usr/local/bin/wait-for-it.sh
RUN chmod +x /usr/local/bin/wait-for-it.sh

# Cria o diretório para os scripts de inicialização do MySQL
RUN mkdir -p /docker-entrypoint-initdb.d/

# Cria um script SQL para conceder permissões de usuário e acesso ao banco de dados
RUN echo "GRANT ALL PRIVILEGES ON adoorei_db.* TO 'root'@'%' IDENTIFIED BY 'root';" > /docker-entrypoint-initdb.d/init.sql \
    && echo "FLUSH PRIVILEGES;" >> /docker-entrypoint-initdb.d/init.sql

CMD php artisan serve --host=0.0.0.0 --port=8000
EXPOSE 8000
