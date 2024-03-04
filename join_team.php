<!DOCTYPE html>

<?php
    session_start();
?>

<html>
    <head>
        <title> Присоединится к команде </title>
    </head>
    <body>
        <form>
            <label> Название команды
                <input type="text" name="teamname">
            </label><br>
            <label> Код доступа
                <input type="password" name="code">
            </label><br>
            <input type="submit" value="Присоединиться!" name="submit">
        </form>
        <a href="homepage.php"> Назад </a>
    </body>
</html>

<?php
    if (isset($_GET["submit"])){
        if (empty($_GET["teamname"])){
            echo "Введите название команды!<br>";
            exit();
        }
        else if (empty($_GET["code"])){
            echo "Ввведите код доступа!<br>";
            exit();
        }

        $conn = new PDO("mysql:host=localhost;dbname=notes_db", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM team WHERE name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($_GET["teamname"]));
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)){
            if (password_verify($_GET["code"], $row["code"])){
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