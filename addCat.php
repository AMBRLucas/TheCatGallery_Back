<?php 

// modificando alguns cabecalhos relacionados ao CORS para permitir requisicoes externas
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// realizando a conexao com o banco de dados a partir do PDO
$pdo = new PDO("mysql:dbname=gallery;host=localhost", "root", "1234");

// Recebendo os dados da imagem aleatoria da API enviados pelo front-end
$dados = json_decode(file_get_contents('php://input'), true);

// Realizando uma consulta ao banco de dados para ver se essa imagem ja nao consta entre as imagens no sistema
$sql = $pdo->prepare("SELECT * FROM imagens WHERE id = :id");
    $sql->bindValue(":id", $dados[0]["id"]);
    $sql->execute();

// Se o retorno da consulta tiver o valor de linhas igual a zero, ou seja, não houver essa imagem salva no banco, la sera adicionada
if($sql->rowCount() === 0){
    $sql = $pdo->prepare("INSERT INTO imagens (id, url) VALUES (:id, :url)");
        $sql->bindValue(":id", $dados[0]["id"]);
        $sql->bindValue(":url", $dados[0]["url"]);
        $sql->execute();
    }

// Pegando e armazenando todos os dados cadastrados no banco, atualizados e retornando eles para o front-end
$sql = $pdo->query("SELECT * FROM imagens");

$dados = $sql->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(["retorno" => $dados]);

