<?php


namespace Components;


  /**
   * Media_Storage_Image_Filesystem
   *
   * @package net.evalcode.components
   * @subpackage media.storage.image
   *
   * @author evalcode.net
   */
  class Media_Storage_Image_Filesystem implements Media_Storage
  {
    // PROPERTIES
    /**
     * @var Components\Media_Store
     */
    public $store;
    //--------------------------------------------------------------------------


    // OVERRIDES
    /**     * @see Components\Media_Storage::init() Components\Media_Storage::init()
     */
    public function init(Media_Store $store_)
    {
      $this->store=$store_;
    }

    /**     * @see Components\Media_Storage::uri() Components\Media_Storage::uri()
     */
    public function uri($id_, $category_=null)
    {
      return "{$this->store->uri}/{$category_}/{$id_}";
    }

    /**     * @see Components\Media_Storage::uriByScheme() Components\Media_Storage::uriByScheme()
     */
    public function uriByScheme($scheme_, $id_, $category_=null)
    {
      if(@is_file(Environment::pathResource("{$this->store->path}/{$scheme_}/{$category_}/{$id_}")))
        return "{$this->store->uri}/{$scheme_}/{$category_}/{$id_}";

      // TODO (CSH) Http_Scriptlet_Router
      return Environment::uriComponents(sprintf('media/image/%1$s%2$s', String::urlEncodeBase64(serialize(array($this->store->path, $id_, $category_, $scheme_))), substr($id_, strrpos($id_, '.'))));
    }

    /**     * @see Components\Media_Storage::find() Components\Media_Storage::find()
     */
    public function find($id_, $category_=null)
    {
      return Io::file(Environment::pathResource("{$this->store->path}/{$category_}/{$id_}"));
    }

    /**     * @see Components\Media_Storage::findByScheme() Components\Media_Storage::findByScheme()
     */
    public function findByScheme($scheme_, $id_, $category_=null)
    {
      return Io::file(Environment::pathResource("{$this->store->path}/{$scheme_}/{$category_}/{$id_}"), Io_File::WRITE|Io_File::CREATE);
    }

    /**     * @see Components\Media_Storage::create() Components\Media_Storage::create()
     */
    public function add(Io_File $file_, $id_, $category_=null)
    {
      if(null===$category_)
        return $file_->copy(Io::file(Environment::pathResource("{$this->store->path}/{$id_}")), true);

      return $file_->copy(Io::file(Environment::pathResource("{$this->store->path}/{$category_}/{$id_}")), true);
    }

    /**     * @see Components\Media_Storage::addByScheme() Components\Media_Storage::addByScheme()
     */
    public function addByScheme($scheme_, Io_File $file_, $id_, $category_=null)
    {
      return $file_->copy(Io::file(Environment::pathResource("{$this->store->path}/{$scheme_}/{$category_}/{$id_}")), true);
    }

    /**     * @see Components\Media_Storage::createByScheme() Components\Media_Storage::createByScheme()
     */
    public function createByScheme($scheme_, $data_, $id_, $category_=null)
    {
      $file=Io::file(Environment::pathResource("{$this->store->path}/{$scheme_}/{$category_}/{$id_}"), Io_File::WRITE|Io_File::CREATE);
      $file->setContent($data_);

      return $file;
    }

    /**     * @see Components\Media_Storage::delete() Components\Media_Storage::delete()
     */
    public function remove($id_, $category_=null)
    {
      $file=Io::file(Environment::pathResource("{$this->store->path}/{$category_}/{$id_}"));

      if($file->exists())
        $file->delete();
    }

    /**     * @see Components\Media_Storage::drop() Components\Media_Storage::drop()
     */
    public function drop($category_)
    {
      $path=Io::path(Environment::pathResource("{$this->store->path}/{$category_}"));

      if($path->exists())
        $path->delete(true);
    }

    /**     * @see Components\Media_Storage::copyCategory() Components\Media_Storage::copyCategory()
     */
    public function copyCategory($categorySource_, $categoryTarget_)
    {
      $source=Io::path(Environment::pathResource("{$this->store->path}/{$categorySource_}"));

      if(false===$source->exists())
        return false;

      $source->copy(
        Io::path(Environment::pathResource("{$this->store->path}/{$categoryTarget_}"))
      );

      return true;
    }

    /**     * @see Components\Object::hashCode() Components\Object::hashCode()
     */
    public function hashCode()
    {
      return object_hash($this);
    }

    /**     * @see Components\Object::equals() Components\Object::equals()
     */
    public function equals($object_)
    {
      if($object_ instanceof self)
        return $this->hashCode()===$object_->hashCode();

      return false;
    }

    /**     * @see Components\Object::__toString() Components\Object::__toString()
     */
    public function __toString()
    {
      return sprintf('%s@%s{}', __CLASS__, $this->hashCode());
    }
    //--------------------------------------------------------------------------
  }
?>
