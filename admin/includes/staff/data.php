<?php
session_start();
require("../../sql.php"); 

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name
	0 => 'staff_admin_id',
	1 => 'picture',
	2 => 'username',
	3 => 'firstname',
	4 => 'lastname',
	5 => 'email',
	6 => 'mobile',
);

// getting total number records without any search
$sql = "SELECT staff_admin_id, picture, username, firstname, lastname, email, mobile from staff_admin ";
//$sql.=" FROM job";
$query=mysqli_query($conn, $sql) or die(mysqli_error());
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT staff_admin_id, picture, username, firstname, lastname, email, mobile from staff_admin WHERE 1 = 1 ";
//$sql.=" FROM job WHERE 1 = 1";

// getting records as per search parameters
if( !empty($requestData['columns'][2]['search']['value']) ){   //name
	$sql.=" AND username LIKE '%".$requestData['columns'][2]['search']['value']."%' ";
}

if( !empty($requestData['columns'][3]['search']['value']) ){   //name
	$sql.=" AND firstname LIKE '%".$requestData['columns'][3]['search']['value']."%' ";
}

if( !empty($requestData['columns'][4]['search']['value']) ){   //name
	$sql.=" AND lastname LIKE '%".$requestData['columns'][4]['search']['value']."%' ";
}

if( !empty($requestData['columns'][5]['search']['value']) ){   //name
	$sql.=" AND email LIKE '%".$requestData['columns'][5]['search']['value']."%' ";
}
//
//if( !empty($requestData['columns'][2]['search']['value']) ){   //name
//	$sql.=" AND act_name LIKE '%".$requestData['columns'][2]['search']['value']."%' ";
//}
//
//if( !empty($requestData['columns'][3]['search']['value']) ){ //age
////	$rangeArray = explode("/",$requestData['columns'][3]['search']['value']);
////	$startdate = $rangeArray[2]."-".$rangeArray[1]."-".$rangeArray[0];
// 	$sql.=" AND startdate='".$requestData['columns'][3]['search']['value']."'";
//}
//
//if( !empty($requestData['columns'][4]['search']['value']) ){   //name
//	$sql.=" AND period LIKE '%".$requestData['columns'][4]['search']['value']."%' ";
//}

$query=mysqli_query($conn, $sql) or die(mysqli_error());
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
	
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";  // adding length
//echo $sql;
$query=mysqli_query($conn, $sql) or die(mysqli_error());


$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 
	
	$nestedData[] = $row["staff_admin_id"];
	if($row["picture"]<>"")
	{
		$nestedData[] = "<img src=\"images/staff/".$row['picture']."\" class=\"avatar\" alt=\"Avatar\">";
	}
	else
	{
		$nestedData[] = "<img src=\"images/user.png\" class=\"avatar\" alt=\"Avatar\">";
	}
	$nestedData[] = $row["username"];
	$nestedData[] = $row["firstname"];
	$nestedData[] = $row["lastname"];
	$nestedData[] = $row["email"];
	$nestedData[] = $row["mobile"];
	
	
	
//	$nestedData[] = $row["job_id"];
//	$nestedData[] = getTruckRelation($row["act_id"]);
//	$nestedData[] = getActivities($row["act_id"]);
//	$nestedData[] = $row["startdate"];
//	if($row["period"]==1)
//	{
//		$nestedData[] = "8:00-10:00";
//	}
//	elseif($row["period"]==2)
//	{
//		$nestedData[] = "10:00-12:00";
//	}
//	elseif($row["period"]==3)
//	{
//		$nestedData[] = "13:00-15:00";
//	}
//	elseif($row["period"]==4)
//	{
//		$nestedData[] = "15:00-17:00";
//	}
//	else
//	{
//		$nestedData[] = "";
//	}
//	$nestedData[] = floor((getField($row['field_id'],"size")/1600))."ไร่ ".floor((getField($row['field_id'],"size")%1600)/400)."งาน ".floor(((getField($row['field_id'],"size")%1600)%400)/4)."วา";
//	
//	$nestedData[] = getField($row['field_id'],"latitude");
//	$nestedData[] = getField($row['field_id'],"longitude");
//	if($row["status"]==0)
//	{
//		$nestedData[] = "<span class=\"label label-success\">เสร็จแล้ว</span>";
//	}
//	elseif($row["status"]==1)
//	{
//		$nestedData[] = "<span class=\"label label-default\">รอการยืนยัน</span>";
//	}
//	else
//	{
//		$nestedData[] = "<span class=\"label label-warning\">รอดำเนินการ</span>";
//	}
//	$nestedData[] = $row["startdate"];
//	
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
