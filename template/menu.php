<?php
session_start();
if (isset($_POST['logout'])) {
    session_start();
    session_destroy();
}
?>
<header>
    <nav>
        <a class="image" href="index.php"><img alt="main_page" src="img/main_page_icon.png"></a>
        <div class="menu_table">
            <ul>
                <?php
                    if (isset($_SESSION['username'])) {
                        echo "<li class='menu'><a href='job_list.php'>Find Work</a></li>";
                        echo "<li class='menu'><a href='freelancer_list.php'>Find Freelancer</a></li>";
                        echo "<li class='menu'><a href='account.php'>My Account</a></li>";
                        echo "<li class='menu'><a href='job_submission_form.php'>Submit Job Offer</a></li>";
                        echo "<li class='menu'><a href='freelancer_submission_form.php'>Submit Freelancer Listing</a></li>";
                    } else {
                        echo "<li class='menu'><a href='job_list.php'>Find Work</a></li>";
                        echo "<li class='menu'><a href='freelancer_list.php'>Find Freelancer</a></li>";
                    }
                ?>
            </ul>
        </div>
        <div class="menu_account_table">
            <ul>
                <?php
                if (isset($_SESSION['username'])) {
                    echo "<li class='account'><a href='account.php'>" . $_SESSION['username'] . "</a></li>";
                    echo "<li class='account'><a href='logout.php'>Log out</a></li>";
                } else {
                    echo "<li class='account'><a href='login.php'>Log in</a></li>";
                    echo "<li class='account'><a href='register.php'>Sign up</a></li>";
                }
                ?>
            </ul>
        </div>
    </nav>
</header>