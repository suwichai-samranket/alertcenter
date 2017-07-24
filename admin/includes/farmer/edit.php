<?
session_start();
include('../../connect_db.php');
$db = new connect_db();
$db->connect_db();

$field_id = $_POST['field_id'];
$rai = $_POST['rai'];
$ngan = $_POST['ngan'];
$wa = $_POST['wa'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$user_id = $_SESSION['user_id'];

$rai_wa = $rai*1600;
$ngan_wa = $ngan*400;

$wa_total = $rai_wa + $ngan_wa + $wa;

$sqltmp = "update field set size='$wa_total', latitude='$latitude', longitude='$longitude' where id='".$_POST["field_id"]."' "; 
$resulttmp = mysql_query($sqltmp) or die(mysql_error());

echo $sqltmp ;
?>
