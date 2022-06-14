<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oxygen&family=PT+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/job_list.css">
    <title>Job Description</title>
</head>

<body>
<?php require_once('template/menu.php'); if (!isset($_SESSION['username']))exit("You must be logged in to view this page");?>
<div class="content">
    <div class="left">
        <?php
        require "db_connection.php";
        $id = $_GET['id'];
        $stmt = "select * from job_form_data";
        $query = $connection->prepare($stmt);
        $query->execute();
        $result = $query->get_result();
        $exist = false;
        foreach ($result as $row) {
            if ($row['ID'] == $id && strcmp($_SESSION['username'],$row['username'])==0) {
                $title = $row['title_text'];
                $description = $row['description_text'];
                $companyname = $row['companyname_text'];
                $wage = $row['rate_text'];
                $wage_type = $row['money_type'];
                $date = $row['daterange'];
                $location = $row['location_type'];
                $job = $row['job_type'];
                $exist = true;
                break;
            }
        }
        if($exist == false)exit("YOUR SUBMISSION DOES NOT EXIST");

        $currentDate = date("Y-m-d");
        $intervalDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentDate)) . " + 1 year"));
        $stmt = "select job_type, job_location from job_freelancer_data";
        $query = $connection->prepare($stmt);
        $query->execute();
        $options = $query->get_result();

        ?>
        <div class="description">
                <form method="post" action="update_submission.php">
                    <h1>Title</h1>
                    <input name="title_text" type="text" value="<?php echo $title ?>"><br>

                    <h1>Company Name</h1>
                    <input name="name_text" type="text" value="<?php echo $companyname ?>"><br>

                    <h1>Description</h1>
                    <textarea name="description_text"><?php echo $description ?></textarea><br>

                    <h3>Wage</h3>
                    <input name="rate_text" type="text" value="<?php echo $wage?>">
                    <select name="money_type">
                        <?php
                        if (strcmp("euro/hour",$wage_type) == 0){
                            echo "<option value='euro/hour'>€/hr</option>";
                            $money = 1;
                        }else if(strcmp("euro/total",$wage_type) == 0){
                            echo "<option value='euro/total'>€</option>";
                            $money = 2;
                        }

                        if ($money == 1){
                            echo "<option value='euro/total'>€</option>";
                        }else{
                            echo "<option value='euro/hour'>€/hr</option>";
                        }
                        ?>
                    </select><br>

                    <h3>Location</h3>
                    <select name="location_type">
                        <option value='<?php echo $location ?>' selected> <?php echo $location ?> </option>
                        <?php foreach($options as $row ){
                            if ($row['job_location'] == null)break;
                            if (strcmp($row['job_location'],$location)== 0){
                                break;
                            }
                            echo "<option value='" . $row['job_location'] . "'>" . $row['job_location'] . "</option>";
                        }
                        ?>
                    </select><br>

                    <h3>Job</h3>
                    <select name="job_type">
                        <option value='<?php echo $job ?>' selected> <?php echo $job ?> </option>
                        <?php foreach($options as $row ){
                            if ($row['job_type'] == null)break;
                            if (strcmp($row['job_type'],$job)== 0){
                                break;
                            }
                            echo "<option value='" . $row['job_location'] . "'>" . $row['job_location'] . "</option>";
                        }
                        ?>
                    </select><br>

                    <h3>Offer is valid until</h3>
                    <input type="date" name="daterange" value="<?php echo $date ?>" min="<?php echo $currentDate ?>" max="<?php echo $intervalDate ?>"><br>

                    <input name="terms" type="checkbox" value="tandc" required>Accept terms<br>
                    <input name="update_compform" type="submit" value="Submit">
                </form>
        </div>
    </div>
</div>
<?php require_once('template/footer.php') ?>
</body>
</html>