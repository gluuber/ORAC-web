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

            require_once '../../mysql.connection.php';
            if (isset($_POST['submit']) && isset($_POST['section']) && dataSetExists($_POST['section'])) {

                // Start spitting out updates to browser...
                echo '<a href="index.shtml">Back</a><br>';
                echo '<h1>Saving ' . $_POST['section'] . ' intro text</h1>';

                $conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // 2. Prepare the UPDATE statement using question mark (?) placeholders
                $query = "UPDATE orac_intros SET section = ?, intro_text = ?, modified_date = ? WHERE rid = ?";
                $response = $conn->prepare($query);

                if ($response) {
                    $rid = $_POST['rid'];
                    $section  = $_POST['section'];
                    $intro_text = $_POST['intro_text'];
                    $current_date = date('Y-m-d H:i:s');

                    // 3. Bind parameters ('ssi' means string, string, integer)
                    $response->bind_param('sssi', $section, $intro_text, $current_date, $rid);

                    // 4. Execute the query
                    if ($response->execute()) {
                        echo $response->affected_rows . " record(s) updated successfully.<br>";
                    } else {
                        echo "Error executing query: " . $response->error;
                    }

                    // Close the statement
                    $response->close();
                    echo '<a href="index.shtml">Back</a><br>';
                } else {
                    echo "Error preparing statement: " . $conn->error;
                }

                // Close the connection
                $conn->close();

                // Write static file for ssi
                $handle = fopen("../../includes/" . $section . "-intro.txt", "w") or die("Cannot open file");
                fwrite($handle, "<!-- ssitem: " . $section ."-intro.html -->\n\n" . $intro_text . "\n\n<!-- /ssitem: " . $section ."-intro.html -->");
                fclose($handle);

            } else {
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