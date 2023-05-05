<?php
<object class="PageManual" name="PageManual" baseclass="MPage">
<property name="DesignConfigName">Samsung Galaxy S II - Vertical (480x762)</property>
<property name="DesignConfigWidth">480</property>
<property name="DesignConfigHeight">650</property>
<property name="UseBackground">0</property>
  <property name="Animations">a:0:{}</property>
  <property name="AutoSize">1</property>
  <property name="Background"></property>
  <property name="Caption">Add/Edit</property>
  <property name="Font">
  <property name="Family">Helvetica, Arial, sans-serif</property>
  <property name="Size">16px</property>
  </property>
  <property name="Height">650</property>
  <property name="HiddenFields">a:0:{}</property>
  <property name="Name">PageManual</property>
  <property name="Theme">dm.MobileTheme1</property>
  <property name="UseAjax">1</property>
  <property name="Width">480</property>
  <property name="OnBeforeShow">PageManualBeforeShow</property>
  <property name="OnCreate">PageManualCreate</property>
  <object class="MEdit" name="EditCameraName" >
    <property name="Animations">a:0:{}</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Name">EditCameraName</property>
    <property name="Top">56</property>
    <property name="Width">363</property>
  </object>
  <object class="MLabel" name="MLabel1" >
    <property name="Alignment">agCenter</property>
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Camera name</property>
    <property name="Font">
    <property name="Family">Helvetica, Arial, sans-serif</property>
    <property name="Size">16px</property>
    </property>
    <property name="Height">20</property>
    <property name="Left">25</property>
    <property name="Name">MLabel1</property>
    <property name="Top">32</property>
    <property name="Width">363</property>
  </object>
  <object class="MLabel" name="MLabel2" >
    <property name="Alignment">agCenter</property>
    <property name="Animations">a:0:{}</property>
    <property name="Caption">RTSP address</property>
    <property name="Font">
    <property name="Family">Helvetica, Arial, sans-serif</property>
    <property name="Size">16px</property>
    </property>
    <property name="Height">20</property>
    <property name="Left">25</property>
    <property name="Name">MLabel2</property>
    <property name="Top">108</property>
    <property name="Width">363</property>
  </object>
  <object class="MEdit" name="EditRTSP" >
    <property name="Animations">a:0:{}</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Name">EditRTSP</property>
    <property name="Top">137</property>
    <property name="Width">363</property>
  </object>
  <object class="MCheckBox" name="CheckBoxDoAudio" >
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Enable Audio</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Name">CheckBoxDoAudio</property>
    <property name="Top">272</property>
    <property name="Width">200</property>
  </object>
  <object class="MButton" name="ButtonAddCamera" >
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Save</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Name">ButtonAddCamera</property>
    <property name="SystemIcon">siCheck</property>
    <property name="Top">360</property>
    <property name="Width">150</property>
    <property name="OnClick">ButtonAddCameraClick</property>
  </object>
  <object class="MLabel" name="Msg" >
    <property name="Alignment">agCenter</property>
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Msg</property>
    <property name="Font">
    <property name="Color">Yellow</property>
    <property name="Family">Helvetica, Arial, sans-serif</property>
    <property name="Size">16px</property>
    </property>
    <property name="Height">20</property>
    <property name="Left">25</property>
    <property name="Name">Msg</property>
    <property name="ParentFont">0</property>
    <property name="Top">328</property>
    <property name="Width">363</property>
  </object>
  <object class="MLink" name="MLinkCancel" >
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Back</property>
    <property name="DivWrap">0</property>
    <property name="Height">43</property>
    <property name="Left">238</property>
    <property name="Link">menu.php</property>
    <property name="Name">MLinkCancel</property>
    <property name="SystemIcon">siBack</property>
    <property name="Top">360</property>
    <property name="Width">150</property>
  </object>
  <object class="MLabel" name="MLabel3" >
    <property name="Alignment">agCenter</property>
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Snapshot URL</property>
    <property name="Font">
    <property name="Family">Helvetica, Arial, sans-serif</property>
    <property name="Size">16px</property>
    </property>
    <property name="Height">20</property>
    <property name="Left">25</property>
    <property name="Name">MLabel3</property>
    <property name="Top">188</property>
    <property name="Width">363</property>
  </object>
  <object class="MEdit" name="EditSnapshot" >
    <property name="Animations">a:0:{}</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Name">EditSnapshot</property>
    <property name="Top">217</property>
    <property name="Width">363</property>
  </object>
</object>
?>
