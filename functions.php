<?php
// functions.php

function conectarBanco() {
    $dbhost = 'localhost';
    $dbname = 'schrecknet';
    $dbuser = 'root';
    $dbpass = '';

    try {
        $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }
}

function obterContadorComentarios($conn, $post_id) {
    $sql = "SELECT COUNT(*) FROM comments WHERE post_id = :post_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchColumn();
    
    return $result;
}
?>