<?php
// Adjust the path to match where you uploaded dompdf
require_once __DIR__ . '/dompdf/autoload.inc.php';

// Reference the Dompdf namespace
use Dompdf\Dompdf;

// Instantiate and use the dompdf class
$dompdf = new Dompdf();
$old_limit = ini_set("memory_limit", "128M");

require_once './mysql.connection.php';

// Create connection
$conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$html_fragment = '<style>h1 {font-size:14px;}table td {font-size:10px;padding:4px;margin:0px!important;border:1px solid #ccc;}</style>';
$query = "SELECT * FROM orac_case_list";
$result = $conn->query($query);
$count = 1;
$html_fragment .= '<h1>Index of Cases</h1><table><tr><td width="10%"><b>ORAC Case</b></td><td width="10%"><b>BARC Case</b></td><td><b>IOC English name</b></td><td><b>Scientific name</b></td><td><b>Location</b></td><td><b>Sighting date</b></td><td><b>Decision</b></td></tr>';
if ($result->num_rows > 0) {
  // output data of each row
  while ($row = $result->fetch_assoc()) {
    $date = date_create($row["sightingdate"]);
    $html_fragment .= '<tr><td>' . $row["orac_case_number"] . '</td><td>' . $row["barc_case_number"] . '</td><td>' . $row["species_name"] . '</td><td><i>' . $row["scientific_name"] . '</i></td><td>' . $row["location"] . '</td><td>' . date_format($date, 'j M Y') . '</td><td>' . $row["decision"] . '</td></tr>';
    $count++;
    if ($count == 700) {
      break;
    }
  }
}
$html_fragment .= '</table>';

$conn->close();
$dompdf->loadHtml($html_fragment);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream('NSW ORAC - Index of Cases');
