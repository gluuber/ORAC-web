  <?php
  require_once '../mysql_connection.php';

  $json_string = '{';

  // Create connection
    $conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $query = "SELECT DISTINCT(IOC_English_name) AS species FROM mytable GROUP BY IOC_English_name";

  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
      //if ($row['type'] == 'bird') {
        $json_string .= json_encode($row['species']) . ':' . json_encode($row['species']) . ',';
      //}
    }
  }

  $conn->close();
  $json_string = trim($json_string, ",");
  $json_string .= "}";

  echo $json_string;
  ?>