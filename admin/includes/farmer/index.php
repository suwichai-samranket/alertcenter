<?php

$display_name = $_POST['display_name'];
$address = $_POST['address'];
$username = $_POST['username'];
$password = $_POST['password'];
$mobile = $_POST['mobile'];
$user_type = $_POST['user_type'];


// Update Profile
if($_POST['saveprofile']<>"" && $display_name<>"" )
{
	//Get Old Data
	$data = array();
	$sqlold = "select * from user where  id='$user_id' "; 
	$resultold = mysql_query($sqlold) or die (mysql_error());
	$rowsold = mysql_fetch_assoc($resultold);

	
  	$sqlupdate = "update user set display_name='$display_name', address='$address', mobile='$mobile' where id='$user_id'";
	$resultupdate = mysql_query($sqlupdate) or die(mysql_error());
	
	//change password
	if($password<>""){
   		$sqlupdate = "update user set password='".md5($password)."' where id='$user_id'";
    	$resultupdate = mysql_query($sqlupdate) or die(mysql_error()); 
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
 
<?php         
}
//end update profile

?>
  	<script>
		$(document).ready(function (){
		   var dataTable = $('#table1').DataTable({ 
			  "processing": true,
			  "serverSide": true,
			  "ajax": {
			  	url: "includes/<? echo $_GET['where']; ?>/grid-data.php", // json datasource
			  	type: "post", // method  , by default get
			  	error: function () { // error handling
			  		$( ".table1-error" ) . html( "" );
			  		$( "#table1" ) . append( '<tbody class="table1-error"><tr><th colspan="6">No data found in the server</th></tr></tbody>' );
			  		$( "#table1_processing" ) . css( "display", "none" );

			  	}
			  },
			  'columnDefs': [{
					 'targets': 0,
					 'searchable':false,
					 'orderable':false,
					 'className':'dt-body-center',
					 'render': function (data, type, row){
						 return '<input type="checkbox" name="id[]" value="'+ row[0] +'">';
					 }
			  	},
			 	{
					 'targets': 5,
					 'searchable':false,
					 'orderable':false,
					 'className':'dt-body-center',
					 'render': function (data, type, row){
						 
						 return '<button type="button" id="GetField" class="btn btn-info btn-xs" data-toggle="modal" data-target=".form-edit-modal" value="'+row[0]+'"><i class="fa fa-pencil"></i> Edit</button>';
//						 
//						 return '<a href="../admin/index.php?where=<? echo $_GET['where']; ?>&active=edit&id=' 
//						+ row[0] + '" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>';
					 }
				}
			  ],
			  'order': [1, 'asc']
		   });
			
			$( "#table1_filter" ) . css( "display", "none" ); // hiding global search box
			$( '.search-input-text' ) . on( 'keyup click', function () { // for text boxes
				var i = $( this ) . attr( 'data-column' ); // getting column index
				var v = $( this ) . val(); // getting search input value
				dataTable . columns( i ) . search( v ) . draw();
			} );
			$( '.search-input-select' ) . on( 'change', function () { // for select box
				var i = $( this ) . attr( 'data-column' );
				var v = $( this ) . val();
				dataTable . columns( i ) . search( v ) . draw();
			} );

		   // Handle click on "Select all" control
		   $('#select-all').on('click', function(){
			  // Check/uncheck all checkboxes in the table
			  var rows = dataTable.rows({ 'search': 'applied' }).nodes();
			  $('input[type="checkbox"]', rows).prop('checked', this.checked);
		   });

		   // Handle click on checkbox to set state of "Select all" control
		   $('#table1 tbody').on('change', 'input[type="checkbox"]', function(){
			  // If checkbox is not checked
			  if(!this.checked){
				 var el = $('#select-all').get(0);
				 // If "Select all" control is checked and has 'indeterminate' property
				 if(el && el.checked && ('indeterminate' in el)){
					// Set visual state of "Select all" control 
					// as 'indeterminate'
					el.indeterminate = true;
				 }
			  }
		   });

		   $('#delete').bootstrap_confirm_delete(
				{
					debug:              false,
					heading:            'Warning !!!',
					message:            'Are you sure you want to delete this item?',
					btn_ok_label:       'Yes',
					btn_cancel_label:   'Cancel',
					data_type:          'post',
					callback:           function ( event )
										{
											// grab original clicked delete button
											var button = event.data.originalObject;
											// execute delete operation
											button.closest( 'tr' ).remove();
										},
					delete_callback:    function() { 
						//add field
						$.ajax({
							url: "includes/<? echo $_GET['where']; ?>/delete.php",
							type: 'POST',
							data: $('#formField').serialize(),
							success: function(data){
								console.log(data);
							}
						});
						$('#table1').DataTable().ajax.reload();
					},
					cancel_callback:    function() { console.log( 'cancel button clicked' ); }
				}
			);
			
		//add field
		$('#AddField').on('submit', function(e){
            e.preventDefault();
            $.ajax({
                url: "includes/<? echo $_GET['where']; ?>/new.php",
                type: 'POST',
                data: $('#AddField').serialize(),
                success: function(data){
                    $('.bs-example-modal-sm').modal('hide');
					$('.bs-example-modal-sm').find('textarea,input').val('');
					$('#table1').DataTable().ajax.reload();
                }
            });
        });
			
		//edit field
		$('#EditField').on('submit', function(e){
            e.preventDefault();
            $.ajax({
                url: "includes/<? echo $_GET['where']; ?>/edit.php",
                type: 'POST',
                data: $('#EditField').serialize(),
                success: function(data){
                    $('.form-edit-modal').modal('hide');
					$('#table1').DataTable().ajax.reload();
                }
            });
        });
			
		//get field
		$('.form-edit-modal').on('show.bs.modal', function(e){
			id = e.relatedTarget.value;
            $.ajax({
                url: "includes/<? echo $_GET['where']; ?>/get.php",
                type: 'GET',
                data: {id:id},
//				dataType: "json",
                success: function(data){
					data = $.parseJSON(data);
					$("#field_id").val(data.results[0]['field_id']);
                    $("#rai").val(data.results[0]['rai']);
					$("#ngan").val(data.results[0]['ngan']);
					$("#wa").val(data.results[0]['wa']);
					$("#latitude").val(data.results[0]['latitude']);
					$("#longitude").val(data.results[0]['longitude']);
//					console.log(data.results[0]['rai']);
                }
            });
        });
			
	});
	</script>
<?
$sql = "select * from user where id='".$_SESSION['user_id']."'"; 
$result = mysql_query($sql) or die(mysql_error());
$rows=mysql_fetch_array($result);
$username = $rows['username'];
$display_name = $rows['display_name'];
$address = $rows['address'];
$mobile = $rows['mobile'];
$user_type = $rows['type'];
?>
   	<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	  <div class="x_title">
		<h2><? echo getMenuName($_GET['where']);?></h2>
		<div class="clearfix"></div>
	  </div>
	  <div class="x_content">
		<div class="" role="tabpanel" data-example-id="togglable-tabs">
		  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
			<li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">ข้อมูลพื้นฐาน</a>
			</li>
			<li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">ข้อมูลแปลง</a>
			</li>
		  </ul>
		  <div id="myTabContent" class="tab-content">
			<div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
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
					<label class="control-label col-md-3 col-sm-3 col-xs-12">ชื่อ - นามสกุล <span class="required">*</span></label>
					<div class="col-md-4 col-sm-6 col-xs-12"> 
						<input type="text" class="form-control" id="display_name" value="<? echo $display_name;?>" name="display_name" placeholder="ชื่อ - นามสกุล" required>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">ที่อยู่ <span class="required">*</span></label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<textarea class="form-control" rows="3" name="address" id="address" placeholder="ที่อยู่" required><? echo $address;?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">เบอร์โทร </label>
					<div class="col-md-4 col-sm-6 col-xs-12">
						<input type="text" class="form-control" id="mobile" name="mobile" placeholder="เบอร์โทร" value="<? echo $mobile;?>">
					</div>
				</div>
				  <div class="ln_solid"></div>
				  <div class="form-group">
					<div class="col-md-6 col-md-offset-3">
					  <button type="reset" class="btn btn-danger">Cancel</button>
					  <button id="saveprofile" name="saveprofile" type="submit" class="btn btn-success" value="1">Submit</button>
					</div>
				  </div>
				</form>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
			  <form id="formField" action="" method="POST">
				 <table id="table1" class="table table-striped" style="width: 100%">
				  <thead>
					<tr>
					  <th><input name="select_all" value="1" id="select-all" type="checkbox" /></th>
					  <th>ขนาดพื้นที่</th>
					  <th>ละติจูด</th>
					  <th>ลองจิจูด </th>
					  <th>วันที่เพิ่มข้อมูล</th>
					  <th>Edit</th>
					</tr>
				  </thead>
				  <thead>
						<tr>
							<th></th>
							<th></th>
							<th><input type="text" data-column="3"  class="form-control search-input-text" style="width:120px"></th>
							<th><input type="text" data-column="4"  class="form-control search-input-text" style="width:120px"></th>
							<th></th>
							<th></th>
						</tr>
					</thead>
				</table>
				<button type="button" id="add" class="btn btn-success" data-toggle="modal" data-target=".bs-example-modal-sm">New</button>
				<button type="submit" id="delete" class="btn btn-danger">Delete</button>
				</form>
                  <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                      <div class="modal-content">
						<form class="form-horizontal form-label-left" id="AddField">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel">เพิ่มข้อมูล</h4>
                        </div>
                        <div class="modal-body">
							<div class="x_panel">
							  <div class="x_content">
								<div class="col-md-12 center-margin">
									<div class="form-group">
									  <label>ไร่</label>
									  <input type="text" name="rai" class="form-control" placeholder="ไร่" required>
									</div>
									<div class="form-group">
									  <label>งาน</label>
									  <input type="text" name="ngan" class="form-control" placeholder="งาน">
									</div>
									<div class="form-group">
									  <label>วา</label>
									  <input type="text" name="wa" class="form-control" placeholder="วา">
									</div>
									<div class="form-group">
									  <label>ละติจูด</label>
									  <input type="text" name="latitude" class="form-control" placeholder="ละติจูด" required>
									</div>
									<div class="form-group">
									  <label>ลองจิจูด</label>
									  <input type="text" name="longitude" class="form-control" placeholder="ลองจิจูด" required>
									</div>
								</div>
							  </div>
							</div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  
				<!--==== Form Edit ====-->
                  <div class="modal fade form-edit-modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                      <div class="modal-content">
						<form class="form-horizontal form-label-left" id="EditField">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel">แก้ไขข้อมูล</h4>
                        </div>
                        <div class="modal-body">
							<div class="x_panel">
							  <div class="x_content">
								<div class="col-md-12 center-margin">
									<div class="form-group">
									  <label>ไร่</label>
									  <input type="text" name="rai" id="rai" class="form-control" placeholder="ไร่" required>
									</div>
									<div class="form-group">
									  <label>งาน</label>
									  <input type="text" name="ngan" id="ngan" class="form-control" placeholder="งาน">
									</div>
									<div class="form-group">
									  <label>วา</label>
									  <input type="text" name="wa" id="wa" class="form-control" placeholder="วา">
									</div>
									<div class="form-group">
									  <label>ละติจูด</label>
									  <input type="text" name="latitude" id="latitude" class="form-control" placeholder="ละติจูด" required>
									</div>
									<div class="form-group">
									  <label>ลองจิจูด</label>
									  <input type="text" name="longitude" id="longitude" class="form-control" placeholder="ลองจิจูด" required>
									</div>
								</div>
							  </div>
							</div>
                        </div>
                        <div class="modal-footer">
                          <input type="hidden" id="field_id" name="field_id">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                        </form>
                      </div>
                    </div>
                  </div>
                 <!--==== Form Edit ====-->
                 
			</div>
		  </div>
		</div>

	  </div>
	</div>
  </div>