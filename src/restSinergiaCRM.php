<?php
class RestSinergiaCRM
{
  private $modul_pagaments = "redk_Pagos";
  private $modul_contact = "Contacts";

  public $username;
  public $passwd;
  public $url;
  public $session_id;


  public function __construct() {
    $this->session_id = null;
  }

  public function login($username, $passwd, $url) {
    $this->username = $username;
    $this->passwd = md5($passwd);
    $this->url = $url;

    $login_parameters = array(
         "user_auth" => array(
              "user_name" => $this->username,
              "password" => $this->passwd,
              "version" => "1"
         ),
         "application_name" => "RestSinergiaCRM",
         "name_value_list" => array(),
    );

      $login_result = $this->call("login", $login_parameters);
      // Posar un missatge en cas d'error.
      if (isset($login_result->id)) {
        $this->session_id = $login_result->id;
        return(true);
      } else {
        return(false);
      }
  }
  public function convertObject ($o){
      $ret = array();
      foreach($o as $k){
        $ret[$k->name] = $k->value;
      }
      return $ret;
  }
  public function getContact($id)
  {
      // Not Login yet.
      if ($this->session_id == null) {
        return (array('status'=>'no_login'));
      }
      $get_entry_fields = array(
        "session" => $this->session_id,
        "module_name" => $this->modul_contact,
        "id" => $id
      );

      $result = $this->call("get_entry", $get_entry_fields);
      //print_r($result);
      // not exist?
      $contact = $this->convertObject($result->entry_list[0]->name_value_list);
      if (array_key_exists("warning", $contact)) {
        return (array('status' => $contact['warning']));
      }
      return ($contact);

  }

  public function createContact($fields){
    if ($this->session_id == null) {
      return (array('status'=>'no_login'));
    }
    $set_entry_fields = array(
      'session' => $this->session_id,
      'module_name' => $this->modul_contact,
      'name_value_list' => $fields
    );

    $result = $this->call("set_entry", $set_entry_fields);
    return($result);
  }

  public function updateContact($id, $fields)
  {
      // Not Login yet.
      if ($this->session_id == null) {
        return (array('status'=>'no_login'));
      }
      $contact = $this->getContact($id);
      if ($contact['id'] == $id) {
        $set_entry_fields = array(
          'session' => $this->session_id,
          'module_name' => $this->modul_contact,
          'name_value_list' => $fields,
        );
        $result = $this->call("set_entry", $set_entry_fields);
        return($result);
      }
      return(false);
  }
  public function deleteContact($id){
    // This is not implemented yet.
    return(false);
  }
  public function getContactWithPayment($id)
  {
      // Not Login yet.
      if ($this->session_id == null) {
        return (array('status'=>'no_login'));
      }
      $get_entry_fields = array(
         'session' =>  $this->session_id,
         'module_name' => $this->modul_pagaments,
         'id' => $id,
         'select_fields' => array(
              'id'
          ),
         'link_name_to_fields_array' => array(
              array(
                    'name' => 'redk_pagos_contacts',
                    'value' => array(
                        'id'
                    ),
              ),
          ),
      );
      $result = $this->call("get_entry", $get_entry_fields);

      // not exist?      //Manera de fer-ho diferent?
      $idContact = $result->relationship_list[0][0]->records[0]->id->value;

      return $this->getContact($idContact);

  }
  public function getPagament($id)
  {
      // Not Login yet.
      if ($this->session_id == null) {
        return (array('status'=>'no_login'));
      }
      $get_entry_fields_parameters = array(
        "session" => $this->session_id,
        "module_name" => $this->modul_pagaments,
        "id" => $id
      );

      $result = $this->call("get_entry", $get_entry_fields_parameters);
      //print_r($result);
      // not exist?
      $pagament = $this->convertObject($result->entry_list[0]->name_value_list);
      if (array_key_exists("warning", $pagament)) {
        return (array('status' => $pagament['warning']));
      }
      return ($pagament);

  }

  public function call($method, $parameters)
  {

      ob_start();
      $curl_request = curl_init();

      curl_setopt($curl_request, CURLOPT_URL, $this->url);
      curl_setopt($curl_request, CURLOPT_POST, 1);
      curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
      curl_setopt($curl_request, CURLOPT_HEADER, 1);
      curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
      //curl_setopt($curl_request, CURLOPT_VERBOSE, true);


      $jsonEncodedData = json_encode($parameters);

      $post = array(
           "method" => $method,
           "input_type" => "JSON",
           "response_type" => "JSON",
           "rest_data" => $jsonEncodedData
      );

      curl_setopt($curl_request, CURLOPT_POSTFIELDS, $post);
      $result = curl_exec($curl_request);
      curl_close($curl_request);

      $result = explode("\r\n\r\n", $result, 2);
      $response = json_decode($result[1]);
      ob_end_flush();

      return $response;
  }
}


?>
