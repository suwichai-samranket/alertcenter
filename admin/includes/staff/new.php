<?
		session_start();
		include( '../../connect_db.php' );
		$db = new connect_db();
		$db->connect_db();
		include( '../../function.php' );
		include('../../../vendors/phpupload/src/class.upload.php');
	
//		echo var_dump($_POST);
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$email = $_POST['email'];
		$mobile = $_POST['mobile'];

		if($username<>"" && $password<>"" && $firstname<>"" && $lastname<>"")
		{
			$sqlcheck  = "select * from staff_admin where username='$username'";
			$resultcheck = mysql_query($sqlcheck) or die(mysql_error());
			$rowcheck = mysql_num_rows($resultcheck);
			if($rowcheck=='0')
			{
				//insert customer admin
				$sqlinsert = "insert into staff_admin (username, password, firstname, lastname, email, mobile, user_status, insertdate) values('$username', '".md5($password)."', '$firstname', '$lastname', '$email', '$mobile', 1, now())";
				$resultinsert = mysql_query($sqlinsert) or die(mysql_error());
				$staff_admin_idx = mysql_insert_id();

				for($i=0;$i<count($_POST['menu']);$i++)
				{
					$sqlinsert = "insert into relation_menu(staff_admin_id, menu_id) values('$staff_admin_idx', '".$_POST['menu'][$i]."')";
					$resultinsert = mysql_query($sqlinsert) or die($sqlinsert);
				}
				
				//upload images
				$handle = new upload($_FILES['image_field']);
				if ($handle->uploaded) {
				  $handle->file_new_name_body   = $username;
				  $handle->image_resize         = true;
				  $handle->image_x              = 200;
				  $handle->image_ratio_y        = true;
				  $handle->process('images/staff/');
				  if ($handle->processed) {

					$sqlupdate = "update staff_admin set picture='$handle->file_new_name_body' where staff_admin_id='$staff_admin_idx'";
					$resultupdate = mysql_query($sqlupdate) or die(mysql_error());
					  
					$handle->clean();
				  } else {
					echo 'error : ' . $handle->error;
				  }
				}
				
				//insert logs
				$detail = varDumpToString($_POST);
				$action = "new";

				$sqlinsertlog = "insert into tb_logs (staff_admin_id, modules, action, detail, insertdate) values('$staff_admin_id', '$where', '$action', '$detail', now())";
				$resultlog = mysql_query($sqlinsertlog) ;
				
				$results = '{"results":"success"}';		
			}
			else
			{
				$results = '{"results":"Duplicate"}';
			}
		}
		echo $results;
    ?>