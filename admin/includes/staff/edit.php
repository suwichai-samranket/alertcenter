<?

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$mobile = $_POST['mobile'];

if($firstname<>"" && $lastname<>"")
{
	//Get Old Data
	$data = array();
	$sqlold = "select * from staff_admin where  staff_admin_id='".$_GET['id']."' "; 
	$resultold = mysql_query($sqlold) or die (mysql_error());
	$rowsold = mysql_fetch_assoc($resultold);

	
  	$sqlupdate = "update staff_admin set firstname='$firstname', lastname='$lastname', mobile='$mobile', email='$email' where staff_admin_id='".$_GET['id']."'";
	$resultupdate = mysql_query($sqlupdate) or die(mysql_error());
	
	//change password
	if($password<>""){
   		$sqlupdate = "update staff_admin set password='".md5($password)."' where staff_admin_id='".$_GET['id']."'";
    	$resultupdate = mysql_query($sqlupdate) or die(mysql_error()); 
 	}
	
	//update menu
	$sqldel = "delete from relation_menu where  staff_admin_id='".$_GET['id']."'";
	$resultdel = mysql_query($sqldel) or die(mysql_error()); 
	
    //insert menu
	for($i=0;$i<count($_POST['menu']);$i++)
	{
		$sqlinsert = "insert into relation_menu(staff_admin_id, menu_id) values('".$_GET['id']."', '".$_POST['menu'][$i]."')";
		$resultinsert = mysql_query($sqlinsert) or die($sqlinsert);
	}
	
	//upload images
	$handle = new upload($_FILES['image_field']);
	if ($handle->uploaded) {
	  $handle->file_new_name_body   = $rowsold['username'];
	  $handle->image_resize         = true;
	  $handle->image_x              = 200;
	  $handle->image_ratio_y        = true;
	  $handle->process('images/staff/');
	  if ($handle->processed) {
		$sqlupdate = "update staff_admin set picture='".$handle->file_dst_name."' where staff_admin_id='".$_GET['id']."'";
		$resultupdate = mysql_query($sqlupdate) or die(mysql_error());
		$handle->clean();
	  } else {
		echo 'error : ' . $handle->error;
	  }
	}
	
	//insert logs
	$detail = varDumpToString($rowsold);
	$detail .= varDumpToString($_POST);
	$action = 'update';
  
	$sqlinsertlog = "insert into tb_logs (staff_admin_id, modules, action, detail, insertdate) values('$staff_admin_id', '$where', '$action', '$detail', now())";
	$resultlog = mysql_query($sqlinsertlog) ;
?>
	  <script language="JavaScript" type="text/JavaScript">
		  $(document).ready(function() {
				new PNotify({
					title: 'Saved.',
					text: 'Save successfully',
					type: "success",
					icon: 'glyphicon glyphicon-ok',
					hide: false,
					styling: 'bootstrap3',
					confirm: {
						confirm: true,
						buttons: [{
							text: 'Ok',
							addClass: 'btn-success',
							click: function(notice) {
								 window.location.href="index.php?where=<? echo $_GET['where'];?>"
							}
						},
						null]
					},
					buttons: {
						closer: false,
						sticker: false
					},
					history: {
						history: false
					},
					addclass: 'stack-modal',
					stack: {'dir1': 'down', 'dir2': 'right', 'modal': true}
					})
		  });

	//				 window.location.href="index.php?where=<? echo $_GET['where'];?>"

	  </script>
 
<?          
}

$sql = "select * from staff_admin where staff_admin_id='".$_GET['id']."'"; 
$result = mysql_query($sql) or die(mysql_error());
$rows=mysql_fetch_array($result);
$username = $rows['username'];
$firstname = $rows['firstname'];
$lastname = $rows['lastname'];
$mobile = $rows['mobile'];
$email = $rows['email'];
$picture = $rows['picture'];
?>
	  <!-- Parsley -->
    <script>
 		$(document).ready(function() {
        $.listen('parsley:field:validate', function() {
          validateFront();
        });
        $('#form1 .btn').on('click', function() {
          $('#form1').parsley().validate();
          validateFront();
        });
        var validateFront = function() {
          if (true === $('#form1').parsley().isValid()) {
            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
          } else {
            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
          }
        };
      });
      try {
        hljs.initHighlightingOnLoad();
      } catch (err) {}	
		
	
    </script>
    <!-- /Parsley -->
	<? if($error){ ?>
	<script>
	  $(document).ready(function() {
		new PNotify({
		  title: "Can not create account !",
		  type: "error",
		  text: "Account are duplicated",
		  styling: 'bootstrap3',
		  hide: true
		});

	  });
	</script>
	<? } ?>
  	<div class="col-md-12 col-sm-12 col-xs-12">
	  <div class="x_panel">
		<div class="x_title">
		  <h2><? echo getMenuName($_GET['where']);?></h2>
		  <div class="clearfix"></div>
		</div>
		<div class="x_content">
		    <form class="form-horizontal form-label-left" enctype="multipart/form-data" id="form1" method="post" action="" data-parsley-validate novalidate>
		     <div class="form-group">
			  <label class="control-label col-md-3 col-sm-3 col-xs-12"> Username</label>
			  <div class="col-md-4 col-sm-6 col-xs-12">
			  	<input type="text" class="form-control" id="username" placeholder="Username" data-parsley-minlength="3" name="<? echo $username;?>" value="<? echo $username;?>" disabled>
			  </div>
			</div>
			<div class="form-group">
			  	<label class="control-label col-md-3 col-sm-3 col-xs-12">Password </label>
			  	<div class="col-md-4 col-sm-6 col-xs-12">
			  		<input type="password" class="form-control" id="password" name="password" placeholder="Password" data-parsley-minlength="4">
				</div>
			</div>
			<div class="form-group">
			  <label class="control-label col-md-3 col-sm-3 col-xs-12">Re-Password </label>
			  	<div class="col-md-4 col-sm-6 col-xs-12">
			  		<input type="password" class="form-control" id="re_password" data-parsley-equalto="#password"  name="re_password" placeholder="Password" data-parsley-minlength="4" >
				</div>
			</div>
			<div class="form-group">
			  	<label class="control-label col-md-3 col-sm-3 col-xs-12">Firstname <span class="required">*</span></label>
				<div class="col-md-4 col-sm-6 col-xs-12"> 
			   		<input type="text" class="form-control" id="firstname" name="firstname" placeholder="Firstname" value="<? echo $firstname;?>">
				</div>
			</div>
			<div class="form-group">
			  	<label class="control-label col-md-3 col-sm-3 col-xs-12">Lastname <span class="required">*</span></label>
			  	<div class="col-md-4 col-sm-6 col-xs-12">
			  		<input type="text" class="form-control" id="lastname" name="lastname" placeholder="Lastname" value="<? echo $lastname;?>"  required>
				</div>
			</div>
			<div class="form-group">
			  	<label class="control-label col-md-3 col-sm-3 col-xs-12">Mobile </label>
			  	<div class="col-md-4 col-sm-6 col-xs-12">
			  		<input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile" value="<? echo $mobile;?>">
				</div>
			</div>
		  	<div class="form-group">
			  	<label class="control-label col-md-3 col-sm-3 col-xs-12">E-Mail </label>
			  	<div class="col-md-4 col-sm-6 col-xs-12">
			  		<input type="text" class="form-control" id="email" name="email" data-parsley-type="email" placeholder="E-Mail" value="<? echo $email;?>">
				</div>
			</div>
			<div class="form-group">
               <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                <div class="col-md-4 col-sm-6 col-xs-12">
                <? if($rows['picture']<>""){?>
			      	<img class="img-responsive avatar-view" src="images/staff/<? echo $picture;?>" alt="Avatar" title="Avatar">
				<? }else{ ?>
					<img class="img-responsive avatar-view" src="images/user.png" alt="Avatar" title="Avatar">
				<? } ?>
               
				</div>
            </div>
			<div class="form-group">
			  	<label class="control-label col-md-3 col-sm-3 col-xs-12">Profile Picture</label>
			  	<div class="col-md-4 col-sm-6 col-xs-12">
			  		 <input type="file" size="32" name="image_field" value="">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-3 col-sm-3 col-xs-12 control-label">Menu Access
				</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
			  	  <? 
						$sql = "select * from menu_master";
						$result = mysql_query($sql) or die(mysql_error());
						while($rows = mysql_fetch_array($result))
						{
				  ?>
				  <div class="checkbox">
					<label>
					  <input type="checkbox" class="flat" id="menu" name="menu[]" <? if(chkPrioty($_GET['id'],$rows['menu_id'])=='true'){?> checked="checked" <? } ?> value="<? echo $rows['menu_id'];?>"> <? echo $rows['menu_name'];?>
					</label>
				  </div>
				  <?
						}
				  ?>
				</div>
			</div>
			  <div class="ln_solid"></div>
			  <div class="form-group">
				<div class="col-md-6 col-md-offset-3">
				  <button type="reset" class="btn btn-danger">Cancel</button>
				  <button id="send" type="submit" class="btn btn-success">Submit</button>
				</div>
			  </div>
			</form>
		</div>
	  </div>
	</div>
	