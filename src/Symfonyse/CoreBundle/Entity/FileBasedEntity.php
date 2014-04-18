<?php

namespace Symfonyse\CoreBundle\Entity;

use Symfony\Component\Yaml\Yaml;
use Symfonyse\CoreBundle\ContentProvider\CoreContentProvider;
use Symfonyse\CoreBundle\Model\FileInfo;

/**
 *
 */
abstract class FileBasedEntity
{
    /**
     * @var \Symfonyse\CoreBundle\Model\FileInfo fileInfo
     *
     */
    protected $fileInfo;

    /**
     * @var array meta
     *
     */
    private $meta;

    /**
     * @var string content
     *
     */
    protected $content;

    /**
     * @var bool fileLoaded
     * Use this to find if the file is loaded or not
     */
    private $fileLoaded=false;

    /**
     * @param FileInfo $file
     */
    public function __construct(FileInfo $file)
    {
        $this->fileInfo = $file;
    }

    /**
     * Get the absolute path to the file
     *
     * @return string
     */
    public function getAbsolutePath()
    {
        return $this->fileInfo->getPathname();
    }

    /**
     * Get the content of the file
     */
    public function getContent()
    {
        $this->loadFile();

        return $this->content;
    }

    /**
     * Load file.
     * Parse the meta data and get the content
     */
    protected function loadFile()
    {
        if ($this->isFileLoaded()) {
            return;
        }

        $content=$this->fileInfo->getContent();
        $matches = array();
        if (preg_match("/---(.*?)---(.*)/ms", $content, $matches)) {
            $this->meta = Yaml::parse($matches[1]);
            $this->content = trim($matches[2]);
        } else {
            $this->content = $content;
        }

        $this->content = preg_replace('|\{\%.*?\%\}|sim', '', $this->content);

        $this->fileLoaded=true;
    }

    /**
     *
     * @return boolean
     */
    public function isFileLoaded()
    {
        return $this->fileLoaded;
    }

    /**
     *
     *
     * @param $name
     * @param $value
     *
     * @return $this
     */
    public function setMeta($name, $value)
    {
        $this->meta[$name]=$value;

        return $this;
    }

    /**
     *
     *
     * @param $name
     *
     * @return mixed|null
     */
    public function getMeta($name)
    {
        if (!isset($this->meta[$name])) {
            return null;
        }

        return $this->meta[$name];
    }

    public function getPostedAt()
    {
        return \DateTime::createFromFormat('U', $this->getMeta('published'));
    }

    public function getModifiedAt()
    {
        return \DateTime::createFromFormat('U', $this->fileInfo->getMTime());
    }

    /**
     * Get name of tags
     *
     * @return mixed|null
     */
    public function getTags()
    {
        $tags=$this->getMeta('tags');
        if ($tags==null) {
            return array();
        }

        return $tags;
    }
}