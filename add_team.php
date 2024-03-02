<!DOCTYPE html>

<?php
    session_start();
?>

<html>
    <head>
        <title> Добавить команду </title>
    </head>
    <body>
    <form>
            <label> Название команды
                <input type="text" name="teamname">
            </label><br>
            <label> Код доступа
                <input type="password" name="code">
            </label><br>
            <label> Повторите код доступа
                <input type="password" name="code_confirm">
            </label><br>
            <input type="submit" value="Создать!" name="submit">
        </form>
    </body>
</html>

<?php
    if (isset($_GET['submit'])){
        if (empty($_GET['teamname'])){
            echo "Введите название команды!";
            exit();
        }
        else if (empty($_GET["code"])){
            echo "Введите код доступа!"; 
            exit();
        }
        else if (empty($_GET["code_confirm"])){
            echo "Повторите код доступа!";
            exit();
        }
        else if ($_GET["code_confirm"] != $_GET["code"]){
            echo "Коды доступа не совпадают!";
            exit();
        }

        $conn = new PDO("mysql:host=localhost;dbname=notes_db", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM team WHERE name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($_GET["teamname"]));
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)){
            echo "Имя команды уже занято";
            exit();
        }

        $sql = "INSERT INTO team (name, code) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($_GET["teamname"], password_hash($_GET["code"], PASSWORD_DEFAULT)));
        $team_id = $conn->lastInsertId();
        $sql = "INSERT INTO `user-team` (user_id, team_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($_SESSION["user"], $team_id));
        echo '<script>
            alert("Команда добавлена успешно");
            window.location.href="homepage.php";
            </script>';

    }
?>