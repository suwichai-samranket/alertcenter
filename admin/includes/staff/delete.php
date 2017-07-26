<?
session_start();
include('../../connect_db.php');
$db = new connect_db();
$db->connect_db();

$id = $_POST['id'];

for($i=0;$i<count($_POST["id"]);$i++)
{
	if($_POST["id"][$i] != "")
	{		
		$sqltmp = "delete from staff_admin where staff_admin_id= '".$_POST["id"][$i]."' "; 
		$resulttmp = mysql_query($sqltmp) or die(mysql_error());
		
		$sqltmp = "delete from relation_menu where staff_admin_id= '".$_POST["id"][$i]."' "; 
		$resulttmp = mysql_query($sqltmp) or die(mysql_error());
	}
}

echo "complete";
?>
