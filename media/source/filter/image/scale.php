<?php


namespace Components;


  /**
   * Media_Filter_Image_Scale
   *
   * @package net.evalcode.components
   * @subpackage media.filter.image
   *
   * @author evalcode.net
   */
  class Media_Filter_Image_Scale implements Media_Filter
  {
    // OVERRIDES
    /**
     * (non-PHPdoc)
     * @see Components.Media_Filter::filter()
     */
    public function filter($data_, array $args_=array())
    {
      // TODO Implement ...
      return $data_;
    }

    /**
     * (non-PHPdoc)
     * @see Components.Object::hashCode()
     */
    public function hashCode()
    {
      return object_hash($this);
    }

    /**
     * (non-PHPdoc)
     * @see Components.Object::equals()
     */
    public function equals($object_)
    {
      if($object_ instanceof self)
        return $this->hashCode()===$object_->hashCode();

      return false;
    }

    /**
     * (non-PHPdoc)
     * @see Components.Object::__toString()
     */
    public function __toString()
    {
      return sprintf('%s@%s{}', __CLASS__, $this->hashCode());
    }
    //--------------------------------------------------------------------------
  }
?>
