<?php
	class system_getconfig{
		protected static $_config=array();
		public static function getConfig($key)
		{
			if(!self::$_config)
			{
				$filename=dirname(__FILE__).DIRECTORY_SEPARATOR.'config_system.ini';
				if(file_exists($filename))
				{
					$config=parse_ini_file($filename);
					if(false===$config)
					{
						throw new Exception('can read this config file');
					}
				}
				else{
					throw new Exception('config_system.ini not available');
				}
				self::$_config=$config;
			}
			
			if(isset(self::$_config[$key])){
				return self::$_config[$key];
			}
		}
	}
?>
