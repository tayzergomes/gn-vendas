<?php
header('Content-Type: application/json; charset=utf-8');

$pdo = new PDO('mysql:host=127.0.0.1;dbname=gn_vendas', $user, $password);

$consulta = $pdo->query("SELECT id,nome,valor FROM produtos;");

$produtos = array();

while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
    $produto = array(
        'id' => $linha['id'],
        'nome' => $linha['nome'],
        'valor' => $linha['valor']
    );
    $produtos[] = $produto;
}
echo json_encode($produtos);
