<script>
	function updateDataTableSelectAllCtrl(table){
	   var $table             = table.table().node();
	   var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
	   var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
	   var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

	   // If none of the checkboxes are checked
	   if($chkbox_checked.length === 0){
		  chkbox_select_all.checked = false;
		  if('indeterminate' in chkbox_select_all){
			 chkbox_select_all.indeterminate = false;
		  }

	   // If all of the checkboxes are checked
	   } else if ($chkbox_checked.length === $chkbox_all.length){
		  chkbox_select_all.checked = true;
		  if('indeterminate' in chkbox_select_all){
			 chkbox_select_all.indeterminate = false;
		  }

	   // If some of the checkboxes are checked
	   } else {
		  chkbox_select_all.checked = true;
		  if('indeterminate' in chkbox_select_all){
			 chkbox_select_all.indeterminate = true;
		  }
	   }
	}

	$( document ).ready( function () {
		var rows_selected = [];
		var dataTable = $( '#table1' ).DataTable( {
			"processing": true,
			"serverSide": true,
			"ajax": {
				url: "includes/<? echo $_GET['where']; ?>/data.php", // json datasource
				type: "post", // method  , by default get
				error: function () { // error handling
					$( ".table1-error" ) . html( "" );
					$( "#table1" ) . append( '<tbody class="table1-error"><tr><th colspan="8">No data found in the server</th></tr></tbody>' );
					$( "#table1_processing" ) . css( "display", "none" );
				}
			},
			'columnDefs': [{
					 'targets': 0,
					 'searchable':false,
					 'orderable':false,
					 'className':'dt-body-center',
					 'render': function (data, type, row){
						 return '<input type="checkbox" id="chkid" name="id[]" value="'+ row[0] +'">';
					 }
			  	},
				{
				'targets': 7,
				'searchable': false,
				'orderable': false,
				'className': 'dt-body-center',
				'render': function ( data, type, row ) {
					 return '<button type="button" id="View" class="btn btn-info btn-xs" data-toggle="modal" data-target=".edit-modal" value="'+row[0]+'"><i class="fa fa-pencil"></i> Edit</button>';
				}
			} ],
			'order': [ 3, 'desc' ],
			  'rowCallback': function(row, data, dataIndex){
				 // Get row ID
				 var rowId = data[0];

				 // If row ID is in the list of selected row IDs
				 if($.inArray(rowId, rows_selected) !== -1){
					$(row).find('input[type="checkbox"]').prop('checked', true);
					$(row).addClass('selected');
				 }
			  }
		} );
		
	 	$( "#table1_filter" ) . css( "display", "none" ); // hiding global search box

	 	// Handle click on checkbox
	 	$( '#table1 tbody' ) . on( 'click', 'input[type="checkbox"]', function ( e ) {
	 		var $row = $( this ) . closest( 'tr' );

	 		// Get row data
	 		var data = dataTable . row( $row ) . data();

	 		// Get row ID
	 		var rowId = data[ 0 ];

	 		// Determine whether row ID is in the list of selected row IDs
	 		var index = $ . inArray( rowId, rows_selected );

	 		// If checkbox is checked and row ID is not in list of selected row IDs
	 		if ( this . checked && index === -1 ) {
	 			rows_selected . push( rowId );

	 			// Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
	 		} else if ( !this . checked && index !== -1 ) {
	 			rows_selected . splice( index, 1 );
	 		}

	 		if ( this . checked ) {
	 			$row . addClass( 'selected' );
	 		} else {
	 			$row . removeClass( 'selected' );
	 		}

	 		// Update state of "Select all" control
	 		updateDataTableSelectAllCtrl( dataTable );

	 		// Prevent click event from propagating to parent
	 		e . stopPropagation();
	 	} );

	 	// Handle click on table cells with checkboxes
	 	$( '#table1' ) . on( 'click', '#chkid', function ( e ) {
	 		$( this ) . parent() . find( 'input[type="checkbox"]' ) . trigger( 'click' );
	 	} );
		 // Handle click on "Select all" control
		 $( 'thead input[name="select_all"]', dataTable . table() . container() ) . on( 'click', function ( e ) {
		 	if ( this . checked ) {
		 		$( '#table1 tbody input[type="checkbox"]:not(:checked)' ) . trigger( 'click' );
		 	} else {
		 		$( '#table1 tbody input[type="checkbox"]:checked' ) . trigger( 'click' );
		 	}

		 	// Prevent click event from propagating to parent
		 	e . stopPropagation();
		 } );

		 // Handle table draw event
		 dataTable . on( 'draw', function () {
		 	// Update state of "Select all" control
		 	updateDataTableSelectAllCtrl( dataTable );
		 } );

		
		// Handle click on checkbox to set state of "Select all" control
	   $('#table1 tbody').on('change', 'input[type="checkbox"], #chkid', function(){
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

		 $( '#delete' ) . bootstrap_confirm_delete( {
		 	debug: false,
		 	heading: 'Warning !!!',
		 	message: 'Are you sure you want to delete this item?',
		 	btn_ok_label: 'Yes',
		 	btn_cancel_label: 'Cancel',
		 	data_type: 'post',
		 	callback: function ( event ) {
		 		// grab original clicked delete button
		 		var button = event . data . originalObject;
		 		// execute delete operation
		 		button . closest( 'tr' ) . remove();
		 	},
		 	delete_callback: function () {
		 		//add field
		 		$ . ajax( {
		 			url: "includes/<? echo $_GET['where']; ?>/delete.php",
		 			type: 'POST',
		 			data: $( '#form1' ) . serialize(),
		 			success: function ( data ) {
		 				console . log( data );
		 			}
		 		} );
		 		$( '#table1' ) . DataTable() . ajax . reload();
		 	},
		 	cancel_callback: function () {
		 		console . log( 'cancel button clicked' );
		 	}
		 } );
		
		
		//validated
		$('#AddField').parsley().on('field:validated', function() {
			var ok = $('.parsley-error').length === 0;
		})
		
		//add field
		$('#AddField').on('submit', function(e){
            e.preventDefault();
			
			var formData = new FormData($(this)[0]);
			
            $.ajax({
                url: "includes/<? echo $_GET['where']; ?>/new.php",
                type: 'POST',
                data: formData,
				async: false,
				cache: false,
				contentType: false,
				processData: false,
                success: function(data){
					if(data.results=="Duplicate"){ //Duplicate Account
						var stack_modal = {"dir1": "down", "dir2": "right", "push": "top", "modal": false, "overlay_close": false};
						var notice = new PNotify({
						  title: "Can not create account !",
						  type: "error",
						  text: "Account are duplicated",
						  styling: 'bootstrap3',
						  hide: false,
						  icon: 'glyphicon glyphicon-remove',
						  buttons: {
								closer: true,
								sticker: false
						  },
						  addclass: 'stack-modal',
						  stack: stack_modal
						});
					}else{ //Add success
						var stack_modal = {"dir1": "down", "dir2": "right", "push": "top", "modal": false, "overlay_close": false};
						var notice = new PNotify({
							title: 'Complate.',
							text: 'Add user successfully',
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
										notice.remove();
										$('.add-modal').modal('hide');
										$('.add-modal').find('textarea,input,select').val('');
										$('#table1').DataTable().ajax.reload();
									}
								},
								null]
							},
							buttons: {
								closer: false,
								sticker: false
							},
							addclass: 'stack-modal',
							stack: stack_modal
						});
						
						
					}
                }
            });
        });
		
		//get field
		$('.edit-modal').on('show.bs.modal', function(e){
		
			id = e.relatedTarget.value;
			
            $.ajax({
                url: "includes/<? echo $_GET['where']; ?>/get.php",
                type: 'GET',
                data: {id:id},
                success: function(data){
					data = $.parseJSON(data);
					$("#username").val(data.results[0]['username']);
					$("#firstname").val(data.results[0]['firstname']);
					$("#lastname").val(data.results[0]['lastname']);
					$("#email").val(data.results[0]['email']);
					$("#mobile").val(data.results[0]['mobile']);
					if(data.results[0]['picture']==''){
						$('#imgstaff').attr('src','images/user.png') ;
					}else{
						$('#imgstaff').attr('src','images/staff/'+data.results[0]['picture']) ;
					}
					
					
					//load check
					var check_menu = data.results[0]['menu'];
					$('input#menu_edit').iCheck('uncheck');
					for(var i=0; i< check_menu.length; i++) {
						var menu_value = data.results[0]['menu'][i]['menu_id'];
						$('input#menu_edit').filter('[value="'+menu_value+'"]').iCheck('check');
					}
					
				}
            });
        });
		
	} );
</script>

<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
		<div class="x_title">
			<h2>
				<? echo getMenuName($_GET['where']);?>
			</h2>
			<div class="clearfix"></div>
		</div>
		<form id="form1" action="" method="POST">
			<div class="x_content">
				<table id="table1" class="table table-striped">
					<thead>
						<tr>
							<th><input name="select_all" value="1" id="select-all" type="checkbox"/></th>
							<th>Picture</th>
							<th>Username</th>
							<th>FirstName</th>
							<th>LastName</th>
							<th>Email</th>
							<th>Mobile</th>
							<th></th>
						</tr>
					</thead>
					<thead>
						<tr>
							<th></th>
							<th></th>
							<th><input data-column="2" type="text" style="width: 100px" class="form-control search-input-text" value=""></th>
							<th><input data-column="3" type="text" style="width: 100px" class="form-control search-input-text" value=""></th>
							<th><input data-column="4" type="text" style="width: 100px" class="form-control search-input-text" value=""></th>
							<th><input data-column="5" type="text" style="width: 100px" class="form-control search-input-text" value=""></th>
							<th></th>
							<th></th>
						</tr>
					</thead>
				</table>
				<button type="button" id="add" class="btn btn-success" data-toggle="modal" data-target=".add-modal">New</button>
				<button type="submit" id="delete" class="btn btn-danger">Delete</button>
			</div>
		</form>
	</div>
	
	<!--Add new-->
	<div class="modal fade add-modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal form-label-left" id="AddField" enctype="multipart/form-data">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
							  </button>
						<h4 class="modal-title" id="myModalLabel">New</h4>
					</div>
					<div class="modal-body">
						<div class="x_panel">
							<div class="x_content">
								<row>
									<div class="col-md-6 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12"> Username<span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" placeholder="Username" data-parsley-minlength="3" name="username" required>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12">Password <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="password" class="form-control" id="password" name="password" placeholder="Password" data-parsley-minlength="4" required>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12">Re-Password <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="password" class="form-control" id="re_password" data-parsley-equalto="#password" name="re_password" placeholder="Password" data-parsley-minlength="4" required>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12">Firstname <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" name="firstname" placeholder="Firstname" required>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12">Lastname <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" name="lastname" placeholder="Lastname" required>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12">Mobile </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" name="mobile" placeholder="Mobile">
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12">E-Mail </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" name="email" data-parsley-type="email" placeholder="E-Mail">
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12">Profile Picture</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="file" size="32" name="image_field" value="">
											</div>
										</div>
										
									</div>
									
									<div class="col-md-4 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="col-md-6 col-sm-6 col-xs-12 control-label">Menu Access
														</label>
											<div class="col-md-12 col-sm-12 col-xs-12">
												<? 
																$sql = "select * from menu_master";
																$result = mysql_query($sql) or die(mysql_error());
																while($rows = mysql_fetch_array($result))
																{
														  ?>
												<div class="checkbox">
													<label>
															  <input type="checkbox" class="flat" id="menu" name="menu[]" checked="checked" value="<? echo $rows['menu_id'];?>"> <? echo $rows['menu_name'];?>
															</label>

												</div>
												<?
													}
												?>
											</div>
										</div>
									</div>
								</row>
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
	<!--Add new-->
	
	<!--Edit-->
	<div class="modal fade edit-modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal form-label-left" id="EditField" enctype="multipart/form-data">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
							  </button>
						<h4 class="modal-title" id="myModalLabel">Edit</h4>
					</div>
					<div class="modal-body">
						<div class="x_panel">
							<div class="x_content">
								<row>
									<div class="col-md-6 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12"> Username<span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" id="username" placeholder="Username" data-parsley-minlength="3" name="username" required>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12">Password <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="password" class="form-control" id="password" name="password" placeholder="Password" data-parsley-minlength="4" required>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12">Re-Password <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="password" class="form-control" id="re_password" data-parsley-equalto="#password" name="re_password" placeholder="Password" data-parsley-minlength="4" required>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12">Firstname <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" id="firstname" name="firstname" placeholder="Firstname" required>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12">Lastname <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" id="lastname" name="lastname" placeholder="Lastname" required>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12">Mobile </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile">
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12">E-Mail </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" id="email" name="email" data-parsley-type="email" placeholder="E-Mail">
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12">Profile Picture</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="file" size="32" name="image_field" value="">
											</div>
										</div>
										
									</div>
									
									<div class="col-md-4 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="col-md-6 col-sm-6 col-xs-12 control-label">Menu Access</label>
											<div class="col-md-12 col-sm-12 col-xs-12">
												 <? 
														$sql = "select * from menu_master";
														$result = mysql_query($sql) or die(mysql_error());
														while($rows = mysql_fetch_array($result))
														{
												  ?>
												  <div class="checkbox">
													<label>
													  <input type="checkbox" id="menu_edit" class="flat" name="menu[]" value="<? echo $rows['menu_id'];?>"> <? echo $rows['menu_name'];?>
													</label>
												  </div>
												  <?
														}
												  ?>
											</div>
										</div>
										<div>
											<label class="col-md-6 col-sm-6 col-xs-12 control-label">Picture</label>
												<img class="img-responsive avatar-view" src="images/user.png" alt="Avatar" title="Avatar" id="imgstaff">
										</div>
									</div>
								</row>
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
	<!--Edit-->
	
</div>