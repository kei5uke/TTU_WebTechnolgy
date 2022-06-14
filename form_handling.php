<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oxygen&family=PT+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <title>Job Submitted</title>
</head>

<body>
    <?php require_once('template/menu.php') ?>
    <div class="content">
        <?php
        if (!isset($_SESSION['username'])) {
            exit("You must be logged in.");
        } else {
            echo '<h1>Data has been sent to the server</h1><a href="index.php">Go back to index page</a>';
        }
        require "db_connection.php";
        if (isset($_POST['jobform'])) {
            // title_text
            $title_text = $_POST['title_text'];
            // description_text
            $description_text = $_POST['description_text'];
            // companyname_text
            $companyname_text = $_POST['companyname_text'];
            // rate_text
            $rate_text = $_POST['rate_text'];
            // money_type
            $money_type = $_POST['money_type'];
            // daterange
            $daterange = $_POST['daterange'];
            // location_type
            $location_type = $_POST['location_type'];
            // job_type
            $job_type = $_POST['job_type'];

            $datastuff = "insert into job_form_data (title_text, description_text, companyname_text, rate_text, money_type, daterange, location_type, job_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $query = $connection->prepare($datastuff);
            $query->bind_param("ssssssss", $title_text, $description_text, $companyname_text, $rate_text, $money_type, $daterange, $location_type, $job_type);
            $query->execute();
        } else if (isset($_POST['freeform'])) {
            // title_text
            $title_text = $_POST['title_text'];
            // description_text
            $description_text = $_POST['description_text'];
            // rate_text
            $rate_text = $_POST['rate_text'];
            // money_type
            $money_type = $_POST['money_type'];
            // location_type
            $location_type = Sanitize_Location($_POST['location_type']);
            // job_type
            $job_type = Sanitize_Job_type($_POST['job_type']);
            // name_text
            $name_text = $_POST['name_text'];
            $datastuff = "insert into freelancer_form_data (title_text, description_text, rate_text, money_type, location_type, job_type, name_text)  VALUES (?, ?, ?, ?, ?, ?, ?)";
            $query = $connection->prepare($datastuff);
            $query->bind_param("sssssss", $title_text, $description_text, $rate_text, $money_type, $location_type, $job_type, $name_text);
            $query->execute();
        } else if (isset($_POST['apply_button'])) {
            $id = $_POST['jobid'];
            $companyname = $_POST['companyname'];
            $username = $_SESSION['username'];
            // remove all spaces and convert to lowercase
            $companyname = preg_replace('/\s*/', '', $companyname);
            // convert the string to all lowercase
            $companyname = strtolower($companyname);
            // add check to see if user is a freelancer
            if ($_SESSION['type'] == "freelancer") {
                // send data to sql server
                $insertdata = "insert into applied_jobs (ID, username, company, status) values (?, ?, ?, 'unconfirmed')";
                $query = $connection->prepare($insertdata);
                $query->bind_param("sss", $id, $username, $companyname);
                $query->execute();
            } else {
                echo "<h1>Companies cant apply for jobs!</h1>";
                echo '<a href="index.php">Go back to index page</a>';
            }
        } else if (isset($_POST['hire_button'])) {
            $id = $_POST['jobid'];
            $companyname = $_SESSION['username'];
            $name = $_POST['name'];
            $insertdata = "select ID, username from job_form_data";
            $query = $connection->prepare($insertdata);
            $query->execute();
            $result = $query->get_result();

            foreach ($result as $row) {
                if ($row['username'] == $companyname) {
                    $id = $row['ID'];
                    // remove all spaces and convert to lowercase
                    $username = preg_replace('/\s*/', '', $name);
                    // convert the string to all lowercase
                    $username = strtolower($username);
                    $insertdata = "insert into applied_jobs (ID, username, company, status) values (?, ?, ?, 'hired')";
                    $query = $connection->prepare($insertdata);
                    $query->bind_param("sss", $id, $username, $companyname);
                    $query->execute();
                    break;
                } else {
                    echo "<h1>You have not posted any job offers</h1>";
                }
            }
        } else if (isset($_POST['unconfirmed'])) {
            $id = $_POST['jobid'];
            $username = $_POST['username'];
            $changedata = "update applied_jobs set status = 'inprogress' where ID = ? and username = ?";
            $query = $connection->prepare($changedata);
            $query->bind_param("is", $id, $username);
            $query->execute();
            $changedata = "update applied_jobs set status = 'canceledC' where ID = ? and username != ?";
            $query = $connection->prepare($changedata);
            $query->bind_param("is", $id, $username);
            $query->execute();
        } else if (isset($_POST['inprogress'])) {
            $id = $_POST['jobid'];
            $username = $_POST['username'];
            $changedata = "update applied_jobs set status = 'workdone' where ID = ? and username = ?";
            $query = $connection->prepare($changedata);
            $query->bind_param("is", $id, $username);
            $query->execute();
        } else if (isset($_POST['workdone'])) {
            $id = $_POST['jobid'];
            $rating = $_POST['rating'];
            $companyname = $_POST['companyname'];
            $feedback = $_POST['feedback'];
            $username = $_POST['username'];
            $type = $_SESSION['type'];

            $changedata = "update applied_jobs set status = 'workconfirmed' where ID = ? and username = ?";
            $query = $connection->prepare($changedata);
            $query->bind_param("is", $id, $username);
            $query->execute();
            send_feedback($username, $companyname, $rating, $feedback, $type);
        } else if (isset($_POST['workconfirmed'])) {
            $id = $_POST['jobid'];
            $rating = $_POST['rating'];
            $companyname = $_POST['companyname'];
            $feedback = $_POST['feedback'];
            $username = $_POST['username'];
            $type = $_SESSION['type'];
            $changedata = "update applied_jobs set status = 'complete' where ID = ? and username = ?";
            $query = $connection->prepare($changedata);
            $query->bind_param("is", $id, $username);
            $query->execute();
            send_feedback($username, $companyname, $rating, $feedback, $type);
        } else if (isset($_POST['incomplete'])) {
            $id = $_POST['jobid'];
            $username = $_POST['username'];
            $changedata = "update applied_jobs set status = 'inprogress' where ID = ? and username = ?";
            $query = $connection->prepare($changedata);
            $query->bind_param("is", $id, $username);
            $query->execute();
        } else if (isset($_POST['decline'])) {
            $id = $_POST['jobid'];
            $username = $_POST['username'];
            $changedata = "update applied_jobs set status = 'canceledF' where ID = ? and username = ?";
            $query = $connection->prepare($changedata);
            $query->bind_param("is", $id, $username);
            $query->execute();
        } else if (isset($_POST['deletejob'])) {
            $id = $_POST['jobid'];
            $username = $_POST['username'];
            $changedata = "delete from applied_jobs where ID = ? and username = ?";
            $query = $connection->prepare($changedata);
            $query->bind_param("is", $id, $username);
            $query->execute();
        }

        function send_feedback($username, $companyname, $rating, $feedback, $type)
        {
            // switching the type around to make it more intuitive for displaying information in freelancer_description and job_description
            // i.e. this feedback is for the freelancer
            if ($type == "company") {
                $type = "freelancer";
            } else if ($type == "freelancer") {
                $type = "company";
            }
            require "db_connection.php";
            $feedbackdata = "insert into feedback (username, company, rating, feedback, type) values (?, ?, ?, ?, ?)";
            $query = $connection->prepare($feedbackdata);
            $query->bind_param("ssiss", $username, $companyname, $rating, $feedback, $type);
            $query->execute();
        }

        function Sanitize_Location($location)
        {
            $AllowedValuesForLocation = ["Tallinn", "Tartu", "Pärnu", "Narva", "Kohtla-järve", null];
            $key = array_search($location, $AllowedValuesForLocation, true);
            if ($key === false) {
                throw new InvalidArgumentException("Invalid location value");
            }
            return $location;
        }

        function Sanitize_Job_Type($job_type)
        {
            $AllowedValuesForJobType = ["Engineering", "Business", "Arts", "Science", "Construction", "Part time job", "IT", null];
            $key = array_search($job_type, $AllowedValuesForJobType, true);
            if ($key === false) {
                throw new InvalidArgumentException("Invalid location value");
            }
            return $job_type;
        }
        ?>
    </div>
    <?php require_once('template/footer.php') ?>
</body>

</html>