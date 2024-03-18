<!DOCTYPE html>

<?php
    session_start();
    if (isset($_GET["note_id"])){
        $_SESSION["note"] = $_GET["note_id"];
        $note_id = $_GET["note_id"];
    }
    else if (isset ($_SESSION["note"]))
        $note_id = $_SESSION["note"];
    else
        header("Location: http://localhost:8081/web_lab_1+2/teampage.php");
    $user_id = $_SESSION["user"];
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
    $sql = "SELECT * FROM note WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array($note_id));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $note_title = $row["title"];
        $sql = "SELECT * FROM category WHERE id = ?";
        $stmt_tmp = $conn->prepare($sql);
        $stmt_tmp->execute(array($row["category_id"]));
        while ($row_tmp = $stmt_tmp->fetch(PDO::FETCH_ASSOC)){
            echo '<div class="note-container">';
            echo '<div class="note" style="background-color: ' . $row_tmp["color"] . ';">';
            echo "<h2> " . $row["title"] . " </h2>";
            echo "<h3> " . $row_tmp["name"] . " </h3>";
        }
        $sql = "SELECT login FROM user WHERE id = ?";
        $stmt_tmp = $conn->prepare($sql);
        $stmt_tmp->execute(array($row["author_id"]));
        while ($row_tmp = $stmt_tmp->fetch(PDO::FETCH_ASSOC)){
            echo "<h4> " . $row_tmp["login"] . " </h4>";
        }
        echo "<p> " . $row["text"] . " </p>";
        echo "</div></div><br>";
    }
?>

<html>
    <head>
        <title>
            <?php echo $note_title ?>
        </title>
        <link rel="stylesheet" href="styles/comments.css">
    </head>
    <body>
        <div class="control">
        <form method="post">
            <input type="submit" name="back" value="Назад" class="buttons" id="back">
        </form>
        <form method="post">
            <input type="text" name="comment" placeholder="Коментарий" class="comment-field"><br>
            <input type="submit" name="submit" value="Отправить" class="buttons">
        </form>
        </div>
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
    $sql = "SELECT * FROM comment WHERE note_id = ? ORDER BY time DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array($note_id));
    echo '<div class="comments">';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $sql = "SELECT * FROM user WHERE id = ?";
        $stmt_tmp = $conn->prepare($sql);
        $stmt_tmp->execute(array($row["author_id"]));
        echo '<div class="comment">';
        echo '<div class="comment-center">';
        while ($row_tmp = $stmt_tmp->fetch(PDO::FETCH_ASSOC)){
            echo '<div class="author-div">';
            echo '<h4 class="author"> ' . $row_tmp["login"] . " </h4>";
            echo '<img class="pic" src="' . $row_tmp['picture'] . '">';
            echo '</div>';
            break;
        }
        echo "<p> " . $row["text"] . " </p>";
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
    if (isset($_POST["back"])){
        unset($_SESSION["note"]);
        echo '<script>
        window.location.href="teampage.php";</script>';
    }
?>