<?php
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Welcome </title>
    </head>
    <body>
        <h1> Welcome </h1>
        <form action="welcome.php">
            <label> Логин 
                <input type="text" name="username">
            </label><br>
            <label> Пароль
                <input type="password" name="password">
            </label><br>
            <input type="submit" value="Войти" name="submit">
        </form>
        <a href="register.php"> Зарегестрироваться </a>
        <br>
    </body>
</html>

<?php
    if (isset($_GET["submit"])){
        if (empty($_GET["username"])){
            echo "Enter the username<br>";
            exit();
        }
        else if (empty($_GET["password"])){
            echo "Enter the password<br>";
            exit();
        }
        
        $conn = new PDO("mysql:host=localhost;dbname=notes_db", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM user WHERE login = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($_GET["username"]));

        while ($row = $stmt->fetch(PDO::FETCH_LAZY)){
            if (password_verify($_GET["password"], $row["password"])){
                $_SESSION["user"] = $row["id"];
                header("Location: http://localhost:8081/web_lab_1+2/homepage.php");
            }
            else {
                echo "Try again <br>";
            }
            exit();
        }
        echo "Try again <br>";
    }
?>