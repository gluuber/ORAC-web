<?php

/**
 * Returns a json response.
 * Accepts a species name.  
 * ./get_species_trend_data.php?type=species&value=Little Shearwater
 * 
 * @param string: type
 * eg; "species"
 * 
 * @param string: value
 * eg; "Southern Fulmar"
 * 
 * @return string: json
 */

require_once '../mysql_connection.php';

$json_string = '{
  "cols": [ 
      {"type":"string", "label":"Date"},
      {"type":"number", "label":"Decision"},
      {"type": "string", "role": "style"},
      {"type": "string", "role": "tooltip", "p": {"html": true}},
      { "type": "string", "role": "annotation" } 
  ],
  "rows": [';
if (isset($_GET['type']) && isset($_GET['value'])) {

  $conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $query = buildQuery($_GET['type'], $_GET['value']);

  $result = $conn->query($query);

  $iteration = 0;

  //  ['2015-06-22', null, -0.5, null, "589: Cape Solander"]
  if ($result->num_rows > 0) {

    $json_string .= '{';
    // Fetch the rows and add them to raw_data array
    while ($row = $result->fetch_assoc()) {
      if ($iteration != 0) {
        $json_string .= '
            ]
          },
          {
          ';
      }
      
      $json_string .= '"c": [{"v":"' . date("d M Y", strtotime($row['Date'])) . '","f":null},';
      // rid  ORAC  BARC  IOC                 Scientific          Location        Date                  Decision      Reference
      // 695 	728 	NULL	Little Shearwater 	Puffinus assimilis 	Cape Solander 	2019-06-22 00:00:00 	NULL          NULL
      // 524 	557 	NULL	Little Shearwater 	Puffinus assimilis 	off Sydney      2012-07-14 00:00:00 	Accepted      NSWORAC no date e (for 2012)
      // 665 	699 	NULL	Little Shearwater 	Puffinus assimilis 	Ulladulla       2018-06-21 00:00:00 	Not accepted 	McGovern 2019
 
      if (isset($row['Decision'])) {
        if (strtolower($row['Decision']) == "accepted") {
          $json_string .= '{"v":1,"f": null}, {"v":"#1b9e77"}';
        }
        elseif (strtolower($row['Decision']) == "not accepted") {
          $json_string .= '{"v":-1,"f": null}, {"v":"#db4437"}';
        }
        else {
          $json_string .= '{"v":-0.5,"f": null}, {"v":"#FF9900"}';
        }
      }
      else {
        $json_string .= '{"v":-0.25, "f":null}, {"v":"#f4b400"}';
      }
      $json_string .= ', {"v": "' . $row['ORAC_Case'] . ': ' . $row['Location'] . '", "f": "<p>' . $row['ORAC_Case'] . ': ' . $row['Location'] . '<br>'. $row['Decision'] . '<br>' . $row['Reference'] . '</p>"}';
      $json_string .= ', {"v": "' . $row['ORAC_Case'] . '", "f": "' . $row['ORAC_Case'] . '"}';
      $iteration++;
    }
  }
}

$json_string .= "         ]
      }
   ]
}";

$conn->close();

echo $json_string;

/**
 * 
 * @param type $type -- junk
 * @param type $value
 * @return string: sql query string
 */
function buildQuery($type, $value) {
  $query = '';

  //$_species = mysqli_real_escape_string($value);
  
  if (stripos($value, "'") !== false) {
    $value = str_replace("'","''", $value);
  }

  $query = "SELECT * FROM mytable WHERE IOC_English_name = '" . $value . "' ORDER BY Date";

  return $query;
}

?>