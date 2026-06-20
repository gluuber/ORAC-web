<?php
// Load dompdf
require_once __DIR__ . '/dompdf/autoload.inc.php';

// Reference the Dompdf namespace
use Dompdf\Dompdf;

// Instantiate and use the dompdf class
$dompdf = new Dompdf();
$old_limit = ini_set("memory_limit", "512M");

// Grab the data
require_once './mysql.connection.php';

// Create connection
$conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$html_fragment = '<style>table {padding:0px!important;margin:0px!important;width: 100%!important;table-layout: auto!important;border-collapse: collapse;}h1 {font-size:14px;font-family:"Helvetica Neue", Arial, sans-serif;}table td {font-size:10px;font-family:"Helvetica Neue", Arial, sans-serif;margin:0px!important;border:1px solid #ccc;}</style>';
$query = "SELECT * FROM orac_case_list";
$result = $conn->query($query);
$count = 1;

// Header
$html_fragment .= '<h1>NSW ORAC - Index of Cases</h1><table cellspacing="0" cellpadding="2"><tr><td width="10%" align="center"><b>ORAC Case</b></td><td width="10%" align="center"><b>BARC Case</b></td><td><b>IOC English name</b></td><td><b>Scientific name</b></td><td><b>Location</b></td><td align="center"><b>Sighting date</b></td><td align="center"><b>Decision</b></td></tr>';

// Body
if ($result->num_rows > 0) {
  // output data of each row
  while ($row = $result->fetch_assoc()) {
    $date = date_create($row["sightingdate"]);
    $html_fragment .= '<tr><td align="center">' . $row["orac_case_number"] . '</td><td align="center">' . $row["barc_case_number"] . '</td><td>' . $row["species_name"] . '</td><td><i>' . $row["scientific_name"] . '</i></td><td>' . $row["location"] . '</td><td align="center">' . date_format($date, 'j M Y') . '</td><td align="center">' . $row["decision"] . '</td></tr>';
    $count++;
    //if ($count == 700) {
    //  break;
    //}
  }
}
$html_fragment .= '</table>';

$conn->close();
$dompdf->loadHtml($html_fragment);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('NSW ORAC - Index of Cases');
//echo $html_fragment;
?>