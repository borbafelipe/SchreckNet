<?php
require_once 'functions.php';

// Conecta ao banco de dados
$conn = conectarBanco();

// Obtém o ID da postagem da URL
if (isset($_GET['post_id'])) {
    $postId = $_GET['post_id'];

    // Obtém a postagem pelo ID
    $post = getPostById($postId, $conn);

    // Se a postagem existe
    if ($post) {
        // Processa o formulário de comentário se enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
            $commentUsername = isset($_POST['comment_username']) ? $_POST['comment_username'] : '';
            $commentMessage = isset($_POST['comment_message']) ? $_POST['comment_message'] : '';

            // Adiciona o comentário
            addComment($conn, $postId, $commentUsername, $commentMessage);
        }

        // Obtém os comentários da postagem
        $comments = getCommentsByPostId($postId, $conn);
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Post Details</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>

        <div class="post-details">
            <h2><?php echo $post['username']; ?></h2>
            <p><?php echo $post['message']; ?></p>
            <img src="<?php echo $post['image_url']; ?>" alt="Imagem da Postagem">

            <h3>Comentários</h3>
            <ul>
                <?php foreach ($comments as $comment) : ?>
                    <li><?php echo "{$comment['username']}: {$comment['message']}"; ?></li>
                <?php endforeach; ?>
            </ul>

            <form method="post">
                <label for="comment_username">Your Name:</label>
                <input type="text" id="comment_username" name="comment_username" required>

                <label for="comment_message">Comment:</label>
                <textarea id="comment_message" name="comment_message" rows="4" required></textarea>

                <button type="submit" name="submit_comment">Add Comment</button>
            </form>
        </div>

        </body>
        </html>

        <?php
    } else {
        echo "<p>Postagem não encontrada.</p>";
    }
} else {
    echo "<p>ID da postagem não fornecido.</p>";
}
?>
