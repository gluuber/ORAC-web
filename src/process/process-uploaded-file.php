<!DOCTYPE html>
<html lang="en">

<head>
    <title>NSW ORAC: Process: Upload CSV Data File</title>
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
// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Verify if the file array exists and there are no upload errors
    if (isset($_FILES['uploaded_file']) && $_FILES['uploaded_file']['error'] === UPLOAD_ERR_OK) {
        
        // Extract file details from the $_FILES superglobal
        $fileTmpPath = $_FILES['uploaded_file']['tmp_name'];
        $fileName    = $_FILES['uploaded_file']['name'];
        $fileSize    = $_FILES['uploaded_file']['size'];
        
        // 2. Security Check: Validate file extension
        $allowedExtensions = ['doc', 'pdf'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        if (!in_array($fileExtension, $allowedExtensions)) {
            die("Error: Only " . implode(', ', $allowedExtensions) . " files are allowed.");
        }

        // 3. Security Check: Enforce maximum file size limit (e.g., 2MB)
        $maxFileSize = 2 * 1024 * 1024; // 2 Megabytes in bytes
        if ($fileSize > $maxFileSize) {
            die("Error: File size exceeds the 2MB limit.");
        }

        // 4. Set up target directory and ensure it exists
        $uploadFolder = '../';
        if (!is_dir($uploadFolder)) {
            mkdir($uploadFolder, 0755, true);
        }

        // Sanitizing the filename prevents directory traversal exploits
        $safeFileName = time() . '_' . basename($fileName);

        switch ($_POST['dataset']) {
            case 'case':
                $safeFileName = 'NSW_ORAC_Index_of_Cases.pdf';
                break;
            case 'submission':
                $safeFileName = 'NSW_ORAC_URR_Form.doc';
                break;
            case 'guidelines':
                $safeFileName = 'NSW_ORAC_guidelines_for_submissions.pdf';
                break;
            case 'rules':
                $safeFileName = 'NSW_ORAC_rules.pdf';
                break;
        }
        $destination  = $uploadFolder . $safeFileName;

        // 5. Move the temporary file to its permanent home
        if (move_uploaded_file($fileTmpPath, $destination)) {
            echo '<p><a href="index.shtml">Back</a></p>';
            echo '<h1>Uploaded</h1>';
            echo "<p>Success: File uploaded successfully as " . htmlspecialchars($safeFileName) . "</p>";
        } else {
            echo "Error: Failed to move the uploaded file.";
        }

    } else {
        // Handle common PHP file upload errors
        $errorCode = $_FILES['uploaded_file']['error'] ?? UPLOAD_ERR_NO_FILE;
        echo "Error code: " . $errorCode . ". Please refer to PHP upload error constants.";
    }
} else {
    echo "Error: Invalid request method.";
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