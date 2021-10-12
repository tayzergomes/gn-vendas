<?php

$host = "127.0.0.1";
$db   = "gn_vendas";
$user = "root";
$password = "d3v3l0p3R";

header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_URI'] == '/cadastro-produtos') {
    require_once './cadastro-produtos.php';
}

if($_SERVER['REQUEST_URI'] == '/listagem-produtos') {
    require_once './listagem-produtos.php';
}

if($_SERVER['REQUEST_URI'] == '/compra-produtos') {
    require_once './compra-produtos.php';
}