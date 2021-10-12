
# Gn-Vendas

## Instruções para instalação e execução do projeto

### 1 - Banco de dados
Na pasta banco_dados tem-se o script.sql para a criação do banco de dados gn_vendas, e criação das tabelas.

### 2 - Backend
Acessar a pasta backend:
```sh
$ cd gn-vendas/backend
```

No arquivo index.php, configure o acesso ao banco:

```sh
$host = "127.0.0.1";
$db   = "gn_vendas";
$user = "root";
$password = "minhasenha";
```
Rode o servidor usando o comando:
```sh
$ php -S localhost:8000 index.php
```
### 3 - Frontend
Acessar a pasta frontend:
```sh
$ cd gn-vendas/frontend
```
Realizar a instalação do http-server:

```sh
$ npm install -g http-server
```
Em seguida execute:
```sh
$ http-server -p 8080
```

Ao acessar o endereço http://localhost:8080 você acessará as telas do Gn-Vendas.