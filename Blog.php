<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}

if (isset($_POST['publish'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $file_name = '';
    $user_id = $_SESSION['user_id'];

    // Vérifier et créer le dossier uploads si nécessaire
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (!empty($_FILES['media']['name'])) {
        $file_name = time() . '_' . basename($_FILES['media']['name']);
        $file_tmp = $_FILES['media']['tmp_name'];
        $file_destination = $upload_dir . $file_name;

        if (move_uploaded_file($file_tmp, $file_destination)) {
            $insert_post = mysqli_query($conn, "INSERT INTO blog (user_id, title, content, media) VALUES ('$user_id', '$title', '$content', '$file_name')");
            if ($insert_post) {
                $message[] = 'Publication ajoutée avec succès!';
            } else {
                $message[] = "Erreur lors de l'ajout de la publication!";
            }
        } else {
            $message[] = "Échec du téléchargement du fichier.";
        }
    } else {
        $insert_post = mysqli_query($conn, "INSERT INTO blog (user_id, title, content, media) VALUES ('$user_id', '$title', '$content', '')");
        if ($insert_post) {
            $message[] = 'Publication ajoutée avec succès!';
        } else {
            $message[] = "Erreur lors de l'ajout de la publication!";
        }
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Récupérer le fichier associé à la publication
    $query = mysqli_query($conn, "SELECT media FROM blog WHERE id = '$id' AND user_id = '{$_SESSION['user_id']}'");
    $row = mysqli_fetch_assoc($query);
    
    if (!empty($row['media']) && file_exists('uploads/' . $row['media'])) {
        unlink('uploads/' . $row['media']); // Supprimer le fichier du serveur
    }

    mysqli_query($conn, "DELETE FROM blog WHERE id = '$id' AND user_id = '{$_SESSION['user_id']}'");
    header('location:blog.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'header.php'; ?>

    <div class="container">
        <h2>Publier un article</h2>
        <?php if(isset($message)) { foreach($message as $msg) { echo '<p>'.$msg.'</p>'; } } ?>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Titre" required class="box">
            <textarea name="content" placeholder="Contenu" required class="box"></textarea>
            <input type="file" name="media" class="box">
            <input type="submit" name="publish" value="Publier" class="btn">
        </form>
        
        <h2>Publications</h2>
        <div class="posts">
            <?php
            $fetch_posts = mysqli_query($conn, "SELECT * FROM blog ORDER BY id DESC");
            while ($row = mysqli_fetch_assoc($fetch_posts)) {
                echo "<div class='post'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
                if (!empty($row['media'])) {
                    echo "<img src='uploads/" . htmlspecialchars($row['media']) . "' alt='media' class='post-media'>";
                }
                if ($row['user_id'] == $_SESSION['user_id']) {
                    echo "<a href='blog.php?delete=" . $row['id'] . "' class='btn delete-btn'>Supprimer</a>";
                }
                echo "</div>";
            }
            ?>
        </div>
    </div>
    <?php include 'footer.php'; ?>

    
</body>
</html> 