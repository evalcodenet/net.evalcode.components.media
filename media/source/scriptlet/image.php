<?php


namespace Components;


  /**
   * Media_Scriptlet_Image
   *
   * @package net.evalcode.components
   * @subpackage media.scriptlet
   *
   * @author evalcode.net
   */
  class Media_Scriptlet_Image extends Http_Scriptlet
  {
    // OVERRIDES
    public function post()
    {
      $chunks=explode('/', $_SERVER['REQUEST_URI']);
      $base64=end($chunks);

      $info=unserialize(String::urlDecodeBase64($base64));

      $path=array_shift($info);
      $id=array_shift($info);
      $category=array_shift($info);
      $scheme=array_shift($info);

      $store=Media::store($path);
      $file=$store->findByScheme($scheme, $id, $category);

      header('Content-Length: '.$file->getSize()->bytes());

      return $file->getContent();
    }

    public function get()
    {
      return $this->post();
    }
    //--------------------------------------------------------------------------
  }
?>
