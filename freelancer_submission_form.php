<?php
require "db_connection.php";
$currentDate = date("Y-m-d");
$intervalDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentDate)) . " + 1 year"));
$stmt = "select job_type, job_location from job_freelancer_data";
$query = $connection->prepare($stmt);
$query->execute();
$result = $query->get_result();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Oxygen&family=PT+Sans&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="styles/main.css">
        <title>Freelancer Listing Submission Form</title>
    </head>

    <body>
        <?php require_once('template/menu.php') ?>
        <div class="content">
            <div class="left">
                <?php if (!isset($_SESSION['username']))exit("You must be logged in to view this page"); ?>
                <form name = "freelancer_submission_form" action="form_handling.php" method="post">
                    <p>Title:</p>
                    <input name="title_text" type="text"><br>
                    <p>Name:</p>
                    <input name="name_text" type="text"><br>
                    <p>Job Description:</p>
                    <textarea name="description_text" placeholder="Enter job description here:"></textarea><br>
                    <input name="rate_text" type="text" placeholder="Enter wage amount here (optional)">
                    <select name="money_type">
                        <option value="" disabled="" selected="" hidden="">Choose wage type</option>
                        <option value="euro/hour">€/hr</option>
                        <option value="euro/total">€</option>
                    </select><br>
                    <select name="location_type">
                        <option value="" disabled="" selected="" hidden="">Choose job location</option>
                        <?php foreach($result as $row ){
                            if ($row['job_location'] == null)break;
                            echo "<option value='" . $row['job_location'] . "'>" . $row['job_location'] . "</option>";
                        }
                        ?>
                    </select><br>
                    <select name="job_type">
                        <option value="" disabled="" selected="" hidden="">Choose job type</option>
                        <?php foreach($result as $row ){
                            if ($row['job_type'] == null)break;
                            echo "<option value='" . $row['job_type'] . "'>" . $row['job_type'] . "</option>";
                        }
                        ?>
                    </select><br>
                    <input name="terms" type="checkbox" value="tandc" required>Accept terms<br>
                    <input name="freeform" type="submit" value="Submit">
                </form>
            </div>
        </div>

        <?php require_once('template/footer.php') ?>
    </body>
</html>