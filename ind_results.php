<!DOCTYPE html>
<html>
<head>
  <title>Grassroots Analytics</title>

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
  <div class = "text" id = 'results'>
  <?php
    if (isset($_GET["countyid"])) {
      $CountyID = $_GET["countyid"];
    } else {
      echo '<p> Value not set, please try again. </p>';
      exit;
    }

    $db = new mysqli('127.0.0.1', 
                     'lehman', 
                     'password', 
                     'tompkins_voters'
                    )or die('Failed to connect.');
  
    if (mysqli_connect_errno()) {
       echo '<p>Error: Could not connect to database. Please try again later!</p>';
       exit;
    }
    
    $query = "SELECT FirstName, LastName, Age, StreetNumber, StreetName, City, AreaCode, TelephoneNumber, Affiliation FROM `cornell`
              WHERE countyid = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $CountyID);
    $stmt->execute();
    $stmt->store_result();

    $stmt->bind_result($FirstName, $LastName, $Age, $StreetNumber, $StreetName, $City, $AreaCode, $TelephoneNumber, $Affiliation);

    while($stmt->fetch()) {
      echo '<p><strong>Name: '.$FirstName.' '.$LastName.'</strong>';
      echo '<br />CountyID: '.$CountyID;
      echo '<br />Address: '.$StreetNumber.' '.$StreetName.', '.$City;
      echo '<br />Party: '.$Affiliation; 
      echo '<br />Age: '.$Age;
      echo '<br />Phone Number: '.$AreaCode.'-'.$TelephoneNumber.'</p>';
    }
    $stmt->free_result();
    $db->close();
  ?>
  </div>
  </body>
</html>
  