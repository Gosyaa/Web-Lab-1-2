<!DOCTYPE html>

<?php
    session_start();
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
?>

<html>
    <head>
        <title> Добавить категорию </title>
        <link rel="stylesheet" href="styles/team-form.css">
        <link rel="stylesheet" href="styles/category-form.css">
    </head>
    <body>
        <div class="form">
            <div class="form-center">
                <form method="post">
                    <input type="text" name="name" placeholder="Название"> <br>
                    <label> Цвет:
                        <select name="color">
                            <option value="white"> Белый </option>
                            <option value="Aquamarine"> Бирюзовый </option>
                            <option value="LightGreen"> Зелёный </option>
                            <option value="LightPink"> Розовый </option>
                            <option value="PeachPuff"> Персиковый </option>
                            <option value="Wheat"> Бежывый </option>
                            <option value="GoldenRod"> Жёлтый </option>
                        </select>
                    </label><br>    
                    <input type="submit" value="Добавить" name="submit" class="button-decor">
                </form>
                <a href="teampage.php"> <button class="button-decor" id="back3"> Назад </button> </a>
            </div>
        </div>
    </body>
</html>

<?php
    if (isset($_POST["submit"])){
        if (empty($_POST["name"])){
            echo '<script> alert("Введите название категоии"); </script>';
            exit();
        }
        $conn = new PDO("mysql:host=localhost;dbname=notes_db", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO category (name, color, team_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($_POST["name"], $_POST["color"], $team_id));
        echo '<script>
            alert("Категория добавлена успешно");
            window.location.href="teampage.php";
            </script>';

    }
?>