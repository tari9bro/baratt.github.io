<?php

require_once 'init.php';

$con = new mysqli(servername,username,password,database) or die('Unable to Connect...');

$sql = "Select * from new_live_wallpaper;";

$result=mysqli_query($con,$sql);

$response = array();

while($row=mysqli_fetch_array($result))
{
	array_push($response,array("id"=>$row[0],"url"=>$row[1],"name"=>$row[2],"thumbnail"=>$row[3]));
}

echo json_encode($response);

mysqli_close($conn);

?>