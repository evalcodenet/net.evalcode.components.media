<?php


namespace Components;


  /**
   * Media_Filter
   *
   * @package net.evalcode.components.media
   *
   * @author evalcode.net
   *
   * @api
   */
  interface Media_Filter extends Object
  {
    // ACCESSORS
    /**
     * @param string $data_
     * @param array $args_
     *
     * @return string
     */
    function filter($data_, array $args_=[]);
    //--------------------------------------------------------------------------
  }
?>
