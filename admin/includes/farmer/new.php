<?
session_start();
include('../../connect_db.php');
$db = new connect_db();
$db->connect_db();

$rai = $_POST['rai'];
$ngan = $_POST['ngan'];
$wa = $_POST['wa'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$user_id = $_SESSION['user_id'];

$rai_wa = $rai*1600;
$ngan_wa = $ngan*400;

$wa_total = $rai_wa + $ngan_wa + $wa;

if($user_id && $latitude && $longitude)
{
	$sql = "insert into field (user_id, size, latitude, longitude, insertdate) value ('$user_id', '$wa_total', '$latitude', '$longitude', now())";
	$resource = mysql_query($sql) or die(mysql_error());
	if($resource) {
		$results = '{"results":"success"}';

	}else{
		$results = '{"results":"error"}';
	}
}
echo $results;
?>
