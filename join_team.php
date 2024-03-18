<!DOCTYPE html>

<?php
    session_start();
    $conn = new PDO("mysql:host=localhost;dbname=notes_db", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $user_id = $_SESSION["user"];
    $sql = "SELECT login FROM user WHERE id = ?";
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
        <title> Присоединится к команде </title>
        <link rel="stylesheet" href = "styles/team-form.css">
    </head>
    <body>
        <div class="form"> 
            <div class="form-center">
                <h1> Присоединиться к команде </h1>
                <form method="post">    
                    <input type="text" name="teamname" placeholder="Название команды"><br>
                    <input type="password" name="code" placeholder="Код доступа"><br>
                    <input type="submit" value="Присоединиться!" name="submit" class="button-decor">
                </form>
                <a href="homepage.php"> <button class="button-decor" id="back2"> Назад </button> </a>
            </div>
        </div>
    </body>
</html>

<?php
    if (isset($_POST["submit"])){
        if (empty($_POST["teamname"])){
            echo '<script> alert("Введите название команды"); </script>';
            exit();
        }
        else if (empty($_POST["code"])){
            echo '<script> alert("Введите код доступа"); </script>';
            exit();
        }

        $conn = new PDO("mysql:host=localhost;dbname=notes_db", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM team WHERE name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($_POST["teamname"]));
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)){
            if (password_verify($_POST["code"], $row["code"])){
                $sql = "INSERT INTO `user-team` (user_id, team_id) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute(array($_SESSION["user"], $row["id"]));
                echo '<script>
                alert("Команда добавлена успешно");
                window.location.href="homepage.php";
                </script>';
            }
            else{
                echo "Код доступа неверный!";
                exit();
            }
        }
        echo "Команда не найдена!";
    }
?>