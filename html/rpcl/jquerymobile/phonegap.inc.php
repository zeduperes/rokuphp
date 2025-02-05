<?php
require_once("rpcl/rpcl.inc.php");
use_unit("classes.inc.php");
use_unit("stdctrls.inc.php");
use_unit('jquerymobile/jmobile.common.inc.php');

/**
 * This is the Base class for all PhoneGap Components
 * It inserts the headers neede to run the Phonegap API
 * It also checks sets teh variable {$this->Name}Active to true if the Device can execute the PhoneGap API
 * To call any PhoneGap Api function  {$this->Name}Active has to be evaluated to verify that the system is ready.
 */

class BaseDevice extends Component
{
    // Documented in the parent.
    function dumpHeaderCode()
    {
        if( ! defined('PHONEGAP_JS'))
        {
            echo "<script src=\"" . RPCL_HTTP_PATH . PHONEGAP_FILE . "\"></script>\n";
            define('PHONEGAP_JS', 1);
        }
    }

    /**
     * Returns a JavaScript declaration of an empty function when an empty string is provided. Otherwise, it retuns
     * the provided string, untouched.
     *
     * @param string $event Definition of a JavaScript event handler, or emptry string.
     *
     * @internal
     */
    function checkEmptyFunction($event)
    {
        if($event == "")
            return "function(){}";
        else
            return $event;
    }

    /**
     * Prints the code to bind the handler for the provided event to the actual jQuery event.
     *
     * If there is no handler for the event, the actual jQuery event is binded to an empty function.
     *
     * @param string $event Name of the class property that contains the event handler. For example: "_jsonclick".
     *
     * @internal
     */
    function bindEvent($event)
    {
        $jqEvent = substr($event, 3);

        echo "  {$this->Name}.$jqEvent=".$this->checkEmptyFunction($this->$event).";\n";
    }

    /**
     * Prints the code defining the provided JavaScript function with optional OnSuccess and OnError event handlers.
     *
     * @param string $function  Function name.
     * @param array  $params    Array of parameters and their default values for the function.
     *
     * @internal
     */
    function dumpEventFunction($function,$params=array())
    {
        $event = strtolower("_json{$function}");
        $eventError= strtolower("_json{$function}error");
        $functionTitle=ucfirst($function);

        $textParam=implode(',',array_keys($params));

        echo "var {$this->Name}{$functionTitle}=function($textParam) {\n";
        echo "  if({$this->Name}){\n";
        foreach($params as $param=>$value)
        echo "    var $param = $param || $value;\n";
        if($textParam!="")
        $textParam.=",";
        echo "    {$this->Name}.{$function}($textParam".$this->checkEmptyFunction($this->$event).",".$this->checkEmptyFunction($this->$eventError).");\n";
        echo "  };\n";
        echo "}\n";
    }

    /**
     * Returns the string "ComponentName=true;\n", where component name is the value of the Name property of the
     * component.
     *
     * @internal
     */
    function ondeviceready()
    {
        $output = "";
        $output .= " {$this->_name}=true;\n";
        return $output;
    }

    // Documented in the parent.
    function dumpJavascript()
    {
        echo "var {$this->Name}=null;\n";
    }

    /**
     * Prints the JavaScript code to bind the event handler for the provided event to the given element of the DOM.
     *
     * @param string $event     Name of the class property that contains the event handler. For example: "_jsonclick".
     * @param string $dom       Element of the DOM.
     *
     * @internal
     */
    function addEventListener($event,$dom="document")
    {
        if($this->$event!="")
        {
            $jqEvent = substr($event, 5);
            if(substr($jqEvent,0,2)=="bb")
            $jqEvent = substr($jqEvent, 2);
            return "$dom.addEventListener(\"$jqEvent\", ".$this->$event.", false);\n";
        }
    }
}

/**
 * Base class for all the Physical events like acceleration,GPS,Compass
 * This components can be activated and desactivated.
 * Once activated, they'll be firing the event JsOnChange every Interval
 * The javascript function to activate them is: {$this->Name}Activate();
 * And the javascript function to deactivate them: {$this->Name}Deactivate();
 */
class CustomMPhysics extends BaseDevice
{
   protected $_active = 1;
   protected $_interval = 10000;
   protected $_jsonchange = "";
   protected $_jsonerror = "";

   /**
    * Interval in miliseconds that the component will wait to get data again
    */
   function readInterval()    {return $this->_interval;}
   function writeInterval($value)    {$this->_interval = $value;}
   function defaultInterval()    {return 10000;}

   /**
    * Indicates the status of the component. If set to true will be getting information every Interval
    */
   function readActive()    {return $this->_active;}
   function writeActive($value)    {$this->_active = $value;}
   function defaultActive()    {return 1;}

   /**
    * Event fired every time the component read data from the device
    */
   function readjsOnChange()    {return $this->_jsonchange;}
   function writejsOnChange($value)    {$this->_jsonchange = $value;}
   function defaultjsOnChange()    {return "";}

   /**
    * Event fired every tie the componenet fails to retrieve data from the device
    */
   function readjsOnError()    {return $this->_jsonerror;}
   function writejsOnError($value)    {$this->_jsonerror = $value;}
   function defaultJsOnError()    {return "";}

   /**
    * Prints the JavaScript code to enable and disable the given element of the PhoneGap navigator object.
    *
    * @param string $element    Target element of the PhoneGap navigator object.
    * @param string $function   Name of the element's method to be called when the ComponentNameEnabled() function is called.
    * @param string $options    JavaScript code for an array of options to be passed to the $function.
    *
    * @internal
    */
   function DumpJSFunctions($element, $function, $options)
   {

      echo "var {$this->Name}Activate=function(options) {\n";
      echo "   var options = options || $options;\n";
      echo "   {$this->Name}=navigator.$element.$function(" . $this->checkEmptyFunction($this->_jsonchange) . "," . $this->checkEmptyFunction($this->_jsonerror) . ",options);\n";
      echo "}\n";
      echo "var {$this->Name}Deactivate=function() {\n";
      echo "   navigator.$element.clearWatch({$this->Name});\n";
      echo "   {$this->Name}=null;\n";
      echo "}\n";
   }

   // Documented in the parent.
   function dumpJavascript()
   {
      parent::dumpJavascript();

      $this->dumpJSEvent($this->_jsonchange);
      $this->dumpJSEvent($this->_jsonerror);
   }

   // Documented in the parent.
   function ondeviceready()
   {
      $output = "";
      if($this->_active)
         $output .= "  {$this->Name}Activate();\n";
      return $output;
   }
}

/**
 * Base class for watchers, PhoneGap components that can watch a value or values.
 */
class MPhysics extends CustomMPhysics
{
    // Documented in the parent.
    function getInterval()    {return $this->readinterval();}
    function setInterval($value)    {$this->writeinterval($value);}

    // Documented in the parent.
    function getActive()    {return $this->readactive();}
    function setActive($value)    {$this->writeactive($value);}

    // Documented in the parent.
    function getjsOnChange()    {return $this->readjsOnChange();}
    function setjsOnChange($value)    {$this->writejsOnChange($value);}

    // Documented in the parent.
    function getjsOnError()    {return $this->readjsonerror();}
    function setjsOnError($value)    {$this->writejsonerror($value);}

}

/**
 * Base class for PhoneGap components that can watch the acceleration of a mobile device.
 */
class CustomMAccelerometer extends MPhysics
{
    // Documented in the parent.
    function dumpJavascript()
    {
        parent::dumpJavascript();

        $options = "{frequency:$this->_interval}";
        $this->DumpJSFunctions("accelerometer", "watchAcceleration", $options);
    }

}


class MAccelerometer extends CustomMAccelerometer
{

   /**
    * Event fired when the device retrieves the Accelerometer data succesfully.
    * It returns the variable "event" that hold 3 properties
    * event.x Acceleration in X Axis range (0..1)
    * event.y Acceleration in Y Axis range (0..1)
    * event.z Acceleration in Z Axis range (0..1)
    */
   function getjsOnChange()    {return $this->readjsOnChange();}
   function setjsOnChange($value)    {$this->writejsOnChange($value);}

}

class CustomMCompass extends MPhysics
{

   function dumpJavascript()
   {
      parent::dumpJavascript();

      $options = "{frequency:$this->_interval}";
      $this->DumpJSFunctions("compass", "watchHeading", $options);

   }

}

class MCompass extends CustomMCompass
{

   /**
    * Event fired when the device retrieves the Compass data succesfully.
    * It returns the variable "event" that holds the degree in a range (0..359.99)
    */
   function getjsOnChange()    {return $this->readjsOnChange();}
   function setjsOnChange($value)    {$this->writejsOnChange($value);}
}

class CustomMGeolocation extends MPhysics
{
   protected $_highaccuracy = 0;
   protected $_timeout = 10000;
   protected $_maxage = 10000;

   /**
    *  Sets when the data stored has to be renewed
    *  In the future will replace the "Interval" value
    */
   function readMaxAge()    {return $this->_maxage;}
   function writeMaxAge($value)    {$this->_maxage = $value;}
   function defaultMaxAge()    {return 10000;}

   /**
    * Set the time that the device will wait for a response
    */
   function readTimeout()    {return $this->_timeout;}
   function writeTimeout($value)    {$this->_timeout = $value;}
   function defaultTimeout()    {return 10000;}

   /**
    * Set it to true to get higes accuracy on the results
    */
   function readHighAccuracy()    {return $this->_highaccuracy;}
   function writeHighAccuracy($value)    {$this->_highaccuracy = $value;}
   function defaultHighAccuracy()    {return 0;}



   function dumpJavascript()
   {
      parent::dumpJavascript();

      $options = "{frequency:$this->_interval,enableHighAccuracy:" . ($this->_highaccuracy? 'true': 'false') . ",timeout:$this->_timeout,maximumAge:$this->_maxage}";
      $this->DumpJSFunctions("geolocation", "watchPosition", $options);

   }

}

class MGeolocation extends CustomMGeolocation
{
   function getMaxAge()    {return $this->readmaxage();}
   function setMaxAge($value)    {$this->writemaxage($value);}

   function getTimeout()    {return $this->readtimeout();}
   function setTimeout($value)    {$this->writetimeout($value);}

   function getHighAccuracy()    {return $this->readhighaccuracy();}
   function setHighAccuracy($value)    {$this->writehighaccuracy($value);}

   /**
    * Event fired when the device retrieves the Geolocation data succesfully.
    * It returns the variable "event" that hold 2 properties
    * event.coordinates, with the following properties:
    *   event.coords.latitude: Latitude in decimal degrees. (Number)
    *   event.coords.longitude: Longitude in decimal degrees. (Number)
    *   event.coords.altitude: Height of the position in meters above the ellipsoid. (Number)
    *   event.coords.accuracy: Accuracy level of the latitude and longitude coordinates in meters. (Number)
    *   event.coords.altitudeAccuracy: Accuracy level of the altitude coordinate in meters. (Number)
    *   event.coords.heading: Direction of travel, specified in degrees counting clockwise relative to the true north. (Number)
    *   event.coords.speed: Current ground speed of the device, specified in meters per second. (Number)
    * event.timestamp: Creation timestamp in miliseconds
    */
   function getjsOnChange()    {return $this->readjsOnChange();}
   function setjsOnChange($value)    {$this->writejsOnChange($value);}
}

class CustomMPageEvents extends BaseDevice
{
   protected $_jsondeviceready = "";
   protected $_jsonpause = "";
   protected $_jsonresume = "";
   protected $_jsononline="";
   protected $_jsonoffline="";
   protected $_jsonbatterycriticall="";
   protected $_jsonbatterylow="";
   protected $_jsonbatterystatus="";

   /**
   * Fired when there is a change in the battery status
   */
   function readjsOnBatterystatus() { return $this->_jsonbatterystatus; }
   function writejsOnBatterystatus($value) { $this->_jsonbatterystatus=$value; }
   function defaultjsOnBatterystatus() { return ""; }

   /**
   * Fired when the battery level is low
   */
   function readjsOnBatterylow() { return $this->_jsonbatterylow; }
   function writejsOnBatterylow($value) { $this->_jsonbatterylow=$value; }
   function defaultjsOnBatterylow() { return ""; }

   /**
   * Fired when the battery level is too low
   */
   function readjsOnBatterycriticall() { return $this->_jsonbatterycriticall; }
   function writejsOnBatterycriticall($value) { $this->_jsonbatterycriticall=$value; }
   function defaultjsOnBatterycriticall() { return ""; }


   /**
   * Fired when de device goes offline
   */
   function readjsOnOffline() { return $this->_jsonoffline; }
   function writejsOnOffline($value) { $this->_jsonoffline=$value; }
   function defaultjsOnOffline() { return ""; }


   /**
   * Fired when the devie is online
   */
   function readjsOnOnline() { return $this->_jsononline; }
   function writejsOnOnline($value) { $this->_jsononline=$value; }
   function defaultjsOnOnline() { return ""; }


   /**
    * Fired whe the application is put into the background
    */
   function readjsOnResume()    {return $this->_jsonresume;}
   function writejsOnResume($value)    {$this->_jsonresume = $value;}
   function defaultJsOnResume()    {return "";}

   /**
    * Fired when the application is retrieved from the background
    */
   function readjsOnPause()    {return $this->_jsonpause;}
   function writejsOnPause($value)    {$this->_jsonpause = $value;}
   function defaultJsOnPause()    {return "";}

   /**
    *  Fired when the PhoneGap environment is set and tha app is running in a PhoneGap enabled device
    */
   function readjsOnDeviceReady()    {return $this->_jsondeviceready;}
   function writejsOnDeviceReady($value)    {$this->_jsondeviceready = $value;}
   function defaultJsOnDeviceReady()    {return "";}

   function dumpJavascript()
   {
      $this->dumpJSEvent($this->_jsondeviceready);
      $this->dumpJSEvent($this->_jsonpause);
      $this->dumpJSEvent($this->_jsonresume);
     /* $this->dumpJSEvent($this->_jsononline);
      $this->dumpJSEvent($this->_jsonoffline);
      $this->dumpJSEvent($this->_jsonbatterycriticall);
      $this->dumpJSEvent($this->_jsonbatterylow);
      $this->dumpJSEvent($this->_jsonbatterystatus);  */
   }

   function ondeviceready()
   {
      $output="";
      if($this->_jsondeviceready!="")
        $output.=$this->_jsondeviceready."();\n";

      $output.=$this->addEventListener('_jsonpause');
      $output.=$this->addEventListener('_jsonresume');
      /*$output.=$this->addEventListener('_jsononline');
      $output.=$this->addEventListener('_jsonoffline');
      $output.=$this->addEventListener('_jsonbatterycriticall','window');
      $output.=$this->addEventListener('_jsonbatterylow','window');
      $output.=$this->addEventListener('_jsonbatterystatus','window'); */
      return $output;
   }

}

class MPageEvents extends CustomMPageEvents
{

   function getjsOnDeviceReady()    {return $this->readjsondeviceready();}
   function setjsOnDeviceReady($value)    {$this->writejsondeviceready($value);}

   function getjsOnPause()    {return $this->readjsonpause();}
   function setjsOnPause($value)    {$this->writejsonpause($value);}

   function getjsOnResume()    {return $this->readjsonresume();}
   function setjsOnResume($value)    {$this->writejsonresume($value);}

   /*function getjsOnOnline() { return $this->readjsononline(); }
   function setjsOnOnline($value) { $this->writejsononline($value); }

   function getjsOnOffline() { return $this->readjsonoffline(); }
   function setjsOnOffline($value) { $this->writejsonoffline($value); }

   function getjsOnBatterycriticall() { return $this->readjsonbatterycriticall(); }
   function setjsOnBatterycriticall($value) { $this->writejsonbatterycriticall($value); }

   function getjsOnBatteryLow() { return $this->readjsonbatterylow(); }
   function setjsOnBatteryLow($value) { $this->writejsonbatterylow($value); }

   function getjsOnBatterystatus() { return $this->readjsonbatterystatus(); }
   function setjsOnBatterystatus($value) { $this->writejsonbatterystatus($value); }  */
}

class CustomMPageExtraEvents extends BaseDevice
{
   protected $_jsonbackbutton = "";
   protected $_jsonmenubutton = "";
   protected $_jsonsearchbutton = "";
   protected $_jsonstartcallbutton="";
   protected $_jsonendcallbutton="";
   protected $_jsonvolumeupbutton ="";
   protected $_jsonvolumedownbutton="";

   /**
   * Fired when the Volume Down button is pressed in a Blackberry device
   */
   function readjsOnVolumeDownButton() { return $this->_jsonvolumedownbutton; }
   function writejsOnVolumeDownButton($value) { $this->_jsonvolumedownbutton=$value; }
   function defaultJsOnVolumeDownButton() { return ""; }

   /**
   * Fired when the Volume Up button is pressed in a Blackberry device
   */
   function readjsOnVolumeUpButton () { return $this->_jsonvolumeupbutton ; }
   function writejsOnVolumeUpButton ($value) { $this->_jsonvolumeupbutton =$value; }
   function defaultJsOnVolumeUpButton () { return ""; }

   /**
   * Fired when the end call button is pressed in a Blackberry device
   */
   function readjsOnEndCallbutton() { return $this->_jsonendcallbutton; }
   function writejsOnEndCallbutton($value) { $this->_jsonendcallbutton=$value; }
   function defaultJsOnEndCallbutton() { return ""; }


   /**
   * Fired when the start call button is pressed in a Blackberry device
   */
   function readjsOnStartCallButton() { return $this->_jsonstartcallbutton; }
   function writejsOnStartCallButton($value) { $this->_jsonstartcallbutton=$value; }
   function defaultJsOnStartCallButton() { return ""; }


   /**
    * Fired when the search button is pressed in an Android device
    */
   function readjsOnSearchButton()    {return $this->_jsonsearchbutton;}
   function writejsOnSearchButton($value)    {$this->_jsonsearchbutton = $value;}
   function defaultjsOnSearchButton()    {return "";}

   /**
    *  Fired when the menu button is pressed in an Android device
    */
   function readjsOnMenuButton()    {return $this->_jsonmenubutton;}
   function writejsOnMenuButton($value)    {$this->_jsonmenubutton = $value;}
   function defaultJsOnMenuButton()    {return "";}

   /**
    * Fired when the back button is clicked in an Android Device
    */
   function readjsOnBackButton()    {return $this->_jsonbackbutton;}
   function writejsOnBackButton($value)    {$this->_jsonbackbutton = $value;}
   function defaultJsOnBackButton()    {return "";}


   function dumpJavascript()
   {
      $this->dumpJSEvent($this->_jsonsearchbutton);
      $this->dumpJSEvent($this->_jsonmenubutton);
      $this->dumpJSEvent($this->_jsonbackbutton);
     /* $this->dumpJSEvent($this->_jsonstartcallbutton);
      $this->dumpJSEvent($this->_jsonendcallbutton);
      $this->dumpJSEvent($this->_jsonvolumeupbutton);
      $this->dumpJSEvent($this->_jsonvolumedownbutton); */
   }

   function ondeviceready()
   {
      $output="";
      $output.=$this->addEventListener('_jsonsearchbutton');
      $output.=$this->addEventListener('_jsonmenubutton');
      $output.=$this->addEventListener('_jsonbackbutton');
      /*$output.=$this->addEventListener('_jsonstartcallbutton');
      $output.=$this->addEventListener('_jsonendcallbutton');
      $output.=$this->addEventListener('_jsonvolumeupbutton');
      $output.=$this->addEventListener('_jsonvolumedownbutton');    */
      return $output;
   }
}

class MPageExtraEvents extends CustomMPageExtraEvents
{

   function getjsOnBackButton()    {return $this->readjsonbackbutton();}
   function setjsOnBackButton($value)    {$this->writejsonbackbutton($value);}

   function getjsOnMenuButton()    {return $this->readjsonmenubutton();}
   function setjsOnMenuButton($value)    {$this->writejsonmenubutton($value);}

   function getjsOnSearchButton()    {return $this->readjsonsearchbutton();}
   function setjsOnSearchButton($value)    {$this->writejsonsearchbutton($value);}

   /*function getjsOnStartCallButton() { return $this->readjsonstartcallbutton(); }
   function setjsOnStartCallButton($value) { $this->writejsonstartcallbutton($value); }

   function getjsOnEndCallButton() { return $this->readjsonendcallbutton(); }
   function setjsOnEndCallButton($value) { $this->writejsonendcallbutton($value); }

   function getjsOnVolumeUpButton() { return $this->readjsonvolumeupbutton(); }
   function setjsOnVolumeUpButton($value) { $this->writejsonvolumeupbutton($value); }

   function getjsOnVolumeDownButton() { return $this->readjsonvolumedownbutton(); }
   function setjsOnVolumeDownButton($value) { $this->writejsonvolumedownbutton($value); } */
}

class CustomMCamera extends BaseDevice
{
   protected $_allowedit = 0;
   protected $_sourcetype = "stCamera";
   protected $_destinationtype = "dtFileUri";
   protected $_quality = 50;
   protected $_jsonerror = "";
   protected $_jsonsuccess = "";

   /**
    * Allow editing the image before saving it. Not supported in Android devices
    */
   function readAllowEdit()    {return $this->_allowedit;}
   function writeAllowEdit($value)    {$this->_allowedit = $value;}
   function defaultAllowEdit()    {return 0;}

   /**
    * Indicate the image source it can be the camara or the device's internal storage
    */
   function readSourceType()    {return $this->_sourcetype;}
   function writeSourceType($value)    {$this->_sourcetype = $value;}
   function defaultSourceType()    {return "stCamera";}

   /**
    * Indicates how the image is going to be retrieved, as an image binary coded in Base64 or an URI
    */
   function readDestinationType()    {return $this->_destinationtype;}
   function writeDestinationType($value)    {$this->_destinationtype = $value;}
   function defaultDestinationType()    {return "dtFileUri";}

   /**
    * Indicates the image's quality
    */
   function readQuality()    {return $this->_quality;}
   function writeQuality($value)    {$this->_quality = $value;}
   function defaultQuality()    {return 50;}

   /**
    * Event fired when the camera retrieves succesfully an image
    * Returns the variable "event". It can hold:
    *   The image URI if "dtFileUri" is selected in DestinationType
    *   The image data coded as Base64 if "dtDataUrl" is selected in DestinationType
    */
   function readjsOnSuccess()    {return $this->_jsonsuccess;}
   function writejsOnSuccess($value)    {$this->_jsonsuccess = $value;}
   function defaultJsOnSuccess()    {return "";}

   /**
    * Event fired every tie the componenet fails to retrieve data from the device
    * Returns the error message in the variable "event"
    */
   function readjsOnError()    {return $this->_jsonerror;}
   function writejsOnError($value)    {$this->_jsonerror = $value;}
   function defaultJsOnError()    {return "";}

   function dumpJavascript()
   {
      parent::dumpJavascript();

      $this->dumpJSEvent($this->_jsonsuccess);
      $this->dumpJSEvent($this->_jsonerror);

      switch($this->_sourcetype)
      {
         case "stPhotoLibrary":
            $sourceType = "PHOTOLIBRARY";
            break;
         case "stCamera":
            $sourceType = "CAMERA";
            break;
         case "stSavedPhotoAlbum":
            $sourceType = "SAVEDPHOTOALBUM";
            break;
      }

      $sourceType = "navigator.camera.PictureSourceType.$sourceType";

      switch($this->_destinationtype)
      {
         case "dtDataUrl":
            $destinationType = "DATA_URL";
            break;
         case "dtFileUri":
            $destinationType = "FILE_URI";
            break;
      }

      $destinationType = "navigator.camera.DestinationType.$destinationType";

      $options = "{quality:$this->_quality,allowEdit:" . ($this->_allowedit? 'true': 'false') . ",destinationType:$destinationType,sourceType:$sourceType}";
      echo "{$this->Name}GetPicture=function(extraOptions) {\n";
      echo " if({$this->Name}){\n";
      echo "  var options = $options;\n";
      echo "  var extraOptions=extraOptions || {};\n";
      echo "  jQuery.extend(options,extraOptions);";
      echo "  navigator.camera.getPicture(" . $this->checkEmptyFunction($this->_jsonsuccess) . ", " . $this->checkEmptyFunction($this->_jsonerror) . ",options);\n";
      echo " }\n";
      echo "}\n";
   }

}

class MCamera extends CustomMCamera
{

   function getSourceType()    {return $this->readsourcetype();}
   function setSourceType($value)    {$this->writesourcetype($value);}

   function getDestinationType()    {return $this->readdestinationtype();}
   function setDestinationType($value)    {$this->writedestinationtype($value);}

   function getAllowEdit()    {return $this->readallowedit();}
   function setAllowEdit($value)    {$this->writeallowedit($value);}

   function getQuality()    {return $this->readquality();}
   function setQuality($value)    {$this->writequality($value);}

   function getjsOnSuccess()    {return $this->readjsonsuccess();}
   function setjsOnSuccess($value)    {$this->writejsonsuccess($value);}

   function getjsOnError()    {return $this->readjsonerror();}
   function setjsOnError($value)    {$this->writejsonerror($value);}

}


/**
* deprecated in phonegap 1.0
**/

/*
class CustomMNetwork extends BaseDevice
{
   protected $_host = "";
   protected $_jsoncheckstatus = "";

   /**
    * Event fired after the device has tried to reach the Host
    * It returns a parameter, "event" ,
    * Deppending on the device, the network status is stored in "event" or in "event.code".
    * Android devices don't return any value if ther is no connection
    * "if(event) network = event.code || event;
    *  else network=NetworkStatus.NOT_REACHABLE;"
    * The possible values can be evaluated against the "NetworkStatus" Constant:
    *   network == NetworkStatus.NOT_REACHABLE, (0) No network connection.
    *   network == NetworkStatus.REACHABLE_VIA_CARRIER_DATA_NETWORK, (1) Carrier data connection.
    *   network == NetworkStatus.REACHABLE_VIA_WIFI_NETWORK, (2) WiFi connection.
    *
    */

/*   function readjsOnCheckStatus()    {return $this->_jsoncheckstatus;}
   function writejsOnCheckStatus($value)    {$this->_jsoncheckstatus = $value;}
   function defaultJsOnCheckStatus()    {return "";}

   /**
    * Enter the host which connectivity you want to check
    */

/*   function readHost()    {return $this->_host;}
   function writeHost($value)    {$this->_host = $value;}
   function defaultHost()    {return "";}

   function dumpJavascript()
   {
      parent::dumpJavascript();
      $this->dumpJSEvent($this->_jsoncheckstatus);

      echo "{$this->Name}isReachable=function(host) {\n";
      echo " if({$this->Name}){\n";
      echo " host= host || '$this->_host';\n";
      echo "  navigator.network.isReachable(host," . $this->checkEmptyFunction($this->_jsoncheckstatus) . ");\n";
      echo " }\n";
      echo "}\n";
   }
}

class MNetwork extends CustomMNetwork
{

   function getjsOnCheckStatus()    {return $this->readjsoncheckstatus();}
   function setjsOnCheckStatus($value)    {$this->writejsoncheckstatus($value);}

   function getHost()    {return $this->readhost();}
   function setHost($value)    {$this->writehost($value);}
}
  */
class CustomMContacts extends BaseDevice
{
   protected $_fields = array();
   protected $_filter = "";
   protected $_multiple = 1;
   protected $_updatedsince = "";
   protected $_jsonlist = "";
   protected $_jsonlisterror = "";
   protected $_jsonsave = "";
   protected $_jsonsaveerror = "";
   protected $_jsonremove = "";
   protected $_jsonremoveerror = "";

   /**
    * Fired when there is an error removing a contact
    */
   function readjsOnRemoveError()    {return $this->_jsonremoveerror;}
   function writejsOnRemoveError($value)    {$this->_jsonremoveerror = $value;}
   function defaultjsOnRemoveError()    {return "";}

   /**
    * Fired when a contact gets removed
    */
   function readjsOnRemove()    {return $this->_jsonremove;}
   function writejsOnRemove($value)    {$this->_jsonremove = $value;}
   function defaultjsOnRemove()    {return "";}

   /**
    * Fired when thre is an error saving a contact
    */
   function readjsOnSaveError()    {return $this->_jsonsaveerror;}
   function writejsOnSaveError($value)    {$this->_jsonsaveerror = $value;}
   function defaultjsOnSaveError()    {return "";}

   /**
    *  Fired when the component writes a contact to the system
    */
   function readjsOnSave()    {return $this->_jsonsave;}
   function writejsOnSave($value)    {$this->_jsonsave = $value;}
   function defaultjsOnSave()    {return "";}

   /**
    * Fired when there is an error getting the data
    * returns the error message in "event"
    */
   function readjsOnListError()    {return $this->_jsonlisterror;}
   function writejsOnListError($value)    {$this->_jsonlisterror = $value;}
   function defaultjsOnListError()    {return "";}

   /**
    * Fired when the component retrieves the data succesfully.
    * It returns the "event" array populated with all the results
    */
   function readjsOnList()    {return $this->_jsonlist;}
   function writejsOnList($value)    {$this->_jsonlist = $value;}
   function defaultjsOnList()    {return "";}

   /**
    * iPhone Only. Returns only the contacts updated after this date
    */
   function readUpdatedSince()    {return $this->_updatedsince;}
   function writeUpdatedSince($value)    {$this->_updatedsince = $value;}
   function defaultUpdatedSince()    {return "";}

   /**
    * Set it to true to get multiple results or to false to get only the first match
    */
   function readMultiple()    {return $this->_multiple;}
   function writeMultiple($value)    {$this->_multiple = $value;}
   function defaultMultiple()    {return 1;}

   /**
    * String to use as a filter for the selected fields
    */
   function readFilter()    {return $this->_filter;}
   function writeFilter($value)    {$this->_filter = $value;}
   function defaultFilter()    {return "";}

   /**
    * List of fields to display
    */
   function readFields()    {return $this->_fields;}
   function writeFields($value)    {$this->_fields = $value;}
   function defaultFields()    {return array();}

   function dumpJavascript()
   {
      parent::dumpJavascript();
      $this->dumpJSEvent($this->_jsonlist);
      $this->dumpJSEvent($this->_jsonlisterror);

      $this->dumpJSEvent($this->_jsonsave);
      $this->dumpJSEvent($this->_jsonsaveerror);

      $this->dumpJSEvent($this->_jsonremove);
      $this->dumpJSEvent($this->_jsonremoveerror);


      if(count($this->_fields) > 0)
      {
        $fields = "[\"" . implode('","', $this->_fields) . "\"]";
      }
      else
        $fields = "[]";

      $options = "{filter:'$this->_filter',multiple:" . ($this->_multiple? 'true': 'false') . ",updatedSince:'$this->_updatedsince'}";

      echo "{$this->Name}List=function(options,fields) {\n";
      echo " var options = options || $options;\n";
      echo " var fields = fields || $fields;\n";
      echo " if({$this->Name}){\n";
      echo "  navigator.contacts.find(fields, " . $this->checkEmptyFunction($this->_jsonlist) . ", " . $this->checkEmptyFunction($this->_jsonlisterror) . ", options);\n";
      echo " }\n";
      echo "}\n";

      echo "{$this->Name}Save=function(contact) {\n";
      echo " if({$this->Name} && contact){\n";
      echo "   contact.save(" . $this->checkEmptyFunction($this->_jsonsave) . "," . $this->checkEmptyFunction($this->_jsonsaveerror) . ");\n";
      echo " }\n";
      echo "}\n";

      echo "{$this->Name}Remove=function(contact) {\n";
      echo " if({$this->Name} && contact){\n";
      echo "  contact.remove(" . $this->checkEmptyFunction($this->_jsonremove) . "," . $this->checkEmptyFunction($this->_jsonremoveerror) . ");\n";
      echo " }\n";
      echo "}\n";
   }
}

class MContacts extends CustomMContacts
{
   function getFields()    {return $this->readfields();}
   function setFields($value)    {$this->writefields($value);}

   function getFilter()    {return $this->readfilter();}
   function setFilter($value)    {$this->writefilter($value);}

   function getMultiple()    {return $this->readmultiple();}
   function setMultiple($value)    {$this->writemultiple($value);}

   function getUpdatedSince()    {return $this->readupdatedsince();}
   function setUpdatedSince($value)    {$this->writeupdatedsince($value);}

   function getjsOnRemove()    {return $this->readjsonremove();}
   function setjsOnRemove($value)    {$this->writejsonremove($value);}

   function getjsOnRemoveError()    {return $this->readjsonremoveerror();}
   function setjsOnRemoveError($value)    {$this->writejsonremoveerror($value);}

   function getjsOnSaveError()    {return $this->readjsonsaveerror();}
   function setjsOnSaveError($value)    {$this->writejsonsaveerror($value);}

   function getjsOnSave()    {return $this->readjsonsave();}
   function setjsOnSave($value)    {$this->writejsonsave($value);}

   function getjsOnList()    {return $this->readjsonlist();}
   function setjsOnList($value)    {$this->writejsonlist($value);}

   function getjsOnListError()    {return $this->readjsonlisterror();}
   function setjsOnListError($value)    {$this->writejsonlisterror($value);}
}

/**
 * Base class for mobile components that can manage a client-side database.
 */
class CustomMDB extends BaseDevice
{
    protected $_dbsize=1000;
    protected $_dbdisplayname="";
    protected $_dbversion="";
    protected $_dbname="";

    /**
     * Logic name of the database.
     */
    function readDBName() { return $this->_dbname; }
    function writeDBName($value) { $this->_dbname=$value; }
    function defaultDBName() { return ""; }

    /**
     * Current version number (integer) of the database.
     *
     * This value is used to control the upgrades of existing databases of previous versions.
     */
    function readDBVersion() { return $this->_dbversion; }
    function writeDBVersion($value) { $this->_dbversion=$value; }
    function defaultDBVersion() { return ""; }

    /**
     * Display name of the database.
     *
     * It does not need to match the logic name (DBName).
     */
    function readDBDisplayName() { return $this->_dbdisplayname; }
    function writeDBDisplayName($value) { $this->_dbdisplayname=$value; }
    function defaultDBDisplayName() { return ""; }

    /**
     * Maximum expected database size, in bytes.
     */
    function readDBSize() { return $this->_dbsize; }
    function writeDBSize($value) { $this->_dbsize=$value; }
    function defaultDBSize() { return 1000; }

    // Documented in the parent.
    function dumpJavascript()
    {
        parent::dumpJavascript();

        echo "var {$this->Name}Activate=function(DBName,DBVersion,DBDisplayName,DBSize) {\n";
        echo "   var DBName = DBName || '$this->_dbname';\n";
        echo "   var DBVersion = DBVersion || '$this->_dbversion';\n";
        echo "   var DBDisplayName = DBDisplayName || '$this->_dbdisplayname';\n";
        echo "   var DBSize = DBSize || '$this->_dbsize';\n";
        echo "  {$this->Name}=window.openDatabase(DBName,DBVersion,DBDisplayName,DBSize);\n";
        echo "}\n";
    }

    // Documented in the parent.
    function ondeviceready()
    {
        $output = "";
        $output .= "  {$this->Name}Activate();\n";
        return $output;
    }
}

/**
 * Client-side database manager.
 *
 * @link wiki://MDB
 */
class MDB extends CustomMDB
{
    // Documented in the parent.
    function getDBSize() { return $this->readdbsize(); }
    function setDBSize($value) { $this->writedbsize($value); }

    // Documented in the parent.
    function getDBDisplayName() { return $this->readdbdisplayname(); }
    function setDBDisplayName($value) { $this->writedbdisplayname($value); }

    // Documented in the parent.
    function getDBVersion() { return $this->readdbversion(); }
    function setDBVersion($value) { $this->writedbversion($value); }

    // Documented in the parent.
    function getDBName() { return $this->readdbname(); }
    function setDBName($value) { $this->writedbname($value); }
}

class CustomMDBTransaction extends BaseDevice
{
    protected $_db="";
    protected $_jsontransaction="";
    protected $_jsontransactionerror="";
    protected $_jsontransactionsuccess="";

    /**
    * The MDB object to connect to the DataBase
    */
    function readDB() { return $this->_db; }
    function writeDB($value) { $this->_db=$this->fixupProperty($value); }
    function defaultDB() { return ""; }

    /**
    *  Fired when there is an error executing the transaction
    */
    function readjsOnTransactionSuccess() { return $this->_jsontransactionsuccess; }
    function writejsOnTransactionSuccess($value) { $this->_jsontransactionsuccess=$value; }
    function defaultjsOnTransactionSuccess() { return ""; }


    /**
    * Fired when there is an error during the DataBase's transaction
    */
    function readjsOnTransactionError() { return $this->_jsontransactionerror; }
    function writejsOnTransactionError($value) { $this->_jsontransactionerror=$value; }
    function defaultjsOnTransactionError() { return ""; }

    /**
    * Fired when a database transaction command is called {$this->Name}Transaction
    */
    function readjsOnTransaction() { return $this->_jsontransaction; }
    function writejsOnTransaction($value) { $this->_jsontransaction=$value; }
    function defaultjsOnTransaction() { return ""; }

   function loaded()
   {
      parent::loaded();
      $this->writeDB($this->_db);
   }

   function dumpJavascript()
   {
      parent::dumpJavascript();
      $this->dumpJSEvent($this->_jsontransaction);
      $this->dumpJSEvent($this->_jsontransactionsuccess);
      $this->dumpJSEvent($this->_jsontransactionerror);

      echo "var {$this->Name}RunTransaction=function() {\n";
      echo "  if({$this->Name} && {$this->_db->Name}){\n";
      echo "    {$this->_db->Name}.transaction(" . $this->checkEmptyFunction($this->_jsontransaction) . "," . $this->checkEmptyFunction($this->_jsontransactionerror) . "," . $this->checkEmptyFunction($this->_jsontransactionsuccess) . ");\n";
      echo "  }\n";
      echo "}\n";

   }

}

class MDBTransaction extends CustomMDBTransaction
{
    function getjsOnTransactionSuccess() { return $this->readjsontransactionsuccess(); }
    function setjsOnTransactionSuccess($value) { $this->writejsontransactionsuccess($value); }

    function getjsOnTransactionError() { return $this->readjsontransactionerror(); }
    function setjsOnTransactionError($value) { $this->writejsontransactionerror($value); }

    function getjsOnTransaction() { return $this->readjsontransaction(); }
    function setjsOnTransaction($value) { $this->writejsontransaction($value); }

    function getDB() { return $this->readdb(); }
    function setDB($value) { $this->writedb($value); }


}

class CustomMFileBase extends BaseDevice{
    protected $_jsonabort="";
    protected $_jsonerror="";


    /**
    * Called when the there is an error
    */
    function readjsOnError() { return $this->_jsonerror; }
    function writejsOnError($value) { $this->_jsonerror=$value; }
    function defaultjsOnError() { return ""; }


    /**
    * Called when the action has been aborted
    */
    function readjsOnAbort() { return $this->_jsonabort; }
    function writejsOnAbort($value) { $this->_jsonabort=$value; }
    function defaultjsOnAbort() { return ""; }

    function dumpJavascript()
    {
      parent::dumpJavascript();

      $this->dumpJSEvent($this->_jsonabort);
      $this->dumpJSEvent($this->_jsonerror);
    }

}

class MFileBase extends CustomMFileBase{

    function getjsOnError() { return $this->readjsonerror(); }
    function setjsOnError($value) { $this->writejsonerror($value); }

    function getjsOnAbort() { return $this->readjsonabort(); }
    function setjsOnAbort($value) { $this->writejsonabort($value); }
}

class CustomMFileReader extends MFileBase{
    protected $_jsonloadstart="";
    protected $_jsonload="";
    protected $_jsonloadend="";

    /**
    * Called when the request has completed (either in success or failure).
    */
    function readjsOnLoadEnd() { return $this->_jsonloadend; }
    function writejsOnLoadEnd($value) { $this->_jsonloadend=$value; }
    function defaultjsOnLoadEnd() { return ""; }

    /**
    * Called when the read has successfully completed.
    */
    function readjsOnLoad() { return $this->_jsonload; }
    function writejsOnLoad($value) { $this->_jsonload=$value; }
    function defaultjsOnLoad() { return ""; }


    /**
    * Called when the File starts loading
    */
    function readjsOnLoadStart() { return $this->_jsonloadstart; }
    function writejsOnLoadStart($value) { $this->_jsonloadstart=$value; }
    function defaultjsOnLoadStart() { return ""; }

   function dumpJavascript()
   {
      parent::dumpJavascript();
      $this->dumpJSEvent($this->_jsonloadstart);
      $this->dumpJSEvent($this->_jsonloadend);
      $this->dumpJSEvent($this->_jsonload);

      echo "var {$this->Name}Activate=function() {\n";
      echo "  {$this->Name}=new FileReader();\n";
      $this->bindEvent('_jsonloadstart');
      $this->bindEvent('_jsonload');
      $this->bindEvent('_jsonabort');
      $this->bindEvent('_jsonerror');
      $this->bindEvent('_jsonloadend');
      echo "}\n";
   }


   function ondeviceready()
   {
      $output = "";
      $output .= "  {$this->Name}Activate();\n";
      return $output;
   }
}

class MFileReader extends CustomMFileReader{

    function getjsOnLoadEnd() { return $this->readjsonloadend(); }
    function setjsOnLoadEnd($value) { $this->writejsonloadend($value); }

    function getjsOnLoad() { return $this->readjsonload(); }
    function setjsOnLoad($value) { $this->writejsonload($value); }

    function getjsOnLoadStart() { return $this->readjsonloadstart(); }
    function setjsOnLoadStart($value) { $this->writejsonloadstart($value); }
}

class CustomMFileWriter extends MFileBase{
    protected $_jsonwritestart="";
    protected $_jsonwrite="";
    protected $_jsonwriteend="";

    /**
    * Called when the request has completed (either in success or failure).
    */
    function readjsOnWriteEnd() { return $this->_jsonwriteend; }
    function writejsOnWriteEnd($value) { $this->_jsonwriteend=$value; }
    function defaultjsOnWriteEnd() { return ""; }

    /**
    * Called when the writing has successfully completed.
    */
    function readjsOnWrite() { return $this->_jsonwrite; }
    function writejsOnWrite($value) { $this->_jsonwrite=$value; }
    function defaultjsOnWrite() { return ""; }


    /**
    * Called when the system starts writting the file
    */
    function readjsOnWriteStart() { return $this->_jsonwritestart; }
    function writejsOnWriteStart($value) { $this->_jsonwritestart=$value; }
    function defaultjsOnWriteStart() { return ""; }

   function dumpJavascript()
   {
      parent::dumpJavascript();
      $this->dumpJSEvent($this->_jsonwriteend);
      $this->dumpJSEvent($this->_jsonwrite);
      $this->dumpJSEvent($this->_jsonwritestart);

      echo "var {$this->Name}Activate=function() {\n";
      echo "  {$this->Name}=new FileWriter();\n";
      $this->bindEvent('_jsonwritestart');
      $this->bindEvent('_jsonwrite');
      $this->bindEvent('_jsonabort');
      $this->bindEvent('_jsonerror');
      $this->bindEvent('_jsonwriteend');
      echo "}\n";
   }


   function ondeviceready()
   {
      $output = "";
      $output .= "  {$this->Name}Activate();\n";
      return $output;
   }
}

class MFileWriter extends CustomMFileWriter{

    function getjsOnWriteEnd() { return $this->readjsonwriteend(); }
    function setjsOnWriteEnd($value) { $this->writejsonwriteend($value); }

    function getjsOnWrite() { return $this->readjsonwrite(); }
    function setjsOnWrite($value) { $this->writejsonwrite($value); }

    function getjsOnWriteStart() { return $this->readjsonwritestart(); }
    function setjsOnWriteStart($value) { $this->writejsonwritestart($value); }
}

class CustomMEntryBase extends BaseDevice{
    protected $_jsongetmetadata="";
    protected $_jsongetmetadataerror="";
    protected $_jsonmoveto="";
    protected $_jsonmovetoerror="";
    protected $_jsoncopyto="";
    protected $_jsoncopytoerror="";
    protected $_jsonremove="";
    protected $_jsonremoveerror="";
    protected $_jsongetparent="";
    protected $_jsongetparenterror="";


    /**
    * Fired if there is an error retrieving the parent directory.
    */
    function readjsOnGetParentError() { return $this->_jsongetparenterror; }
    function writejsOnGetParentError($value) { $this->_jsongetparenterror=$value; }
    function defaultjsOnGetParentError() { return ""; }


    /**
    * Fired when retrieving the parent directory.
    */
    function readjsOnGetParent() { return $this->_jsongetparent; }
    function writejsOnGetParent($value) { $this->_jsongetparent=$value; }
    function defaultjsOnGetParent() { return ""; }


    /**
    * Fired if there is an error deleting a file.
    */
    function readjsOnRemoveError() { return $this->_jsonremoveerror; }
    function writejsOnRemoveError($value) { $this->_jsonremoveerror=$value; }
    function defaultjsOnRemoveError() { return ""; }


    /**
    * Fired when deleting a file.
    */
    function readjsOnRemove() { return $this->_jsonremove; }
    function writejsOnRemove($value) { $this->_jsonremove=$value; }
    function defaultjsOnRemove() { return ""; }


    /**
    * Fired when there is an error copying the file.
    */
    function readjsOnCopyToError() { return $this->_jsoncopytoerror; }
    function writejsOnCopyToError($value) { $this->_jsoncopytoerror=$value; }
    function defaultjsOnCopyToError() { return ""; }


    /**
    * Fired when copying a fileto a different location on the file system.
    */
    function readjsOnCopyTo() { return $this->_jsoncopyto; }
    function writejsOnCopyTo($value) { $this->_jsoncopyto=$value; }
    function defaultjsOnCopyTo() { return ""; }


    /**
    * Fired if there is an error moving to a different location.
    */
    function readjsOnMoveToError() { return $this->_jsonmovetoerror; }
    function writejsOnMoveToError($value) { $this->_jsonmovetoerror=$value; }
    function defaultjsOnMoveToError() { return ""; }


    /**
    * Fired when the file is moved to a different location on the file system.
    */
    function readjsOnMoveTo() { return $this->_jsonmoveto; }
    function writejsOnMoveTo($value) { $this->_jsonmoveto=$value; }
    function defaultjsOnMoveTo() { return ""; }


    /**
    * Fired if there is an error retrieving the metadata.
    */
    function readjsOnGetMetadataError() { return $this->_jsongetmetadataerror; }
    function writejsOnGetMetadataError($value) { $this->_jsongetmetadataerror=$value; }
    function defaultjsOnGetMetadataError() { return ""; }


    /**
    * Fired when metadata about a file is succesfully retrieved.
    */
    function readjsOnGetMetadata() { return $this->_jsongetmetadata; }
    function writejsOnGetMetadata($value) { $this->_jsongetmetadata=$value; }
    function defaultjsOnGetMetadata() { return ""; }

  function dumpJavascript()
  {
    parent::dumpJavascript();
      $this->dumpJSEvent($this->_jsongetmetadataerror);
      $this->dumpJSEvent($this->_jsongetmetadata);
      $this->dumpJSEvent($this->_jsonmovetoerror);
      $this->dumpJSEvent($this->_jsonmoveto);
      $this->dumpJSEvent($this->_jsoncopytoerror);
      $this->dumpJSEvent($this->_jsoncopyto);
      $this->dumpJSEvent($this->_jsonremoveerror);
      $this->dumpJSEvent($this->_jsonremove);
      $this->dumpJSEvent($this->_jsongetparenterror);
      $this->dumpJSEvent($this->_jsongetparent);

      $this->dumpEventFunction('getMetadata');
      $params=array('DirectoryEntry'=>"undefined",'Name'=>"''");
      $this->dumpEventFunction('moveTo',$params);
      $params=array('DirectoryEntry'=>"undefined",'Name'=>"''");
      $this->dumpEventFunction('copyTo',$params);
      $this->dumpEventFunction('remove');
      $this->dumpEventFunction('getParent');

  }

}


class MEntryBase extends CustomMEntryBase{

    function getjsOnGetParent() { return $this->readjsongetparent(); }
    function setjsOnGetParent($value) { $this->writejsongetparent($value); }

    function getjsOnGetParentError() { return $this->readjsongetparenterror(); }
    function setjsOnGetParentError($value) { $this->writejsongetparenterror($value); }

    function getjsOnRemoveError() { return $this->readjsonremoveerror(); }
    function setjsOnRemoveError($value) { $this->writejsonremoveerror($value); }

    function getjsOnRemove() { return $this->readjsonremove(); }
    function setjsOnRemove($value) { $this->writejsonremove($value); }

    function getjsOnCopyTo() { return $this->readjsoncopyto(); }
    function setjsOnCopyTo($value) { $this->writejsoncopyto($value); }

    function getjsOnCopyToError() { return $this->readjsoncopytoerror(); }
    function setjsOnCopyToError($value) { $this->writejsoncopytoerror($value); }

    function getjsOnMoveTo() { return $this->readjsonmoveto(); }
    function setjsOnMoveTo($value) { $this->writejsonmoveto($value); }

    function getjsOnMoveToError() { return $this->readjsonmovetoerror(); }
    function setjsOnMoveToError($value) { $this->writejsonmovetoerror($value); }

    function getjsOnGetMetadata() { return $this->readjsongetmetadata(); }
    function setjsOnGetMetadata($value) { $this->writejsongetmetadata($value); }

    function getjsOnGetMetadataError() { return $this->readjsongetmetadataerror(); }
    function setjsOnGetMetadataError($value) { $this->writejsongetmetadataerror($value); }
}

class CustomMFileEntry extends MEntryBase{
    protected $_filename="";
    protected $_jsonfile="";
    protected $_jsonfileerror="";
    protected $_jsoncreatewriter="";
    protected $_jsoncreatewritererror="";

    /**
    * Fired if there is an error creating the writer.
    */
    function readjsOnCreateWriterError() { return $this->_jsoncreatewritererror; }
    function writejsOnCreateWriterError($value) { $this->_jsoncreatewritererror=$value; }
    function defaultjsOnCreateWriterError() { return ""; }

    /**
    * Fired when a file writer is created succesfully.
    */
    function readjsOnCreateWriter() { return $this->_jsoncreatewriter; }
    function writejsOnCreateWriter($value) { $this->_jsoncreatewriter=$value; }
    function defaultjsOnCreateWriter() { return ""; }

    /**
    * The name of the file including the full absolute path to it
    */
    function readFilename() { return $this->_filename; }
    function writeFilename($value) { $this->_filename=$value; }
    function defaultFilename() { return ""; }

    /**
    * Fired if there is an error creating a file reader
    */
    function readjsOnFileError() { return $this->_jsonfileerror; }
    function writejsOnFileError($value) { $this->_jsonfileerror=$value; }
    function defaultjsOnFileError() { return ""; }


    /**
    * Fired when a file reader is created succesfully.
    */
    function readjsOnFile() { return $this->_jsonfile; }
    function writejsOnFile($value) { $this->_jsonfile=$value; }
    function defaultjsOnFile() { return ""; }

   function dumpJavascript()
   {
      parent::dumpJavascript();
      $this->dumpJSEvent($this->_jsonfileerror);
      $this->dumpJSEvent($this->_jsonfile);
      $this->dumpJSEvent($this->_jsoncreatewritererror);
      $this->dumpJSEvent($this->_jsoncreatewriter);

      echo "var {$this->Name}Activate=function(Filename) {\n";
      echo "  var Filename=Filename || '$this->_filename';\n";
      echo "  {$this->Name}=new FileEntry();\n";
      echo "  {$this->Name}.fullPath=Filename;\n";
      echo "}\n";

      $this->dumpEventFunction('file');
      $this->dumpEventFunction('createWriter');
   }

   function ondeviceready()
   {
      $output = "";
      $output .= "  {$this->Name}Activate();\n";
      return $output;
   }
}

class MFileEntry extends CustomMFileEntry{
    function getjsOnCreateWriter() { return $this->readjsoncreatewriter(); }
    function setjsOnCreateWriter($value) { $this->writejsoncreatewriter($value); }

    function getjsOnCreateWriterError() { return $this->readjsoncreatewritererror(); }
    function setjsOnCreateWriterError($value) { $this->writejsoncreatewritererror($value); }

    function getjsOnFileError() { return $this->readjsonfileerror(); }
    function setjsOnFileError($value) { $this->writejsonfileerror($value); }

    function getjsOnFile() { return $this->readjsonfile(); }
    function setjsOnFile($value) { $this->writejsonfile($value); }

    function getFilename() { return $this->readfilename(); }
    function setFilename($value) { $this->writefilename($value); }
}

class CustomMDirectoryEntry extends MEntryBase{
    protected $_directoryname="";
    protected $_jsongetdirectory="";
    protected $_jsongetdirectoryerror="";
    protected $_jsongetfile="";
    protected $_jsongetfileerror="";
    protected $_jsonremoverecursively="";
    protected $_jsonremoverecursivelyerror="";

    /**
    * Fired if there is an error deleting a directory recursively.
    */
    function readjsOnRemoveRecursivelyError() { return $this->_jsonremoverecursivelyerror; }
    function writejsOnRemoveRecursivelyError($value) { $this->_jsonremoverecursivelyerror=$value; }
    function defaultjsOnRemoveRecursivelyError() { return ""; }


    /**
    * Fired when a directory and all of its contents is deleted.
    */
    function readjsOnRemoveRecursively() { return $this->_jsonremoverecursively; }
    function writejsOnRemoveRecursively($value) { $this->_jsonremoverecursively=$value; }
    function defaultjsOnRemoveRecursively() { return ""; }


    /**
    * Fired if there is an err accessing a file(either existing or new).
    */
    function readjsOnGetFileError() { return $this->_jsongetfileerror; }
    function writejsOnGetFileError($value) { $this->_jsongetfileerror=$value; }
    function defaultjsOnGetFileError() { return ""; }


    /**
    * Fired when a file is retrieved or created.
    */
    function readjsOnGetFile() { return $this->_jsongetfile; }
    function writejsOnGetFile($value) { $this->_jsongetfile=$value; }
    function defaultjsOnGetFile() { return ""; }


    /**
    * Fired if there is an error creating or getting a directory.
    */
    function readjsOnGetDirectoryError() { return $this->_jsongetdirectoryerror; }
    function writejsOnGetDirectoryError($value) { $this->_jsongetdirectoryerror=$value; }
    function defaultjsOnGetDirectoryError() { return ""; }


    /**
    * Fired when creating or getting a directory succesfully.
    */
    function readjsOnGetDirectory() { return $this->_jsongetdirectory; }
    function writejsOnGetDirectory($value) { $this->_jsongetdirectory=$value; }
    function defaultjsOnGetDirectory() { return ""; }



    /**
    * The name of the directory including the full absolute path to it
    */
    function readDirectoryname() { return $this->_directoryname; }
    function writeDirectoryname($value) { $this->_directoryname=$value; }
    function defaultDirectoryname() { return ""; }



   function dumpJavascript()
   {
      parent::dumpJavascript();
      $this->dumpJSEvent($this->_jsongetdirectory);
      $this->dumpJSEvent($this->_jsongetdirectoryerror);
      $this->dumpJSEvent($this->_jsongetfile);
      $this->dumpJSEvent($this->_jsongetfileerror);
      $this->dumpJSEvent($this->_jsonremoverecursively);
      $this->dumpJSEvent($this->_jsonremoverecursivelyerror);

      echo "var {$this->Name}Activate=function(Directoryname) {\n";
      echo "  var Directoryname=Directoryname || '$this->_directoryname';\n";
      echo "  {$this->Name}=new DirectoryEntry();\n";
      echo "  {$this->Name}.fullPath=Directoryname;\n";
      echo "}\n";

      $params=array('Filename'=>"''",'options'=>"{}");
      $this->dumpEventFunction('getDirectory',$params);
      $params=array('Filename'=>"''",'options'=>"{}");
      $this->dumpEventFunction('getFile',$params);
      $this->dumpEventFunction('removeRecursively');
   }

   function ondeviceready()
   {
      $output = "";
      $output .= "  {$this->Name}Activate();\n";
      return $output;
   }
}

class MDirectoryEntry extends CustomMDirectoryEntry{
    function getjsOnRemoveRecursivelyError() { return $this->readjsonremoverecursivelyerror(); }
    function setjsOnRemoveRecursivelyError($value) { $this->writejsonremoverecursivelyerror($value); }

    function getjsOnRemoveRecursively() { return $this->readjsonremoverecursively(); }
    function setjsOnRemoveRecursively($value) { $this->writejsonremoverecursively($value); }

    function getjsOnGetFileError() { return $this->readjsongetfileerror(); }
    function setjsOnGetFileError($value) { $this->writejsongetfileerror($value); }

    function getjsOnGetFile() { return $this->readjsongetfile(); }
    function setjsOnGetFile($value) { $this->writejsongetfile($value); }

    function getjsOnGetDirectoryError() { return $this->readjsongetdirectoryerror(); }
    function setjsOnGetDirectoryError($value) { $this->writejsongetdirectoryerror($value); }

    function getjsOnGetDirectory() { return $this->readjsongetdirectory(); }
    function setjsOnGetDirectory($value) { $this->writejsongetdirectory($value); }

    function getDirectoryname() { return $this->readdirectoryname(); }
    function setDirectoryname($value) { $this->writedirectoryname($value); }
}

class CustomMDirectoryReader extends BaseDevice{
    protected $_jsonreadentries="";
    protected $_jsonreadentrieserror="";

    /**
    * Fired when ther is an erro retrieving the directory structure.
    */
    function readjsOnReadEntriesError() { return $this->_jsonreadentrieserror; }
    function writejsOnReadEntriesError($value) { $this->_jsonreadentrieserror=$value; }
    function defaultjsOnReadEntriesError() { return ""; }


    /**
    * Fired when the list of directory and files is succesfully retrieved.
    */
    function readjsOnReadEntries() { return $this->_jsonreadentries; }
    function writejsOnReadEntries($value) { $this->_jsonreadentries=$value; }
    function defaultjsOnReadEntries() { return ""; }

   function dumpJavascript()
   {
      parent::dumpJavascript();
      $this->dumpJSEvent($this->_jsonreadentries);
      $this->dumpJSEvent($this->_jsonreadentrieserror);

      $this->dumpEventFunction('readEntries');
   }

}

class MDirectoryReader extends CustomMDirectoryReader{
    function getjsOnReadEntriesError() { return $this->readjsonreadentrieserror(); }
    function setjsOnReadEntriesError($value) { $this->writejsonreadentrieserror($value); }

    function getjsOnReadEntries() { return $this->readjsonreadentries(); }
    function setjsOnReadEntries($value) { $this->writejsonreadentries($value); }
}

class CustomMFileSystem extends BaseDevice{
    protected $_type="fsPersistent";
    protected $_jsonrequestfilesystem="";
    protected $_jsonrequestfilesystemerror="";
    protected $_jsonresolvelocalfilesystemuri="";
    protected $_jsonresolvelocalfilesystemurierror="";

    /**
    *  Fired when there is an error translating a URI into a FileEntry
    */
    function readjsOnResolveLocalFileSystemURIError() { return $this->_jsonresolvelocalfilesystemurierror; }
    function writejsOnResolveLocalFileSystemURIError($value) { $this->_jsonresolvelocalfilesystemurierror=$value; }
    function defaultjsOnResolveLocalFileSystemURIError() { return ""; }


    /**
    *  Fired when translating a URI into a fileEntry
    */
    function readjsOnResolveLocalFileSystemURI() { return $this->_jsonresolvelocalfilesystemuri; }
    function writejsOnResolveLocalFileSystemURI($value) { $this->_jsonresolvelocalfilesystemuri=$value; }
    function defaultjsOnResolveLocalFileSystemURI() { return ""; }


    /**
    * Fired if there is an error retrieving the file system
    */
    function readjsOnRequestFileSystemError() { return $this->_jsonrequestfilesystemerror; }
    function writejsOnRequestFileSystemError($value) { $this->_jsonrequestfilesystemerror=$value; }
    function defaultjsOnRequestFileSystemError() { return ""; }


    /**
    *  Fired when the File system has been succesfully retrieved
    */
    function readjsOnRequestFileSystem() { return $this->_jsonrequestfilesystem; }
    function writejsOnRequestFileSystem($value) { $this->_jsonrequestfilesystem=$value; }
    function defaultjsOnRequestFileSystem() { return ""; }


    /**
    * The type of file system we want ot retrieve from the device, it can either be Persistent or temporary
    */
    function readType() { return $this->_type; }
    function writeType($value) { $this->_type=$value; }
    function defaultType() { return "fsPersistent"; }

   function dumpJavascript()
   {
      parent::dumpJavascript();
      $this->dumpJSEvent($this->_jsonrequestfilesystemerror);
      $this->dumpJSEvent($this->_jsonrequestfilesystem);
      $this->dumpJSEvent($this->_jsonresolvelocalfilesystemurierror);
      $this->dumpJSEvent($this->_jsonresolvelocalfilesystemuri);

      switch ($this->_type)
      {
        case 'fsPersistent':
          $type="LocalFileSystem.PERSISTENT";
          break;
        default:
          $type="LocalFileSystem.TEMPORARY";
          break;
      }

      echo "var {$this->Name}ResolveLocalFileSystemURI=function(URI) {\n";
      echo "  var URI=URI || '';\n";
      echo "  window.resolveLocalFileSystemURI(URI," . $this->checkEmptyFunction($this->_jsonresolvelocalfilesystemuri) . "," . $this->checkEmptyFunction($this->_jsonresolvelocalfilesystemurierror) . ");\n";
      echo "}\n";

      echo "var {$this->Name}RequestFileSystem=function(Type) {\n";
      echo "  var Type=Type || $type;\n";
      echo "  window.requestFileSystem(Type,0," . $this->checkEmptyFunction($this->_jsonrequestfilesystem) . "," . $this->checkEmptyFunction($this->_jsonrequestfilesystemerror) . ");\n";
      echo "}\n";
   }

}

class MFileSystem extends  CustomMFileSystem{
    function getjsOnResolveLocalFileSystemURI() { return $this->readjsonresolvelocalfilesystemuri(); }
    function setjsOnResolveLocalFileSystemURI($value) { $this->writejsonresolvelocalfilesystemuri($value); }

    function getjsOnResolveLocalFileSystemURIError() { return $this->readjsonresolvelocalfilesystemurierror(); }
    function setjsOnResolveLocalFileSystemURIError($value) { $this->writejsonresolvelocalfilesystemurierror($value); }

    function getjsOnRequestFileSystemError() { return $this->readjsonrequestfilesystemerror(); }
    function setjsOnRequestFileSystemError($value) { $this->writejsonrequestfilesystemerror($value); }

    function getjsOnRequestFileSystem() { return $this->readjsonrequestfilesystem(); }
    function setjsOnRequestFileSystem($value) { $this->writejsonrequestfilesystem($value); }

    function getType() { return $this->readtype(); }
    function setType($value) { $this->writetype($value); }
}

class CustomMFileTransfer extends BaseDevice{
    protected $_uploaduri="";
    protected $_key="file";
    protected $_filename="";
    protected $_mimetype="image/jpeg";
    protected $_extraparameters=array();
    protected $_jsonupload="";
    protected $_jsonuploaderror="";

    /**
    * Fired if there is an error with the transfer.
    */
    function readjsOnUploadError() { return $this->_jsonuploaderror; }
    function writejsOnUploadError($value) { $this->_jsonuploaderror=$value; }
    function defaultjsOnUploadError() { return ""; }


    /**
    * Fired when the file has been sucesfully uploaded.
    */
    function readjsOnUpload() { return $this->_jsonupload; }
    function writejsOnUpload($value) { $this->_jsonupload=$value; }
    function defaultjsOnUpload() { return ""; }


    /**
    * A set of optional key/value pairs to be passed along in the HTTP request.
    */
    function readExtraParameters() { return $this->_extraparameters; }
    function writeExtraParameters($value) { $this->_extraparameters=$value; }
    function defaultExtraParameters() { return array(); }


    /**
    * The mime type of the data you are uploading. If not set defaults to "image/jpeg".
    */
    function readMimeType() { return $this->_mimetype; }
    function writeMimeType($value) { $this->_mimetype=$value; }
    function defaultMimeType() { return "image/jpeg"; }


    /**
    * The file name you want the file to be saved as on the server. If not set defaults to "image.jpg".
    */
    function readFilename() { return $this->_filename; }
    function writeFilename($value) { $this->_filename=$value; }
    function defaultFilename() { return ""; }


    /**
    * The name of the form element. If not set defaults to "file".
    */
    function readKey() { return $this->_key; }
    function writeKey($value) { $this->_key=$value; }
    function defaultKey() { return "file"; }


    /**
    * URI that will handle the uploaded file.
    */
    function readUploadURI() { return $this->_uploaduri; }
    function writeUploadURI($value) { $this->_uploaduri=$value; }
    function defaultUploadURI() { return ""; }

    function dumpJavascript()
   {
      parent::dumpJavascript();
      $this->dumpJSEvent($this->_jsonupload);
      $this->dumpJSEvent($this->_jsonuploaderror);

      $params="{";
      foreach($this->_extraparameters as $id=>$val)
      {
        $params.="$id:'$val',";
      }
      $params=rtrim($params,',')."}";

      $options="{fileKey:'$this->_key',fileName:'$this->_filename',mimeType:'$this->_mimetype',params:$params}";

      echo "var {$this->Name}Upload=function(File,URI,ExtraOptions) {\n";
      echo "  if({$this->Name}){\n";
      echo "    var URI=URI || '$this->_uploaduri';\n";
      echo "    Options=$options;\n";
      echo "    var ExtraOptions = ExtraOptions || {}\n";
      echo "    jQuery.extend(Options,ExtraOptions);\n";
      echo "    {$this->Name}=new FileTransfer();\n";
      echo "    {$this->Name}.upload(File,URI," . $this->checkEmptyFunction($this->_jsonupload) . "," . $this->checkEmptyFunction($this->_jsonuploaderror) . ",Options);\n";
      echo "  }\n";
      echo "}\n";
   }

}

class MFileTransfer extends CustomMFileTransfer{
    function getUploadURI() { return $this->readuploaduri(); }
    function setUploadURI($value) { $this->writeuploaduri($value); }

    function getKey() { return $this->readkey(); }
    function setKey($value) { $this->writekey($value); }

    function getFilename() { return $this->readfilename(); }
    function setFilename($value) { $this->writefilename($value); }

    function getMimeType() { return $this->readmimetype(); }
    function setMimeType($value) { $this->writemimetype($value); }

    function getExtraParameters() { return $this->readextraparameters(); }
    function setExtraParameters($value) { $this->writeextraparameters($value); }

    function getjsOnUpload() { return $this->readjsonupload(); }
    function setjsOnUpload($value) { $this->writejsonupload($value); }

    function getjsOnUploadError() { return $this->readjsonuploaderror(); }
    function setjsOnUploadError($value) { $this->writejsonuploaderror($value); }


}

class CustomMCapture extends BaseDevice{
    protected $_limit=1;
    protected $_duration=1;
    protected $_type="mcAudio";
    protected $_jsonerror="";
    protected $_jsonsuccess="";

    /**
     * Triggered once the media has been succesfully captured.
     */
    function readjsOnSuccess() { return $this->_jsonsuccess; }
    function writejsOnSuccess($value) { $this->_jsonsuccess=$value; }
    function defaultjsOnSuccess() { return ""; }


    /**
    * Fired when there is an error capturing the media.
    */
    function readjsOnError() { return $this->_jsonerror; }
    function writejsOnError($value) { $this->_jsonerror=$value; }
    function defaultjsOnError() { return ""; }

    /**
    * Determines the type of media to capture.
    */
    function readType() { return $this->_type; }
    function writeType($value) { $this->_type=$value; }
    function defaultType() { return "mcAudio"; }


    /**
    * Sets the maximun duration of each media file in seconds
    */
    function readDuration() { return $this->_duration; }
    function writeDuration($value) { $this->_duration=$value; }
    function defaultDuration() { return 1; }


    /**
    * Stablish the number of media files the device can store
    */
    function readLimit() { return $this->_limit; }
    function writeLimit($value) { $this->_limit=$value; }
    function defaultLimit() { return 1; }


    function dumpJavascript()
   {
      parent::dumpJavascript();
      $this->dumpJSEvent($this->_jsonsuccess);
      $this->dumpJSEvent($this->_jsonerror);

      switch($this->_type)
      {
        case "mcVideo":
          $capture="captureVideo";
          break;
        case "mcImage":
          $capture="captureImage";
          break;
        default:
          $capture="captureAudio";
          break;
      }

      $options="{limit:$this->limit,duration:$this->_duration}";

      echo "var {$this->Name}Capture=function(ExtraOptions) {\n";
      echo "  if({$this->Name}){\n";
      echo "    Options=$options;\n";
      echo "    var ExtraOptions = ExtraOptions || {}\n";
      echo "    jQuery.extend(Options,ExtraOptions);\n";
      echo "    navigator.device.capture.$capture(" . $this->checkEmptyFunction($this->_jsonsuccess) . "," . $this->checkEmptyFunction($this->_jsonerror) . ",Options);\n";
      echo "  }\n";
      echo "}\n";
   }

}

class MCapture extends CustomMCapture{
    function getLimit() { return $this->readlimit(); }
    function setLimit($value) { $this->writelimit($value); }

    function getDuration() { return $this->readduration(); }
    function setDuration($value) { $this->writeduration($value); }

    function getType() { return $this->readtype(); }
    function setType($value) { $this->writetype($value); }

    function getjsOnError() { return $this->readjsonerror(); }
    function setjsOnError($value) { $this->writejsonerror($value); }

    function getjsOnSuccess() { return $this->readjsonsuccess(); }
    function setjsOnSuccess($value) { $this->writejsonsuccess($value); }
}

class CustomMNotification extends BaseDevice{
    protected $_type="ntAlert";
    protected $_message="";
    protected $_title="";
    protected $_buttonlabels=array();
    protected $_jsonclosenotification="";

    /**
    * Fired when the notification is closed by pressing any button
    */
    function readjsOnCloseNotification() { return $this->_jsonclosenotification; }
    function writejsOnCloseNotification($value) { $this->_jsonclosenotification=$value; }
    function defaultjsOnCloseNotification() { return ""; }


    /**
    * The button's labels to display, if the Type ntAlert is chosen only the first one will be displayed.
    */
    function readButtonLabels() { return $this->_buttonlabels; }
    function writeButtonLabels($value) { $this->_buttonlabels=$value; }
    function defaultButtonLabels() { return array(); }


    /**
    * The title of the notification window
    */
    function readTitle() { return $this->_title; }
    function writeTitle($value) { $this->_title=$value; }
    function defaultTitle() { return ""; }


    /**
    * The message that MNotification will display
    */
    function readMessage() { return $this->_message; }
    function writeMessage($value) { $this->_message=$value; }
    function defaultMessage() { return ""; }


    /**
    * Choose the notification type, Alert or Prompt
    */
    function readType() { return $this->_type; }
    function writeType($value) { $this->_type=$value; }
    function defaultType() { return "ntAlert"; }

    function dumpJavascript()
   {
      parent::dumpJavascript();
      $this->dumpJSEvent($this->_jsonclosenotification);

      switch($this->_type)
      {
        case "ntConfirm":
          $notification="confirm";
          break;
        default:
          $notification="alert";
          break;
      }

      $buttons=implode(",",$this->_buttonlabels);

      echo "var {$this->Name}Notification=function() {\n";
      echo "  if({$this->Name}){\n";
      echo "    navigator.notification.$notification('$this->_message'," . $this->checkEmptyFunction($this->_jsonclosenotification) . ",'$this->_title','$buttons');\n";
      echo "  }\n";
      echo "}\n";
   }
}

class MNotification extends CustomMNotification{
    function getType() { return $this->readtype(); }
    function setType($value) { $this->writetype($value); }

    function getMessage() { return $this->readmessage(); }
    function setMessage($value) { $this->writemessage($value); }

    function getButtonLabels() { return $this->readbuttonlabels(); }
    function setButtonLabels($value) { $this->writebuttonlabels($value); }

    function getTitle() { return $this->readtitle(); }
    function setTitle($value) { $this->writetitle($value); }

    function getjsOnCloseNotification() { return $this->readjsonclosenotification(); }
    function setjsOnCloseNotification($value) { $this->writejsonclosenotification($value); }


}

class CustomMConnection extends Label{
    protected $_connectiontypes=array('Unknown connection','Ethernet connection','WiFi connection','Cell 2G connection','Cell 3G connection','Cell 4G connection','No network connection');

    /**
    * Array with a description of the different connection types
    */
    function readConnectionTypes() { return $this->_connectiontypes; }
    function writeConnectionTypes($value) { $this->_connectiontypes=$value; }
    function defaultConnectionTypes() { return array('UNKNOWN'=>'Unknown connection','ETHERNET'=>'Ethernet connection','WIFI'=>'WiFi connection','CELL_2G'=>'Cell 2G connection','CELL_3G'=>'Cell 3G connection','CELL_4G'=>'Cell 4G connection','NONE'=>'No network connection');; }

    // Documented in the parent.
    function dumpJavascript()
    {
        parent::dumpJavascript();

        echo "var {$this->Name}Activate=function() {\n";
        echo "  if({$this->Name}){\n";
        echo "    var states={};\n";
        foreach($this->_connectiontypes as $key=>$value)
        {
            echo "    states[Connection.$key]='$value';\n";
        }
        echo "    var value=states[navigator.network.connection.type];\n";
        echo "  jQuery('#{$this->Name}').html(value);\n";
        echo "  }\n";
        echo "}\n";
    }

    /**
     * Returns the string "ComponentNameActivate();\n", where component name is the value of the Name property of the
     * component.
     *
     * @internal
     */
    function ondeviceready()
    {
        $output = "";
        $output .= "  {$this->Name}Activate();\n";
        return $output;
    }
}

class MConnection extends CustomMConnection{

    function getConnectionTypes() { return $this->readconnectiontypes(); }
    function setConnectionTypes($value) { $this->writeconnectiontypes($value); }
}

class CustomMDevice extends Label{
    protected $_display="dsName";

    /**
    * The information to display
    */
    function readDisplay() { return $this->_display; }
    function writeDisplay($value) { $this->_display=$value; }
    function defaultDisplay() { return "dsName"; }

    // Documented in the parent.
    function dumpJavascript()
    {
        parent::dumpJavascript();

        switch($this->_display)
        {
            case "dsVersion":
            $notification="version";
            break;
            case "dsUuid":
            $notification="uuid";
            break;
            case "dsPlatform":
            $notification="platform";
            break;
            case "dsPhonegap":
            $notification="cordova";
            break;
            default:
            $notification="name";
            break;
        }

        echo "var {$this->Name}Activate=function() {\n";
        echo "  if({$this->Name}){\n";
        echo "    var value=device.$notification || 'Undefined';\n";
        echo "  jQuery('#{$this->Name}').html(value);\n";
        echo "  }\n";
        echo "}\n";
    }

    /**
     * Returns the string "ComponentNameActivate();\n", where component name is the value of the Name property of the
     * component.
     *
     * @internal
     */
    function ondeviceready()
    {
        $output = "";
        $output .= "  {$this->Name}Activate();\n";
        return $output;
    }
}

class MDevice extends CustomMDevice{
    function getDisplay() { return $this->readdisplay(); }
    function setDisplay($value) { $this->writedisplay($value); }
}