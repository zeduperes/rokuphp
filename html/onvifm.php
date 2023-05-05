<?php
require_once("rpcl/rpcl.inc.php");
require_once("lib/class.ponvif.php");
require_once("dm.php");
require_once("dmauth.php");
//Includes
use_unit("jquerymobile/forms.inc.php");
use_unit("extctrls.inc.php");
use_unit("stdctrls.inc.php");
use_unit("jquerymobile/jmobile.inc.php");

//Class definition
class OnvifPage extends MPage
{
   public $EditCameraName = null;
   public $MLabel5 = null;
   public $CheckBoxDoAudio = null;
   public $ButtonAddCamera = null;
   public $Msg = null;
   public $MLabel4 = null;
   public $ComboBoxProfiles = null;
   public $ButtonCheckCamera = null;
   public $MLabel3 = null;
   public $MLabel2 = null;
   public $EditPassword = null;
   public $EditLogin = null;
   public $ListBoxONVIF = null;
   public $MLabel1 = null;
   public $ButtonONVIFScan = null;
   public $MLinkCancel = null;
   function ButtonCheckCameraClick($sender, $params)
   {
      try
      {
         $this->EnableAdd(false);
         global $dm;
         $path = $dm->GetDataPath();
         $camarray = json_decode(file_get_contents($path . "temp.json"), true);
         $this->ComboBoxProfiles->Clear();
         $onvif = new Ponvif();
         $onvif->setUsername($this->EditLogin->Text);
         $onvif->setPassword($this->EditPassword->Text);

         // get the selected (this looks more complicated than it is, but only like this works well)
         $items = $this->ListBoxONVIF->Items;
         foreach($items as $item)
         {
            if($item["Key"] == $this->ListBoxONVIF->ItemIndex)
            {
               $selectedip = $item["Value"];
               break;
            }
         }

         $onvif->setIPAddress($selectedip);
         $onvif->setMediaUri($camarray[$selectedip]);

         $onvif->initialize();
         $sources = $onvif->getSources();
         if(count($sources[0]) > 0)
         {
            $j = 0;
            for($i = 0; $i < count($sources[0]); $i++)
            {
               if($sources[0][$i]['encoding'] == 'H264')
               {
                  $profileToken = $sources[0][$i]['profiletoken'];
                  $profileitems[$j]["Value"] = $profileToken;
                  $profileitems[$j]["Key"] = $j;
                  $j++;
               }
            }
            $this->ComboBoxProfiles->Items = $profileitems;
            $this->EnableAdd(true);
         }
      }catch(Exception$e)
      {
         $this->Msg->Caption = "Make sure the ONVIF login/password are correct";
      }
   }
   function ButtonAddCameraClick($sender, $params)
   {
      global $dm;
      $path = $dm->GetDataPath();
      if($this->EditCameraName->Text == "")
      {
         $this->Msg->Caption = "Camera name is required and needs to be unique";
         return;
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
      //check if the name is unique
      foreach($cameras->camera as $cam)
      {
         if(strcasecmp($cam->name, $this->EditCameraName->Text) == 0)
         {
            $this->Msg->Caption = "Camera name needs to be unique";
            return;
         }
      }

      // get the selected (this looks more complicated than it is, but only like this works well)
      $items = $this->ListBoxONVIF->Items;
      foreach($items as $item)
      {
         if($item["Key"] == $this->ListBoxONVIF->ItemIndex)
         {
            $ip = $item["Value"];
            break;
         }
      }

      $camarray = json_decode(file_get_contents($path . "temp.json"), true);
      $onvif = new Ponvif();
      $onvif->setUsername($this->EditLogin->Text);
      $onvif->setPassword($this->EditPassword->Text);
      $onvif->setIPAddress($ip);
      $onvif->setMediaUri($camarray[$ip]);
      $onvif->initialize();

      // get the selected (this looks more complicated than it is, but only like this works well)
      $items = $this->ComboBoxProfiles->Items;
      foreach($items as $item)
      {
         if($item["Key"] == $this->ComboBoxProfiles->ItemIndex)
         {
            $profileToken = $item["Value"];
            break;
         }
      }

      $streamUri = $onvif->media_GetStreamUri($profileToken);
      $snapshotUri = $onvif->media_GetSnapshotUri($profileToken);

      if(($this->EditLogin->Text != "") && ($this->EditPassword->Text != ""))
      {
         $auth = $this->EditLogin->Text . ":" . $this->EditPassword->Text . "@" . $ip;
      }
      else
      {
         if($this->EditLogin->Text != "")
         {
            $auth = $this->EditLogin->Text . "@" . $ip;
         }
         else
         {
            $auth = $ip;
         }
      }
      $streamUri = str_replace($ip, $auth, $streamUri);
      $snapshotUri = str_replace($ip, $auth, $snapshotUri);
      // add the camera to xml file
      global $dmauth;
      $cam = $cameras->addChild('camera');
      $cam->addChild('name', $this->EditCameraName->Text);
      $cam->addChild('url', urlencode($streamUri));
      $cam->addChild('snapshoturl', urlencode($snapshotUri));
      $cam->addChild("sound", $this->CheckBoxDoAudio->Checked);
      $cam->addChild("profileToken", $profileToken);
      $cam->addChild("login", $this->EditLogin->Text);
      $cam->addChild("password", urlencode($dmauth->doEncrypt($this->EditPassword->Text)));
      $cam->addChild('mediaurl', urlencode($camarray[$ip]));
      $cameras->saveXML($path . "cameras.xml");
      $this->Msg->Caption = "Camera \"" . $this->EditCameraName->Text . "\" added successfully.";
   }
   private function EnableAdd($enable)
   {
      $this->EditCameraName->Enabled = $enable;
      $this->CheckBoxDoAudio->Enabled = $enable;
      $this->ComboBoxProfiles->Enabled = $enable;
      $this->ButtonAddCamera->Enabled = $enable;
   }
   private function EnableGetProfiles($enable)
   {
      $this->ButtonCheckCamera->Enabled = $enable;
      $this->ListBoxONVIF->Enabled = $enable;
   }
   function ButtonONVIFScanClick($sender, $params)
   {
      $this->EnableGetProfiles(false);
      global $dm;
      $path = $dm->GetDataPath();
      $this->ListBoxONVIF->Clear();

      $onvif = new Ponvif();
      $result = $onvif->discover();
      if(count($result) > 0)
      {
         for($i = 0; $i < count($result); $i++)
         {
            $items[$i]["Value"] = $result[$i]["IPAddr"];
            $items[$i]["Key"] = $i;
            // XAddrs can have multiple values, use just the one that maches the camera IP
            $pieces = explode(" ", $result[$i]["XAddrs"]);
            // In some cases you need to set MediaUrl manually. You can find it in "XAddrs" key (see above).
            foreach($pieces as $murl)
            {
               if(strpos($murl, $result[$i]["IPAddr"]) !== false)
               {
                  $camarray[$result[$i]["IPAddr"]] = $murl;
               }
            }
         }
         $this->ListBoxONVIF->Items = $items;
         $this->EnableGetProfiles(true);
      }
      file_put_contents($path . "temp.json", json_encode($camarray));
   }
   function OnvifPageBeforeShow($sender, $params)
   {
      global $dmauth;
      //Before showing the page, execute the ZAuth component
      $dmauth->ZAuth->Execute();
   }
}

global $application;

global $OnvifPage;

//Creates the form
$OnvifPage = new OnvifPage($application);

//Read from resource file
$OnvifPage->loadResource(__FILE__);

//Shows the form
$OnvifPage->show();

?>