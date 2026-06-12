<?php

/**
{
"data": [
  ["1","","Sooty Albatross","Phoebetria fusca","Moruya","2 Aug 1987","Not Confirmed (not assessed)"  ],
  ["2","","Pink Robin","Petroica rodinogaster","Korrungulla Swamp, Primbee","4 Sep 1987","Accepted"  ]
  ]}

  rid
  orac_case_number
  barc_case_number
  species_name
  scientific_name
  location
  sightingdate
  decision
*/

require_once '../mysql.connection.php';

$json_string = '{';

// Create connection
  $conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$json = '{"data": [';
$query = "SELECT * FROM orac_case_list";
$result = $conn->query($query);
if ($result->num_rows > 0) {
  // output data of each row
  while ($row = $result->fetch_assoc()) {
    $json .= '["'. $row["orac_case_number"] . '","'. $row["barc_case_number"] . '","'. $row["species_name"] . '","'. $row["scientific_name"] . '","'. $row["location"] . '","'. $row["sightingdate"] . '","'. $row["decision"] . '"  ],';
  }
}
$conn->close();
$json = trim($json, ",");
$json .= "]}";
echo $json;
?>

