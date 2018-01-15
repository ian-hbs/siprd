<?php
  require_once("inc/init.php");
  require_once("lib/user_controller.php");
  require_once("lib/global_obj.php");
  require_once("helpers/mix_helper.php");

  $uc = new user_controller($db,$__SESSION_ID_NAME);  
  $global = new global_obj($db);
  
  // $ip = get_ip();

  // $activity = "logout";
  // $global->insert_logs($activity,$ip);

  $uc->logout_process();
  
?>
