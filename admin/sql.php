<?php

/* Database connection start */
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bpsmart_mfm_db";

$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());
mysqli_set_charset($conn, "utf8");

function getUserType($v)
{
	$value = "";
	global $conn;
	$f_sql = "select * from user_type where id='$v' "; 
	$f_rs = mysqli_query($conn, $f_sql) or die ('Error: ' . mysqli_error($conn));
	while ($f_objdata = mysqli_fetch_object($f_rs))
   	{
		$value = $f_objdata->name;
   	}
		return $value;
}

function getActivities($v)
{
	$value = "";
	global $conn;
	$f_sql = "select * from activities where act_id='$v' "; 
	$f_rs = mysqli_query($conn, $f_sql) or die ('Error: ' . mysqli_error($conn));
	while ($f_objdata = mysqli_fetch_object($f_rs))
   	{
		$value = $f_objdata->act_name;
   	}
		return $value;
}

function getField($id,$v)
{
	$value = "";
	global $conn;
	$f_sql = "select * from field where id='$id' "; 
	$f_rs = mysqli_query($conn, $f_sql) or die ('Error: ' . mysqli_error($conn));
	while ($f_objdata = mysqli_fetch_object($f_rs))
   	{
		$value = $f_objdata->$v;
   	}
		return $value;
}

function getTruckRelation($v)
{
	$value = "";
	global $conn;
	$f_sql = "select * from relation where act_id='$v' "; 
	$f_rs = mysqli_query($conn, $f_sql) or die ('Error: ' . mysqli_error($conn));
	while ($f_objdata = mysqli_fetch_object($f_rs))
   	{
		$value = getTruck($f_objdata->truck_id,'truck_name') ;
   	}
		return $value;
}

function getTruck($id,$v)
{
	$value = "";
	global $conn;
	$f_sql = "select * from truck where truck_id='$id' "; 
$f_rs = mysqli_query($conn, $f_sql) or die ('Error: ' . mysqli_error($conn));
	while ($f_objdata = mysqli_fetch_object($f_rs))
   	{
		$value = $f_objdata->$v;
   	}
		return $value;
}

?>