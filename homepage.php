<!DOCTYPE html>

<?php
    session_start();
    $conn = new PDO("mysql:host=localhost;dbname=notes_db", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $user_id = $_SESSION["user"];
    $sql = "SELECT * FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array($user_id));
    while ($row = $stmt->fetch(PDO::FETCH_LAZY)){
        $user = $row["login"];
        break;
    }
    //echo $user;
    echo "<h1> " . $user . " </h1>";
?>

<html>
    <head>
        <title> Главная страница </title>
    </head>
    <body>
        <form method="post">
            <input type="submit" name="back" value="Выйти">
        </form><br>
        <a href="join_team.php"> <button>  Присоеденится к команде </button></a>
        <a href="add_team.php"> <button> Создать новую команду </button></a>
    </body>
</html>

<?php
    if (isset($_SESSION["user"])){
        $sql = "SELECT * FROM `user-team` WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($user_id));
        $count = 1;
        while ( $row = $stmt->fetch(PDO::FETCH_LAZY)){
            echo "<h4> " . $row["team_id"] . " </h4><br>";
            echo '<a href="teampage.php?team_id=' . $row["team_id"] . '"> Перейти к команде </a><br>';
        }
    }

    if (isset($_POST["back"])){
        unset($_SESSION["user"]);
        header("Location: http://localhost:8081/web_lab_1+2/welcome.php");
    }
?>