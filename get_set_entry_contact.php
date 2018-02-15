<?php
  require './config.php';
  require './restSinergiaCRM.php';


  $id = '5bd20503-1ab3-bf30-b58c-59f90f1703b5';

  $sinergiaCRM = new RestSinergiaCRM();
  
  $sinergiaCRM->login($username, $password, $urlserver);
  
  
  //Obtenim un contact amb un id de pagament
  $report = $sinergiaCRM->getContactWithPayment($id);
  print_r($report);

  //update contact
  $sinergiaCRM->updateContact($report);
  
  
  /*
  //Modifiquem un contact
  $set_entry_list_parameters = array(
     'session' =>  $sinergiaCRM->session_id,
     'module_name' => "Contacts",
      //Record attributes
      'name_value_list' => array(
            //to update a record, you will nee to pass in a record id as commented below
          array("name" => "id", "value" => "cbcb2635-d2df-63c1-1152-5a8421ea6db7"),
          array("name" => "first_name", "value" => "Test Contact Api"),
          array("name" => "last_name", "value" => "TESTTEST_modify"),
          //array("name" => "comentarios_c", "value" => "test"),
      ),
  );
  
$set_entry_list_result = $sinergiaCRM->call("set_entry", $set_entry_list_parameters);

print_r($set_entry_list_result);
*/
?>