<?php


namespace Components;


  /**
   * Media_Scriptlet_Engine
   *
   * @package net.evalcode.components.media
   * @subpackage scriptlet
   *
   * @author evalcode.net
   */
  class Media_Scriptlet_Engine extends Http_Scriptlet
  {
    // OVERRIDES
    /**
     * @param \Components\Http_Scriptlet_Context $context_
     * @param \Components\Uri $uri_
     */
    public static function dispatch(Http_Scriptlet_Context $context_, Uri $uri_)
    {
      $uri=$context_->getRequest()->getUri();
      $extension=$uri->getFileExtension();
      $name=$uri->getFilename(true);

      $uri_->popPathParam();
      $pathTarget=Environment::pathApplication().$uri_->getPath().'/'.String::urlDecode($name).".$extension";

      if(false===is_file($pathTarget))
      {
        $fileTarget=Io::file($pathTarget);
        $directoryTarget=$fileTarget->getDirectory();

        if(false===$directoryTarget->exists())
          $directoryTarget->create();

        $info=@json_decode(String::fromBase64Url($name));

        if(false===isset($info[0]))
          throw Http_Exception::notFound('media/scriptlet/engine', sprintf('Not found [%s].', $uri));

        $pathSource=Environment::pathApplication().$info[0];

        if(false===is_file($pathSource))
          throw Http_Exception::notFound('media/scriptlet/engine', sprintf('Not found [%s].', $uri));

        if(isset($info[1]) && isset($info[2]))
        {
          Io::image($pathSource)
            ->scale(Point::of($info[1], $info[2]))
            ->saveAs($fileTarget);
        }
        else
        {
          Io::file($pathSource)->copy($fileTarget);
        }
      }

      header('Content-Length: '.Io::fileSize($pathTarget)->bytes());
      readfile($pathTarget);
    }

    /**
     * @param \Components\Uri $target_
     * @param string $path_
     * @param integer $width_
     * @param integer $height_
     *
     * @return \Components\Uri
     */
    public static function imageUri(Uri $target_, Io_Path $path_, $width_=null, $height_=null)
    {
      $target_->pushPathParam(
        String::toBase64Url(json_encode([
          (string)Io::path(Environment::pathApplication())->getRelativePath($path_),
          $width_,
          $height_
        ])).
        '.'.
        Io::fileExtension($path_)
      );

      return $target_;
    }

    /**
     * @param string $path_
     * @param integer $width_
     * @param integer $height_
     *
     * @return \Components\Uri
     */
    public static function imageName(Io_Path $path_, $width_=null, $height_=null)
    {
      return String::toBase64Url(json_encode([
          (string)Io::path(Environment::pathApplication())->getRelativePath($path_),
          $width_,
          $height_
        ])).
      '.'.
      Io::fileExtension($path_);
    }
    //--------------------------------------------------------------------------
  }
?>
