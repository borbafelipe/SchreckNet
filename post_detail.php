<?php
require_once 'functions.php';

$conn = conectarBanco();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar se é um novo comentário
    if (isset($_POST['submit_comment'])) {
        $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : '';
        $comment_username = isset($_POST['comment_username']) ? $_POST['comment_username'] : '';
        $comment_message = isset($_POST['comment_message']) ? $_POST['comment_message'] : '';

        // Inserir comentário no banco de dados
        $stmt_comment = $conn->prepare("INSERT INTO comments (post_id, username, message) VALUES (:post_id, :username, :message)");
        $stmt_comment->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt_comment->bindParam(':username', $comment_username, PDO::PARAM_STR);
        $stmt_comment->bindParam(':message', $comment_message, PDO::PARAM_STR);
        $stmt_comment->execute();
    }
}

// Verificar se o ID do post foi fornecido na URL
if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // Consultar detalhes do post
    $stmt_post = $conn->prepare("SELECT * FROM posts WHERE id = :id");
    $stmt_post->bindParam(':id', $post_id, PDO::PARAM_INT);
    $stmt_post->execute();
    $post = $stmt_post->fetch(PDO::FETCH_ASSOC);

    if ($post) {
        // Exibir detalhes do post
        echo "<h1>{$post['username']}</h1>";
        echo "<p>{$post['text']}</p>";
        echo "<img src='{$post['image_url']}' alt='Imagem do Post'>";

        // Formulário para adicionar comentários
        echo "<form method='POST' action='post_detail.php'>";
        echo "<input type='hidden' name='post_id' value='{$post_id}'>";
        echo "<label for='comment_username'>Seu Nome:</label>";
        echo "<input type='text' name='comment_username' required>";
        echo "<label for='comment_message'>Comentário:</label>";
        echo "<textarea name='comment_message' required></textarea>";
        echo "<button type='submit' name='submit_comment'>Comentar</button>";
        echo "</form>";

        // Consultar e exibir comentários relacionados ao post
        $stmt_comments = $conn->prepare("SELECT * FROM comments WHERE post_id = :post_id");
        $stmt_comments->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt_comments->execute();
        $comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);

        if ($comments) {
            echo "<div id='comments-section'>";
            echo "<h2>Comentários</h2>";
            foreach ($comments as $comment) {
                echo "<div class='comment'>";
                echo "<p>{$comment['username']} diz:</p>";
                echo "<p>{$comment['message']}</p>";
                echo "</div>";
            }
            echo "</div>";
        }
    } else {
        echo "Post não encontrado.";
    }
} else {
    echo "ID do post não fornecido.";
}
?>