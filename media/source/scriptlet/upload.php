<?php


namespace Components;


  /**
   * Media_Scriptlet_Upload
   *
   * @package net.evalcode.components
   * @subpackage media.scriptlet
   *
   * @author evalcode.net
   */
  class Media_Scriptlet_Upload extends Http_Scriptlet
  {
    // OVERRIDES
    public static function dispatch(Http_Scriptlet_Context $context_, Uri $uri_)
    {
      if(1>count($_FILES))
        return;

      $params=$uri_->getPathParams();

      $storeName=array_shift($params);
      $categoryName=array_shift($params);

      $fileInfo=reset($_FILES);
      $fileTmp=Io::tmpFile();
      $fileName=Io::sanitizeFileName($fileInfo['name']);

      move_uploaded_file($fileInfo['tmp_name'], $fileTmp);

      $store=Media::store($storeName);
      $store->add($fileTmp, $fileName, $categoryName);

      // TODO JSON
      echo $store->uri($fileName, $categoryName);
    }
    //--------------------------------------------------------------------------
  }
?>
