<?php
require_once "vendor/autoload.php";
require_once "src/restSinergiaCRM.php";
// config file
require_once "src/config.php";

use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
  private $user;
  private $pass;
  private $url;
  private $restSCRM;

  private function login() {
    global $username, $password, $urlserver;

    $this->user = $username;
    $this->pass = $password;
    $this->url = $urlserver;

    $this->restSCRM = new RestSinergiaCRM();

    return($this->restSCRM->login($this->user, $this->pass, $this->url));
  }

  protected function setup(){
    if(!$this->login()){
      $this->markTestIncomplete('Error Login');
    }
  }
  public function testTesting(){
    $this->assertEquals(2,2);
  }

// Get contact
  public function testExistentContact() {
    $id = '1489d748-fb74-8b87-1d2a-591c1be6dfbe';
    $field = 'full_name';
    $full_name = 'Carles Bouchaud Sabaté';
    $contact = $this->restSCRM->getContact($id);
    $this->assertEquals($full_name, $contact[$field]);
  }

  public function testNonIdContact() {
    $id = '';
    $error_message = 'Access to this object is denied since it has been deleted or does not exist';
    $contact = $this->restSCRM->getContact($id);
    $this->assertEquals($error_message, $contact['status']);
  }
  public function testNonExistContact() {
    $id = '1489d748-fb74-8b87-1d2a-591c1be6dfba';
    $error_message = 'Access to this object is denied since it has been deleted or does not exist';
    $contact = $this->restSCRM->getContact($id);
    $this->assertEquals($error_message, $contact['status']);
  }

 
}
