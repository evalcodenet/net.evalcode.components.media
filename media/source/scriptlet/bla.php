<?php


namespace Components;


  /**
   * Media_Scriptlet_Bla
   *
   * @package net.evalcode.components
   * @subpackage media.scriptlet
   *
   * @author evalcode.net
   */
  class Media_Scriptlet_Bla extends Http_Scriptlet
  {
    // OVERRIDES/IMPLEMENTS
    public function post()
    {
      return 'foo';
    }

    public function get()
    {
      return $this->post();
    }
    //--------------------------------------------------------------------------
  }
?>
