<?php

namespace GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Location
 *
 * @ORM\Table(name="location")
 * @ORM\Entity(repositoryClass="GameBundle\Repository\LocationRepository")
 */
class Location
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255, unique=true)
     */
    private $reference;
    
    /**
     * @var string
     *
     * @ORM\Column(name="shortDescription", type="string", length=255, nullable=true)
     */
    private $shortDescription;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="internalDescription", type="string", length=255, nullable=true)
     */
    private $internalDescription;

    /**
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumn(name="north", referencedColumnName="id")
     */
    private $north;
    
    /**
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumn(name="east", referencedColumnName="id")
     */
    private $east;
    
    /**
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumn(name="south", referencedColumnName="id")
     */
    private $south;
    
    /**
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumn(name="west", referencedColumnName="id")
     */
    private $west;
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Location
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    function setReference($reference) 
    {    
        $this->reference = $reference;
        
        return $this;
    }
    
    function getReference() 
    {
        return $this->reference;
    }
    
    function setShortDescription($shortDescription) 
    {
        $this->shortDescription = $shortDescription;
        return $this;
    }
    
    function getShortDescription() 
    {
        return $this->shortDescription;
    }
    
    function setDescription($description) 
    {
        $this->description = $description;
        return $this;
    }
    
    function getDescription() 
    {
        return $this->description;
    }
    
    function setInternalDescription($internalDescription) 
    {
        $this->internalDescription = $internalDescription;
        return $this;
    }
    
    function getInternalDescription() 
    {
        return $this->internalDescription;
    }
    
    
    
    // TODO fix ordering
    
    function getNorth() {
        return $this->north;
    }

    function getEast() {
        return $this->east;
    }

    function getSouth() {
        return $this->south;
    }

    function getWest() {
        return $this->west;
    }

    function setNorth($north) {
        $this->north = $north;
        return $this;
    }

    function setEast($east) {
        $this->east = $east;
        return $this;
    }

    function setSouth($south) {
        $this->south = $south;
        return $this;
    }

    function setWest($west) {
        $this->west = $west;
        return $this;
    }
}

