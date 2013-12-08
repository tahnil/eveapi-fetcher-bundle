<?php

namespace Tarioch\EveapiFetcherBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="charUpcomingEvents", uniqueConstraints={
 *	@ORM\UniqueConstraint(name="event_owner", columns={"eventId", "ownerId"})
 * }, indexes={
 * 	@ORM\Index(name="eventDate", columns={"eventDate"})
 * })
 */
class CharUpcomingEvents
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="bigint", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="eventID", type="bigint", options={"unsigned"=true})
     */
    private $eventId;

    /**
     * @var integer
     *
     * @ORM\Column(name="ownerID", type="bigint", options={"unsigned"=true})
     */
    private $ownerId;

    /**
     * @var string
     *
     * @ORM\Column(name="ownerName", type="string")
     */
    private $ownerName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="eventDate", type="datetime")
     */
    private $eventDate;

    /**
     * @var string
     *
     * @ORM\Column(name="eventTitle", type="string")
     */
    private $eventTitle;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;

    /**
     * @var boolean
     *
     * @ORM\Column(name="importance", type="boolean")
     */
    private $importance;

    /**
     * @var string
     *
     * @ORM\Column(name="eventText", type="text")
     */
    private $eventText;

    /**
     * @var string
     *
     * @ORM\Column(name="response", type="string")
     */
    private $response;

    /**
     * @param integer $eventId
     * @param integer $ownerId
     */
    public function __construct($eventId, $ownerId)
    {
        $this->eventId = $eventId;
	$this->ownerId = $ownerId;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get eventId
     *
     * @return integer 
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * Get ownerId
     *
     * @return integer 
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * Set ownerName
     *
     * @param string $ownerName
     */
    public function setOwnerName($ownerName)
    {
        $this->ownerName = $ownerName;
    }

    /**
     * Get ownerName
     *
     * @return string 
     */
    public function getOwnerName()
    {
        return $this->ownerName;
    }

    /**
     * Set eventDate
     *
     * @param \DateTime $eventDate
     */
    public function setEventDate($eventDate)
    {
        $this->eventDate = $eventDate;
    }

    /**
     * Get eventDate
     *
     * @return \DateTime 
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * Set eventTitle
     *
     * @param string $eventTitle
     */
    public function setEventTitle($eventTitle)
    {
        $this->eventTitle = $eventTitle;
    }

    /**
     * Get eventTitle
     *
     * @return string 
     */
    public function getEventTitle()
    {
        return $this->eventTitle;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set importance
     *
     * @param boolean $importance
     */
    public function setImportance($importance)
    {
        $this->importance = $importance;
    }

    /**
     * Get importance
     *
     * @return boolean 
     */
    public function getImportance()
    {
        return $this->importance;
    }

    /**
     * Set eventText
     *
     * @param string $eventText
     */
    public function setEventText($eventText)
    {
        $this->eventText = $eventText;
    }

    /**
     * Get eventText
     *
     * @return string 
     */
    public function getEventText()
    {
        return $this->eventText;
    }

    /**
     * Set response
     *
     * @param string $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * Get response
     *
     * @return string 
     */
    public function getResponse()
    {
        return $this->response;
    }
}
