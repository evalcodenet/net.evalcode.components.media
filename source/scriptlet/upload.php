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
      $params=$uri_->getPathParams();

      $storeName=array_shift($params);
      $categoryName=array_shift($params);

      $file=Io::fileUpload();

      $store=Media::store($storeName);
      $store->add($file, $file->getName(), $categoryName);

      // TODO JSON
      echo $store->uri($file->getName(), $categoryName);
    }
    //--------------------------------------------------------------------------
  }
?>
