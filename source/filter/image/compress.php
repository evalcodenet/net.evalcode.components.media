<?php


namespace Components;


  /**
   * Media_Filter_Image_Compress
   *
   * @package net.evalcode.components.media
   * @subpackage filter.image
   *
   * @author evalcode.net
   */
  class Media_Filter_Image_Compress implements Media_Filter
  {
    // OVERRIDES
    /**
     * @see \Components\Media_Filter::filter() \Components\Media_Filter::filter()
     */
    public function filter($data_, array $args_=[])
    {
      // TODO Implement ...
      return $data_;
    }

    /**
     * @see \Components\Object::hashCode() \Components\Object::hashCode()
     */
    public function hashCode()
    {
      return \math\hasho($this);
    }

    /**
     * @see \Components\Object::equals() \Components\Object::equals()
     */
    public function equals($object_)
    {
      if($object_ instanceof self)
        return $this->hashCode()===$object_->hashCode();

      return false;
    }

    /**
     * @see \Components\Object::__toString() \Components\Object::__toString()
     */
    public function __toString()
    {
      return sprintf('%s@%s{}', __CLASS__, $this->hashCode());
    }
    //--------------------------------------------------------------------------
  }
?>
