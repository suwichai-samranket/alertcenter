<?

if($_POST['send']<>"" && $_GET['id']<>"")
{
	//check status
	if($_POST['send']==1)
	{
		$status_update = 2;	
	}
	elseif($_POST['send']==2)
	{
		$status_update = 0;	
	}
		

	//Get Old Data
	$sqlold = "select * from job where job_id='".$_GET['id']."' "; 
	$resultold = mysql_query($sqlold) or die (mysql_error());
	$rowsold = mysql_fetch_assoc($resultold);
	

	$sqlupdate = "update job set status='$status_update', proprietor_id='$staff_admin_id', enddate=now() where job_id='".$_GET['id']."' ";
	$resultupdate = mysql_query($sqlupdate) or die(mysql_error());


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

$sql = "select * from job where job_id='".$_GET['id']."'"; 
$result = mysql_query($sql) or die(mysql_error());
$rows=mysql_fetch_array($result);
?>
	  <!-- Parsley -->
    <script>
 		$(document).ready(function() {
        $.listen('parsley:field:validate', function() {
          validateFront();
        });
        $('#form1 .btn btn-success').on('click', function() {
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
 	<script>
      function initMap() {
        var uluru = {lat: <? echo getField($rows['field_id'],'latitude'); ?>, lng: <? echo getField($rows['field_id'],'longitude'); ?>};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 15,
          center: uluru
        });
        var marker = new google.maps.Marker({
          position: uluru,
          map: map
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC7-mm1sY7ZugoavgfNggnhGTSxpPkXIEU&callback=initMap">
    </script>
  	<div class="col-md-12 col-sm-12 col-xs-12">
	  <div class="x_panel">
		<div class="x_title">
		  <h2><? echo getMenuName($_GET['where']);?></h2>
		  <div class="clearfix"></div>
		</div>
		<div class="x_content">
	   <form class="form-horizontal form-label-left" enctype="multipart/form-data" id="form1" method="post" action="" data-parsley-validate novalidate>
		  <div class="dashboard-widget-content">
			<div class="col-md-4 hidden-small">
			  <h2 class="line_30">กิจกรรม</h2>

			  <table class="countries_list">
				<tbody>
				  <tr>
					<td>ประเภทรถ: </td>
					<td class="fs10 fw700 text-right"><span class="label label-info"><? echo getTruckRelation($rows['act_id']);?></span></td>
				  </tr>
				  <tr>
					<td>กิจกรรม: </td>
					<td class="fs15 fw700 text-right"><span class="label label-info"><? echo getActivities($rows['act_id']);?></span></td>
				  </tr>
				  <tr>
					<td>วันที่: </td>
					<td class="fs15 fw700 text-right"><span class="label label-info"><? echo $rows['startdate'];?></span></td>
				  </tr>
				  <tr>
					<td>เวลา: </td>
					<td class="fs15 fw700 text-right"><span class="label label-info">
						<?
							if($rows["period"]==1)
							{
								echo "8:00-10:00";
							}
							elseif($rows["period"]==2)
							{
								echo "10:00-12:00";
							}
							elseif($rows["period"]==3)
							{
								echo "13:00-15:00";
							}
							elseif($rows["period"]==4)
							{
								echo "15:00-17:00";
							}
						?></span>
					</td>
				  </tr>
				</tbody>
			  </table>
			<? if($rows['status']==1){ ?>
			  	<button id="send" name="send" type="submit" class="btn btn-warning" value="<? echo $rows['status'];?>">ยืนยันการรับงาน</button>
			<? 
				}
				elseif($rows['status']==2)
				{
			?>
				<button id="send" name="send" type="submit" class="btn btn-success" value="<? echo $rows['status'];?>">ปิดการทำงาน</button>
			<?
				}
			?>
			</div>
			<div id="map" class="col-md-8 col-sm-12 col-xs-12" style="height: 230px; position: relative; overflow: hidden;"></div>
			 
		  </div>
		</form>
		</div>
	  </div>
	</div>
	