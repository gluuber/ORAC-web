<!DOCTYPE html>
<html lang="en">

<head>
    <title>NSW ORAC: Process: Update intro text</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        html,
        body,
        h1,
        h2,
        h3,
        h4,
        h5 {
            font-family: "Raleway", sans-serif
        }
    </style>
</head>

<body class="w3-light-grey">
    <!-- Top container -->
    <div class="w3-bar w3-top w3-black w3-large" style="z-index:4">
        <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey"
            onclick="w3_open();"><i class="fa fa-bars"></i> NSW ORAC ADMIN</button>
        <span class="w3-bar-item w3-right">NSW ORAC ADMIN</span>
    </div>

    <!-- !PAGE CONTENT! -->
    <div class="w3-main" style="margin-top:63px;">
        <div class="w3-row w3-padding">
            <?php
if (isset($_POST['submit']) && $_FILES['uploaded_file']['error'] === UPLOAD_ERR_OK) {
    $tmpName = $_FILES['uploaded_file']['tmp_name'];

    // Convert lines directly into an array of arrays
    $csv = array_map('str_getcsv', file($tmpName));

//echo "<pre>";
//print_r($csv);
//echo "</pre>";

    /**
     * Script to insert CSV data into DB.
     * 
     * To run use following URLS:
     * /src/process/insert-data.php?dataset=case
     * /src/process/insert-data.php?dataset=review
     */
    require_once '../../mysql.connection.php';

    if (isset($_POST['dataset']) && dataSetExists($_POST['dataset'])) {

        // Define a constant for the MySQL table to use in queries.
        define('MYSQL_TABLE', 'orac_intros');
        echo '<a href="index.shtml">Back</a><br>';
        echo '<h1>Processing ' . $_POST['dataset'] . ' intro text</h1>';

        // DROP EXISTING TABLE
        $conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // CREATE FRESH TABLE
        $conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $query = "INSERT INTO " . MYSQL_TABLE . " "
        . "VALUES("
        . $count . ","
        . "'" . $record[0] . "',"
        . "'" . $record[1] . "',"
        . "'" . mysqli_real_escape_string($conn, $record[2]) . "',"
        . "'" . mysqli_real_escape_string($conn, $record[3]) . "',"
        . "'" . mysqli_real_escape_string($conn, $record[4]) . "',"
        . "'" . date("Y-m-d H:i:s", strtotime($record[5])) . "',"
        . "'" . $record[6] . "'"
        . ")";

        echo 'Done updating ' . $data_set . ' intro.<br>';
        echo '<a href="index.shtml">Back</a><br>';
    } else {
        echo ('Something went wrong@#$!<br>');
        echo '<a href="index.shtml">Back</a><br>';
    }
}
else {
    echo ('Something went wrong@#$!<br>');
    echo '<a href="index.shtml">Back</a><br>';
}
/**
 * Check if the parameter dataset is legit.
 * 
 * @param string $data_set
 * @return boolean
 */
function dataSetExists($data_set)
{
    $exists = false;
    switch ($data_set) {
        case 'about':
            $exists = true;
            break;
        case 'case':
            $exists = true;
            break;
        case 'review':
            $exists = true;
            break;
        case 'submission':
            $exists = true;
            break;
        case 'barc':
            $exists = true;
            break;
        default:
            break;
    }
    return $exists;
}

?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="w3-container w3-padding-16 w3-light-grey">
        <hr>
    </footer>

</body>

</html>