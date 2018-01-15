<?php	
	class db_getconfig
	{
		protected static $_config=array();

		public static function getConfig($key)
		{
			if(!self::$_config)
			{
				$filename=dirname(__FILE__).DIRECTORY_SEPARATOR.'config_db.ini';
				if(file_exists($filename))
				{
					$config=parse_ini_file($filename);
					if(false === $config)
					{
						throw new Exception('Can not read this config file');
					}
				}
				else{
					throw new Exception('config_db.ini not available');
				}
				self::$_config=$config;
			}

			if(isset(self::$_config[$key])){
				return self::$_config[$key];	
			}
		}
	}
?>