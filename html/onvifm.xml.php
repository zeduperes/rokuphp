<?php
<object class="OnvifPage" name="OnvifPage" baseclass="MPage">
<property name="DesignConfigName">Samsung Galaxy S II - Vertical (480x762)</property>
<property name="DesignConfigWidth">480</property>
<property name="DesignConfigHeight">650</property>
<property name="UseBackground">0</property>
  <property name="Animations">a:0:{}</property>
  <property name="AutoSize">1</property>
  <property name="Background"></property>
  <property name="Caption">ONVIF</property>
  <property name="Font">
  <property name="Family">Helvetica, Arial, sans-serif</property>
  <property name="Size">16px</property>
  </property>
  <property name="Height">650</property>
  <property name="HiddenFields">a:0:{}</property>
  <property name="Name">OnvifPage</property>
  <property name="Theme">dm.MobileTheme1</property>
  <property name="UseAjax">1</property>
  <property name="Width">480</property>
  <property name="OnBeforeShow">OnvifPageBeforeShow</property>
  <object class="MButton" name="ButtonONVIFScan" >
    <property name="Animations">a:0:{}</property>
    <property name="ButtonType">btNormal</property>
    <property name="Caption">Scan for ONVIF cameras</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Name">ButtonONVIFScan</property>
    <property name="SystemIcon">siSearch</property>
    <property name="Top">17</property>
    <property name="Width">363</property>
    <property name="OnClick">ButtonONVIFScanClick</property>
  </object>
  <object class="MLabel" name="MLabel1" >
    <property name="Alignment">agCenter</property>
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Found ONVIF Cameras</property>
    <property name="Font">
    <property name="Family">Helvetica, Arial, sans-serif</property>
    <property name="Size">16px</property>
    </property>
    <property name="Height">20</property>
    <property name="Left">25</property>
    <property name="Name">MLabel1</property>
    <property name="Top">68</property>
    <property name="Width">363</property>
  </object>
  <object class="MComboBox" name="ListBoxONVIF" >
    <property name="Animations">a:0:{}</property>
    <property name="Enabled">0</property>
    <property name="Height">43</property>
    <property name="Items">a:0:{}</property>
    <property name="Left">25</property>
    <property name="Name">ListBoxONVIF</property>
    <property name="Sorted">1</property>
    <property name="Top">88</property>
    <property name="Width">363</property>
  </object>
  <object class="MEdit" name="EditLogin" >
    <property name="Animations">a:0:{}</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Name">EditLogin</property>
    <property name="Top">161</property>
    <property name="Width">363</property>
  </object>
  <object class="MEdit" name="EditPassword" >
    <property name="Animations">a:0:{}</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Name">EditPassword</property>
    <property name="Top">234</property>
    <property name="Width">363</property>
  </object>
  <object class="MLabel" name="MLabel2" >
    <property name="Alignment">agCenter</property>
    <property name="Animations">a:0:{}</property>
    <property name="Caption">ONVIF User Login</property>
    <property name="Font">
    <property name="Family">Helvetica, Arial, sans-serif</property>
    <property name="Size">16px</property>
    </property>
    <property name="Height">20</property>
    <property name="Left">25</property>
    <property name="Name">MLabel2</property>
    <property name="Top">141</property>
    <property name="Width">363</property>
  </object>
  <object class="MLabel" name="MLabel3" >
    <property name="Alignment">agCenter</property>
    <property name="Animations">a:0:{}</property>
    <property name="Caption">ONVIF User Password</property>
    <property name="Font">
    <property name="Family">Helvetica, Arial, sans-serif</property>
    <property name="Size">16px</property>
    </property>
    <property name="Height">20</property>
    <property name="Left">25</property>
    <property name="Name">MLabel3</property>
    <property name="Top">214</property>
    <property name="Width">363</property>
  </object>
  <object class="MButton" name="ButtonCheckCamera" >
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Get Streaming Profiles</property>
    <property name="Enabled">0</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Name">ButtonCheckCamera</property>
    <property name="Top">290</property>
    <property name="Width">363</property>
    <property name="OnClick">ButtonCheckCameraClick</property>
  </object>
  <object class="MComboBox" name="ComboBoxProfiles" >
    <property name="Animations">a:0:{}</property>
    <property name="Enabled">0</property>
    <property name="Height">43</property>
    <property name="Items">a:0:{}</property>
    <property name="Left">25</property>
    <property name="Name">ComboBoxProfiles</property>
    <property name="Top">363</property>
    <property name="Width">363</property>
  </object>
  <object class="MLabel" name="MLabel4" >
    <property name="Alignment">agCenter</property>
    <property name="Animations">a:0:{}</property>
    <property name="Caption">H.264 Profiles Available</property>
    <property name="Font">
    <property name="Family">Helvetica, Arial, sans-serif</property>
    <property name="Size">16px</property>
    </property>
    <property name="Height">20</property>
    <property name="Left">25</property>
    <property name="Name">MLabel4</property>
    <property name="Top">343</property>
    <property name="Width">363</property>
  </object>
  <object class="MEdit" name="EditCameraName" >
    <property name="Animations">a:0:{}</property>
    <property name="Enabled">0</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Name">EditCameraName</property>
    <property name="Top">435</property>
    <property name="Width">363</property>
  </object>
  <object class="MLabel" name="MLabel5" >
    <property name="Alignment">agCenter</property>
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Camera Name</property>
    <property name="Font">
    <property name="Family">Helvetica, Arial, sans-serif</property>
    <property name="Size">16px</property>
    </property>
    <property name="Height">20</property>
    <property name="Left">25</property>
    <property name="Name">MLabel5</property>
    <property name="Top">415</property>
    <property name="Width">363</property>
  </object>
  <object class="MCheckBox" name="CheckBoxDoAudio" >
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Enable Audio</property>
    <property name="Enabled">0</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Name">CheckBoxDoAudio</property>
    <property name="Top">487</property>
    <property name="Width">200</property>
  </object>
  <object class="MButton" name="ButtonAddCamera" >
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Save</property>
    <property name="Enabled">0</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Name">ButtonAddCamera</property>
    <property name="SystemIcon">siCheck</property>
    <property name="Top">571</property>
    <property name="Width">171</property>
    <property name="OnClick">ButtonAddCameraClick</property>
  </object>
  <object class="MLabel" name="Msg" >
    <property name="Alignment">agCenter</property>
    <property name="Animations">a:0:{}</property>
    <property name="Font">
    <property name="Color">Yellow</property>
    <property name="Family">Helvetica, Arial, sans-serif</property>
    <property name="Size">16px</property>
    </property>
    <property name="Height">20</property>
    <property name="Left">25</property>
    <property name="Name">Msg</property>
    <property name="ParentFont">0</property>
    <property name="Top">541</property>
    <property name="Width">363</property>
  </object>
  <object class="MLink" name="MLinkCancel" >
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Back</property>
    <property name="DivWrap">0</property>
    <property name="Height">43</property>
    <property name="Left">217</property>
    <property name="Link">menu.php</property>
    <property name="Name">MLinkCancel</property>
    <property name="SystemIcon">siBack</property>
    <property name="Top">571</property>
    <property name="Width">171</property>
  </object>
</object>
?>
