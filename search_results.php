<!DOCTYPE html>
<html>
<head>
  <title>Quick Look-Up Results</title>

  <link href="styles/style.css" type="text/css" rel="stylesheet">

  <!-- Monofonto font for header -->
  <link href = "https://www.fonts.com/font/typodermic/monofonto?QueryFontType=Web&src=GoogleWebFonts" type="text/css" rel = stylesheet>

  <!-- Source Sans Pro font for body -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">

  <div class="navigation">
    <center>
      <a href="index.html">Home</a> 
      <a href="search.html">Search</a> 
      <a href="make_list.html">Make a List</a>
      <a href="/settings">Settings</a>
      <a href="help">Help</a>
    </center>
  </div>

</head>
<body>
  <div class = "title">
  <h2>Search Results</h2>
  </div>
  <div class = "text" style = "overflow-x:auto;">
  <?php
    // code from chapter 11 of PHP and MySQL® Web Development, Fifth Edition
    // create short variable names
    $searchid = trim($_POST['searchid']);
    $searchfirst='%'.trim($_POST['searchfirst']).'%';
    $searchlast='%'.trim($_POST['searchlast']).'%';
    $searchcity='%'.trim($_POST['searchcity']).'%';
    $searchstreet='%'.trim($_POST['searchstreet']).'%';
    $searchnumber='%'.trim($_POST['searchnumber']).'%';

   if (!$searchlast && !$searchcity && !$searchstreet &&!$searchnumber && !$searchid &&!$searchfirst) {
       echo '<p>You have not entered search details. Please go back and try again.</p>';
       exit;
    }

/*    // whitelist the searchtype
    switch ($searchtype) {
      case 'LastName':
      case 'Address':
      case 'CountyID':
        break;
      default:
        echo '<p>That is not a valid search type. Please go back and try again.</p>';
        exit;
    }
*/    
    $db = new mysqli('127.0.0.1', 
                     'lehman', 
                     'password', 
                     'tompkins_voters'
                    )or die('Failed to connect.');
  
    if (mysqli_connect_errno()) {
       echo '<p>Error: Could not connect to database.<br/>
       Please try again later!</p>';
       exit;
    }

    if ($searchid) {
      $query = "SELECT CountyID, FirstName, LastName, Age, StreetNumber, StreetName, City
                FROM cornell WHERE COUNTYID = ? AND FirstName LIKE ? AND LastName LIKE ? AND City LIKE ? AND StreetName LIKE ? AND StreetNumber LIKE ?";
      $stmt = $db->prepare($query);
      $stmt->bind_param('isssss', $searchid, $searchfirst, $searchlast, $searchcity, $searchstreet, $searchnumber);
    }
    else { //countyid can't utilize '%' wildcard
      $query = "SELECT CountyID, FirstName, LastName, Age, StreetNumber, StreetName, City
                FROM cornell WHERE FirstName LIKE ? AND LastName LIKE ? AND City LIKE ? AND StreetName LIKE ? AND StreetNumber LIKE ?";
      $stmt = $db->prepare($query);
      $stmt->bind_param('sssss', $searchfirst, $searchlast, $searchcity, $searchstreet, $searchnumber);      
    }
    $stmt->execute();
    $stmt->store_result();

    $stmt->bind_result($CountyID, $FirstName, $LastName, $Age, $StreetNumber,$StreetName, $City);

    echo "<center><p>Number of records found: ".$stmt->num_rows.". Showing  ".min($stmt->num_rows,25).".<br /></p>";

    echo '<table>
            <thead id = "QLhead">
            <tr>
              <th>COUNTY ID</th>
              <th>NAME</th>
              <th>ADDRESS</th>
              <th>CITY</th>
              <th>AGE</th>
            </tr>
            </thead>
            <tbody id = "QLbody">';

    $row = 0;
    while($stmt->fetch() & $row < 25) {
      echo '
        <tr>
          <td>
            <a href = "ind_results.php?countyid='.$CountyID.'">'.$CountyID.'</a>
          </td>
          <td>'.$FirstName.' '.$LastName.'</td>
          <td>'.$StreetNumber.' '.$StreetName.'</td>
          <td>'.$City.'</td>
          <td>'.$Age.'</td>
        </tr>';
        $row = $row + 1;
    }
    echo '</tbody></center>';
    $stmt->free_result();
    $db->close();
  ?>
  </div>
  </body>
</html>
  