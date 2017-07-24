<?
session_start();
include( '../../connect_db.php' );
$db = new connect_db();
$db->connect_db();

$user_id = $_SESSION[ 'user_id' ];
$act_id = $_POST[ 'act_id' ];
$field_id = $_POST[ 'field_id' ];
$startdate = $_POST[ 'jobstart' ];
$period = $_POST[ 'period' ];

if ( $user_id && $act_id && $field_id && $startdate && $period ) {
	$sql = "insert into job (user_id, act_id, field_id, startdate, period, status, insertdate) value ('$user_id', '$act_id', '$field_id', '$startdate', '$period', '1', now())";
	$resource = mysql_query( $sql )or die( mysql_error() );

	if ( $resource ) {
		$results = '{"results":"success"}';

	} else {
		$results = '{"results":"error"}';
	}
}
echo $results;
?>
