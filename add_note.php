<!DOCTYPE html>

<?php
    session_start();
    $team_id = $_SESSION["team"];
    $user_id = $_SESSION["user"];
    $conn = new PDO("mysql:host=localhost;dbname=notes_db", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

<html>
    <head>
        <title> Новая заметка </title>
    </head>
    <body>
        <form>
            <label> Заголовок 
                <input type="text" name="title">
            </label><br>
            <select name="category">
                <?php
                    $sql = "SELECT * FROM category WHERE team_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute(array($team_id));
                    while ($row = $stmt->fetch(PDO::FETCH_LAZY)){
                        echo '<option value="' . $row["id"] . '"> ' . $row["name"] . ' </option>';
                    }
                ?>
            </select><br>
            <label> Текст
                <textarea name="text" rows="5" cols="40"></textarea>
            </label><br>
            <input type="submit" name="submit" value="Добавить"> 
        </form>
        <a href="teampage.php"> Назад </a>
    </body>
</html>

<?php
    if (isset($_GET["submit"])){
        $sql = "INSERT INTO note (author_id, team_id, title, text, category_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($user_id, $team_id, $_GET["title"], $_GET["text"], $_GET["category"]));
        echo '<script>
            alert("Заметка добавлена успешно");
            window.location.href="teampage.php";
            </script>';
    }
?>