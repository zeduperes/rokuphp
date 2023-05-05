<?php
<object class="DeletePage" name="DeletePage" baseclass="MPage">
<property name="DesignConfigName">Samsung Galaxy S II - Vertical (480x762)</property>
<property name="DesignConfigWidth">480</property>
<property name="DesignConfigHeight">650</property>
<property name="UseBackground">0</property>
  <property name="Animations">a:0:{}</property>
  <property name="AutoSize">1</property>
  <property name="Background"></property>
  <property name="Caption">Delete</property>
  <property name="Font">
  <property name="Family">Helvetica, Arial, sans-serif</property>
  <property name="Size">16px</property>
  </property>
  <property name="Height">650</property>
  <property name="HiddenFields">a:0:{}</property>
  <property name="Name">DeletePage</property>
  <property name="Theme">dm.MobileTheme1</property>
  <property name="UseAjax">1</property>
  <property name="Width">480</property>
  <property name="OnBeforeShow">DeletePageBeforeShow</property>
  <property name="OnCreate">DeletePageCreate</property>
  <object class="MEdit" name="EntryBox" >
    <property name="Animations">a:0:{}</property>
    <property name="DataField">item</property>
    <property name="Enabled">0</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Name">EntryBox</property>
    <property name="Top">58</property>
    <property name="Width">363</property>
  </object>
  <object class="MButton" name="Delete" >
    <property name="Animations">a:0:{}</property>
    <property name="ButtonType">btNormal</property>
    <property name="Caption">Delete</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Name">Delete</property>
    <property name="Top">114</property>
    <property name="Width">363</property>
    <property name="OnClick">DeleteClick</property>
  </object>
  <object class="MLabel" name="MLabel1" >
    <property name="Alignment">agCenter</property>
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Delete Camera</property>
    <property name="Font">
    <property name="Family">Helvetica, Arial, sans-serif</property>
    <property name="Size">16px</property>
    </property>
    <property name="Height">20</property>
    <property name="Left">25</property>
    <property name="Name">MLabel1</property>
    <property name="Top">24</property>
    <property name="Width">363</property>
  </object>
  <object class="MLink" name="MLinkCancel" >
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Cancel</property>
    <property name="DivWrap">0</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Link">menu.php</property>
    <property name="Name">MLinkCancel</property>
    <property name="Top">168</property>
    <property name="Width">363</property>
  </object>
</object>
?>
