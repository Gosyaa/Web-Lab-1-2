<!DOCTYPE html>

<?php
    session_start();
    if (isset($_GET["team_id"])){
        $_SESSION["team"] = $_GET["team_id"];
        $team_id = $_GET["team_id"];
        $conn = new PDO("mysql:host=localhost;dbname=notes_db", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM team WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($team_id));
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)){
            $team = $row["name"];
            break;
        }
        echo "<h1> " . $team ." </h1>";
    }
    else if(isset($_SESSION["team"])){
        $team_id = $_SESSION["team"];
        $conn = new PDO("mysql:host=localhost;dbname=notes_db", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM team WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($team_id));
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)){
            $team = $row["name"];
            break;
        }
        echo "<h1> " . $team ." </h1>";
    }
    else
        echo "Ошибка"; 
?>

<html>
    <head>
        <title> Моя команда </title>
    </head>
    <body>
        <a href="add_note.php"> <button> Добавить заметку </button> </a>
        <a href="add_category.php"> <button> Создать новую категорию </button> </a>
        <br><form method="post">
            <input type="submit" name="back" value="Назад">
        </form>
    </body>
</html>

<?php
    $sql = "SELECT * FROM note WHERE team_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array($team_id));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo "<h2> " . $row["title"] . " </h2>";
        $sql = "SELECT * FROM category WHERE id = ?";
        $stmt_tmp = $conn->prepare($sql);
        $stmt_tmp->execute(array($row["category_id"]));
        while ($row_tmp = $stmt_tmp->fetch(PDO::FETCH_ASSOC)){
            echo "<h4> " . $row_tmp["name"] . " </h4>";
        }
        echo "<p> " . $row["text"] . " </p>";
        echo '<a href="notepage.php?note_id=' . $row["id"] . '"> Коментраии </a>';
        echo "<br>";
    }

    if (isset($_POST["back"])){
        unset($_SESSION["team"]);
        header("Location: http://localhost:8081/web_lab_1+2/homepage.php");
    }
?>