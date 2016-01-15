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
      $pathTarget=Environment::pathWeb().$uri_->getPath().'/'.\str\decodeUrl($name, true).".$extension";

      if(false===is_file($pathTarget))
      {
        $fileTarget=Io::file($pathTarget);
        $directoryTarget=$fileTarget->getDirectory();

        if(false===$directoryTarget->exists())
          $directoryTarget->create();

        $info=@json_decode(\str\decodeBase64Url($name));

        if(false===isset($info[0]))
          throw new Http_Exception('media/scriptlet/engine', sprintf('Not found [%s].', $uri), Http_Exception::NOT_FOUND);

        $pathSource=Environment::pathWeb($info[0]);

        if(false===is_file($pathSource))
          throw new Http_Exception('media/scriptlet/engine', sprintf('Not found [%s].', $uri), Http_Exception::NOT_FOUND);

        if(isset($info[1]) || isset($info[2]))
        {
          if(!isset($info[1]))
            $info[1]=0;
          if(!isset($info[2]))
            $info[2]=0;

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
     * @param string $path_
     * @param integer $width_
     * @param integer $height_
     *
     * @return \Components\Uri
     */
    public static function imageUri($route_, Io_Path $path_, $width_=null, $height_=null)
    {
      $uri=Uri::valueOf(Http_Router::uri($route_));
      $uri->pushPathParam(
        \str\encodeBase64Url(json_encode([
          (string)Io::path(Environment::pathWeb())->getRelativePath($path_),
          $width_,
          $height_
        ])).
        '.'.
        Io::fileExtension($path_)
      );

      return $uri;
    }

    /**
     * @param string $path_
     * @param integer $width_
     * @param integer $height_
     *
     * @return string
     */
    public static function imagePath($route_, Io_Path $path_, $width_=null, $height_=null)
    {
      $file=\str\encodeBase64Url(json_encode([
          (string)Io::path(Environment::pathWeb())->getRelativePath($path_),
          $width_,
          $height_
        ])).
        '.'.
        Io::fileExtension($path_);

      return Http_Router::path($route_)."/$file";
    }
    //--------------------------------------------------------------------------
  }
?>
