<?php
//    $vPathlib = './rpcl/Zend/framework/library' . PATH_SEPARATOR . '/rpcl/PEAR';
if (mb_strpos(ini_get("include_path"),"/rpcl/Zend/framework/library",0, 'UTF-8')===false)
{
  ini_set('include_path', ini_get("include_path") . PATH_SEPARATOR . getenv('DOCUMENT_ROOT')."../");
  ini_set('include_path', ini_get("include_path") . PATH_SEPARATOR . getenv('DOCUMENT_ROOT').".");
  ini_set('include_path', ini_get("include_path") . PATH_SEPARATOR . getenv('DOCUMENT_ROOT')."/rpcl/PEAR");
  ini_set('include_path', ini_get("include_path") . PATH_SEPARATOR . getenv('DOCUMENT_ROOT')."/rpcl/Zend/framework/library");
}

?>