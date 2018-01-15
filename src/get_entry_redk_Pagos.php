<?php
  require './config.php';
  require './restSinergiaCRM.php';


  $id = 'your id'; //modificar!

  $sinergiaCRM = new RestSinergiaCRM();

  $login_result = $sinergiaCRM->login($username, $password, $urlserver);

  $get_entry_list_parameters = array(
     'session' =>  $sinergiaCRM->session_id,

     'module_name' => "redk_Pagos",

     'id' => $id,

     'select_fields' => array(
          'id',
     ),

     'link_name_to_fields_array' => array(
          array(
               'name' => 'contacts',
               'value' => array(
                    'id',
               ),
          ),
     ),


);

$get_entry_list_result =  $sinergiaCRM->call("get_entry", $get_entry_list_parameters);

print_r($get_entry_list_result);
?>
