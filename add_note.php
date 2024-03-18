<!DOCTYPE html>

<?php
    session_start();
    $conn = new PDO("mysql:host=localhost;dbname=notes_db", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if(isset($_SESSION["team"])){
        $team_id = $_SESSION["team"];
        $sql = "SELECT name FROM team WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($team_id));
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)){
            $team = $row["name"];
            break;
        }
    }
    else{
        header("Location: http://localhost:8081/web_lab_1+2/homepage.php");
        exit();
    }
    if (isset($_SESSION["user"])){
        $user_id = $_SESSION["user"];
        $sql = "SELECT login FROM user WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($user_id));
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $user = $row["login"];
        }
    }
    else{
        header("Location: http://localhost:8081/web_lab_1+2/welcome.php");
        exit();
    }
    include("headers/header2.php");
?>

<html>
    <head>
        <title> Новая заметка </title>
        <link rel="stylesheet" href="styles/team-form.css">
        <link rel="stylesheet" href="styles/new-note.css">
    </head>
    <body>
        <div class="form">
            <div class="form-center">
                <form method="post">
                        <input type="text" name="title" placeholder="Заголовок" class="title-field"><br>
                    <label>Категория
                    <select name="category">
                        <?php
                            $sql = "SELECT * FROM category WHERE team_id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute(array($team_id));
                            while ($row = $stmt->fetch(PDO::FETCH_LAZY)){
                                echo '<option value="' . $row["id"] . '"> ' . $row["name"] . ' </option>';
                            }
                        ?>
                    </select></label><br>
                        <textarea name="text" rows="7" cols="40" placeholder="Текст"></textarea><br>
                    <input type="submit" name="submit" value="Добавить" class="button-decor"> 
                </form>
                <a href="teampage.php"> <button class="button-decor" id="back4"> Назад </button> </a>
            </div>
        </div>
    </body>
</html>

<?php
    if (isset($_POST["submit"])){
        if (empty($_POST["title"]) || empty($_POST["text"])){
            echo '<script>
            alert("Заполните все поля");
            </script>';
            exit();
        }
        $sql = "INSERT INTO note (author_id, team_id, title, text, category_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($user_id, $team_id, $_POST["title"], $_POST["text"], $_POST["category"]));
        echo '<script>
            alert("Заметка добавлена успешно");
            window.location.href="teampage.php";
            </script>';
    }
?>