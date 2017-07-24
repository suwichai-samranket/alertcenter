<?php
session_start();
require("../../sql.php"); 

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name
	1 => 'id', 
	2 => 'size', 
	3 => 'latitude',
	4 => 'longitude',
	5 => 'insertdate'
);

// getting total number records without any search
$sql = "SELECT id, size, latitude, longitude, insertdate ";
$sql.=" FROM field WHERE user_id='".$_SESSION['user_id']."' ";
$query=mysqli_query($conn, $sql) or die(mysqli_error());
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT id, size, latitude, longitude, insertdate  ";
$sql.=" FROM field WHERE user_id='".$_SESSION['user_id']."' ";
// echo $sql;
// getting records as per search parameters
if( !empty($requestData['columns'][3]['search']['value']) ){   //name
	$sql.=" AND latitude LIKE '%".$requestData['columns'][3]['search']['value']."%' ";
}
if( !empty($requestData['columns'][4]['search']['value']) ){  //salary
	$sql.=" AND longitude LIKE '%".$requestData['columns'][4]['search']['value']."%' ";
}
if( !empty($requestData['columns'][5]['search']['value']) ){  //salary
	$sql.=" AND insertdate = '".$requestData['columns'][5]['search']['value']."' ";
}

// if( !empty($requestData['columns'][2]['search']['value']) ){ //age
// 	$rangeArray = explode("-",$requestData['columns'][2]['search']['value']);
// 	$minRange = $rangeArray[0];
// 	$maxRange = $rangeArray[1];
// 	$sql.=" AND ( employee_age >= '".$minRange."' AND  employee_age <= '".$maxRange."' ) ";
// }
$query=mysqli_query($conn, $sql) or die(mysqli_error());
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
	
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";  // adding length

$query=mysqli_query($conn, $sql) or die(mysqli_error());


$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 
	$nestedData[] = $row["id"];
	$nestedData[] = floor($row["size"]/1600)."ไร่ ".floor(($row["size"]%1600)/400)."งาน ".floor((($row["size"]%1600)%400)/4)."วา";
	$nestedData[] = $row["latitude"];
	$nestedData[] = $row["longitude"];
	$nestedData[] = $row["insertdate"];
	
	
	$data[] = $nestedData;
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>
