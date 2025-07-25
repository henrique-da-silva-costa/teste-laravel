# Para rodar o projeto

# Back-end

## Abra o projeto na pasta teste-php no terminal e rode o comando (composer install) ou (composer update

## renomeie o .env.example para .env

## Rode esse comando no terminal

git clone https://github.com/laradock/laradock.git

## Entre na pasta laradock pelo terminal

## renomeie o .env.example para .env dentro da pasta laradock (sao dois .env.example, uma na pasta laradock e o outro na pasta normal do projeto)

## dentro da pasta laradock rode no termianl

docker-compose up -d nginx mysql phpmyadmin

docker-compose exec --user=laradock workspace bash

rode php artisan key:generate

rode php artisan migrate

## Por fim o projeto vai estar rodando em -> http://127.0.0.1:80 

# Front-end

## Abra o projeto na pasta teste-php no terminal

rode o comando docker-compose up -d --build

## Por fim o projeto vai estar rodando em -> http://127.0.0.1:5176
