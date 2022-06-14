<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oxygen&family=PT+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <title>My Account</title>
</head>

<body>
    <?php require_once('template/menu.php') ?>
    <div class="content">
        <div class="left">
            <h1><a href="edit_submission.php">My submission</a></h1>
            <h1><a href="update_password.php">Change password</a></h1>
            <h1>My jobs</h1>
            <?php
                require "db_connection.php";
                $stmt = "select applied_jobs.ID, title_text, companyname_text, applied_jobs.username, company, status from applied_jobs inner join job_form_data on applied_jobs.ID = job_form_data.ID";
                $query = $connection->prepare($stmt);
                $query->execute();
                $result = $query->get_result();

                $have_jobs = false;

                foreach ($result as $row) {
                    switch ($row['status']){
                        case "unconfirmed":
                            if ($_SESSION['type'] == "company") {
                                if ($row['company'] == $_SESSION['username']) {
                                    echo "<h3>You have a job application from ", $row['username'], " on the ", $row['title_text'], " position</h3>";
                                    echo "<form name='unconfirmed' action='form_handling.php' method='post'>";
                                    echo "<input type='hidden' name='jobid' value='", $row['ID'], "'>";
                                    echo "<input type='hidden' name='username' value='", $row['username'], "'>";
                                    echo "<input type='submit' name='unconfirmed' value='Pick this candidate for the job'></form>";
                                    $have_jobs = true;
                                }
                            }else if($_SESSION['type'] == "freelancer"){
                                if ($row['username'] == $_SESSION['username']) {
                                    echo "<h3>Your job application has been sent to ", $row['company'], " waiting for them to select you for the ", $row['title_text'], " position</h3>";
                                }
                            }
                            break;
                        case "inprogress":
                            if ($_SESSION['type'] == "freelancer") {
                                if ($row['username'] == $_SESSION['username']) {
                                    //display jobs
                                    echo "<h3>Your current job is from ", $row['companyname_text'], " and you're working as a ", $row['title_text'], "</h3>";
                                    echo "<form name='inprogress' action='form_handling.php' method='post'>";
                                    echo "<input type='hidden' name='jobid' value='", $row['ID'], "'>";
                                    echo "<input type='hidden' name='username' value='", $row['username'], "'>";
                                    echo "<input type='submit' name='inprogress' value='Mark the job as done'></form>";
                                    $have_jobs = true;
                                }
                            }else if($_SESSION['type'] == "company"){
                                if ($row['company'] == $_SESSION['username']) {
                                    echo "<h3>Your job ", $row['title_text'], " is being worked on.</h3>";
                                }
                            }
                            break;
                        case "workdone":
                        if ($_SESSION['type'] == "company") {
                            if ($row['company'] == $_SESSION['username']) {
                                echo "<h3>", $row['username'], " marked his job: ", $row['title_text'], " as finished, do you confirm that the job is finished?</h3>";
                                echo "<form name='workdone' action='form_handling.php' method='post'>Feedback:<br>";
                                echo "<textarea placeholder='Leave feedback here' name='feedback'></textarea><br>";
                                echo "Rate the freelancer:<select name='rating'><option value='1'>1</option>";
                                echo "<option value='2'>2</option>";
                                echo "<option value='3'>3</option>";
                                echo "<option value='4'>4</option>";
                                echo "<option value='5'>5</option></select><br>";
                                echo "<input type='hidden' name='jobid' value='", $row['ID'], "'>";
                                echo "<input type='hidden' name='companyname' value='", $row['company'], "'>";
                                echo "<input type='hidden' name='username' value='", $row['username'], "'>";
                                echo "<input type='submit' name='workdone' value='Confirm that job is finished and leave feedback'>";
                                echo "<input type='submit' name='incomplete' value='Incomplete job'></form>";
                                $have_jobs = true;
                            }
                        }else if($_SESSION['type'] == "freelancer"){
                            echo "<h3>Waiting for ", $row['company'], " to confirm that your job is finished</h3>";
                        }
                            break;
                        case "workconfirmed":
                            if ($_SESSION['type'] == "freelancer") {
                                if ($row['username'] == $_SESSION['username']) {
                                    echo "<h3>", $row['company'], " marked your job: ", $row['title_text'], " as finished, leave feedback for the company below</h3>";
                                    echo "<form name='workconfirmed' action='form_handling.php' method='post'>Feedback:<br>";
                                    echo "<textarea placeholder='Leave feedback here' name='feedback'></textarea><br>";
                                    echo "Rate the company:<select name='rating'><option value='1'>1</option>";
                                    echo "<option value='2'>2</option>";
                                    echo "<option value='3'>3</option>";
                                    echo "<option value='4'>4</option>";
                                    echo "<option value='5'>5</option></select>";
                                    echo "<input type='hidden' name='jobid' value='", $row['ID'], "'>";
                                    echo "<input type='hidden' name='companyname' value='", $row['company'], "'>";
                                    echo "<input type='hidden' name='username' value='", $row['username'], "'>";
                                    echo "<input type='submit' name='workconfirmed' value='Leave feedback for the company'></form>";
                                    $have_jobs = true;
                                }
                            }
                            break;
                        case "hired":
                            if ($_SESSION['type'] == "freelancer") {
                                if ($row['username'] == $_SESSION['username']) {
                                    echo "<h3>You have a job request from ", $row['company'], " on the ", $row['title_text'], " position</h3>";
                                    echo "<form name='unconfirmed' action='form_handling.php' method='post'>";
                                    echo "<input type='hidden' name='jobid' value='", $row['ID'], "'>";
                                    echo "<input type='hidden' name='username' value='", $_SESSION['username'], "'>";
                                    echo "<input type='submit' name='unconfirmed' value='Accept job offer'>";
                                    echo "<input type='submit' name='decline' value='Decline job offer'></form>";
                                    $have_jobs = true;
                                }
                            }else if($_SESSION['type'] == "company"){
                                if ($row['company'] == $_SESSION['username']) {
                                    echo "<h3>Waiting for ", $row['username'], " to accept your hiring request</h3>";
                                }
                            }
                            break;
                        case "canceledC":
                            if($_SESSION['type'] == "freelancer"){
                                if ($row['username'] == $_SESSION['username']) {
                                    echo "<h3>", $row['company'], " has picked someone else for their job.</h3>";
                                    echo "<form name='deletejob' action='form_handling.php' method='post'>";
                                    echo "<input type='hidden' name='jobid' value='", $row['ID'], "'>";
                                    echo "<input type='hidden' name='username' value='", $_SESSION['username'], "'>";
                                    echo "<input type='submit' name='deletejob' value='Delete job request'></form>";
                                }
                            }
                            break;
                        case "canceledF":
                            if($_SESSION['type'] == "company"){
                                if ($row['company'] == $_SESSION['username']) {
                                    echo "<h3>", $row['username'], " has canceled your job request.</h3>";
                                    echo "<form name='deletejob' action='form_handling.php' method='post'>";
                                    echo "<input type='hidden' name='jobid' value='", $row['ID'], "'>";
                                    echo "<input type='hidden' name='username' value='", $row['username'], "'>";
                                    echo "<input type='submit' name='deletejob' value='Delete job request'></form>";
                                }
                            }
                        }
                    }



                if ($have_jobs == false) {
                    echo "<h1>You currently have no jobs</h1>";
                }
                ?>
        </div>
</div>
    <?php require_once('template/footer.php') ?>

</body>

</html>
