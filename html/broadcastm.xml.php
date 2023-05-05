<?php
<object class="PageLive" name="PageLive" baseclass="MPage">
<property name="DesignConfigName">Samsung Galaxy S II - Vertical (480x762)</property>
<property name="DesignConfigWidth">480</property>
<property name="DesignConfigHeight">650</property>
<property name="UseBackground">0</property>
  <property name="Animations">a:0:{}</property>
  <property name="AutoSize">1</property>
  <property name="Background"></property>
  <property name="Caption">Live Broadcast</property>
  <property name="Font">
  <property name="Family">Helvetica, Arial, sans-serif</property>
  <property name="Size">16px</property>
  </property>
  <property name="Height">650</property>
  <property name="HiddenFields">a:0:{}</property>
  <property name="Name">PageLive</property>
  <property name="Theme">dm.MobileTheme1</property>
  <property name="UseAjax">1</property>
  <property name="Width">480</property>
  <property name="OnBeforeShow">PageLiveBeforeShow</property>
  <property name="OnCreate">PageLiveCreate</property>
  <object class="MLink" name="MLinkCancel" >
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Back</property>
    <property name="DivWrap">0</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Link">menu.php</property>
    <property name="Name">MLinkCancel</property>
    <property name="SystemIcon">siBack</property>
    <property name="Top">464</property>
    <property name="Width">363</property>
  </object>
  <object class="MLabel" name="MLabel1" >
    <property name="Alignment">agCenter</property>
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Select camera to live stream</property>
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
    <property name="Caption">To</property>
    <property name="Font">
    <property name="Family">Helvetica, Arial, sans-serif</property>
    <property name="Size">16px</property>
    </property>
    <property name="Height">20</property>
    <property name="Left">25</property>
    <property name="Name">MLabel2</property>
    <property name="Top">107</property>
    <property name="Width">363</property>
  </object>
  <object class="MLabel" name="MLabel3" >
    <property name="Alignment">agCenter</property>
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Stream key</property>
    <property name="Font">
    <property name="Family">Helvetica, Arial, sans-serif</property>
    <property name="Size">16px</property>
    </property>
    <property name="Height">20</property>
    <property name="Left">25</property>
    <property name="Name">MLabel3</property>
    <property name="Top">187</property>
    <property name="Width">363</property>
  </object>
  <object class="MComboBox" name="ComboBoxCameras" >
    <property name="Animations">a:0:{}</property>
    <property name="Height">43</property>
    <property name="Items">a:0:{}</property>
    <property name="Left">25</property>
    <property name="Name">ComboBoxCameras</property>
    <property name="Sorted">1</property>
    <property name="Top">56</property>
    <property name="Width">363</property>
    <property name="OnShow">ComboBoxCamerasShow</property>
  </object>
  <object class="MComboBox" name="ComboBoxProviders" >
    <property name="Animations">a:0:{}</property>
    <property name="Height">43</property>
    <property name="Items">a:0:{}</property>
    <property name="Left">25</property>
    <property name="Name">ComboBoxProviders</property>
    <property name="Top">128</property>
    <property name="Width">363</property>
    <property name="OnChange">ComboBoxProvidersChange</property>
    <property name="OnShow">ComboBoxProvidersShow</property>
  </object>
  <object class="MEdit" name="EditKey" >
    <property name="Animations">a:0:{}</property>
    <property name="Enabled">0</property>
    <property name="Height">43</property>
    <property name="InputType">cePassword</property>
    <property name="Left">25</property>
    <property name="Name">EditKey</property>
    <property name="Top">216</property>
    <property name="Width">363</property>
  </object>
  <object class="MButton" name="ButtonSave" >
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Save Key</property>
    <property name="Enabled">0</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Name">ButtonSave</property>
    <property name="SystemIcon">siCheck</property>
    <property name="Top">272</property>
    <property name="Width">163</property>
    <property name="OnClick">ButtonSaveClick</property>
  </object>
  <object class="MButton" name="ButtonStart" >
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Start Live Broadcast</property>
    <property name="Enabled">0</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Name">ButtonStart</property>
    <property name="SystemIcon">siArrowR</property>
    <property name="Top">352</property>
    <property name="Width">363</property>
    <property name="OnClick">ButtonStartClick</property>
  </object>
  <object class="MButton" name="ButtonStop" >
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Stop All Broadcasts</property>
    <property name="Enabled">0</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Name">ButtonStop</property>
    <property name="SystemIcon">siDelete</property>
    <property name="Top">408</property>
    <property name="Width">363</property>
    <property name="OnClick">ButtonStopClick</property>
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
    <property name="Top">323</property>
    <property name="Width">363</property>
  </object>
</object>
?>
