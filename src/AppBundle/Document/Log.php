<?php
namespace AppBundle\Document;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 */
class Log
{
    /**
     * @MongoDB\String
     */
    protected $uri;

    /**
     * @MongoDB\String
     */
    protected $title;

    /**
     * @Gedmo\Timestampable(on="create")
     * @MongoDB\Date
     */
    protected $date_time;


    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set uri
     *
     * @param string $uri
     * @return self
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * Get uri
     *
     * @return string $uri
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set dateTime
     *
     * @param date $dateTime
     * @return self
     */
    public function setDateTime($dateTime)
    {
        $this->date_time = $dateTime;
        return $this;
    }

    /**
     * Get dateTime
     *
     * @return date $dateTime
     */
    public function getDateTime()
    {
        return $this->date_time;
    }
}
