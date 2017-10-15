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
}

