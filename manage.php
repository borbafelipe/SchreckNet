<?php
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_post'])) {
    $post_id = $_POST['post_id'];

    $conn = conectarBanco();

    // Consulta para deletar o post
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = :post_id");
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
}

// Consulta para obter todos os posts
$conn = conectarBanco();
$sql_posts = "SELECT * FROM posts ORDER BY id DESC";
$result_posts = $conn->query($sql_posts);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SchreckNet - Janitor Panel</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>SchreckNet</h1>
    </header>

    <section id="admin-panel">
        <h2>Painel Janitor</h2>

        <div id="posts">
            <?php while ($post = $result_posts->fetch(PDO::FETCH_ASSOC)) : ?>
                <div class="post-thumbnail">
                    <img src="<?= $post['image_url'] ?>" alt="Post Image">
                    <p><?= $post['username'] ?></p>
                    <p><?= $post['message'] ?></p>

                    <form method="post" action="">
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        <button type="submit" name="delete_post">Deletar</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>

        <a href="index.php">Sair e Voltar para a Página Principal</a>
    </section>

    <footer>
        <p>&copy; 2023 SchreckNet. Todos os direitos reservados.</p>
    </footer>
</body>

</html>
