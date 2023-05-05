<?php
require_once("rpcl/rpcl.inc.php");
require_once("dm.php");
require_once("dmauth.php");
//Includes
use_unit("jquerymobile/forms.inc.php");
use_unit("extctrls.inc.php");
use_unit("stdctrls.inc.php");
use_unit("jquerymobile/jmobile.inc.php");
use_unit("checklst.inc.php");
use_unit("comctrls.inc.php");

//Class definition
class PageLive extends MPage
{
   public $ButtonStop = null;
   public $ButtonStart = null;
   public $ButtonSave = null;
   public $EditKey = null;
   public $ComboBoxProviders = null;
   public $ComboBoxCameras = null;
   public $MLabel3 = null;
   public $MLabel2 = null;
   public $MLabel1 = null;
   public $MLinkCancel = null;
   public $Msg = null;

   function EnableStreaming()
   {
      $this->ButtonStop->Enabled = true;
      $this->ButtonStart->Enabled = true;
      $this->ButtonSave->Enabled = true;
      $this->EditKey->Enabled = true;
   }
   function ButtonSaveClick($sender, $params)
   {
      global $dm;
      global $dmauth;
      $configpath = $dm->GetConfigPath();
      $items = $this->ComboBoxProviders->Items;
      // get the selected provider (this looks more complicated than it is, but only like this works well)
      foreach($items as $item)
      {
         if($item["Key"] == $this->ComboBoxProviders->ItemIndex)
         {
            $providername = $item["Value"];
            break;
         }
      }
      if($providername == "Please select...")
      {
         $this->Msg->Caption = "Select a provider first!";
         return;
      }
      if(file_exists($configpath . "streamer.xml"))
      {
         $providers = simplexml_load_file($configpath . "streamer.xml");
         $sxe = new SimpleXMLElement($providers->asXML());
         foreach($sxe->live->platform as $p)
         {
            if((string)$p->name == $providername)
            {
               unset($p->key);
               $p->addChild('key', urlencode($dmauth->doEncrypt($this->EditKey->Text)));
               break;
            }
         }
         $sxe->saveXML($configpath . "streamer.xml");
      }
   }
   function ComboBoxProvidersShow($sender, $params)
   {
      global $dm;
      $configpath = $dm->GetConfigPath();
      if(count($this->ComboBoxProviders->Items) == 0)
      {
         if(file_exists($configpath . "streamer.xml"))
         {
            $providers = simplexml_load_file($configpath . "streamer.xml");
            $j = 0;
            $items[$j]["Value"] = "Please select...";
            $items[$j]["Key"] = $j;
            for($i = 0; $i < count($providers->live->platform); $i++)
            {
               $j++;
               $items[$j]["Value"] = (string)$providers->live->platform[$i]->name;
               $items[$j]["Key"] = $j;
            }
            $this->ComboBoxProviders->Items = $items;
            $this->EnableStreaming();
         }
      }
   }
   function ComboBoxCamerasShow($sender, $params)
   {
      global $dm;
      $datapath = $dm->GetDataPath();
      if(file_exists($datapath . "cameras.xml"))
      {
         $cameras = simplexml_load_file($datapath . "cameras.xml");
         for($i = 0; $i < count($cameras->camera); $i++)
         {
            $items[$i]["Value"] = (string)$cameras->camera[$i]->name;
            $items[$i]["Key"] = $i;
         }
         $this->ComboBoxCameras->Items = $items;
      }
   }
   function ComboBoxProvidersChange($sender, $params)
   {
      global $dm;
      global $dmauth;
      $configpath = $dm->GetConfigPath();
      $this->EditKey->Text = "";
      // get the selected (this looks more complicated than it is, but only like this works well)
      $items = $this->ComboBoxProviders->Items;
      foreach($items as $item)
      {
         if($item["Key"] == $this->ComboBoxProviders->ItemIndex)
         {
            $provider = $item["Value"];
            break;
         }
      }
      if(file_exists($configpath . "streamer.xml"))
      {
         $providers = simplexml_load_file($configpath . "streamer.xml");
         foreach($providers->live->platform as $p)
         {
            if($p->name == $provider)
            {
               $this->EditKey->Text = $dmauth->doDecrypt(urldecode((string)$p->key));
               break;
            }
         }
      }
   }
   function ButtonStartClick($sender, $params)
   {
      global $dm;

      if($this->EditKey->Text == "")
      {
         $this->Msg->Caption = "Stream Key is needed for Live Broadcast.";
         return;
      }

      $configdir = $dm->GetConfigPath();

      // get the selected camera (this looks more complicated than it is, but only like this works well)
      $items = $this->ComboBoxCameras->Items;
      foreach($items as $item)
      {
         if($item["Key"] == $this->ComboBoxCameras->ItemIndex)
         {
            $camera = $item["Value"];
            break;
         }
      }

      // get the camera rtsp address
      $path = $dm->GetDataPath();
      $cameras = simplexml_load_file($path . "cameras.xml");
      foreach($cameras->camera as $cam)
      {
         if(strcasecmp((string)$cam->name, $camera) == 0)
         {
            $rtsp = urldecode($cam->url);
            $doaudio = (int)$cam->sound;
            break;
         }
      }

      // get the selected provider (this looks more complicated than it is, but only like this works well)
      $items = $this->ComboBoxProviders->Items;
      foreach($items as $item)
      {
         if($item["Key"] == $this->ComboBoxProviders->ItemIndex)
         {
            $provider = $item["Value"];
            break;
         }
      }

      if(file_exists($configdir . "streamer.xml"))
      {
         $stream = simplexml_load_file($configdir . "streamer.xml");
         $app = (string)$stream->app->name;
         $disable_audio = (string)$stream->audio->disable;
         foreach($stream->live->platform as $p)
         {
            if($p->name == $provider)
            {
               $infile_options = (string)$p->infile_options;
               $outfile_options = (string)$p->outfile_options;
               break;
            }
         }
      }
      else
         exit;

      // if the process already exists, return the stream address
      $processStr = exec("ps ax | grep -v grep | grep ffmpeg | grep '" . $rtsp . "'");
      if($processStr != "")
      {
         $this->Msg->Caption = "Already ON AIR!";
      }
      else
      {
         $dm->killall("ffmpeg");
         // if the process doesn't exist (see above comment) start it
         $ffmpeg = $app . " ";

         if( ! $sound)
         {
            $ffmpeg .= $disable_audio . " ";
         }

         $ffmpeg .= $infile_options . " \"";
         $ffmpeg .= $rtsp . "\" ";
         $ffmpeg .= $outfile_options;
         $ffmpeg .= $this->EditKey->Text;
         $ffmpeg .= " &";

         Proc_Close(Proc_Open($ffmpeg, Array(), $foo));
         $this->Msg->Caption = "ON AIR!";
      }
   }
   function PageLiveCreate($sender, $params)
   {
      $this->Msg->Caption = "";
   }
   function ButtonStopClick($sender, $params)
   {
      global $dm;
      $dm->killall("ffmpeg");
      $this->Msg->Caption = "Live stream terminated.";
   }
   function PageLiveBeforeShow($sender, $params)
   {
      global $dmauth;
      //Before showing the page, execute the ZAuth component
      $dmauth->ZAuth->Execute();
   }
}

global $application;

global $PageLive;

//Creates the form
$PageLive = new PageLive($application);

//Read from resource file
$PageLive->loadResource(__FILE__);

//Shows the form
$PageLive->show();

?>