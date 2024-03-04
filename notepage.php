<!DOCTYPE html>

<?php
    session_start();
    if (isset($_GET["note_id"])){
        $_SESSION["note"] = $_GET["note_id"];
        $note_id = $_GET["note_id"];
    }
    else if (isset ($_SESSION["note"]))
        $note_id = $_SESSION["note"];
    $user_id = $_SESSION["user"];
    $conn = new PDO("mysql:host=localhost;dbname=notes_db", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM note WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array($note_id));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo "<h2> " . $row["title"] . " </h2>";
        $note_title = $row["title"];
        $sql = "SELECT * FROM category WHERE id = ?";
        $stmt_tmp = $conn->prepare($sql);
        $stmt_tmp->execute(array($row["category_id"]));
        while ($row_tmp = $stmt_tmp->fetch(PDO::FETCH_ASSOC)){
            echo "<h4> " . $row_tmp["name"] . " </h4>";
        }
        echo "<p> " . $row["text"] . " </p>";
        echo "<br>";
    }
?>

<html>
    <head>
        <title>
            <?php echo $note_title ?>
        </title>
    </head>
    <body>
        <form method="post">
            <input type="submit" name="back" value="Назад">
        </form><br>
        <form method="post">
            <label> Оставить комментарий
                <input type="text" name="comment">
            </label><br>
            <input type="submit" name="submit" value="Отправить">
        </form>
    </body>
</html>

<?php
    if (isset($_POST["submit"]) && !empty($_POST["comment"])) {
        $sql = "INSERT INTO comment (author_id, note_id, text) VALUE (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($user_id, $note_id, $_POST["comment"]));
        $_POST = array();
        header("Location: http://localhost:8081/web_lab_1+2/notepage.php");
    }
    $sql = "SELECT * FROM comment WHERE note_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array($note_id));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $sql = "SELECT * FROM user WHERE id = ?";
        $stmt_tmp = $conn->prepare($sql);
        $stmt_tmp->execute(array($row["author_id"]));
        while ($row_tmp = $stmt_tmp->fetch(PDO::FETCH_ASSOC)){
            echo "<h4> " . $row_tmp["login"] . " </h4>";
            break;
        }
        echo "<p> " . $row["text"] . " </p>";
    }

    if (isset($_POST["back"])){
        unset($_SESSION["note"]);
        header("Location: http://localhost:8081/web_lab_1+2/teampage.php");
    }
?>