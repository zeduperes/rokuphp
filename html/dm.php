<?php
require_once("rpcl/rpcl.inc.php");
//Includes
use_unit("forms.inc.php");
use_unit("extctrls.inc.php");
use_unit("stdctrls.inc.php");
use_unit("jquerymobile/jmobile.inc.php");

//Class definition
class dm extends DataModule
{
   public $MobileTheme1 = null;
   public function killall($match)
   {
      $killed = 0;
      if($match == '')
         return 'no pattern specified';
      $match = escapeshellarg($match);
      exec("ps x|grep $match|grep -v grep|awk '{print $1}'", $output, $ret);
      if($ret)
         return 'you need ps, grep, and awk installed for this to work';
      while(list(, $t) = each($output))
      {
         if(preg_match('/^([0-9]+)/', $t, $r))
         {
            system('kill ' . $r[1], $k);
            if( ! $k)
               $killed = 1;
         }
      }
      if($killed)
      {
         return '';
      }
      else
      {
         return "$match: no process killed";
      }
   }
   public function goHome()
   {
      if($this->UseAjax)
         mobileRedirect("menu.php");
      else
         header("Location: menu.php");
   }
   public function GetDataPath()
   {
      if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
      {
         // This is a server using Windows
         return "data\\";
      }
      else
      {
         // This is a server not using Windows
         return "data/";
      }
   }
   public function GetConfigPath()
   {
      if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
      {
         // This is a server using Windows
         return "config\\";
      }
      else
      {
         // This is a server not using Windows
         return "config/";
      }
   }
   public function ForceRefresh()
   {
      // not sure if this works so I modified forms.inc.php
      header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
      header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
      header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
      header("Cache-Control: post-check=0, pre-check=0", false);
      header("Pragma: no-cache");
   }
   public function generateRandomString($length = 10)
   {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for($i = 0; $i < $length; $i++)
      {
         $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
   }
}

global $application;

global $dm;

//Creates the form
$dm = new dm($application);

//Read from resource file
$dm->loadResource(__FILE__);

?>