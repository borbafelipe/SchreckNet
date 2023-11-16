<?php

// functions.php
session_start();

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

function getPostById($post_id, $conn) {
    $sql = "SELECT * FROM posts WHERE id = :post_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function getCommentsByPostId($post_id, $conn) {
    $sql = "SELECT * FROM comments WHERE post_id = :post_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function addComment($conn, $post_id, $username, $message) {
    $sql = "INSERT INTO comments (post_id, username, message) VALUES (:post_id, :username, :message)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);
    $stmt->execute();
}

// Restante do código...

?>

