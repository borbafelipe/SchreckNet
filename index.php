<?php
require_once 'functions.php';

$conn = conectarBanco();

// Inicia a sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();}

// Verifica se o formulário de postagem foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_post'])) {
        $image_url = isset($_POST['image_url']) ? $_POST['image_url'] : '';

        $stmt = $conn->prepare("INSERT INTO posts (username, message, image_url) VALUES (:username, :message, :image_url)");
        $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
        $stmt->bindParam(':message', $_POST['message'], PDO::PARAM_STR);
        $stmt->bindParam(':image_url', $image_url, PDO::PARAM_STR);
        $stmt->execute();

        // Atualiza a última postagem exibida
        $_SESSION['lastDisplayedPost'] = $conn->lastInsertId();
        
        header("Location: index.php");
    }

    // Restante do código para comentários
}

// Obtém a última postagem exibida
$lastDisplayedPost = isset($_SESSION['lastDisplayedPost']) ? $_SESSION['lastDisplayedPost'] : 0;

// Obtém todas as postagens do banco de dados a partir da última exibida
$sql_posts = "SELECT * FROM posts WHERE id > :lastDisplayedPost ORDER BY id DESC";
$stmt_posts = $conn->prepare($sql_posts);
$stmt_posts->bindParam(':lastDisplayedPost', $lastDisplayedPost, PDO::PARAM_INT);
$stmt_posts->execute();
$result_posts = $stmt_posts->fetchAll(PDO::FETCH_ASSOC);

// Exibe as postagens na página
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SchreckNet</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>SchreckNet</h1>
        <a href="manage.php">Janitor's Entrance</a>
    </header>

    <section id="user-actions">
        <form method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="4" required></textarea>

            <label for="image_url">Image URL:</label>
            <input type="url" id="image_url" name="image_url">

            <button type="submit" name="submit_post">Post</button>
        </form>
    </section>

    <section id="posts">
        <?php foreach ($result_posts as $row) : ?>
            <div class="post-thumbnail">
                <img src="<?php echo $row['image_url']; ?>" alt="Post Image">
                <p><strong><?php echo $row['username']; ?></strong></p>
                <p><?php echo $row['message']; ?></p>
                <a href="post_detail.php?post_id=<?php echo $row['id']; ?>" class="expand-button">Details</a>
                <p>ID: <?php echo $row['id']; ?></p>
            </div>
        <?php endforeach; ?>
    </section>

    <footer>
        &copy; 2023 SchreckNet
    </footer>
</body>
</html>
