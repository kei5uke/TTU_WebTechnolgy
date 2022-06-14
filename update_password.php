<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oxygen&family=PT+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <title>Sign Up</title>
</head>

<body>
    <?php require_once('template/menu.php') ?>

    <?php if (!isset($_SESSION['username'])) {
        echo "You must be logged in.";
    }
    ?>
    <div class="content">
        CHANGE PASSWORD:<br>
        <form action="#" method="post">
            <label for="password">
                New password:
                <input type="password" name="password" id="password" placeholder="PASSWORD" required>
                <br>
            </label>
            <input type="submit" name="submit">
            <br>
        </form>
    </div>

    <?php
    //SQL <=> PHP Part
    require "db_connection.php";

    if ($_POST && isset($_POST["submit"])) {
        $password = "";
        switch (empty($_POST["password"])) {
            case false:
                switch (preg_match("/^[a-z0-9][a-z0-9_]*[a-z0-9]$/", $_POST["password"])) {
                    case 1:
                        if (strlen($_POST["password"]) > 20) {
                            exit("Password is too long (max 20 characters).");
                        } else {
                            $password = $_POST["password"];
                        }
                        break;
                    case 0:
                        exit("Invalid password input (must use numbers and letters).");
                }
                break;
            case true:
                exit("Empty password input.");
        }
        $update = "UPDATE account SET password='" . $password . "' WHERE username='" . $_SESSION['username'] . "';";
        echo $update;
        $query = $connection->prepare($update);
        $query->execute();
        echo ("The password has been changed successfully.");
    }

    ?>

    <?php require_once('template/footer.php') ?>

</body>

</html>