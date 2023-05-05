<?php
<object class="dmauth" name="dmauth" baseclass="DataModule">
  <property name="Animations">a:0:{}</property>
  <property name="Height">370</property>
  <property name="Name">dmauth</property>
  <property name="Width">597</property>
  <object class="ZAuth" name="ZAuth" >
        <property name="Left">416</property>
        <property name="Top">152</property>
    <property name="AuthAdapter">ZAuthDigest</property>
    <property name="Name">ZAuth</property>
    <property name="UserRealm">rokuphp</property>
    <property name="OnLogin">ZAuthLogin</property>
  </object>
  <object class="ZAuthDigest" name="ZAuthDigest" >
        <property name="Left">416</property>
        <property name="Top">224</property>
    <property name="FileName">data/validuser.txt</property>
    <property name="Name">ZAuthDigest</property>
  </object>
</object>
?>
