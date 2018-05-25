<?php session_start();

include 'includes/check_logged_in.php';

$my_query = $_SESSION['query'];
include("configs/config.php");
# TODO: change this so that it uses user credentials, not default
$db = new mysqli(
DB_HOST,
DB_USER,
DB_PASSWORD,
DB_NAME
)or die('Failed to connect.');

$query = "INSERT INTO lists (query_text) VALUES(?);";
$stmt = $db->prepare($query);
$stmt->bind_param('s', $my_query);
$stmt->execute();

$query = "SELECT MAX(List_ID) FROM lists LIMIT 1";
$stmt = $db->prepare($query);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($number);
$stmt->fetch();
$file_name = "list_number".$number.".csv";

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename= $file_name");

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('Voter ID', 'First', 'Last', 'Age','Gender', 'Party', 'Address', 'City'));

// fetch the data
$db = new mysqli(
DB_HOST,
DB_USER,
DB_PASSWORD,
DB_NAME)or die('Failed to connect.');

$stmt = $db->prepare($my_query);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($Voter_ID, $First_Name, $Last_Name, $Age, $Address, $City, $Party, $Gender);

// loop over the rows, outputting them
while ($stmt->fetch()) {
    fputcsv($output, array( $Voter_ID, $First_Name, $Last_Name, $Age, $Gender, $Party, $Address, $City ));
}
fclose($output);
?>
