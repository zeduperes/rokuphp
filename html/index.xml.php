<?php
<object class="PageLogin" name="PageLogin" baseclass="MPage">
<property name="DesignConfigName">Samsung Galaxy S II - Vertical (480x762)</property>
<property name="DesignConfigWidth">480</property>
<property name="DesignConfigHeight">650</property>
<property name="UseBackground">0</property>
  <property name="ActiveControl">ButtonLogin</property>
  <property name="Animations">a:0:{}</property>
  <property name="Background"></property>
  <property name="Caption">Login</property>
  <property name="Font">
  <property name="Family">Helvetica, Arial, sans-serif</property>
  <property name="Size">16px</property>
  </property>
  <property name="Height">650</property>
  <property name="HiddenFields">a:0:{}</property>
  <property name="Name">PageLogin</property>
  <property name="Theme">dm.MobileTheme1</property>
  <property name="UseAjax">1</property>
  <property name="Width">480</property>
  <property name="OnShow">PageLoginShow</property>
  <object class="MLabel" name="MsgCreate" >
    <property name="Alignment">agCenter</property>
    <property name="Animations">a:0:{}</property>
    <property name="Font">
    <property name="Color">Yellow</property>
    <property name="Family">Helvetica, Arial, sans-serif</property>
    <property name="Size">16px</property>
    </property>
    <property name="Height">20</property>
    <property name="Left">25</property>
    <property name="Name">MsgCreate</property>
    <property name="ParentFont">0</property>
    <property name="Top">263</property>
    <property name="Width">363</property>
  </object>
  <object class="MLabel" name="LabelCPasswor" >
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Password</property>
    <property name="Font">
    <property name="Family">Helvetica, Arial, sans-serif</property>
    <property name="Size">16px</property>
    </property>
    <property name="Height">20</property>
    <property name="Left">27</property>
    <property name="Name">LabelCPasswor</property>
    <property name="Top">119</property>
    <property name="Width">75</property>
  </object>
  <object class="MLabel" name="LabelCRPassword" >
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Verify Password</property>
    <property name="Font">
    <property name="Family">Helvetica, Arial, sans-serif</property>
    <property name="Size">16px</property>
    </property>
    <property name="Height">20</property>
    <property name="Left">27</property>
    <property name="Name">LabelCRPassword</property>
    <property name="Top">185</property>
    <property name="Width">123</property>
  </object>
  <object class="MLabel" name="LabelCLogin" >
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Login</property>
    <property name="Font">
    <property name="Family">Helvetica, Arial, sans-serif</property>
    <property name="Size">16px</property>
    </property>
    <property name="Height">20</property>
    <property name="Left">27</property>
    <property name="Name">LabelCLogin</property>
    <property name="Top">49</property>
    <property name="Width">75</property>
  </object>
  <object class="MEdit" name="EditRePassword" >
    <property name="Animations">a:0:{}</property>
    <property name="DataField">item</property>
    <property name="Height">43</property>
    <property name="InputType">cePassword</property>
    <property name="Left">25</property>
    <property name="Name">EditRePassword</property>
    <property name="TabOrder">3</property>
    <property name="Top">210</property>
    <property name="Width">363</property>
  </object>
  <object class="MEdit" name="EditPassword" >
    <property name="Animations">a:0:{}</property>
    <property name="DataField">item</property>
    <property name="Height">43</property>
    <property name="InputType">cePassword</property>
    <property name="Left">25</property>
    <property name="Name">EditPassword</property>
    <property name="TabOrder">2</property>
    <property name="Top">141</property>
    <property name="Width">363</property>
  </object>
  <object class="MButton" name="ButtonCreateUser" >
    <property name="Animations">a:0:{}</property>
    <property name="ButtonType">btNormal</property>
    <property name="Caption">Save user</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Name">ButtonCreateUser</property>
    <property name="SystemIcon">siCheck</property>
    <property name="TabOrder">4</property>
    <property name="Top">290</property>
    <property name="Width">363</property>
    <property name="OnClick">ButtonCreateUserClick</property>
  </object>
  <object class="MLabel" name="LabelCTitle" >
    <property name="Alignment">agCenter</property>
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Create a new user</property>
    <property name="Font">
    <property name="Family">Helvetica, Arial, sans-serif</property>
    <property name="Size">16px</property>
    </property>
    <property name="Height">20</property>
    <property name="Left">25</property>
    <property name="Name">LabelCTitle</property>
    <property name="Top">24</property>
    <property name="Width">363</property>
  </object>
  <object class="MEdit" name="EditLogin" >
    <property name="Animations">a:0:{}</property>
    <property name="DataField">item</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Name">EditLogin</property>
    <property name="TabOrder">1</property>
    <property name="Top">72</property>
    <property name="Width">363</property>
  </object>
  <object class="MLabel" name="LabelLPassword" >
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Password</property>
    <property name="Font">
    <property name="Family">Helvetica, Arial, sans-serif</property>
    <property name="Size">16px</property>
    </property>
    <property name="Height">20</property>
    <property name="Left">25</property>
    <property name="Name">LabelLPassword</property>
    <property name="Top">119</property>
    <property name="Width">363</property>
  </object>
  <object class="MLabel" name="LabelLLogin" >
    <property name="Animations">a:0:{}</property>
    <property name="Caption">Login</property>
    <property name="Font">
    <property name="Family">Helvetica, Arial, sans-serif</property>
    <property name="Size">16px</property>
    </property>
    <property name="Height">20</property>
    <property name="Left">25</property>
    <property name="Name">LabelLLogin</property>
    <property name="Top">49</property>
    <property name="Width">363</property>
  </object>
  <object class="MEdit" name="EditLoginPassword" >
    <property name="Animations">a:0:{}</property>
    <property name="DataField">item</property>
    <property name="Height">43</property>
    <property name="InputType">cePassword</property>
    <property name="Left">25</property>
    <property name="Name">EditLoginPassword</property>
    <property name="TabOrder">2</property>
    <property name="Top">141</property>
    <property name="Width">363</property>
  </object>
  <object class="MButton" name="ButtonLogin" >
    <property name="Animations">a:0:{}</property>
    <property name="ButtonType">btNormal</property>
    <property name="Caption">Login</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Name">ButtonLogin</property>
    <property name="SystemIcon">siCheck</property>
    <property name="TabOrder">4</property>
    <property name="Top">205</property>
    <property name="Width">363</property>
    <property name="OnClick">ButtonLoginClick</property>
  </object>
  <object class="MEdit" name="EditLoginUser" >
    <property name="Animations">a:0:{}</property>
    <property name="DataField">item</property>
    <property name="Height">43</property>
    <property name="Left">25</property>
    <property name="Name">EditLoginUser</property>
    <property name="TabOrder">1</property>
    <property name="Top">72</property>
    <property name="Width">363</property>
  </object>
</object>
?>
