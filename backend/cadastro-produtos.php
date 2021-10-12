<?php

$nome = $_POST['nome'];
$valor = doubleval($_POST['valor']);

$response = array('sucesso' => true, 'mensagem' => 'Produto cadastrado com sucesso');

if((empty($nome) || empty($valor)) || !is_double($valor) || !is_string($nome)) {
    $response['sucesso'] = false;
    $response['mensagem'] = 'campos invalidos';
    echo "Falha ao cadastrar produto <a href='http://localhost:8080/pages/products/list.html'> Ver produtos</a>";
    exit;
}

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=gn_vendas', $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
    $stmt = $pdo->prepare('INSERT INTO produtos (nome, valor) VALUES(:nome, :valor)');
    $stmt->execute(array(
      ':nome' => $nome,
      ':valor' => $valor
    ));
  
    echo "Produto cadastrado com sucesso! <a href='http://localhost:8080/pages/products/list.html'> Ver produtos</a>";

} catch(PDOException $e) {
    $response['sucesso'] = false;
    $response['mensagem'] = $e->getMessage();
    echo "Falha ao cadastrar produto <a href='http://localhost:8080/pages/products/list.html'> Ver produtos</a>";
}