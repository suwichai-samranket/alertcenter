<?
session_start();
//header('Content-Type: application/json');
include('../../connect_db.php');
$db = new connect_db();
$db->connect_db();
include('../../function.php');

$id = $_GET['id'];
$rows = array();
$sql = "SELECT * FROM job where job_id='$id' ";
$resource = mysql_query($sql) or die(mysql_error());
$count_row = mysql_num_rows($resource);

if($count_row > 0) {
	while($result = mysql_fetch_array($resource)){
		
		if($result["period"]==1)
		{
			$period = "8:00-10:00";
		}
		elseif($result["period"]==2)
		{
			$period = "10:00-12:00";
		}
		elseif($result["period"]==3)
		{
			$period = "13:00-15:00";
		}
		elseif($result["period"]==4)
		{
			$period = "15:00-17:00";
		}
		else
		{
			$period = "";
		}
		
		if($result["status"]==0)
		{
			$status = "<span class=\"label label-success\">เสร็จแล้ว</span>";
		}
		elseif($result["status"]==1)
		{
			$status = "<span class=\"label label-default\">รอการยืนยัน</span>";
		}
		else
		{
			$status = "<span class=\"label label-warning\">รอดำเนินการ</span>";
		}

		$rows[]= ["act_name"=>getActivities($result['act_id']),
		"area"=>getArea(getField($result['field_id'],"size")),
		"lat"=>getField($result['field_id'],"latitude"),
		"long"=>getField($result['field_id'],"longitude"),
		"startdate"=>$result['startdate'],
		"period"=>$period,
		"enddate"=>$result['enddate'],
		"status"=>$status
		];
	}
	$data = json_encode($rows);
	$results = '{"results":'.$data.'}';

}else{
	$results = '{"results":"null"}';
}

echo $results;
?>
