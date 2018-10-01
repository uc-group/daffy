FROM php:7.2 AS php-build
WORKDIR /usr/src/daffy
RUN DEBIAN_FRONTEND=noninteractive apt-get -y update && apt-get -y install zlib1g-dev zip curl
RUN docker-php-ext-install zip
COPY --from=composer:1.7 /usr/bin/composer /usr/bin/composer
COPY app .
RUN composer install

FROM node:8 AS assets-build
WORKDIR /home/node/app
COPY app/assets assets
COPY app/package.json /home/node/app
COPY app/webpack.config.js /home/node/app
RUN mkdir -p /home/node/app/public && yarn install --no-cache && npm run build
RUN ls && ls public && ls public/build

FROM php:7.2
WORKDIR /usr/src/daffy
COPY app .
COPY --from=php-build /usr/src/daffy/vendor vendor
COPY --from=assets-build /home/node/app/public/build public/build
