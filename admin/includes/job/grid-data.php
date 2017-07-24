<?php
session_start();
require("../../sql.php"); 

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name
	0 => 'job_id',
	1 => 'truck_name',
	2 => 'act_name',
	3 => 'startdate',
	4 => 'period',
	5 => 'size',
	6 => 'status',
);

// getting total number records without any search
$sql = "SELECT job.user_id, job_id, truck_name, act_name, startdate, period, size, status FROM `job`, `activities`, `field`, `relation`, `truck` WHERE job.field_id=field.id AND activities.act_id=job.act_id AND activities.act_id=relation.act_id AND truck.truck_id=relation.truck_id and job.user_id='".$_SESSION['user_id']."' ";
//$sql.=" FROM job";
$query=mysqli_query($conn, $sql) or die(mysqli_error());
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT job.user_id, job_id, truck_name, act_name, startdate, period, size, status FROM `job`, `activities`, `field`, `relation`, `truck` WHERE job.field_id=field.id AND activities.act_id=job.act_id AND activities.act_id=relation.act_id AND truck.truck_id=relation.truck_id and job.user_id='".$_SESSION['user_id']."'";
//$sql.=" FROM job WHERE 1 = 1";

// getting records as per search parameters
if( !empty($requestData['columns'][1]['search']['value']) ){   //name
	$sql.=" AND truck_name LIKE '%".$requestData['columns'][1]['search']['value']."%' ";
}

if( !empty($requestData['columns'][2]['search']['value']) ){   //name
	$sql.=" AND act_name LIKE '%".$requestData['columns'][2]['search']['value']."%' ";
}

if( !empty($requestData['columns'][3]['search']['value']) ){ //age
//	$rangeArray = explode("/",$requestData['columns'][3]['search']['value']);
//	$startdate = $rangeArray[2]."-".$rangeArray[1]."-".$rangeArray[0];
 	$sql.=" AND startdate='".$requestData['columns'][3]['search']['value']."'";
}

if( !empty($requestData['columns'][4]['search']['value']) ){   //name
	$sql.=" AND period LIKE '%".$requestData['columns'][4]['search']['value']."%' ";
}

$query=mysqli_query($conn, $sql) or die(mysqli_error());
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
	
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";  // adding length
//echo $sql;
$query=mysqli_query($conn, $sql) or die(mysqli_error());


$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 
	
	$nestedData[] = $row["job_id"];
	$nestedData[] = $row["truck_name"];
	$nestedData[] = $row["act_name"];
	$nestedData[] = $row["startdate"];
	if($row["period"]==1)
	{
		$nestedData[] = "8:00-10:00";
	}
	elseif($row["period"]==2)
	{
		$nestedData[] = "10:00-12:00";
	}
	elseif($row["period"]==3)
	{
		$nestedData[] = "13:00-15:00";
	}
	elseif($row["period"]==4)
	{
		$nestedData[] = "15:00-17:00";
	}
	else
	{
		$nestedData[] = "";
	}
	
	$nestedData[] = floor($row["size"]/1600)."ไร่ ".floor(($row["size"]%1600)/400)."งาน ".floor((($row["size"]%1600)%400)/4)."วา";
	
	if($row["status"]==0)
	{
		$nestedData[] = "<span class=\"label label-success\">เสร็จแล้ว</span>";
	}
	elseif($row["status"]==1)
	{
		$nestedData[] = "<span class=\"label label-default\">รอการยืนยัน</span>";
	}
	else
	{
		$nestedData[] = "<span class=\"label label-warning\">รอดำเนินการ</span>";
	}
	
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
