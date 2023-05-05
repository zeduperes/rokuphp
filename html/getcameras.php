<?php
require_once("dm.php");

global $dm;
$datadir = $dm->GetDataPath();

$response = "";

if(file_exists($datadir . "cameras.xml"))
{
   $cameras = simplexml_load_file($datadir . "cameras.xml");
   foreach($cameras->camera as $cam)
   {
      $response .= "<item title = \"" . (string)$cam->name . "\" command = \"hls\" description = \"HLS (Live Stream)\" getpicture = \"" . (string)$cam->snapshoturl . "\" />";
   }
}

echo $response;
?>