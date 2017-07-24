<?
session_start();
//header('Content-Type: application/json');
include('../../connect_db.php');
$db = new connect_db();
$db->connect_db();

$id = $_GET['id'];
$rows = array();
$sql = "SELECT * FROM field where id='$id' ";
$resource = mysql_query($sql) or die(mysql_error());
$count_row = mysql_num_rows($resource);

if($count_row > 0) {
	while($result = mysql_fetch_array($resource)){
		$rows[]= ["rai"=>floor($result['size']/1600),
		"ngan"=>floor((($result['size'])%1600)/400),
		"wa"=>floor(((($result['size'])%1600)%400)/4),
		"latitude"=>$result['latitude'],
		"longitude"=>$result['longitude'],
		"field_id"=>$result['id']
		];
	}
	$data = json_encode($rows);
	$results = '{"results":'.$data.'}';

}else{
	$results = '{"results":"null"}';
}

echo $results;
?>
