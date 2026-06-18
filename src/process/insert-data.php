<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title></title>
</head>

<body>
  <?php

  /**
   * Script to insert CSV data into DB.
   * 
   * To run use following URLS:
   * /src/process/insert-data.php?dataset=case
   * /src/process/insert-data.php?dataset=review
   */
  require_once '../../mysql.connection.php';

  if (isset($_GET['dataset']) && dataSetExists($_GET['dataset'])) {
    $data_set = mapDataSet($_GET['dataset']);
    $csv_file = mapCSVfile($_GET['dataset']);

    // Define a constant for the MySQL table to use in queries.
    define('MYSQL_TABLE', $data_set);
    echo '<a href="index.shtml">Back</a><br>';
    echo 'Processing ' . $_GET['dataset'] . ' data.<br>';

    // DROP EXISTING TABLE
    $conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    $query = "DROP TABLE IF EXISTS " . MYSQL_TABLE;
    $result = $conn->query($query);
    echo 'Successfully dropped ' . MYSQL_TABLE . ' table.<br>';
    mysqli_close($conn);

    // CREATE FRESH TABLE
    $conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    if (MYSQL_TABLE == 'orac_case_list') {
      /**
       * ORAC Case,BARC Case,IOC English name,Scientific name,Location,Date,Decision
       * 1,,Sooty Albatross,Phoebetria fusca,Moruya,2 Aug 1987,Not Confirmed (not assessed)
       * 2,,Pink Robin,Petroica rodinogaster,"Korrungulla Swamp, Primbee",4 Sep 1987,Accepted  
       */
      $query = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE . "` ("
        . "`rid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'The primary identifier for a record.',"
        . "`orac_case_number` varchar(32) DEFAULT '' COMMENT 'The case number assigned by NSW ORAC.',"
        . "`barc_case_number` varchar(32) DEFAULT '' COMMENT 'The case number assigned by BARC.',"
        . "`species_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'The species common name, always treated as non-markup plain text.',"
        . "`scientific_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'The species scientific name, always treated as non-markup plain text.',"
        . "`location` varchar(255) NOT NULL DEFAULT '' COMMENT 'The location where the sighting event happened.',"
        . "`sightingdate` datetime DEFAULT NULL,"
        . "`decision` varchar(255) NOT NULL DEFAULT '' COMMENT 'The outcome, if any, of the sighting review.',"
        . "PRIMARY KEY (`rid`)"
        . ") ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='The base table for records.' AUTO_INCREMENT=1000";
    } else if (MYSQL_TABLE == 'orac_review_list') {
      $query = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE . "` ("
        . "`rid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'The primary identifier for a record.',"
        . "`species_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'The species common name, always treated as non-markup plain text.',"
        . "`scientific_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'The species scientific name, always treated as non-markup plain text.',"
        . "`exemption` varchar(255) NOT NULL DEFAULT '' COMMENT 'Geographic exemptions for species.',"
        . "`releasedate` datetime DEFAULT NULL,"
        . "PRIMARY KEY (`rid`)"
        . ") ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='The base table for records.' AUTO_INCREMENT=1000";
    } else if (MYSQL_TABLE == 'barc_case_list') {
      /**
       * Case No.,IOC English name,Scientific name,Location,State/Territory,Sighting Date,Submission,Decision,Acceptance tally
       * 297,Abbott's Booby,Papasula abbotti,Echo Beach ,WA,12/16/1999,,Accepted,1
       */
      $query = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE . "` ("
        . "`rid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'The primary identifier for a record.',"
        . "`barc_case_number` varchar(32) DEFAULT '' COMMENT 'The case number assigned by BARC.',"
        . "`species_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'The species common name, always treated as non-markup plain text.',"
        . "`scientific_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'The species scientific name, always treated as non-markup plain text.',"
        . "`location` varchar(255) NOT NULL DEFAULT '' COMMENT 'The location where the sighting event happened.',"
        . "`state` varchar(32) DEFAULT '' COMMENT 'The state or territory the sighting was made in.',"
        . "`sightingdate` varchar(32) DEFAULT '' COMMENT 'The sighting date. Not really a date, just a free for all text splurge.',"
        . "`decision` varchar(255) NOT NULL DEFAULT '' COMMENT 'The outcome, if any, of the sighting review.',"
        . "PRIMARY KEY (`rid`)"
        . ") ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='The base table for records.' AUTO_INCREMENT=1000";
    }
    $result = $conn->query($query);
    echo 'Connected successfully to create ' . MYSQL_TABLE . ' table.<br>';
    mysqli_close($conn);

    // PREPARE TO INSERT NEW DATA
    // Name, Type, Record Date, Record Date, Record Date...
    $csv = array_map('str_getcsv', file('./src/' . $csv_file));

    echo 'Got the raw ' . $csv_file . ' file.<br>';
    $count = 1000;
    echo 'Preparing data...<br>';
    foreach ($csv as $row => $record) {
      if ($row > 1) {
        $conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }
        $query = "";
        if (MYSQL_TABLE == 'orac_case_list') {
          $query = "INSERT INTO " . MYSQL_TABLE . " "
            . "VALUES("
            . $count . ","
            . "'" . $record[0] . "',"
            . "'" . $record[1] . "',"
            . "'" . mysqli_real_escape_string($conn, $record[2]) . "',"
            . "'" . mysqli_real_escape_string($conn, $record[3]) . "',"
            . "'" . mysqli_real_escape_string($conn, $record[4]) . "',"
            . "'" . date("Y-m-d H:i:s", strtotime($record[5])) . "',"
            . "'" . $record[6] . "'"
            . ")";
        } else if (MYSQL_TABLE == 'orac_review_list') {
          $query = "INSERT INTO " . MYSQL_TABLE . " "
            . "VALUES("
            . $count . ","
            . "'" . mysqli_real_escape_string($conn, $record[0]) . "',"
            . "'" . mysqli_real_escape_string($conn, $record[1]) . "',"
            . "'" . mysqli_real_escape_string($conn, $record[2]) . "',"
            . "'" . date("Y-m-d H:i:s", strtotime($record[3])) . "'"
            . ")";
        } else if (MYSQL_TABLE == 'barc_case_list') {
          $query = "INSERT INTO " . MYSQL_TABLE . " "
            . "VALUES("
            . $count . ","
            . "'" . $record[0] . "',"
            . "'" . mysqli_real_escape_string($conn, $record[1]) . "',"
            . "'" . mysqli_real_escape_string($conn, $record[2]) . "',"
            . "'" . mysqli_real_escape_string($conn, $record[3]) . "',"
            . "'" . mysqli_real_escape_string($conn, $record[4]) . "',"
            . "'" . mysqli_real_escape_string($conn, $record[5]) . "',"
            . "'" . $record[7] . "'"
            . ")";
        }
        $result = $conn->query($query);
        mysqli_close($conn);
        if (MYSQL_TABLE == 'orac_case_list') {
          echo 'Added data: ' . $record[0] . '; ' . $record[2] . '<br>';
        } else if (MYSQL_TABLE == 'orac_review_list') {
          echo 'Added data: ' . $record[0] . '<br>';
        }

        $count++;
      }
    }

    echo 'Done updating ' . $data_set . ' data.<br>';
    echo '<a href="index.shtml">Back</a><br>';
  } else {
    echo ('Something went wrong@#$!<br>');
    echo '<a href="index.shtml">Back</a><br>';
  }

  /**
   * Check if the parameter dataset is legit.
   * 
   * @param string $data_set
   * @return boolean
   */
  function dataSetExists($data_set)
  {
    $exists = false;
    switch ($data_set) {
      case 'case':
        $exists = true;
        break;
      case 'review':
        $exists = true;
        break;
      case 'barc':
        $exists = true;
        break;
      default:
        break;
    }
    return $exists;
  }

  /**
   * Map data_set to table name.
   * 
   * @param string $data_set
   * @return string
   */
  function mapDataSet($data_set)
  {
    $table_name = '';
    switch ($data_set) {
      case 'case':
        $table_name = 'orac_case_list';
        break;
      case 'review':
        $table_name = 'orac_review_list';
        break;
      case 'barc':
        $table_name = 'barc_case_list';
        break;
      default:
        break;
    }
    return $table_name;
  }

  /**
   * Map csv_file to table name.
   * 
   * @param string $data_set
   * @return string
   */
  function mapCSVfile($data_set)
  {
    $csv_file_name = '';
    switch ($data_set) {
      case 'case':
        $csv_file_name = 'Index of Cases.csv';
        break;
      case 'review':
        $csv_file_name = 'Review List.csv';
        break;
      case 'barc':
        $csv_file_name = 'BARC_Index_of_Cases.csv';
        break;
      default:
        break;
    }
    return $csv_file_name;
  }
  ?>
</body>

</html>