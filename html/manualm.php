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
class PageManual extends MPage
{
   public $MLinkCancel = null;
   public $Msg = null;
   public $ButtonAddCamera = null;
   public $CheckBoxDoAudio = null;
   public $MLabel2 = null;
   public $EditRTSP = null;
   public $MLabel1 = null;
   public $EditCameraName = null;
   public $MLabel3 = null;
   public $EditSnapshot = null;

   function ButtonAddCameraClick($sender, $params)
   {
      global $dm;
      global $dmauth;
      $path = $dm->GetDataPath();

      if($this->EditCameraName->Text == "")
      {
         $this->Msg->Caption = "Camera name is required and needs to be unique";
         return;
      }
      if($this->EditRTSP->Text == "")
      {
         $this->Msg->Caption = "Camera RTSP address is required.";
         return;
      }
      else
      {
         if(filter_var($this->EditRTSP->Text, FILTER_VALIDATE_URL) === FALSE)
         {
            $this->Msg->Caption = "RTSP address is not a valid URL";
            return;
         }
      }

      $snapshot == "";
      if(($this->EditSnapshot->Text != "http://") & ($this->EditSnapshot->Text != ""))
      {
         $snapshot = $this->EditSnapshot->Text;
         if(filter_var($snapshot, FILTER_VALIDATE_URL) === FALSE)
         {
            $this->Msg->Caption = "Snapshot URL is not a valid";
            return;
         }
      }
      $cameraAddress = null;
      $cameras = null;
      if(file_exists($path . "cameras.xml"))
      {
         $cameras = simplexml_load_file($path . "cameras.xml");
      }
      else
      {
         $cameras = new SimpleXMLElement('<xml/>');
      }
      $login = "";
      $pass = "";
      $profileToken = "";
      $mediaurl = "";
      //check if the name is unique
      foreach($cameras->camera as $cam)
      {
         if(strcasecmp($cam->name, $this->EditCameraName->Text) == 0)
         {
            $login = (string)$cam->login;
            $pass = $dmauth->doDecrypt(urldecode((string)$cam->password));
            $profileToken = (string)$cam->profileToken;
            $mediaurl = (string)$cam->mediaurl;
            $dom = dom_import_simplexml($cam);
            $dom->parentNode->removeChild($dom);
         }
      }

      // add the camera to xml file
      $cam = $cameras->addChild('camera');
      $cam->addChild('name', $this->EditCameraName->Text);
      $cam->addChild('url', urlencode($this->EditRTSP->Text));
      $cam->addChild('snapshoturl', urlencode($snapshot));
      $cam->addChild("sound", $this->CheckBoxDoAudio->Checked);
      $cam->addChild("profileToken", $profileToken);
      $cam->addChild("login", $login);
      $cam->addChild("password", urlencode($dmauth->doEncrypt($pass)));
      $cam->addChild('mediaurl', urlencode($mediaurl));

      $cameras->saveXML($path . "cameras.xml");
      $this->Msg->Caption = "Camera \"" . $this->EditCameraName->Text . "\" saved.";
   }
   function PageManualCreate($sender, $params)
   {
      global $dm;
      $dm->ForceRefresh();
      $path = $dm->GetDataPath();
      $this->EditCameraName->Text = "";
      $this->EditCameraName->Enabled = true;
      $this->EditRTSP->Text = "rtsp://";
      $this->EditSnapshot->Text = "http://";
      $this->Msg->Caption = "";

      $this->EditCameraName->Text = urldecode($_GET['edit']);
      if($this->EditCameraName->Text != "")
      {
         if(file_exists($path . "cameras.xml"))
         {
            $cameras = simplexml_load_file($path . "cameras.xml");
            foreach($cameras->camera as $cam)
            {
               if(strcasecmp((string)$cam->name, $this->EditCameraName->Text) == 0)
               {
                  $this->EditRTSP->Text = urldecode($cam->url);
                  $this->EditSnapshot->Text = urldecode($cam->snapshoturl);
                  $this->CheckBoxDoAudio->Checked = (int)$cam->sound;
                  break;
               }
            }
         }
      }
   }
   function PageManualBeforeShow($sender, $params)
   {
      global $dmauth;
      //Before showing the page, execute the ZAuth component
      $dmauth->ZAuth->Execute();
   }
}

global $application;

global $PageManual;

//Creates the form
$PageManual = new PageManual($application);

//Read from resource file
$PageManual->loadResource(__FILE__);

//Shows the form
$PageManual->show();

?>