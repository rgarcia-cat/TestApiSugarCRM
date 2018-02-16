<?php
require_once "vendor/autoload.php";
require_once "src/restSinergiaCRM.php";
// config file
require_once "src/config.php";

use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
  private $user;
  private $pass;
  private $url;

  public function testTesting(){
    $this->assertEquals(2,2);
  }

  public function testLoginIn() {
    global $username, $password, $urlserver;

    $this->user = $username;
    $this->pass = $password;
    $this->url = $urlserver;

    $restSCRM = new RestSinergiaCRM();

    $actual = $restSCRM->login($this->user, $this->pass, $this->url);
    $this->assertTrue($actual);
  }

  public function testNoLogin() {
    global $urlserver;

    $this->user = "";
    $this->pass = "";
    $this->url = $urlserver;

    $restSCRM = new RestSinergiaCRM();

    $actual = $restSCRM->login($this->user, $this->pass, $this->url);
    $this->assertFalse($actual);
  }

  public function testLoginUserNonExist() {
    global $username, $urlserver;

    $this->user = "no-exist";
    $this->pass = "no-password";
    $this->url = $urlserver;

    $restSCRM = new RestSinergiaCRM();

    $actual = $restSCRM->login($this->user, $this->pass, $this->url);
    $this->assertFalse($actual);
  }

  public function testLoginIncorrectPassword() {
    global $username, $urlserver;

    $this->user = $username;
    $this->pass = "no-password";
    $this->url = $urlserver;

    $restSCRM = new RestSinergiaCRM();

    $actual = $restSCRM->login($this->user, $this->pass, $this->url);
    $this->assertFalse($actual);
  }

}
