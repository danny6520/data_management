<?php
include('election_db_connect/election_db_connect.php'); 
$query = "SELECT Party_Name from cbe_mp_consituency_result_2019";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}
mysqli_close($conn);
header("Content-Type: application/json");
echo json_encode($data);
?>
