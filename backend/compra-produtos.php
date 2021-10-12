<?php

header('Content-Type: application/json; charset=utf-8');

include './gn-api-sdk-php/vendor/autoload.php';

use Gerencianet\Exception\GerencianetException;
use Gerencianet\Gerencianet;

$produtoId = $_POST['produto_id'];
$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$telefone = $_POST['telefone'];
$response = array('sucesso' => true, 'mensagem' => '');

if(empty($produtoId) || empty($nome) || empty($cpf) || empty($telefone)) {
    $response['sucesso'] = false;
    $response['mensagem'] = 'campos inválidos';
    echo json_encode($response);
    exit;
}

$existeProdutoId = true;
$produtoObj;

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=gn_vendas', $user, $password);
  
    $consulta = $pdo->query('SELECT id, nome, valor FROM produtos where id = '.$produtoId.';');
    while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
        if(empty($linha) || empty($linha['id'])) {
            $existeProdutoId = false;
        } else {
            $produtoObj = array('id'=> $linha['id'], 'nome' => $linha['nome'], 'valor' => $linha['valor']);
        }
    }

    if(!isset($produtoObj)) {
        $existeProdutoId = false;
    }

} catch(PDOException $e) {
    $existeProdutoId = false;
    $response['mensagem'] = $e->getMessage();
    exit;
}

if(!$existeProdutoId) {
    $response['sucesso'] = false;
    $response['mensagem'] = 'Produto não encontrado';
    echo json_encode($response);
    exit;
}

$options = [
    'client_id' => 'Client_Id_4e4327e045ceb277ed5f62db8c46c399c309e0bf',
    'client_secret' => 'Client_Secret_bb1ad596c70e1c17089cd27ec860816670412681',
    'sandbox' => true,
    'debug' => false,
    'timeout' => 30
];

$customer = [
    'name' => $nome,
    'cpf' => $cpf,
    'phone_number' => $telefone
];

$expireAt = date('Y-m-d', strtotime('+2 days'));

$body = [
    'payment' => [
        'banking_billet' => [
            'expire_at' =>  $expireAt,
            'customer' => $customer
        ]
    ],
    'items' => [
        [
            'name' => $produtoObj['nome'],
            'amount' => 1,
            'value'  => $produtoObj['valor']*100
        ]
    ]
];

try {
    $api = new Gerencianet($options);
    $response = $api->oneStep([], $body);
    //echo '<pre>' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</pre>';
} catch (GerencianetException $e) {
    print_r($e->code);
    print_r($e->error);
    print_r($e->errorDescription);
    exit;
} catch (Exception $e) {
    print_r($e->getMessage());
    exit;
}

$linkPdf = $response['data']['pdf']['charge'];
$chargeId = $response['data']['charge_id'];

if(empty($response['data']['pdf'])) {
    echo json_encode($response);
    exit;
}

try {
    $stmt = $pdo->prepare('INSERT INTO compras (nome, cpf, telefone, produto_id, id_boleto, link_boleto) VALUES(:nome, :cpf, :telefone, :produto_id, :id_boleto, :link_boleto)');
    $stmt->execute(array(
      ':nome' => $nome,
      ':cpf' => $cpf,
      ':telefone' => $telefone,
      ':produto_id' => $produtoId,
      ':id_boleto' => $chargeId,
      ':link_boleto' => $linkPdf
    ));

}catch(PDOException $e) {
    $response['mensagem'] = $e->getMessage();
    $response['sucesso'] = false; 
    exit;
}

$response['data'] = array(
    'nome' => $nome,
    'cpf' => $cpf,
    'telefone' => $telefone,
    'produto_id' => $produtoId,
    'id_boleto' => $chargeId,
    'link_boleto' => $linkPdf
);
echo json_encode($response);
