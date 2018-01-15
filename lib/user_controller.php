<?php
	
	class user_controller
	{
		protected $_db;
		protected $__session_id_name;
		protected $_idle_time_before_loggedout;
		function __construct($db){
			global $__SESSION_ID_NAME,$_IDLE_TIME_BEFORE_LOGGEDOUT;
			$this->_db=$db;
			$this->__session_id_name = $__SESSION_ID_NAME;
			$this->_idle_time_before_loggedout = $_IDLE_TIME_BEFORE_LOGGEDOUT;
		}

		function login_process($username,$password,$ip)
		{
	        
	        $sql = "SELECT a.usr_id,a.username,a.first_name,a.last_name,a.email,a.usr_type_id,a.inquiry_access,a.status,b.name as usr_type FROM app_user as a LEFT JOIN app_user_types as b ON (a.usr_type_id=b.usr_type_id) 
			WHERE username='".$username."' AND password='".$password."'";
	        
			$result = $this->_db->Execute($sql);
			if(!$result)
			{
				return 'failed';				
			}

			if($result->RecordCount()>0)
			{
				$data = $result->FetchRow();
				$usr_id = $data['usr_id'];

				$sec1 = microtime();
			    mt_srand((double)microtime()*1000000);
			    $sec2 = mt_rand(1000,9999);
			    $session_id = md5($sec2.$sec2);

				//delete session data for current user
				$sql_manipulating = "DELETE FROM app_session WHERE(usr_id='".$usr_id."')";
				$result = $this->_db->Execute($sql_manipulating);
			    if (!$result) 
			    {
			      return 'failed1';
			    }
			  	// ===== //

			  	//save new session data for current user
			  	$time= explode(" ", microtime());
		    	$last_access= (double) $time[1];
		    	$ctime = date('Y-m-d H:i:s');
		    	$challenge = md5($session_id);
		    	$session_content = "{\"username\":\"".$data['username']."\",
		    						 \"first_name\":\"".$data['first_name']."\",
		    						\"last_name\":\"".$data['last_name']."\",
		    						\"email\":\"".$data['email']."\",
		    						\"status\":\"".$data['status']."\",
		    						\"user_type\":\"".$data['usr_type']."\",
		    						\"inquiry_access\":\"".$data['inquiry_access']."\"}";
			    $sql_manipulating = "INSERT INTO app_session (session_id,usr_id,last_access,ip,user_agent,status,ctime,challenge,session_content) 
			    		   VALUES ('".$session_id."','".$usr_id."','".$last_access."','".$ip."','".$_SERVER['HTTP_USER_AGENT']."','1','".$ctime."','".$challenge."','".$session_content."')";

			    
			    $result = $this->_db->Execute($sql_manipulating);
			    if (!$result)
			    {
			    	return 'failed2';
			    }
			  	// ===== //


			  	$_SESSION[$this->__session_id_name] = $session_id;
			    $_SESSION['username'] = trim($data['username']);
			    $_SESSION['usr_type_id'] = $data['usr_type_id'];
			    $_SESSION['usr_type'] = $data['usr_type'];
			    $_SESSION['usr_id'] = $data['usr_id'];
			    $_SESSION['login_date'] = date('Y-m-d');
			    $_SESSION['login_time'] = date('H:i:s');

				return 'success';
			}
			else
			{
				return 'failed';
			}
		}

		function logout_process()
		{			
			$sql_manipulating = "DELETE from app_session WHERE session_id='".$_SESSION[$this->__session_id_name]."'";
			
			$result = $this->_db->Execute($sql_manipulating);

			if (!$result) 
				echo $this->_db->ErrorMsg();
				   
			session_destroy();

			header("location:login.php");
		}

		function check_access()
		{
			if(!isset($_SESSION[$this->__session_id_name]) or (isset($_SESSION[$this->__session_id_name]) and empty($_SESSION[$this->__session_id_name])))
			{
				echo "<script type='text/javascript'>					
					document.location.href='login.php';
				</script>";				
				exit();
			}
			
			//Execute the SQL Statement (Get Username)
			$sql = "SELECT usr_id,last_access from app_session WHERE session_id='".$_SESSION[$this->__session_id_name]."'";
			$result	= $this->_db->Execute($sql);
			if(!$result)
				die($this->_db->ErrorMsg());

			if ($result->RecordCount() == 0)
			{
				echo "
					<script type='text/javascript'>
						alert('Ada pengguna lain yang menggunakan login anda atau session anda telah expired, silahkan login kembali');
						document.location.href='logout_process.php';
					</script>";
				exit();
			}

			$data = $result->FetchRow();
			$usr_id = $data['usr_id'];
			$last_access = $data['last_access'];

			/*=====================================================
			AUTO LOG-OFF 15 MINUTES
			======================================================*/

			//Update last access!
			$time= explode(" ", microtime());
			$usersec= (double) $time[1];
			
			$diff   = $usersec-$last_access;
			$limit  = 60*$this->_idle_time_before_loggedout;
			
			if($diff>$limit)
			{				
	      		echo "
					<script type='text/javascript'>
						alert('Maaf status anda idle lebih dari 30 menit dan session Anda telah expired, silahkan login kembali');
						document.location.href='logout_process.php';
					</script>";
	      		exit();
			}
			else
			{
			    $sql="update app_session set last_access='".$usersec."' where usr_id='".$usr_id."'";
			    $result = $this->_db->Execute($sql);
			    if (!$result)
			    	echo $this->_db->ErrorMsg();
			}

		}

		function check_priviledge($restriction="all",$menu_id)
		{			
			$access_granted=false;

			$usr_type_id = $_SESSION['usr_type_id'];

			if($restriction != 'all')
			{
				$_restriction=strtolower($restriction."_priv");

				$sql="select ".$_restriction." as check_access from app_function_access where usr_type_id='".$usr_type_id."' and men_id='".$menu_id."'";
				
				$result = $this->_db->Execute($sql);
				if (!$result)
					die($this->_db->ErrorMsg());
				
				$data = $result->FetchRow();
				
				$access_granted=($data['check_access']==1?true:false);
			}
			else
			{
				$access_granted=true;
			}
			$result->Close();

			return $access_granted;
		}

		function get_menu_id($key,$value)
		{
			$sql = "SELECT men_id FROM app_menu WHERE(".$key."='".$value."')";
			$result = $this->_db->Execute($sql);
			if (!$result)
				die($this->_db->ErrorMsg());
			
			if($result->RecordCount()>0)
			{
				$data = $result->FetchRow();
				return $data['men_id'];
			}
			else return "";

		}

		function get_last_account_activity()
		{
			$x_login_date = explode('-',$_SESSION['login_date']);
			$x_login_time = explode(':',$_SESSION['login_time']);

			$x_curr_date = explode('-',date('Y-m-d'));
			$x_curr_time = explode(':',date('H:i:s'));
			
			if(count($x_login_date)==3 and count($x_login_time)==3)
			{
				$timestamp1 = mktime($x_login_time[0],$x_login_time[1],$x_login_time[2],$x_login_date[1],$x_login_date[2],$x_login_date[0]);
				$timestamp2 = mktime($x_curr_time[0],$x_curr_time[1],$x_curr_time[2],$x_curr_date[1],$x_curr_date[2],$x_curr_date[0]);

				$diff = $timestamp2-$timestamp1;
				$x_formatted = explode(':',$this->time_formatter($diff));


				echo ($x_formatted[0]>0?$x_formatted[0].' hrs ':'').$x_formatted[1].' mins';
			}
			return false;
		}

		function time_formatter($input)
		{
			$mod1 = $input%3600;
			$m1 = $input-$mod1;
			$H = sprintf('%02d',floor($m1/3600));
			
			$mod2 = $mod1%60;
			$m2 = $mod1-$mod2;
			$i = sprintf('%02d',floor($m2/60));
			
			$s = sprintf('%02d',floor($mod2));
			return $H.':'.$i.':'.$s;
		}

	}
?>