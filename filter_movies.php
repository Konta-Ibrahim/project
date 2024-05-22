<?php
include 'config.php';
$letter = $_GET['letter'];

$filtered_products = mysqli_query($conn, "SELECT * FROM `products` WHERE `name` LIKE '$letter%'") or die('query failed');
$response = [];

if(mysqli_num_rows($filtered_products) > 0){
   while($row = mysqli_fetch_assoc($filtered_products)){
      $response[] = $row;
   }
}

echo json_encode($response);
?>
