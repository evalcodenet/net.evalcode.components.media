<?php


namespace Components;


  /**
   * Media_Storage
   *
   * @package net.evalcode.components
   * @subpackage media
   *
   * @author evalcode.net
   */
  interface Media_Storage extends Object
  {
    // ACCESSORS
    /**
     * Initialize storage engine per request if necessary.
     *
     * @param \Components\Media_Store $store_
     */
    function init(Media_Store $store_);

    /**
     * Resolve uri to original file.
     *
     * @param string $id_
     * @param string $category_
     *
     * @return string
     */
    function uri($id_, $category_=null);

    /**
     * Resolve uri to file for defined scheme.
     *
     * @param string $scheme_
     * @param string $id_
     * @param string $category_
     *
     * @return string
     */
    function uriByScheme($scheme_, $id_, $category_=null);

    /**
     * Resolve original file.
     *
     * @param string $id_
     * @param string $category_
     *
     * @return \Components\Io_File
     */
    function find($id_, $category_=null);

    /**
     * Resolve file for defined scheme.
     *
     * @param string $scheme_
     * @param string $id_
     * @param string $category_
     *
     * @return \Components\Io_File
     */
    function findByScheme($scheme_, $id_, $category_=null);

    /**
     * Add file.
     *
     * @param \Components\Io_File $file_
     * @param string $id_
     * @param string $category_
     *
     * @return \Components\Io_File
     */
    function add(Io_File $file_, $id_, $category_=null);

    /**
     * Add file for defined scheme.
     *
     * @param string $scheme_
     * @param \Components\Io_File $file_
     * @param string $id_
     * @param string $category_
     *
     * @return \Components\Io_File
     */
    function addByScheme($scheme_, Io_File $file_, $id_, $category_=null);

    /**
     * @param string $scheme_
     * @param string $data_
     * @param string $id_
     * @param string $category_
     *
     * @return \Components\Io_File
     */
    function createByScheme($scheme_, $data_, $id_, $category_=null);

    /**
     * Remove file.
     *
     * @param string $id_
     * @param string $category_
     *
     * @return boolean
     */
    function remove($id_, $category_=null);

    /**
     * Remove category.
     *
     * @param string $category_
     */
    function drop($category_);

    /**
     * @param string $categorySource_
     * @param string $categoryTarget_
     */
    function copyCategory($categorySource_, $categoryTarget_);
    //--------------------------------------------------------------------------
  }
?>
