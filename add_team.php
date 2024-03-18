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
        <title> Добавить команду </title>
        <link rel="stylesheet" href="styles/team-form.css">
    </head>
    <body>
        <div class="form">
            <div class="form-center">
                <h1> Добавить команду </h1>
                <form method="post">
                    <input type="text" name="teamname" placeholder="Название команды"><br>
                    <input type="password" name="code" placeholder="Код доступа"><br>
                    <input type="password" name="code_confirm" placeholder="Повтор кода доступа"><br>
                    <input type="submit" value="Создать!" name="submit" class="button-decor"><br>
                </form>
                <a href="homepage.php"> <button class="button-decor"  id="back"> Назад </button> </a>
            </div>
        </div>
    </body>
</html>

<?php
    if (isset($_POST['submit'])){
        if (empty($_POST['teamname'])){
            echo '<script> alert("Введите название команды"); </script>';
            exit();
        }
        else if (empty($_POST["code"])){
            echo '<script> alert("Введите код доступа"); </script>';
            exit();
        }
        else if (empty($_POST["code_confirm"])){
            echo '<script> alert("Повторите код доступа"); </script>';
            exit();
        }
        else if ($_POST["code_confirm"] != $_POST["code"]){
            echo '<script> alert("Коды не совпадают"); </script>';
            exit();
        }

        $conn = new PDO("mysql:host=localhost;dbname=notes_db", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM team WHERE name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($_POST["teamname"]));
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)){
            echo "Имя команды уже занято";
            exit();
        }
        try{
            $conn->beginTransaction();
            $sql = "INSERT INTO team (name, code) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute(array($_POST["teamname"], password_hash($_POST["code"], PASSWORD_DEFAULT)));
            $team_id = $conn->lastInsertId();
            $sql = "INSERT INTO category (name, color, team_id) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute(array("Обычная", "white", $team_id));
            $sql = "INSERT INTO `user-team` (user_id, team_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute(array($_SESSION["user"], $team_id));
            $conn->commit();
            echo '<script>
                alert("Команда добавлена успешно");
                window.location.href="homepage.php";
                </script>';
        }
        catch(PDOException $e){
            $conn->rollBack();
            echo '<script>
                alert("Ошибка! Попробуйте снова!");
                window.location.href="add_team.php";
                </script>';
        }
    }
?>