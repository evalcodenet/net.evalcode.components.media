<?php


namespace Components;


  /**
   * Media
   *
   * @package net.evalcode.components
   * @subpackage media
   *
   * @author evalcode.net
   */
  class Media
  {
    // STATIC ACCESSORS
    /**
     * @param string $path_
     *
     * @return Media_Store
     */
    public static function store($path_)
    {
      if(false===isset(self::$m_cacheStores[$path_]))
        self::$m_cacheStores[$path_]=Media_Store::forPath($path_);

      return self::$m_cacheStores[$path_];
    }

    /**
     * @param string $storePath_
     * @param string $categoryName_
     * @param string $fileName_
     *
     * @return string
     */
    public static function uri($storePath_, $fileName_)
    {
      return self::store($storePath_)->uri($fileName_);
    }

    /**
     * @param string $storePath_
     * @param string $categoryName_
     * @param string $fileName_
     *
     * @return Media_File
     */
    public static function file($storePath_, $fileName_)
    {
      return self::store($storePath_)->file($fileName_);
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    private static $m_cacheStores=array();
    //--------------------------------------------------------------------------
  }
?>
