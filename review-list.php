<!DOCTYPE html>
<html lang="en-AU">

<head>
  <title>Review List: NSW ORAC - NSW Ornithological Records Appraisal Committee</title>
  <!-- ssi: headtag.html -->

<meta charset="utf-8">
<meta name="description" content="NSW ORAC - NSW Ornithological Records Appraisal Committee">
<meta name="keywords" content="NSW ORAC - NSW Ornithological Records Appraisal Committee">
<meta name="author" content="NSW ORAC - NSW Ornithological Records Appraisal Committee">

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

<!-- Styles -->
<!-- Bootstrap CSS -->
<link href="/css/bootstrap.min.css" rel="stylesheet">
<!-- Font awesome CSS -->
<link href="/css/font-awesome.min.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="/css/style.min.css" rel="stylesheet">
<!-- Favicon -->
<link rel="shortcut icon" href="#">

<!-- /ssi: headtag.html -->
</head>

<body>
  <div class="wrapper">
<!-- ssitem: header.html -->

<header>
    <!-- navigation -->
    <nav class="navbar navbar-default">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" xdata-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/index.shtml"><img class="img-responsive" src="/img/logo-horiz.jpg" alt="NSW ORAC - NSW Ornithological Records Appraisal Committee"></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li id="home-button" class="active"><a href="/index.shtml">Home</a></li>
                    <li id="about-button" class="active"><a href="/about.shtml">About</a></li>
                    <li id="cases-button" class="active"><a href="/cases.shtml">Index of Cases</a></li>
                    <li id="review-list-button" class="active"><a href="/review-list.shtml">Review List</a></li>
                    <li id="submission-button" class="active"><a href="/submission.shtml">Make a Submission</a></li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</header>

<!-- /ssitem: header.html -->
    <!-- main content -->
    <div class="main-content bottom-0">
      <!-- benefits -->
      <div class="benefits pad">
        <div class="container">
          <div class="default-heading">
            <!-- heading -->
            <h1><i class="fa fa-briefcase"></i> Review List</h1>
          </div>
          <div class="row">
            <!-- icon -->
            <!-- heading -->
            <p>NSW ORAC maintains a Review List of species for which submissions are required. The threshold for
              inclusion on the Review List is any species which has been observed less than 1.5 times per year over
              seven years of the previous decade. The committee will review sightings in NSW, seabirds observed within
              the 200NM fishing zone, beachcast specimens, road kill specimens, collected specimens and birds of Lord
              Howe Island and that part of the ACT at Jervis Bay. The current edition of the NSW ORAC Review List is
              also included here as a downloadable <a href="/src/NSW_ORAC_Review_List.pdf">PDF document</a>.
            </p>
            <!-- Start Review List -->
            <p><span class="c0">The following list comprises birds that are considered rare in NSW based on reported
                observations over a preceding ten year period to a maximum average of 1.5 records per year. NSW ORAC
                encourages anyone sighting these species to take comprehensive notes, photographs where possible and to
                supply substantiation by other observers where appropriate. Details of the sighting should be forwarded
                to the Secretary of NSW ORAC at </span><span class="c6 c0"><a class="c5"
                  href="mailto:roglou@bigpond.net.au">roglou@bigpond.net.au</a></span><span class="c0">&nbsp;by way of
                an Unusual Record Report (URR) Form which can be downloaded from the NSWORAC website at </span><span
                class="c0 c6"><a class="c5"
                  href="https://www.google.com/url?q=http://www.nsworac.org&amp;sa=D&amp;source=editors&amp;ust=1690589783058605&amp;usg=AOvVaw3A-FMtbhWFdbavKv7Cfpxt">www.nsworac.org</a></span><span
                class="c3 c0">&nbsp;.</span></p>
            <p><span class="c3 c0">This list does not include any of those species on the Birdlife Australia Rarities
                Committee (BARC) Review List and the NSW ORAC Review List taxonomy is based on the IOC Checklist
                v14.1</span></p>
                
                <?php
        require_once './mysql.connection.php';

          // Create connection
          $conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
          // Check connection
          if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
          }

          $query = "SELECT * FROM orac_review_list";
          $result = $conn->query($query);
          $count = 1;
          $html_fragment = '<p><table style="margin:0 0 5px 15px!important;"><tr><th style="padding:0 0 5px 15px!important;"><span>IOC English name</span></th><th style="padding:0 0 5px 15px!important;"><span>Scientific name</span></th><th style="padding:0 0 5px 15px!important;"><span>Exemption</span></th></tr>';

          if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                if ($count == 1) {
                    $review_date = date_create($row["releasedate"]);
                    $html_fragment .= '<p class="c8"><span class="c0 c9">(Revised ' . date_format($review_date, 'j M Y') . ')</span></p>';
                }
                $html_fragment .= '<tr><td style="padding:0 0 5px 15px!important;">' . $row["species_name"]. '</td><td style="padding:0 0 5px 15px!important;"><i>' . $row["scientific_name"] . '</i></td>';
                if (isset($row["exemption"]) && $row["exemption"] != "") {
                    $html_fragment .= '<td style="padding:0 0 5px 15px!important;">(' . $row["exemption"] . ')</td></tr>';
                } else {
                    $html_fragment .= '<td style="padding:0 0 5px 15px!important;"></td></tr>';
                }
                $count++;
            }
          }
          $html_fragment .= '</table></p>';
          echo $html_fragment;

                ?>
          </div>
        </div>
      </div>
    </div>
  </div>
<!-- ssitem: footer.html -->
 
<footer>
    <div class="container">
        <p class="copy-right">Copyright &copy; 2026 NSW ORAC</p>
    </div>
</footer>

<!-- /ssitem: footer.html -->
<!-- ssitem: footer-scripts.html -->
 
<!-- Javascript files -->
<!-- jQuery -->
<script src="js/jquery.js"></script>
<!-- Bootstrap JS -->
<script src="js/bootstrap.min.js"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-170217165-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    gtag('js', new Date());

    gtag('config', 'UA-170217165-1');
</script>

<!-- /ssitem: footer-scripts.html -->
<script>
  document.getElementById("home-button").className = "";
  document.getElementById("about-button").className = "";
  document.getElementById("cases-button").className = "";
  document.getElementById("review-list-button").className = "active";
  document.getElementById("submission-button").className = "";
</script>
</body>

</html>