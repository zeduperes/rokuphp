<?php
require_once("rpcl/rpcl.inc.php");
require_once("dmauth.php");
require_once("dm.php");
//Includes
use_unit("jquerymobile/forms.inc.php");
use_unit("extctrls.inc.php");
use_unit("stdctrls.inc.php");
use_unit("jquerymobile/jmobile.inc.php");


//Class definition
class PageIndex extends MPage
{
    public $MLinkBroadcast = null;
    public $MLinkManual = null;

    public $ListBoxAddedCameras = null;
    public $MLinkONVIF = null;
    function ListBoxAddedCamerasShow($sender, $params)
    {
      global $dm;
      $path = $dm->GetDataPath();

      if(file_exists($path . "cameras.xml"))
      {
         $cameras = simplexml_load_file($path . "cameras.xml");
         for($i = 0; $i < count($cameras->camera); $i++)
         {
            $items[$i]['Icon'] = 'siDelete';
            $items[$i]['Caption'] = (string)$cameras->camera[$i]->name;
            $items[$i]['ExtraButtonLink'] =  'delete.php?id=' . urlencode((string)$cameras->camera[$i]->name) ;
            $items[$i]['Link'] =  'manualm.php?edit=' . urlencode((string)$cameras->camera[$i]->name) ;
         }
      }

      $this->ListBoxAddedCameras->Items = $items;
    }
    function PageIndexBeforeShow($sender, $params)
    {
      global $dmauth;
      //Before showing the page, execute the ZAuth component
      $dmauth->ZAuth->Execute();
    }
}

global $application;

global $PageIndex;

//Creates the form
$PageIndex=new PageIndex($application);

//Read from resource file
$PageIndex->loadResource(__FILE__);

//Shows the form
$PageIndex->show();

?>