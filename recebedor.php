<?php 

//CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Conexão com BD
$pdo = new PDO("mysql:dbname=gallery;host=localhost", "root", "1234");

// recebendo as imagens da API
$dados = json_decode(file_get_contents('php://input'), true);

// verificando as imagens do banco
$sql = $pdo->query("SELECT * FROM imagens");

// Se não houver registros no banco, armazena as 10 imagens aleatorias da API, caso contrario ignora pois o front puxara as imagens do banco
if($sql->rowCount() === 0) {
    for ($i = 0; $i < 10; $i++){
        $sql = $pdo->prepare("SELECT * FROM imagens WHERE id = :id");
        $sql->bindValue(":id", $dados[$i]["id"]);
        $sql->execute();
    
        if($sql->rowCount() === 0){
            $sql = $pdo->prepare("INSERT INTO imagens (id, url) VALUES (:id, :url)");
            $sql->bindValue(":id", $dados[$i]["id"]);
            $sql->bindValue(":url", $dados[$i]["url"]);
            $sql->execute();
        }
    }
}else{

}

$sql = $pdo->query("SELECT * FROM imagens");

$dados = $sql->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(["retorno" => $dados]);
