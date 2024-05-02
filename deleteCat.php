<?php 

// modificando alguns cabecalhos relacionados ao CORS para permitir requisicoes externas
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// realizando a conexao com o banco de dados a partir do PDO
$pdo = new PDO("mysql:dbname=gallery;host=localhost", "root", "1234");

$dados = json_decode(file_get_contents('php://input'), true);

// Deleta a linha da tabela que conste o Id passado pela função no front
$sql = $pdo->prepare('DELETE FROM imagens WHERE id = :id');
$sql ->bindValue(':id', $dados);
$sql->execute();

// Atualiza a lista de imagens e envia para o front
$sql = $pdo->query("SELECT * FROM imagens");

$dados = $sql->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(["retorno" => $dados]);