<!DOCTYPE html>

<?php
    session_start();
    $team_id = $_SESSION["team"];
?>

<html>
    <head>
        <title> Добавить категорию </title>
    </head>
    <body>
        <form>
            <label> Название
                <input type="text" name="name"> 
            </label><br>
            <label> Цвет 
                <select name="color">
                    <option value="gray"> Серый </option>
                    <option value="red"> Красный </option>
                </select>
            </label>
            <input type="submit" value="Добавить" name="submit">
        </form>
        <a href="teampage.php"> Назад </a>
    </body>
</html>

<?php
    if (isset($_GET["submit"])){
        $conn = new PDO("mysql:host=localhost;dbname=notes_db", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO category (name, color, team_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($_GET["name"], $_GET["color"], $team_id));
        echo '<script>
            alert("Категория добавлена успешно");
            window.location.href="teampage.php";
            </script>';

    }
?>