<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oxygen&family=PT+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main_page.css">
    <link rel="stylesheet" href="styles/main.css">
    <title>Sign Up</title>
    <script src="JS/validation.js" defer></script>
</head>

<body>
    <?php require_once('template/menu.php') ?>
    <div class="content">
        <div class="left">
            <form action="login_account.php" method="post">
                <label for="username">
                    USERNAME:
                    <input type="text" name="username" id="username" pattern="[A-Za-z0-9_]+" placeholder="USERNAME" minlength="4" maxlength="20" required>
                    <br>
                </label>
                <label for="password">
                    PASSWORD:
                    <input type="password" name="password" id="password" pattern="[A-Za-z0-9_]+" placeholder="PASSWORD" minlength="6" maxlength="20" required>
                    <br>
                </label>
                <label for="type">
                    TYPE:
                    <select name="type" id="type">
                        <option value="company">COMPANY</option>
                        <option value="freelancer">FREELANCER</option>
                    </select>
                    <br>
                </label>
                <input type="submit" id="submit" name="submit" value="Log in">
                <br>

            </form>
        </div>
    </div>
    <?php require_once('template/footer.php') ?>
</body>

</html>