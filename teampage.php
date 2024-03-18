<!DOCTYPE html>

<?php
    session_start();
    $conn = new PDO("mysql:host=localhost;dbname=notes_db", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (isset($_GET["team_id"])){
        $_SESSION["team"] = $_GET["team_id"];
        $team_id = $_GET["team_id"];
        $sql = "SELECT name FROM team WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($team_id));
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)){
            $team = $row["name"];
            break;
        }
    }
    else if(isset($_SESSION["team"])){
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
    include("headers/header2.php");
?>

<html>
    <head>
        <title> Моя команда </title>
        <link rel="stylesheet" href="styles/notes.css">
        <link rel="stylesheet" href="styles/buttons.css">
    </head>
    <body>
        <div class="team-buttons">
            <a href="add_note.php"> <button class="team-button"> Добавить заметку </button> </a>
            <a href="add_category.php"> <button class="team-button"> Создать новую категорию </button> </a>
        </div>  
        <br><form method="post">
            <input type="submit" name="back" value="Назад" class="leave">
        </form>
        <div class="notes">
            <?php
                $sql = "SELECT * FROM note WHERE team_id = ? ORDER BY time DESC";
                $stmt = $conn->prepare($sql);
                $stmt->execute(array($team_id));
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $sql = "SELECT name, color FROM category WHERE id = ?";
                    $stmt_tmp = $conn->prepare($sql);
                    $stmt_tmp->execute(array($row["category_id"]));
                    while ($row_tmp = $stmt_tmp->fetch(PDO::FETCH_ASSOC)){
                        echo '<div class="note" style="background-color: ' . $row_tmp["color"] . ';">';
                        echo "<h2> " . $row["title"] . " </h2>";
                        echo "<h3> " . $row_tmp["name"] . " </h3>";
                    }
                    $sql = "SELECT login, picture FROM user WHERE id = ?";
                    $stmt_tmp = $conn->prepare($sql);
                    $stmt_tmp->execute(array($row["author_id"]));
                    while ($row_tmp = $stmt_tmp->fetch(PDO::FETCH_ASSOC)){
                        echo '<div class="author">';
                        echo "<h4> " . $row_tmp["login"] . " </h4>";
                        echo '<img class="pic" src="' . $row_tmp['picture'] . '">';
                        echo '</div>';
                    }
                    echo "<p> " . $row["text"] . " </p>";
                    echo '<a href="notepage.php?note_id=' . $row["id"] .
                    '"> <img class="comment-pic" src="images/comment.png"> </a>';
                    echo '</div>';
                }
            ?>
        </div>
    </body>
</html>

<?php
    if (isset($_POST["back"])){
        unset($_SESSION["team"]);
        header("Location: http://localhost:8081/web_lab_1+2/homepage.php");
    }
?>
