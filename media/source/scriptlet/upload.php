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
    // OVERRIDES/IMPLEMENTS
    public function post($store_, $category_=null)
    {
      if(1>count($_FILES))
        return;

      $fileInfo=reset($_FILES);
      $fileTmp=Io::tmpFile();
      $fileName=Io::sanitizeFileName($fileInfo['name']);

      @move_uploaded_file($fileInfo['tmp_name'], $fileTmp);

      $store=Media::store($store_);
      $store->add($fileTmp, $fileName, $category_);

      echo $store->uri($fileName, $category_);
    }

    public function get($store_, $category_=null)
    {
      return $this->post($store_, $category_);
    }
    //--------------------------------------------------------------------------
  }
?>
