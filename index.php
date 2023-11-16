<?php
require_once 'functions.php';

$conn = conectarBanco();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_post'])) {
    $stmt = $conn->prepare("INSERT INTO posts (username, message, image_url) VALUES (:username, :message, :image_url)");
    $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
    $stmt->bindParam(':message', $_POST['message'], PDO::PARAM_STR);
    $stmt->bindParam(':image_url', $_POST['image_url'], PDO::PARAM_STR);
    $stmt->execute();
}

$sql_posts = "SELECT * FROM posts ORDER BY id DESC";
$result_posts = $conn->query($sql_posts);
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
        <form method="post" action="">
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="message">Message:</label>
            <textarea name="message" required></textarea>

            <label for="image_url">Image URL:</label>
            <input type="text" name="image_url">

            <button type="submit" name="submit_post">Postar</button>
        </form>
    </section>

    <div id="posts">
        <?php
        while ($row = $result_posts->fetch(PDO::FETCH_ASSOC)) {
            $comments_count = obterContadorComentarios($conn, $row['id']);

            echo '<div class="post-thumbnail">';
            echo '<img src="' . $row['image_url'] . '" alt="Post Image">';
            echo '<div class="post-content">';
            echo '<p>Usuário: ' . $row['username'] . '</p>';
            echo '<p>Mensagem: ' . $row['message'] . '</p>';
            echo '<div class="post-comments">' . $comments_count . ' Comentários</div>';
            echo '<a href="post_detail.php?id=' . $row['id'] . '">Detalhes</a>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>

    <footer>
        <p>&copy; 2023 SchreckNet. Todos os direitos reservados.</p>
    </footer>
</body>

</html>
