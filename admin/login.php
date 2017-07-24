<?
error_reporting(E_ALL ^ E_NOTICE);
session_start();

$Submit = $_POST['Submit'];
$username = $_POST['username'];
$password = $_POST['password'];

include("connect_db.php");  
$db = new connect_db();
$db->connect_db();

if($username<>"" && $password<>"")
{
        $sql = "select * from staff_admin where username='$username' and password='".md5($password)."' and user_status='1' ";
        $result = mysql_query($sql) or die(mysql_error());
        $fetch = mysql_fetch_array($result);
        $staff_admin_id = $fetch["staff_admin_id"];
		$changepass= $fetch["changepass"];
	
		if($changepass=="0")
		{
				?>
				<script language="JavaScript" type="text/JavaScript">
					window.location.href="changepass.php"
				</script>
				<?
		}
		
        $num = mysql_num_rows($result);
        if ($num == '1' )
        {
                //session_unregister('staff_admin_id');
                //session_register("staff_admin_id");
				$_SESSION['staff_admin_id']=$staff_admin_id;

        ?>
		<script language="JavaScript" type="text/JavaScript">
			window.location.href="index.php?where=project"
		</script>
		<?
	}
	else
	{
		$error="Username and password do not match.";
	}
}
elseif($username==""&&$tmp<>"")
{
	$bError1="error";
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>UIH Alert System</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="../vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
    
  </head>

  <body class="login">
    <div>
        <div class="animate form login_form">
        <div class="login_wrapper">
        <? if($error){ ?>
       	<div class="x_content bs-example-popovers">
		  <div class="alert alert-danger alert-dismissible fade in" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<strong><? echo $error;?> </strong> 
		  </div>
		</div>
        <? } ?>
         <p><img src="images/uihlogo.png" width="350px"></p>
         
          <section class="login_content">
           <form id="form-login" data-parsley-validate method="post" action="">
				<h1>Alert System.</h1>
              <div class="item form-group">
                <input type="text" class="form-control" placeholder="Username" name="username" required="required" />
              </div>
              <div class="item form-group">
                <input type="password" class="form-control" placeholder="Password" name="password" required="required" />
              </div>
             <div class="form-group">
				<div class="col-md-6 col-md-offset-3">
				  <button id="send" type="submit" class="btn btn-success">Submit</button>
				  <button type="reset" class="btn btn-primary">Cancel</button>
				</div>
			  </div>

              <div class="clearfix"></div>

              <div class="separator">
                <div class="clearfix"></div>
                <br />

                <div>
                  <p>©Copyright © United Information Highway Co., Ltd. All Rights Reserved Disclaimer.</p>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
    <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
    
    <!-- Parsley -->
    <script src="../vendors/parsleyjs/dist/parsley.min.js"></script>

   <!-- Parsley -->
    <script>

      $(document).ready(function() {
        $.listen('parsley:field:validate', function() {
          validateFront();
        });
        $('#form-login .btn').on('click', function() {
          $('#form-login').parsley().validate();
          validateFront();
        });
        var validateFront = function() {
          if (true === $('#form-login').parsley().isValid()) {
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
  </body>
</html>
