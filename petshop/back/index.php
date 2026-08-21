<?php
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require "config.php";

// Roteador simples baseado em query string (?rota=...)
$rota = $_GET["rota"] ?? "teste";

function teste() {
    echo json_encode(["mensagem" => "Back-end respondendo"]);
}

// Rota já pronta 1: lista todos os animais
function listarAnimais($con) {
    $stmt = $con->query("SELECT * FROM Animais");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

// Rota já pronta 2: filtra animais por espécie
function listarPorEspecie($con) {
    $especie = $_GET["especie"] ?? "";
    $stmt = $con->prepare("SELECT * FROM Animais WHERE especie = ?");
    $stmt->execute([$especie]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

// Rota 3: filtra animais por raça
function listarPorRaca($con) {
    $raca = $_GET["raca"] ?? "";
    $stmt = $con->prepare("SELECT * FROM Animais WHERE raca = ?");
    $stmt->execute([$raca]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

// Rota 4: calcula a idade média
function idadeMedia($con) {
    $stmt = $con->query("SELECT AVG(idade) AS idade_media FROM Animais");
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
}

// Rota 5: lista todos os serviços
function listarServicos($con) {
    $stmt = $con->query("SELECT * FROM Servicos");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

// Rota 6: filtra serviços por categoria
function listarPorCategoria($con) {
    $categoria = $_GET["categoria"] ?? "";
    $stmt = $con->prepare("SELECT * FROM Servicos WHERE categoria = ?");
    $stmt->execute([$categoria]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

// Rota 7: calcula o preço médio dos serviços
function precoMedio($con) {
    $stmt = $con->query("SELECT AVG(preco) AS preco_medio FROM Servicos");
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
}

// Rota 8: mostra o serviço mais caro
function maiorPreco($con) {
    $stmt = $con->query("SELECT * FROM Servicos ORDER BY preco DESC LIMIT 1");
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
}

// Rota bônus: junta animais e serviços
function listarAnimaisEServicos($con) {
    $stmtAnimais = $con->query("SELECT * FROM Animais");
    $animais = $stmtAnimais->fetchAll(PDO::FETCH_ASSOC);

    $stmtServicos = $con->query("SELECT * FROM Servicos");
    $servicos = $stmtServicos->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "animais" => $animais,
        "servicos" => $servicos
    ]);
}

switch ($rota) {
    case "animais":
        listarAnimais($con);
        break;

    case "animais/especie":
        listarPorEspecie($con);
        break;

    case "animais/raca":
        listarPorRaca($con);
        break;

    case "animais/idade-media":
        idadeMedia($con);
        break;

    case "servicos":
        listarServicos($con);
        break;

    case "servicos/categoria":
        listarPorCategoria($con);
        break;

    case "servicos/media-preco":
        precoMedio($con);
        break;

    case "servicos/maior-preco":
        maiorPreco($con);
        break;

    case "animais/servicos":
        listarAnimaisEServicos($con);
        break;

    default:
        teste();
        break;
}