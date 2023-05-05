<?php
require_once("dm.php");

global $dm;
$datadir = $dm->GetDataPath();
$rtsp = "";
$app = "";
$infile_options = "";
$outfile_options = "";
$disable_audio = "";
$outfile_dir = "";
$sound = 0;

if(isset($_GET['url']) && !empty($_GET['url']))
{
  $rtsp = "\"" . urldecode((string)$_GET['url']) . "\"";
  $sound = 1;
}
else 
{
  if(file_exists($datadir . "cameras.xml"))
  {
    $cameras = simplexml_load_file($datadir . "cameras.xml");
    foreach($cameras->camera as $cam)
    {
      if(strcasecmp($cam->name, $_GET['cam']) == 0)
      {
         $rtsp = "\"" . urldecode((string)$cam->url) . "\"";
         $sound = (int)$cam->sound;
      }
    }
  }
}

$configdir = $dm->GetConfigPath();

if(file_exists($configdir . "streamer.xml"))
{
   $stream = simplexml_load_file($configdir . "streamer.xml");
   $app = (string)$stream->app->name;
   $disable_audio = (string)$stream->audio->disable;
   $infile_options = (string)$stream->hls->infile_options;
   $outfile_options = (string)$stream->hls->outfile_options;
   $outfile_dir = (string)$stream->hls->outfile_dir;
}

// if the process already exists, return the stream address
$processStr = exec("ps ax | grep -v grep | grep ffmpeg | grep " . $rtsp);
if($processStr == "")
{
   $ret = $dm->killall("ffmpeg");
   Proc_Close(Proc_Open("rm /dev/shm/*", Array(), $foo));
}
else
{
   $array = explode(" ", $processStr);
   foreach($array as $line)
   {
      if(substr($line, 0, strlen($outfile_dir)) === $outfile_dir)
      {
         // needed stream is still running, just return it.
         $streamfile = substr($line, strlen($outfile_dir));
         echo "/hls/" . $streamfile;
         exit;
      }
   }
}

// if the process doesn't exist (see above comment) start it
$fprefix = $dm->generateRandomString();
$fname = $outfile_dir . $fprefix . ".m3u8";
$ffmpeg = $app . " ";

if( ! $sound)
{
   $ffmpeg .= $disable_audio . " ";
}

$ffmpeg .= $infile_options . " ";
$ffmpeg .= $rtsp . " ";
$ffmpeg .= $outfile_options . " ";
$ffmpeg .= "\"" . $fname . "\"";
$ffmpeg .= " &";

//echo $ffmpeg . "/n";
Proc_Close(Proc_Open($ffmpeg, Array(), $foo));

$starttime = time();
$timeout = false;

while(!file_exists($fname) &&  !$timeout)
{
   if(exec("ps ax | grep -v grep | grep ffmpeg | grep " . $rtsp) == "")
   {
      $timeout = true;
   }
   else
   {
      if((time() - $starttime) > 20)
         $timeout = true;
      else
         sleep(1);
   }
}

if(!$timeout)
  echo "/hls/" . $fprefix . ".m3u8";
else
  echo "error";

?>