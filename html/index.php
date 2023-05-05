<?php
require_once("rpcl/rpcl.inc.php");
require_once("dm.php");
require_once("dmauth.php");
//Includes
use_unit("jquerymobile/forms.inc.php");
use_unit("extctrls.inc.php");
use_unit("stdctrls.inc.php");
use_unit("jquerymobile/jmobile.inc.php");

//Class definition
class PageLogin extends MPage
{
    public $EditLoginUser = null;
    public $ButtonLogin = null;
    public $EditLoginPassword = null;
    public $LabelLLogin = null;
    public $LabelLPassword = null;
   public $MsgCreate = null;
   public $LabelCPasswor = null;
   public $LabelCRPassword = null;
   public $LabelCLogin = null;
   public $EditRePassword = null;
   public $EditPassword = null;
   public $EditLogin = null;
   public $ButtonCreateUser = null;
   public $LabelCTitle = null;
   function ShowHideCreateUser($val)
   {
     $this->MsgCreate->Visible = $val;
     $this->LabelCTitle->Visible = $val;
     $this->LabelCLogin->Visible = $val;
     $this->EditLogin->Visible = $val;
     $this->LabelCPasswor->Visible = $val;
     $this->EditPassword->Visible = $val;
     $this->LabelCRPassword->Visible = $val;
     $this->EditRePassword->Visible = $val;
     $this->ButtonCreateUser->Visible = $val;
   }
   function ShowHideLogin($val)
   {
     $this->LabelLLogin->Visible = $val;
     $this->EditLoginUser->Visible = $val;
     $this->LabelLPassword->Visible = $val;
     $this->EditLoginPassword->Visible = $val;
     $this->ButtonLogin->Visible = $val;
   }
   function ButtonCreateUserClick($sender, $params)
   {
      global $dm;
      $path = $dm->GetDataPath();
      if($this->EditLogin->Text == "")
      {
         $this->MsgCreate->Caption = "Login is required";
         return;
      }
      if($this->EditPassword->Text == "")
      {
         $this->MsgCreate->Caption = "Password is required";
         return;
      }
      if($this->EditPassword->Text != $this->EditRePassword->Text)
      {
         $this->MsgCreate->Caption = "Password does not match.";
         return;
      }
      $hash = md5($this->EditLogin->Text . ":rokuphp:" . $this->EditPassword->Text);
      if($fh = fopen($path . "validuser.txt", 'w'))
      {
         $stringData = $this->EditLogin->Text . ":rokuphp:" . $hash;
         fwrite($fh, $stringData, 1024);
         fclose($fh);
      }
      global $dmauth;
      $dmauth->ZAuth->UserName = $this->EditLoginUser->Text;
      $dmauth->ZAuth->UserPassword = $this->EditLoginPassword->Text;
      mobileRedirect("menu.php");
   }
    function PageLoginShow($sender, $params)
    {
      global $dm;
      $path = $dm->GetDataPath();
      $val = file_exists($path . "validuser.txt");
      $this->ShowHideCreateUser(!$val);
      $this->ShowHideLogin($val);
    }
    function ButtonLoginClick($sender, $params)
    {
      global $dmauth;
      $dmauth->ZAuth->UserName = $this->EditLoginUser->Text;
      $dmauth->ZAuth->UserPassword = $this->EditLoginPassword->Text;
      mobileRedirect("menu.php");
    }
}

global $application;

global $PageLogin;

//Creates the form
$PageLogin = new PageLogin($application);

//Read from resource file
$PageLogin->loadResource(__FILE__);

//Shows the form
$PageLogin->show();

?>