<!DOCTYPE html>
<html lang="en">

<head>
    <title>NSW ORAC: Process: Upload CSV Data File</title>
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
        <div class="w3-row w3-row w3-orange w3-round" style="width:50%;padding:32px;margin:32px">
            <?php
            if (isset($_GET['dataset'])) {
                $title = '';
                switch ($_GET['dataset']) {
                    case 'case':
                        $title = 'Index of Cases CSV';
                        break;
                    case 'review':
                        $title = 'Review List CSV';
                        break;
                    case 'barc':
                        $title = 'Index of BARC Cases CSV';
                        break;
                }
            echo '<h2 class="w3-text-white">Upload ' . $title . '</h2>';
            }
            ?>
            <form action="process-csv-file.php" method="POST" enctype="multipart/form-data">
                <label for="fileUpload">Choose a text file:</label>
                <input type="file" name="uploaded_file" id="fileUpload" required><br>
                <?php
                if (isset($_GET['dataset'])) {
                    echo '<input type="hidden" name="dataset" value="' . $_GET['dataset'] . '">';
                }
                echo '<p><button type="submit" name="submit" class="w3-button w3-round w3-white">Upload and process ' . $title . '</button>&nbsp;&nbsp;<a href="index.shtml" class="w3-button w3-round w3-white">Cancel</a></p>';
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