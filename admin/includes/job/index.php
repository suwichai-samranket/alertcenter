<script>
	$( document ).ready( function () {
		var dataTable = $( '#table1' ) . DataTable( {
			"processing": true,
			"serverSide": true,
			"ajax": {
				url: "includes/<? echo $_GET['where']; ?>/grid-data.php", // json datasource
				type: "post", // method  , by default get
				error: function () { // error handling
					$( ".table1-error" ) . html( "" );
					$( "#table1" ) . append( '<tbody class="table1-error"><tr><th colspan="10">No data found in the server</th></tr></tbody>' );
					$( "#table1_processing" ) . css( "display", "none" );
				}
			},
			'columnDefs': [ {
				'targets': 0,
				'searchable': false,
				'orderable': false,
				'className': 'dt-body-center',
				'render': function ( data, type, row ) {
					if(row[6]!='<span class=\"label label-warning\">รอดำเนินการ</span>')
						{
							return '<input type="checkbox" name="id[]" value="' + row[ 0 ] + '">';
						}
					else{
						return '';
					}
					
				}
			},{
				'targets': 7,
				'searchable': false,
				'orderable': false,
				'className': 'dt-body-center',
				'render': function ( data, type, row ) {
					 return '<button type="button" id="View" class="btn btn-info btn-xs" data-toggle="modal" data-target=".view-modal" value="'+row[0]+'"><i class="fa fa-search"></i> ดูข้อมูล</button>';
				}
			} ],
			'order': [ 3, 'desc' ]
		} );
		$( "#table1_filter" ).css( "display", "none" ); // hiding global search box
		$( '.search-input-text' ).on( 'change', function () { // for text boxes
			var i = $( this ).attr( 'data-column' ); // getting column index
			var v = $( this ).val(); // getting search input value
			dataTable.columns( i ).search( v ).draw();
		} );
		$( '.search-input-select' ).on( 'change', function () { // for select box
			var i = $( this ).attr( 'data-column' );
			var v = $( this ).val();
			dataTable.columns( i ).search( v ).draw();
		} );
		// Handle click on "Select all" control
		$( '#select-all' ).on( 'click', function () {
			// Check/uncheck all checkboxes in the table
			var rows = dataTable.rows( {
				'search': 'applied'
			} ).nodes();
			$( 'input[type="checkbox"]', rows ).prop( 'checked', this.checked );
		} );
		
		
		$( ".select2_single" ).select2( {
			placeholder: "เลือกประเภท",
			allowClear: true
		} );
		
		$( '#statdate' ).datepicker( {
			autoUpdateInput: false,
			dateFormat: 'yy-mm-dd',
			locale: {
				cancelLabel: 'Clear'
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
		
		//add field
		$('#AddField').on('submit', function(e){
            e.preventDefault();
            $.ajax({
                url: "includes/<? echo $_GET['where']; ?>/new.php",
                type: 'POST',
                data: $('#AddField').serialize(),
                success: function(data){
                    $('.add-modal').modal('hide');
					$('.add-modal').find('textarea,input,select').val('');
					$('#table1').DataTable().ajax.reload();
                }
            });
        });
		
		//get field
		$('.view-modal').on('show.bs.modal', function(e){
		
			id = e.relatedTarget.value;
			
            $.ajax({
                url: "includes/<? echo $_GET['where']; ?>/get.php",
                type: 'GET',
                data: {id:id},
                success: function(data){
					data = $.parseJSON(data);
					$("#area").text(data.results[0]['area']);
					$("#act_name").text(data.results[0]['act_name']);
					$("#startdate").text(data.results[0]['startdate']);
					$("#enddate").text(data.results[0]['enddate']);
					$("#status").html(data.results[0]['status']);
					$("#jobperiod").text(data.results[0]['period']);
//					console.log(data.results[0]['period']);
					initMap();
					setInterval(function () {
						google.maps.event.trigger(map2, 'resize');
//						map.fitBounds();
					}, 2000);
					function initMap() {
						var uluru = {
							lat: parseInt(data.results[0]['lat']),
							lng : parseInt(data.results[0]['long']) 
						};
						var map = new google.maps.Map(document.getElementById( 'map2' ), {
							zoom: 15,
							center: uluru
						} );
						var marker = new google.maps.Marker( {
							position: uluru,
							map: map
						} );
					}
				}
            });
        });
		
		
		//load google map
		$( '#field_id' ) . on( 'change', function ( e ) { // for select box
			e . preventDefault();
			var element = $( "option:selected", this );
			var latLng = element . attr( "data-latLng" );
			initMap( latLng );

			function initMap( latLng ) {
				latLng = latLng . split( "," );
				var uluru = {
					lat: parseFloat( latLng[ 0 ] ),
					lng: parseFloat( latLng[ 1 ] )
				};
				var map = new google . maps . Map( document . getElementById( 'map' ), {
					zoom: 15,
					center: uluru
				} );
				var marker = new google . maps . Marker( {
					position: uluru,
					map: map
				} );
			}

		} );
		
		//job start
		$( "#jobstart" ).datepicker({
			 dateFormat: 'yy-mm-dd'
		});
		
	} );
</script>

<script>
	function initMap() {
		var uluru = {
			lat: 14.024648,
			lng : 99.971880 
		};
		var map = new google.maps.Map( document.getElementById( 'map' ), {
			zoom: 15,
			center: uluru
		} );
		var marker = new google.maps.Marker( {
			position: uluru,
			map: map
		} );
	}
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC7-mm1sY7ZugoavgfNggnhGTSxpPkXIEU">
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
							<th><input name="select_all" value="1" id="select-all" type="checkbox"/>
							</th>
							<th>เครื่องมือ</th>
							<th>กิจกรรม</th>
							<th>วันที่</th>
							<th>ช่วงเวลา</th>
							<th>ขนาดพื้นที่</th>
							<th>สถานะ</th>
							<th>Edit</th>
						</tr>
					</thead>
					<thead>
						<tr>
							<th></th>
							<th>
								<select data-column="1" class="form-control select2_single search-input-select" style="width: 150px">
									<option value="">เลือกเครื่องมือ</option>
									<? 
                                    $sql = "select * from truck";
                                    $result = mysql_query($sql) or die(mysql_error());
                                    while($rows = mysql_fetch_array($result))
                                    {
                                    ?>
									<option value="<? echo $rows['truck_name'];?>">
										<? echo $rows['truck_name'];?>
									</option>
									<?
                                    }
                                    ?>
								</select>
							</th>
							<th>
								<select data-column="2" class="form-control select2_single search-input-select" style="width: 100px">
									<option value="">เลือกกิจกรรม</option>
									<? 
                                    $sql = "select * from activities";
                                    $result = mysql_query($sql) or die(mysql_error());
                                    while($rows = mysql_fetch_array($result))
                                    {
                                    ?>
									<option value="<? echo $rows['act_name'];?>">
										<? echo $rows['act_name'];?>
									</option>
									<?
                                    }
                                    ?>
								</select>
							</th>
							<th>
								<input data-column="3" type="text" style="width: 120px" name="statdate" id="statdate" class="form-control search-input-text" value="">
							</th>
							<th>
								<select data-column="4" class="form-control select2_single search-input-select" style="width: 100px">
									<option value="">เลือกช่วงเวลา</option>
									<option value="1">8:00-10:00</option>
									<option value="2">10:00-12:00</option>
									<option value="3">13:00-15:00</option>
									<option value="4">15:00-17:00</option>
								</select>
							</th>
							<th></th>
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
				<form class="form-horizontal form-label-left" id="AddField">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
							  </button>
						<h4 class="modal-title" id="myModalLabel">เพิ่มข้อมูล</h4>
					</div>
					<div class="modal-body">
						<div class="x_panel">
							<div class="x_content">
								<row>
									<div class="col-md-4">
										<div class="form-group">
											<label>แปลง</label>
											<select class="form-control" id="field_id" name="field_id" required>
												<option value="">เลือกแปลง</option>
												<? 
												$sql = "select * from field where user_id='".$_SESSION['user_id']."' ";
												$result = mysql_query($sql) or die(mysql_error());
												while($rows = mysql_fetch_array($result))
												{
												?>
												<option value="<? echo $rows['id'];?>" data-latLng="<? echo $rows['latitude'];?>,<? echo $rows['longitude'];?>">
													<? echo getArea($rows['size']);?>
												</option>
												<?
												}
												?>
											</select>
										</div>
										<div class="form-group">
											<label>กิจกรรม</label>
											<select class="form-control" id="act_id" name="act_id" required>
												<option value="">เลือกกิจกรรม</option>
												<? 
												$sql = "select * from activities";
												$result = mysql_query($sql) or die(mysql_error());
												while($rows = mysql_fetch_array($result))
												{
												?>
												<option value="<? echo $rows['act_id'];?>">
													<? echo $rows['act_name'];?>
												</option>
												<?
												}
												?>
											</select>
										</div>
										<div class="form-group">
											<label>วันที่</label>
											<input id="jobstart" name="jobstart" class="date-picker form-control" type="text" required>
										</div>
										<div class="form-group">
											<label>ช่วงเวลา</label>
											<select class="form-control" name="period" id="period" required>
												<option value="">เลือกช่วงเวลา</option>
												<option value="1">8:00-10:00</option>
												<option value="2">10:00-12:00</option>
												<option value="3">13:00-15:00</option>
												<option value="4">15:00-17:00</option>
											</select>
										</div>
									</div>
									
									<div id="map" class="col-md-8 col-sm-12 col-xs-12" style="height: 350px; position: relative; overflow: hidden;"></div>
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
	
	<!--View-->
	<div class="modal fade view-modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal form-label-left" id="AddField">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
							  </button>
						<h4 class="modal-title" id="myModalLabel"> ดูข้อมูล</h4>
					</div>
					<div class="modal-body">
						<div class="x_panel">
							<div class="x_content">
								<row>
									<div class="col-md-4">
										<div class="form-group">
											<label>ขนาดแปลง: </label>
											<span class="green" id="area"></span>
										</div>
										<div class="form-group">
											<label>กิจกรรม: </label>
											<span class="green" id="act_name"></span>
										</div>
										<div class="form-group">
											<label>วันที่: </label>
											<span class="green" id="startdate"></span>
										</div>
										<div class="form-group">
											<label>วันที่ทำงานเสร็จ: </label>
											<span class="green" id="enddate"></span>
										</div>
										<div class="form-group">
											<label>ช่วงเวลา: </label>
											<span class="green" id="jobperiod"></span>
										</div>
										<div class="form-group">
											<label>สถานะ: </label>
											<span id="status"></span>
										</div>
									</div>
									
									<div id="map2" class="col-md-8 col-sm-12 col-xs-12" style="height: 350px; position: relative; overflow: hidden;"></div>
								</row>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!--Add new-->
	
</div>