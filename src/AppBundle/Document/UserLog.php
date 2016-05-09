<?php
namespace AppBundle\Document;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class UserLog
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $user_name;

    /**
     * @Gedmo\Timestampable(on="create")
     * @MongoDB\Date
     */
    protected $created_at;

    /**
     * @Gedmo\Timestampable(on="update")
     * @MongoDB\Date
     */
    protected $updated_at;

    /**
     * @MongoDB\EmbedMany(targetDocument="Log")
     */
    protected $logs = [];

    /**
     * UserLog constructor.
     */
    public function __construct()
    {
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set userName
     *
     * @param string $userName
     * @return self
     */
    public function setUserName($userName)
    {
        $this->user_name = $userName;
        return $this;
    }

    /**
     * Get userName
     *
     * @return string $userName
     */
    public function getUserName()
    {
        return $this->user_name;
    }

    /**
     * Set createdAt
     *
     * @param date $createdAt
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return date $createdAt
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Дата заведения пользователя
     *
     * @return string
     */
    public function getCreatedAtFormat($format = 'd.m.y H:i')
    {
        return $this->getCreatedAt()->format($format);
    }

    /**
     * Set updatedAt
     *
     * @param date $updatedAt
     * @return self
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return date $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Дата обновления пользователя
     *
     * @return string
     */
    public function getUpdatedAtFormat($format = 'd.m.y H:i')
    {
        return $this->getUpdatedAt()->format($format);
    }

    /**
     * Add log
     *
     * @param AppBundle\Document\Log $log
     */
    public function addLog(\AppBundle\Document\Log $log)
    {
        $this->logs[] = $log;
    }

    /**
     * Remove log
     *
     * @param AppBundle\Document\Log $log
     */
    public function removeLog(\AppBundle\Document\Log $log)
    {
        $this->logs->removeElement($log);
    }

    /**
     * Get logs
     *
     * @return \Doctrine\Common\Collections\Collection $logs
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * 5 последних логов
     *
     * @return array
     */
    public function getLastLogs()
    {
        return $this->getLogs()->slice(-5,5);
    }

    /**
     * Кол-во логов у пользователя
     *
     * @return int
     */
    public function getCountLogs()
    {
        return $this->getLogs()->count();
    }
}
