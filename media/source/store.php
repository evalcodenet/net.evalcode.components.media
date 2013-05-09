<?php


namespace Components;


  /**
   * Media_Store
   *
   * @package net.evalcode.components
   * @subpackage media
   *
   * @author evalcode.net
   *
   * TODO Use Io_File, Io_Image ..
   */
  class Media_Store implements Object /* TODO (CSH) Serializable_Php, Serializable_Json */
  {
    // PREDEFINED PROPERTIES
    const NAME_MANIFEST='.manifest';
    //--------------------------------------------------------------------------


    // PROPERTIES
    /**
     * @var string
     */
    public $path;
    /**
     * @var string
     */
    public $uri;
    //--------------------------------------------------------------------------


    // CONSTRUCTION
    public function __construct($path_)
    {
      $this->path=$path_;
    }
    //--------------------------------------------------------------------------


    // STATIC ACCESSORS
    /**
     * @param string $path_
     *
     * @return Components\Media_Store
     */
    public static function forPath($path_)
    {
      if($serialized=Cache::get('components/media/store/'.md5($path_)))
        return @unserialize($serialized);

      $instance=new self($path_);
      $instance->load();

      Cache::set('components/media/store/'.md5($path_), serialize($instance));

      return $instance;
    }

    /**
     * @param string $path_
     * @param string $storage_
     * @param array|string $schema_
     *
     * @return Components\Media_Store
     */
    public static function create($path_, $storage_, array $schema_)
    {
      $instance=new self($path_);
      $instance->initialize();

      @file_put_contents($instance->path.'/'.self::NAME_MANIFEST, json_encode(array(
        'storage'=>$storage_,
        'schema'=>$schema_
      )));

      $instance->load();

      Cache::set('components/media/store/'.md5($path_), serialize($instance));

      return $instance;
    }
    //--------------------------------------------------------------------------


    // ACCESSORS
    /**
     * @param string $id_
     * @param string $category_
     *
     * @return string
     */
    public function uri($id_, $category_=null)
    {
      return $this->m_storage->uri($id_, $category_);
    }

    /**
     * @param string $id_
     * @param string $category_
     *
     * @return Components\Io_File
     */
    public function find($id_, $category_=null)
    {
      return $this->m_storage->find($id_, $category_);
    }

    /**
     * @param string $scheme_
     * @param string $id_
     * @param string $category_
     *
     * @return Components\Io_File
     */
    public function findByScheme($scheme_, $id_, $category_=null)
    {
      if(false===isset($this->m_schema[$scheme_]))
        return null;

      $file=$this->m_storage->findByScheme($scheme_, $id_, $category_);

      if(false===$file->exists())
      {
        $scheme=$this->m_schema[$scheme_];
        $image=$this->m_storage->find($id_, $category_)->getContent();

        foreach($scheme as $args)
        {
          $filter=array_shift($args);
          $filter=new $filter();

          $image=$filter->filter($image, $args);
        }

        $file->setContent($image);
      }

      return $file;
    }

    /**
     * @param Components\Io_File $file_
     * @param string $id_
     * @param string $category_
     *
     * @return Components\Io_File
     */
    public function add(Io_File $file_, $id_, $category_=null)
    {
      return $this->m_storage->add($file_, $id_, $category_);
    }

    /**
     * @param string $id_
     * @param string $category_
     *
     * @return boolean
     */
    public function remove($id_, $category_=null)
    {
      return $this->m_storage->remove($id_, $category_);
    }

    /**
     * Remove category.
     *
     * @param string $category_
     */
    public function drop($category_)
    {
      return $this->m_storage->drop($category_);
    }

    /**
     * @return array|Components\Media_Filter
     */
    public function getSchema()
    {
      return $this->m_schema;
    }

    /**
     * @param string $name_
     *
     * @return Components\Media_Filter
     */
    public function getScheme($name_)
    {
      if(false===isset($this->m_schema[$name_]))
        return null;

      return $this->m_schema[$name_];
    }

    /**
     * @return Components\Media_Storage
     */
    public function getStorage()
    {
      return $this->m_storage;
    }
    //--------------------------------------------------------------------------


    // OVERRIDES
    public function __call($name_, array $args_=array())
    {
      if(false===isset($this->m_schema[$name_]))
        return null;

      return $this->m_storage->uriByScheme($name_, array_shift($args_), array_shift($args_));
    }

    public function __sleep()
    {
      return array('path', 'm_manifest');
    }

    public function __wakeup()
    {
      $this->load();
    }

    /**
     * (non-PHPdoc)
     * @see Components.Object::hashCode()
     */
    public function hashCode()
    {
      return string_hash($this->path);
    }

    /**
     * (non-PHPdoc)
     * @see Components.Object::equals()
     */
    public function equals($object_)
    {
      if($object_ instanceof self)
        return String::equal($this->path, $object_->path);

      return false;
    }

    /**
     * (non-PHPdoc)
     * @see Components.Object::__toString()
     */
    public function __toString()
    {
      return sprintf('%s@%s{path: %s}',
        __CLASS__,
        $this->hashCode(),
        $this->path
      );
    }
    //--------------------------------------------------------------------------


    // IMPLEMENTATION
    /**
     * @var Media_Storage
     */
    private $m_storage;
    /**
     * @var array|Media_Filter
     */
    private $m_schema=array();
    /**
     * @var array|string
     */
    private $m_manifest=array();
    //-----


    private function initialize()
    {
      $path=Environment::pathResource($this->path);

      if(false===@is_dir($path))
      {
        if(false===@mkdir($path, 0755, true))
        {
          throw new Runtime_Exception('components/media/store', sprintf(
            'Unable to initialize media store for given path [path: %1$s].', $path
          ));
        }

        return;
      }

      if(false===@is_writable($path))
      {
        throw new Runtime_Exception('components/media/store', sprintf(
          'Unable to initialize media store at given location due to lack of permissions [path: %1$s].', $path
        ));
      }
    }

    private function load()
    {
      $path=Environment::pathResource($this->path);

      if(false===@is_file($manifest=($path.'/'.self::NAME_MANIFEST)))
      {
        throw new Runtime_Exception('components/media/store', sprintf(
          'Unable to resolve media store manifest [path: %1$s, manifest: %2$s].', $path, self::NAME_MANIFEST
        ));
      }

      $this->m_manifest=@json_decode(file_get_contents($manifest), true);
      $this->uri=$this->m_manifest['uri'];

      $this->m_storage=new $this->m_manifest['storage']();
      $this->m_storage->init($this);

      foreach($this->m_manifest['schema'] as $schema)
      {
        foreach($schema as $name=>$scheme)
          $this->m_schema[$name]=$scheme;
      }
    }
    //--------------------------------------------------------------------------
  }
?>
