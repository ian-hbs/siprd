<?php
	class global_obj
	{
		protected $_db;
		
		public function __construct($db=null)
		{
			$this->_db=$db;			
		}
		
		function real_escape_string($input)
		{
			$result = preg_replace("/'/i","\'",$input);			
			return $result;
		}		

		function dropseparate_specialChar($str)
		{
			$result = array($str,'','');
			if(strlen($str)>1)
			{
				$arr_specialChar = array('`','~','!','@','#',
										 '$','%','^','&','*',
										 '(',')','_','-','+',
										 '=',',','.','<','>',
										 '/','?',';','\'',':',
										 '"','[',']','\\','|');
				$firstChar = substr($str,0,1);
				$lastChar = substr($str,-1,1);

				$x = array_search($firstChar,$arr_specialChar,true);
				$y = array_search($lastChar,$arr_specialChar,true);
							
				$str1 = ($x?substr($str,1,strlen($str)-1):$str);
				$str2 = ($y?substr($str1,0,strlen($str1)-1):$str);
				$result [0] = $str2;
				$result [1] = ($x?$arr_specialChar[$x]:'');
				$result [2] = ($y?$arr_specialChar[$y]:'');
			}			

			return $result;
		}		
		

		function get_incrementID($table,$pk)
		{
			$sql = "SELECT ".$pk." FROM public.".$table." ORDER BY ".$pk." DESC";
			$result = $this->_db->Execute($sql);
			if(!$result)
			{
				echo($this->_db->ErrorMsg());
			}
			$new = 1;
			if($result->RecordCount()>0)	
			{
				$row = $result->FetchRow();
				$new = (int) $row[$pk] + 1;				
			}
			return $new;
		}

		function get_registerNum2($table,$pk)
		{
			$initials = array('MEN','USRT','USR');
			$num = '';
			switch($table)
			{
				case 'app_menu':$num=0;break;
				case 'app_user_types':$num=1;break;
				case 'app_user':$num=2;break;
			}

			
			$sql = "SELECT MAX(".$pk.") as last_ordnum FROM ".$table." WHERE(".$pk." LIKE '".$initials[$num]."%')";
			

			$result = $this->_db->Execute($sql);
			if(!$result)
			{
				echo($this->_db->ErrorMsg());
			}
			
			$order_num = 1;
			$len1 = strlen($initials[$num])+1;
			$len2 = 10-$len1;

			if($result->RecordCount()>0)
			{
				$row = $result->FetchRow();
				$order_num = (int) substr($row['last_ordnum'], $len1, $len2) + 1;
			}
			
			$regnum = $initials[$num]."-".sprintf("%0".$len2."s", $order_num);

			return $regnum;

		}
		
		function get_registerNum()
		{
			$curr_year = date('Y');
			$sql = "SELECT MAX(no_registrasi) as last_regnum FROM public.app_reg_wr WHERE no_registrasi LIKE '".$curr_year."%'";
			$result = $this->_db->Execute($sql);
			if(!$result)
				echo($this->_db->ErrorMsg());

			$order_num = 1;
			if($result->RecordCount()>0)
			{
				$row = $result->FetchRow();
				$order_num = (int) substr($row['last_regnum'],4,4) + 1;
			}			

			$regnum = $curr_year.sprintf("%04s", $order_num);
			return $regnum;
		}		

		function get_user_id()
		{
			$curr_year = date('Y');
			$sql = "SELECT MAX(usr_id) as last_id FROM public.app_user";
			$result = $this->_db->Execute($sql);
			if(!$result)
				echo($this->_db->ErrorMsg());

			$order_num = 1;
			if($result->RecordCount()>0)
			{
				$row = $result->FetchRow();
				$order_num = (int) substr($row['last_id'],4,6) + 1;
			}			

			$userid = 'USR-'.sprintf("%06s", $order_num);
			return $userid;
		}

		function get_new_number($type)
		{
			$table = "";
			$field = "";
			switch($type)
			{
				case '1':$table='app_nota_perhitungan';$field="no_nota_perhitungan";break;
				case '2':$table='app_skrd';$field="no_skrd";break;
			}

			$curr_year = date('Y');
			$sql = "SELECT MAX(".$field.") as last_num FROM public.".$table." WHERE thn_retribusi='".$curr_year."'";
			
			$result = $this->_db->Execute($sql);
			if(!$result)
				echo($this->_db->ErrorMsg());

			$new_number = 1;
			if($result->RecordCount()>0)
			{
				$row = $result->FetchRow();
				$new_number = (int) $row['last_num'] + 1;
			}

			return $new_number;	
		}

		function get_new_bill_number()
		{
			return $this->get_new_number('1');
		}

		function get_new_skrd_number()
		{
			return $this->get_new_number('2');
		}

		function get_district_name($id)
		{
			$sql = "SELECT camat_nama as name FROM public.kecamatan WHERE(camat_id='".$id."')";
			$name = $this->_db->getOne($sql);
			return $name;
		}

		function get_district_id($name)
		{
			$sql = "SELECT camat_id as id FROM kecamatan WHERE(LOWER(camat_nama)='".strtolower($name)."')";
			$id = $this->_db->getOne($sql);
			return $id;
		}

		function get_village_name($id)
		{
			$sql = "SELECT lurah_nama as name FROM kelurahan WHERE(lurah_id='".$id."')";
			$name = $this->_db->getOne($sql);			
			return $name;
		}
		
		function get_village_id($name,$dis_id)
		{
			$sql = "SELECT lurah_id as id FROM kelurahan  WHERE(LOWER(lurah_nama)='".strtolower($name)."') AND (lurah_kecamatan='".$dis_id."')";
			$id = $this->_db->getOne($sql);
			return $id;
		}

		function get_npwrd($type,$district_id='')
		{
			$curr_year = date('y');

			$search_value = 'R.'.($type=='1'?$curr_year:$district_id);

			$sql = "SELECT MAX(npwrd) as last_npwrd FROM app_reg_wr WHERE(npwrd LIKE '".$search_value."%')";

		    $result = $this->_db->Execute($sql);
		    if(!$result)
		        die('ERROR:terjadi kesalahan!');

		    $order_num = 1;
		    if($result->RecordCount()>0)
		    {
		        $row = $result->FetchRow();
		        $order_num = (int) substr($row['last_npwrd'],4,4) + 1;
		    }


		    $npwrd = 'R.'.($type=='1'?$curr_year:$district_id).sprintf("%04s", $order_num);
		    return $npwrd;
		}

		function get_billing_code($type)
		{
			$prefix	= $type.'0';
			$stamp1	= date("m");
			$stamp2	= date("d");
			$len = 5; 
			$base = '123456789'; 
			$max = strlen($base)-1;
			$activatecode='';
			
			mt_srand((double)microtime()*1000000);
			
			while (strlen($activatecode)<$len+1)
			{
				$activatecode .= $base{mt_rand(0,$max)};
			}
			$billing_code = $prefix.$stamp1.$activatecode.$stamp2;
			return $billing_code;
		}

		function get_ntpd()
		{
			$stamp = date("Ymdhis");			
			$orderid = $stamp;
			$orderid = str_replace(".","",$orderid);

			return $orderid;	
		}

		function get_payment_position($billing_code)
		{
			$sql = "SELECT MAX(pembayaran_ke) as pembayaran_terakhir FROM app_pembayaran_retribusi WHERE(kd_billing='".$billing_code."') AND (status_reversal='0')";
			$last_payment = $this->_db->getOne($sql);
			$payment_position = (!is_null($last_payment) && !empty($last_payment)?$last_payment+1:1);
			return $payment_position;
		}

		function get_total_payment($billing_code)
		{
			$sql = "SELECT SUM(total_bayar) as total_pembayaran FROM app_pembayaran_retribusi WHERE(kd_billing='".$billing_code."') AND (status_reversal='0')";
			$total_payment = $this->_db->getOne($sql);
			return $total_payment;
		}

		function get_system_params()
		{
			$sql = "SELECT * FROM app_system_params";
			$result = $this->_db->Execute($sql);
			if(!$result)
				return false;
			$system_params = array();
			while($row=$result->FetchRow())
			{
				$system_params[$row['id']] = $row['value'];
			}

			return $system_params;
		}

		function format_account_code($account_code)
		{
			$result = '';
			if(strlen($account_code)==7)
			{
				$result = substr($account_code,0,1).'.'.substr($account_code,1,1).'.'.substr($account_code,2,1).'.'.substr($account_code,3,2).'.'.substr($account_code,5,2);
			}
			return $result;
		}


	}
?>