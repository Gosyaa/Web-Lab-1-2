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
    include("headers/header2.php");
?>

<html>
    <head>
        <title> Главная страница </title>
        <link rel="stylesheet" href="styles/homepage.css">
        <link rel="stylesheet" href="styles/buttons.css">
    </head>
    <body>
        <div class="main">
            <div class="teams">

                <?php
                    if (isset($_SESSION["user"])){
                        $sql = "SELECT team_id FROM `user-team` WHERE user_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute(array($user_id));
                        $count = 1;
                        while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            $sql = "SELECT name FROM team WHERE id = ?";
                            $stmt_tmp = $conn->prepare($sql);
                            $stmt_tmp->execute(array($row["team_id"]));
                            echo '<div class="team-div">';
                            while ($row_tmp = $stmt_tmp->fetch(PDO::FETCH_ASSOC)){ 
                                echo "<h2> " . $row_tmp["name"] . " </h2><br>";
                                echo '<a href="teampage.php?team_id=' . $row["team_id"] . '"> Перейти к команде --> </a><br>';
                            }
                            echo '</div>';
                        }
                    }
                ?>

            <div class="team-buttons">
                <a href="join_team.php"> <button class="team-button">  Присоеденится к команде </button></a>
                <a href="add_team.php"> <button class="team-button"> Создать новую команду </button></a>
            </div>
            
            <form method="post">
                <input type="submit" name="back" value="Выйти" class="leave">
            </form><br>

                <?php    
                    if (isset($_POST["back"])){
                        unset($_SESSION["user"]);
                        header("Location: http://localhost:8081/web_lab_1+2/welcome.php");
                    }
                ?>
            </div>
        </div>
    </body>
</html>