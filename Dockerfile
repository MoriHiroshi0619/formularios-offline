# Usar a imagem oficial do PHP com a versão necessária
FROM php:8.3-fpm

# Configurar o diretório de trabalho
WORKDIR /var/www/html

# Instalar dependências do sistema e pacotes necessários
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libfreetype6-dev \
    libwebp-dev \
    libxpm-dev \
    libxml2-dev \
    libssl-dev \
    unzip \
    git \
    curl \
    fonts-liberation \
    fonts-dejavu \
    fontconfig \
    libicu-dev \
    && docker-php-ext-install intl \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP necessárias
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm
RUN docker-php-ext-install pdo_pgsql mbstring zip exif pcntl gd

# Instalar Node.js (versão LTS)
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - && \
    apt-get install -y nodejs && \
    rm -rf /var/lib/apt/lists/*

# Instalar o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar o arquivo composer.json e composer.lock
COPY composer.json composer.lock ./

# Instalar dependências PHP
RUN composer install --no-dev --no-scripts --no-autoloader

# Copiar todos os arquivos do projeto
COPY . .

# Instalar autoloader do Composer
RUN composer dump-autoload --optimize

# Instalar dependências NPM e construir assets
RUN npm install
RUN npm run dev

# Ajustar permissões
RUN chown -R www-data:www-data storage bootstrap/cache

# Expor a porta 9000 e iniciar o PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]
