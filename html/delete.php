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
class DeletePage extends MPage
{
   public $MLinkCancel = null;
   public $EntryBox = null;
   public $Delete = null;
   public $MLabel1 = null;

   function DeletePageCreate($sender, $params)
   {
      global $dm;
      $dm->ForceRefresh();
      if( ! empty($_GET['id']))
      {
         $this->EntryBox->Text = urldecode($_GET['id']);
      }
   }

   function DeleteClick($sender, $params)
   {
      global $dm;
      $path = $dm->GetDataPath();
      if(file_exists($path . "cameras.xml"))
      {
         $cameras = simplexml_load_file($path . "cameras.xml");
         foreach($cameras->camera as $cam)
         {
            if(strcasecmp((string)$cam->name, $this->EntryBox->Text) == 0)
            {
               $dom = dom_import_simplexml($cam);
               $dom->parentNode->removeChild($dom);
            }
         }
         $cameras->saveXML($path . "cameras.xml");
      }
      mobileRedirect("menu.php");
   }
    function DeletePageBeforeShow($sender, $params)
    {
      global $dmauth;
      //Before showing the page, execute the ZAuth component
      $dmauth->ZAuth->Execute();
    }
}

global $application;

global $DeletePage;

//Creates the form
$DeletePage = new DeletePage($application);

//Read from resource file
$DeletePage->loadResource(__FILE__);

//Shows the form
$DeletePage->show();

?>