<?php


namespace Components;


  /**
   * Media_Filter
   *
   * @package net.evalcode.components
   * @subpackage media
   *
   * @author evalcode.net
   */
  interface Media_Filter
  {
    // ACCESSORS/MUTATORS
    /**
     * @param string $data_
     * @param array $args_
     *
     * @return string
     */
    function filter($data_, array $args_=array());
    //--------------------------------------------------------------------------
  }
?>
