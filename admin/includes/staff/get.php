<?
session_start();
//header('Content-Type: application/json');
include('../../connect_db.php');
$db = new connect_db();
$db->connect_db();
include('../../function.php');

$id = $_GET['id'];
//$rows = array();
$sql = "SELECT * FROM staff_admin where staff_admin_id='$id' ";
$resource = mysql_query($sql) or die(mysql_error());
$count_row = mysql_num_rows($resource);

if($count_row > 0) {
	while($result = mysql_fetch_array($resource)){
		
//		$rows[]=$result;

		$rows[]= ["firstname"=>$result['firstname'],
		  "lastname"=>$result['lastname'],
		  "username"=>$result['username'],
		  "email"=>$result['email'],
		  "mobile"=>$result['mobile'],
		  "picture"=>$result['picture']
		];
	}
	$data = json_encode($rows);
	$results = '{"results":'.$data.'}';

}else{
	$results = '{"results":"null"}';
}

echo $results;
?>
