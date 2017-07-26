<?
	error_reporting(1);
	class connect_db {
		// ======= Start variable of class ===========
		var $host = "localhost";
		var $database = "alertcenter";
		var $user = "root";
		var $password = "";
		var $link_con = 0;
		var $sqlquery;
		var $result;
		var $count;
		var $ERRNO;
		var $ERROR;
		var $charset;
		// ======= Stop variable of class ===========
		// ======= Start method of class ===========
		function connect_db () {
//			global $HTTP_HOST;
	  		$this->link_con = mysql_connect ($this->host, $this->user, $this->password);
			mysql_query("SET NAMES utf8");
  			mysql_select_db ($this->database, $this->link_con);
		}

		function send_cmd ($query){
  			$this->sqlquery = $query;
  			$this->result = mysql_query ($query,$this->link_con);
  			$this->ERRNO = mysql_errno ();
  			$this->ERROR = mysql_error ();
  			$this->count = mysql_num_rows ($this->result);
//  			return $this->count;
		}

		function affected_rows() {
			return mysql_affected_rows($this->link_con);
		}

		function get_id () {
			return mysql_insert_id ($this->link_con);
		}

		function get_object (){
  			return mysql_fetch_object ($this->result);
		}
		
		function get_data (){
  			return mysql_fetch_row ($this->result);
		}

		function data_seek ($torow){
  			return mysql_data_seek ($this->result,$torow);
		}

		function num_rows (){
  			return $this->count;
		}

		function show_error () {
			echo $this->sqlquery."<br>".mysql_errno().":".mysql_error();
		}

		function free_result (){
  			mysql_free_result ($this->result);
		}

		function close () {
  			mysql_close ($this->link_con);
		}
		// ======= Stop method of class ===========
	} // end of class
//	$db = new connect_db();
		function showpackage($this)	
		{
			$sqlpackage = "select * from package where package_id='$this'";
			$resultpackage = mysql_query($sqlpackage) or die("select * from package where package_id='$this'");
			$rowpackage = mysql_fetch_array($resultpackage);
			
			echo "<span class='englarge'><strong><font color='#CC9966'>$rowpackage[package_name]</font></strong><br>
                                </span><span class='engbody'>$rowpackage[place]</span><span class='englarge'><br>
                                </span><span class='engbody'>$rowpackage[totalday]</span><span class='englarge'><br>
                                </span><span class='smalleng'>Tour Code :$rowpackage[tourcode]</span>";	
        }
		function showdate($this)	{
				list ($year, $month, $day) = split ('[-.-]', $this);
						if($month=='01'){$monthname='Jan';}elseif($month=='02'){$monthname='Feb';}
						elseif($month=='03'){$monthname='Mar';}elseif($month=='04'){$monthname='Apr';}
						elseif($month=='05'){$monthname='May';}elseif($month=='06'){$monthname='Jun';}
						elseif($month=='07'){$monthname='Jul';}elseif($month=='08'){$monthname='Aug';}
						elseif($month=='09'){$monthname='Sep';}elseif($month=='10'){$monthname='Oct';}
						elseif($month=='11'){$monthname='Nov';}elseif($month=='12'){$monthname='Dec';}

						echo $monthname." ".$day." ".$year;	
        }
		function showMonth($month)	{
						if($month=='01'){$monthname='Jan';}elseif($month=='02'){$monthname='Feb';}
						elseif($month=='03'){$monthname='Mar';}elseif($month=='04'){$monthname='Apr';}
						elseif($month=='05'){$monthname='May';}elseif($month=='06'){$monthname='Jun';}
						elseif($month=='07'){$monthname='Jul';}elseif($month=='08'){$monthname='Aug';}
						elseif($month=='09'){$monthname='Sep';}elseif($month=='10'){$monthname='Oct';}
						elseif($month=='11'){$monthname='Nov';}elseif($month=='12'){$monthname='Dec';}
						echo $month;	
        }	
		function showdatetm($this)	{
				list ($year, $month, $day) = split ('[-.-]', $this);
						if($month=='01'){$monthname='Jan';}elseif($month=='02'){$monthname='Feb';}
						elseif($month=='03'){$monthname='Mar';}elseif($month=='04'){$monthname='Apr';}
						elseif($month=='05'){$monthname='May';}elseif($month=='06'){$monthname='Jun';}
						elseif($month=='07'){$monthname='Jul';}elseif($month=='08'){$monthname='Aug';}
						elseif($month=='09'){$monthname='Sep';}elseif($month=='10'){$monthname='Oct';}
						elseif($month=='11'){$monthname='Nov';}elseif($month=='12'){$monthname='Dec';}

						echo $monthname."-".$day."-".$year;	
        }					
		function doUploadFile( $source, $subpath, $source_name)
        {
                        $path = AddSlashes(dirname($PATH_TRANSLATED)).$subpath;
                        if(($source <> "none")&&($source <> "")) {
                                if($error1 <> 1) {
                                        $dest = $path.$source_name;
                                        copy( $source, $dest);
					chmod($dest, 0755);
                                }
                        }

        }
function GenLink($sqlx, $link, $ratio) {
	global $p_result, $db, $page, $p_total; 
	$s_list = 20;		// Link page per page
	$r_page = ceil($page/$s_list);
	$link_code = $s_n;

$db=mysql_query($sqlx);
$row=mysql_fetch_array($db);
$p_total=$row[0];

	$t_page = $p_total;
	$e_page = ceil($t_page/$ratio);


	if (($t_page > $ratio)) {
		if (!$page || ($page == "1")) {	// First page
			for ($i=1; $i<=$e_page; $i++) {
				if ($e_page < $s_list) {
					if ($i == 1) { $p_result .= "&nbsp<b>$i</b>&nbsp;"; }
					else { $p_result .= "&nbsp<a href=\"$link_code?page=$i&$link\">$i</a>&nbsp;"; }
				}
				else {
					if ($i == 1) { $p_result .= "&nbsp<b>$i</b>&nbsp;"; }
					elseif ($i <= ($r_page*$s_list))  { 
						$p_result .= "&nbsp<a href=\"$link_code?page=$i\">$i</a>&nbsp;"; 
					}
				}
			}
			$p_result .= " <a href=\"$link_code?page=2&$link\">Next &gt;</a> &nbsp;";
		}
		elseif (($e_page > $page) && ($e_page != $page)) {	// Between list
			$b_page = $page-1;
			$n_page = $page+1;
			$p_result .= "<a href=\"$link_code?page=$b_page&$link\">&lt; Back</a> &nbsp;";
			$ni = (($r_page*$s_list)-$s_list)+1;
			for ($i=$ni; $i<=$e_page; $i++) {
				if ($i == $page) { $p_result .= "&nbsp<b>$i</b>&nbsp;"; }
				elseif ($i <= ($r_page*$s_list))  { 
					$p_result .= "&nbsp<a href=\"$link_code?page=$i&$link\">$i</a>&nbsp;"; 
				}
			}
			$i--;
			$p_result .= "<a href=\"$link_code?page=$n_page&$link\">Next &gt;</a> &nbsp;";
		}
		elseif ($e_page > $page) {
			$ni = (($r_page*$s_list)-$s_list)+1;
			for ($i=1; $i<=$e_page; $i++) {
				if ($i == $page) { $p_result .= "&nbsp<b>$i</b>&nbsp;"; }
				elseif ($i <= ($r_page*$s_list))  { 
					$p_result .= "&nbsp<a href=\"$link_code?page=$i&$link\">$i</a>&nbsp;"; 
				}
			}
			$i--;
			$p_result .= "<a href=\"$link_code?page=$i&$link\">Next &gt;</a> &nbsp;";
		}
		elseif ($e_page == $page) {
			$b_page = $page-1;
			$ni = (($r_page*$s_list)-$s_list)+1;
			$p_result .= "<a href=\"$link_code?page=$b_page&$link\">&lt; Back</a> &nbsp;";
			for ($i=$ni; $i<=$e_page; $i++) {
				if ($i == $page) { $p_result .= "&nbsp<b>$i</b>&nbsp;"; }
				elseif ($i <= ($r_page*$s_list)) { 
					$p_result .= "&nbsp<a href=\"$link_code?page=$i&$link\">$i</a>&nbsp;"; 
				}
			}
			$i = $i-2;
		}
	}
}		
?>
