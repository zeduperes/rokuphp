<?php
require_once("rpcl/rpcl.inc.php");
require_once("config.php");// my config.php includes $vPathlib, see it.
//Includes
use_unit("forms.inc.php");
use_unit("extctrls.inc.php");
use_unit("stdctrls.inc.php");
use_unit("Zend/zauth.inc.php");
use_unit("Zend/zauthdigest.inc.php");

//Class definition
class dmauth extends DataModule
{
   public $ZAuth = null;
   public $ZAuthDigest = null;
   function ZAuthLogin($sender, $params)
   {
      //ZAuth component is responsible for manage authentication
      //ZAuthDigest uses the file validusers.txt to validate the user
      redirect('index.php');
   }
   public function doEncrypt($val)
   {
      if ($val == "") return "";
      try
      {
        return openssl_encrypt($val, "AES-128-ECB", $this->ZAuth->UserPassword);
      } catch (Exception $e)
      {
        return "";
      }
   }
   public function doDecrypt($val)
   {
      if ($val == "") return "";
      try
      {
          return openssl_decrypt($val, "AES-128-ECB", $this->ZAuth->UserPassword);
      } catch (Exception $e)
      {
        return "";
      }
   }

}

global $application;

global $dmauth;

//Creates the form
$dmauth = new dmauth($application);

//Read from resource file
$dmauth->loadResource(__FILE__);

?>