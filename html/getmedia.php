<?php

require './lib/class.ponvif.php';

$onvif = new Ponvif();
$onvif->setUsername('root');
$onvif->setPassword('v1nch3Iau');
$onvif->setIPAddress('192.168.1.58');

// In some cases you need to set MediaUrl manually. You can find it in "XAddrs" key (see above).
// $onvif->setMediaUri('http://192.168.1.108:3388/onvif/device_service');

try
{
        $onvif->initialize();

        $sources = $onvif->getSources();
        var_dump($sources);
        //$profileToken = $sources[0][0]['profiletoken'];
        ///$mediaUri = $onvif->media_GetStreamUri($profileToken);

        //var_dump($mediaUri);
        //$ptz = $onvif->core_GetCapabilities();
        //var_dump($ptz);
}
catch(Exception $e)
{

}

?>
