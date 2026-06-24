<!DOCTYPE html>
<html lang="en">

<head>
    <title>NSW ORAC: Process: Update intro text</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="NSW ORAC">
    <meta property="og:description" content="NSW ORAC - Ornithological Records Appraisal Committee.">
    <meta property="og:url" content="https://www.nsworac.org/">
    <meta property="og:type" content="website">
    <meta property="fb:app_id" content="966242223397117">
    <meta property="og:image" content="https://www.nsworac.org/favicon/og-image-1200.jpg" />
    <meta property="og:image:secure_url" content="https://www.nsworac.org/favicon/og-image-1200.jpg" />
    <meta property="og:image:type" content="image/jpg" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <!-- /og:rubbish -->
    <!-- Fave icon -->
    <link rel="icon" type="image/png" href="/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon/favicon.svg" />
    <link rel="shortcut icon" href="/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png" />
    <link rel="manifest" href="/favicon/site.webmanifest" />
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
        <div class="w3-row w3-row w3-orange w3-round" style="text-align:center;padding:32px;margin:32px">
            <?php
            $dataset = '';
            if (isset($_GET['dataset'])) {
                $title = '';
                switch ($_GET['dataset']) {
                    case 'about':
                        $dataset = 'about';
                        $title = 'About';
                        break;
                    case 'case':
                        $dataset = 'case';
                        $title = 'Index of Cases';
                        break;
                    case 'review':
                        $dataset = 'review';
                        $title = 'Review List';
                        break;
                    case 'submission':
                        $dataset = 'submission';
                        $title = 'Make a Submission';
                        break;
                    case 'barc':
                        $dataset = 'barc';
                        $title = 'Index of BARC Cases';
                        break;
                }
                echo '<h2 class="w3-text-white">Update ' . $title . ' intro text</h2>';
            }

            // Grab the data
            require_once '../../mysql.connection.php';
            // DROP EXISTING TABLE
            $conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $query = "DROP TABLE IF EXISTS orac_intros";
            $result = $conn->query($query);
            echo 'Successfully dropped orac_intros table.<br>';
            mysqli_close($conn);

            // Create connection
            $conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $query = "CREATE TABLE IF NOT EXISTS `orac_intros` ("
                . "`rid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'The primary identifier for a record.',"
                . "`section` varchar(32) DEFAULT '' COMMENT 'The section index name.',"
                . "`intro_text` TEXT NOT NULL COMMENT 'The intro text.',"
                . "`modified_date` datetime DEFAULT NULL,"
                . "PRIMARY KEY (`rid`)"
                . ") ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='The base table for records.' AUTO_INCREMENT=1000";
            $result = $conn->query($query);
            mysqli_close($conn);

            $conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $current_date = date('Y-m-d H:i:s');
            $query = "INSERT IGNORE INTO orac_intros (rid, section, intro_text, modified_date) VALUES (1,'about','<h1>About</h1>',' . $current_date . '), (2,'cases','<h1>Index of Cases</h1>',' . $current_date . '),(3,'review','<h1>Review List</h1>',' . $current_date . '),(4,'submission','<h1>Make a Submission</h1>',' . $current_date . '),(5,'barc','<h1>Index of BARC Cases</h1>',' . $current_date . ')";

            $conn->multi_query($query);
            mysqli_close($conn);

            $intro_fragment = '';
            $rid = 1;
            $conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $query = "SELECT * FROM orac_intros WHERE section='" . $dataset . "'";
            $result = $conn->query($query);

            // Body
            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    $intro_fragment = $row["intro_text"];
                    $rid = $row["rid"];
                }
            }
            mysqli_close($conn);
            ?>
            <form action="process-intro-text.php" method="POST" enctype="multipart/form-data">
                <textarea name="intro_text" id="intro_text" rows="20" cols="100" required><?php echo $intro_fragment; ?></textarea>
                <?php
                echo '<input type="hidden" name="rid" value="' . $rid . '">';
                echo '<input type="hidden" name="section" value="' . $dataset . '">';
                echo '<p><a href="index.shtml" class="w3-button w3-round w3-white">Cancel</a>&nbsp;&nbsp;<button type="submit" name="submit" class="w3-button w3-round w3-white">Save</button></p>';
                ?>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="w3-container w3-padding-16 w3-light-grey">
        <hr>
    </footer>

</body>

</html>