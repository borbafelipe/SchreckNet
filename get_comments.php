<?php
require_once 'functions.php';

$conn = conectarBanco();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    $sql_comments = "SELECT * FROM comments WHERE post_id = :post_id";
    $stmt_comments = $conn->prepare($sql_comments);
    $stmt_comments->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt_comments->execute();

    while ($comment = $stmt_comments->fetch(PDO::FETCH_ASSOC)) {
        echo '<div class="comment">';
        echo '<p><strong>' . htmlspecialchars($comment['username']) . '</strong></p>';
        echo '<p>' . htmlspecialchars($comment['comment']) . '</p>';
        echo '</div>';
    }
} else {
    echo 'Erro: Informações inválidas.';
}
?>
