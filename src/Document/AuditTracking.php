<?php
/**
 * document class for Graviton\AuditTrackingBundle\Document\ActivityLog
 */

namespace Graviton\AuditTrackingBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://swisscom.ch
 *
 * @ODM\Document(collection="App")
 * @ODM\InheritanceType("COLLECTION_PER_CLASS")
 */
class AuditTracking
{
    /**
     * @var mixed $id
     *
     * @ODM\Id(type="string", strategy="CUSTOM", options={"class"="Graviton\DocumentBundle\Doctrine\IdGenerator"})
     */
    protected $id;

    /**
     * @var string $thread
     *
     * @ODM\Field(type="string")
     */
    protected $thread;

    /**
     * @var string $username
     *
     * @ODM\Field(type="string")
     */
    protected $username;

    /**
     * @var string $action
     *
     * @ODM\Field(type="string")
     */
    protected $action;

    /**
     * @var string $type
     *
     * @ODM\Field(type="string")
     */
    protected $type;

    /**
     * @var string $location
     *
     * @ODM\Field(type="string")
     */
    protected $location;

    /**
     * @var ArrayCollection $data
     *
     * @ODM\Field(type="raw")
     */
    protected $data;

    /**
     * @var string $collectionName
     *
     * @ODM\Field(type="string")
     */
    protected $collectionId;

    /**
     * @var string $collectionName
     *
     * @ODM\Field(type="string")
     */
    protected $collectionName;

    /**
     * @var \datetime $createdAt
     *
     * @ODM\Field(type="date")
     */
    protected $createdAt;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * @param string $thread string id to UUID thread for user
     * @return void
     */
    public function setThread($thread)
    {
        $this->thread = $thread;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username Current user name
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action what happened
     * @return void
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type type of event
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location where did the action happen
     * @return void
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return object
     */
    public function getData()
    {
        return empty($this->data) ? null : $this->data;
    }

    /**
     * @param Object $data additional information
     * @return void
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getCollectionId()
    {
        return $this->collectionId;
    }

    /**
     * @param mixed $collectionId Collection ID
     * @return void
     */
    public function setCollectionId($collectionId)
    {
        $this->collectionId = $collectionId;
    }

    /**
     * @return mixed
     */
    public function getCollectionName()
    {
        return $this->collectionName;
    }

    /**
     * @param mixed $collectionName Collection name
     * @return void
     */
    public function setCollectionName($collectionName)
    {
        $this->collectionName = $collectionName;
    }

    /**
     * @return \datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \datetime $createdAt when the event took place
     * @return void
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
}
