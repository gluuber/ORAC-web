<!DOCTYPE html>
<html lang="en-AU">

<head>
  <title>Review List: NSW ORAC - NSW Ornithological Records Appraisal Committee</title>
<?php include './includes/headtag.html'; ?>
<link href="/css/datatables.min.css" rel="stylesheet">
<!-- Bootstrap CSS -->
<link href="/css/bootstrap.min.css" rel="stylesheet">
<!-- Font awesome CSS -->
<link href="/css/font-awesome.min.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="/css/style.min.css" rel="stylesheet">
<!-- Favicon -->
<link rel="shortcut icon" href="#">
<style>
div.dt-length { margin-left:60px!important; }
div.dt-search { margin-right:60px!important; }
label {margin-left: 5px!important;}
</style>
</head>

<body>
  <div class="wrapper">
<?php include './includes/header.html'; ?>
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
            <?php include './includes/review-intro.html'; ?>
                <?php
                
        require_once './mysql.connection.php';
          $conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
          if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
          }

          $query = "SELECT * FROM orac_review_list";
          $result = $conn->query($query);
          $count = 1;
          $html_fragment = '';

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($count == 1) {
                    $review_date = date_create($row["releasedate"]);
                    $html_fragment .= '<p class="c8"><span class="c0 c9">(Revised ' . date_format($review_date, 'j M Y') . ')</span></p>';
                }
                break;
            }
          }
          echo $html_fragment;

                ?>                
          <div class="row" style="width:90%!important"></div>
          <div class="row">
            <table id="example" class="display" style="width:90%!important">
              <thead>
                <tr>
                  <th>IOC English name</th>
                  <th>Scientific name</th>
                  <th>Exception</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>IOC English name</th>
                  <th>Scientific name</th>
                  <th>Exception</th>
                </tr>
              </tfoot>
            </table>
          </div>

          </div>
        </div>
      </div>
    </div>
  </div>
<?php include './includes/footer.html'; ?>
<?php include './includes/footer-scripts.html'; ?>
  <script src="/js/datatables.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#example').DataTable({
        responsive: true,
        "ajax": 'src/getReviewListData.php',
        "order": [[ 0, "asc" ]],
        "info": false,
        "pageLength": 30,
        columnDefs: [
          { type: 'natural', targets: 0 }
        ]
      });
    });

    /*
    $(document).ready(function() {
    $('#example').DataTable( {
    "paging": false,
    "ordering": false,
    "info": false
    } );
    } );*/
  </script>
<script>
  document.getElementById("home-button").className = "";
  document.getElementById("about-button").className = "";
  document.getElementById("cases-button").className = "";
  document.getElementById("review-list-button").className = "active";
  document.getElementById("submission-button").className = "";
</script>
</body>

</html>