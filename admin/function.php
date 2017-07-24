<?php

function chkPrioty($s,$v)
{
	$bchk="";
	$f_sql = "select * From relation_menu where menu_id='$v' and staff_admin_id='$s' ";
	$f_rs = mysql_query($f_sql) or die (mysql_error());
	while ($f_objdata = mysql_fetch_object($f_rs))
	{
		$bchk="true";
	}
	return $bchk;
}

function getStaff($v,$c)
{
	$value="";
	$f_sql = "select * from staff_admin where staff_admin_id='$v' "; 
	$f_rs = mysql_query($f_sql) or die (mysql_error());
	while ($f_objdata = mysql_fetch_object($f_rs))
   	{
		$value = $f_objdata->$c;
   	}
		return $value;
}

function getUser($v,$c)
{
	$value="";
	$f_sql = "select * from user where id='$v' "; 
	$f_rs = mysql_query($f_sql) or die (mysql_error());
	while ($f_objdata = mysql_fetch_object($f_rs))
   	{
		$value = $f_objdata->$c;
   	}
		return $value;
}

function getMenuName($v)
{
	$value="";
	$f_sql = "select * from menu_master where menu_link='$v' "; 
	$f_rs = mysql_query($f_sql) or die (mysql_error());
	while ($f_objdata = mysql_fetch_object($f_rs))
   	{
		$value = $f_objdata->menu_name;
   	}
		return $value;
}

function ChkDateDiff($e)
{
	$date_now = date("Y-m-d");
	$f_sql = "SELECT DATEDIFF('$e','$date_now') as datediff from project "; 
	$f_rs = mysql_query($f_sql) or die (mysql_error());
	while ($f_objdata = mysql_fetch_object($f_rs))
   	{
		$value = $f_objdata-> datediff;
   	}
	return $value;
}

function getUserType($v)
{
	$value="";
	$f_sql = "select * from user_type where id='$v' "; 
	$f_rs = mysql_query($f_sql) or die (mysql_error());
	while ($f_objdata = mysql_fetch_object($f_rs))
   	{
		$value = $f_objdata->name;
   	}
		return $value;
}

function varDumpToString($var)
{
    ob_start();
    var_dump($var);
    return ob_get_clean();
}

function getActivities($v)
{
	$f_sql = "select * from activities where act_id='$v' "; 
	$f_rs = mysql_query($f_sql) or die (mysql_error());
	while ($f_objdata = mysql_fetch_object($f_rs))
   	{
		$value = $f_objdata->act_name;
   	}
		return $value;
}

function getField($id,$v)
{
	$f_sql = "select * from field where id='$id' "; 
	$f_rs = mysql_query($f_sql) or die (mysql_error());
	while ($f_objdata = mysql_fetch_object($f_rs))
   	{
		$value = $f_objdata->$v;
   	}
		return $value;
}

function getTruckRelation($v)
{
	$f_sql = "select * from relation where act_id='$v' "; 
	$f_rs = mysql_query($f_sql) or die (mysql_error());
	while ($f_objdata = mysql_fetch_object($f_rs))
   	{
		$value = getTruck($f_objdata->truck_id,'truck_name') ;
   	}
		return $value;
}

function getTruck($id,$v)
{
	$f_sql = "select * from truck where truck_id='$id' "; 
$f_rs = mysql_query($f_sql) or die (mysql_error());
	while ($f_objdata = mysql_fetch_object($f_rs))
   	{
		$value = $f_objdata->$v;
   	}
		return $value;
}

function getFieldAll($truck_id,$start,$end,$v)
{
	$f_sql = "SELECT truck_id, SUM(size) as size_all, count(job.field_id) as count_all FROM job, relation, field WHERE job.act_id=relation.act_id and job.field_id=field.id and truck_id='$truck_id' and date(enddate) between '$start' and '$end' and status=2 GROUP BY truck_id "; 
//	echo $f_sql;
	$f_rs = mysql_query($f_sql) or die (mysql_error());
	while ($f_objdata = mysql_fetch_object($f_rs))
   	{
		$value = $f_objdata->$v;
   	}
		return $value;
}

function getArea($v)
{
	$value = floor($v/1600)."ไร่ ".floor(($v%1600)/400)."งาน ".floor((($v%1600)%400)/4)."วา";
	return $value;
}

?>