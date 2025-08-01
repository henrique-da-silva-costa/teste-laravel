# Descrição

## Esse projeto foi desenvolvido para um teste.
### Nesse projeto, eu utilizei a API dos dados abertos, para pegar dados dos deputados e listar seus dados de forma dinâmica.
### Nos dados, eu fiz uma sincronização com as despesas e os órgãos dos deputados.
### Essa sincronização foi feita usando as, jobs do Laravel, um tipo de CRON, para que os dados carreguem e não interfira no funcionamento do sistema.

# Tecnologias usadas
## Docker
## Back-end -> **PHP/Laravel/Mysql**
## Front-end -> **React**

# Para rodar o projeto

## Tenha as seguintes feramentas instaladas **DOCKER**, **COMPOSER** e **NODE JS**.

# Back-end

## Abra o projeto na pasta teste-php no terminal e rode o comando (composer install) ou (composer update).

## Renomeie o .env.example para .env

## Rode esse comando no terminal.

git clone https://github.com/laradock/laradock.git

## Entre na pasta laradock pelo terminal.

## Renomeie o .env.example para .env dentro da pasta laradock (são dois .env.example, uma na pasta laradock e o outro na pasta normal do projeto).

## Dentro da pasta laradock rode no terminal os comandos abaixo.

docker-compose up -d nginx mysql phpmyadmin

docker-compose exec --user=laradock workspace bash

php artisan key:generate

php artisan migrate

## configurações do banco.

## Na pasta do projeto vai estar o banco para usar.

### Arquivo teste_php.sql

## Nos arquivos .env colocar as configurações como esta abaixo.

### arquivo .env normal.
<img width="187" height="109" alt="image" src="https://github.com/user-attachments/assets/5575dc9d-ef1c-446e-b63c-c9780a9f08d3" />

### arquivo .env da pasta laradock.
<img width="556" height="131" alt="image" src="https://github.com/user-attachments/assets/c201eb50-ad6e-4782-b73d-8975e3cc2d7f" />

## Por fim o projeto vai estar rodando em -> http://127.0.0.1:80 e o Banco de dados -> http://127.0.0.1:8081

# Front-end

## Abra o projeto na pasta teste-php-front no terminal.

### Agora rode os seguintes comandos.

npm install

docker-compose up -d --build

## Por fim o projeto vai estar rodando em -> http://127.0.0.1:5176


# Para rodar os jobs do laravel.

## Back end

## Abra a pasta teste-php pelo terminal, depois abra a pasta laradock pelo terminal

### Rode os comados.

docker-compose exec workspace bash

php artisan queue:work

## Assim ele vai começar rodar os jobs

## Front-end

### recarregue a pagina pelo navegador, assim ele vai executar os jobs

<img width="1365" height="597" alt="image" src="https://github.com/user-attachments/assets/46bd92e6-34f6-4748-8cc5-7b16a0f96513" />

