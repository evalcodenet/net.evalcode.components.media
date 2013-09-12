<?php


namespace Components;


  /**
   * Media_Scriptlet_Image
   *
   * @package net.evalcode.components.media
   * @subpackage scriptlet
   *
   * @author evalcode.net
   */
  class Media_Scriptlet_Image extends Http_Scriptlet
  {
    // OVERRIDES
    public static function dispatch(Http_Scriptlet_Context $context_, Uri $uri_)
    {
      $params=$uri_->getPathParams();
      $base64=end($params);

      $info=unserialize(String::urlDecodeBase64($base64));

      $path=array_shift($info);
      $id=array_shift($info);
      $category=array_shift($info);
      $scheme=array_shift($info);

      $store=Media::store($path);
      $file=$store->findByScheme($scheme, $id, $category);

      header('Content-Length: '.$file->getSize()->bytes());
      readfile((string)$file);
    }
    //--------------------------------------------------------------------------
  }
?>
