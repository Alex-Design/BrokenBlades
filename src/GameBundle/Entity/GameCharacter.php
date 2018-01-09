<?php

namespace GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table(name="gameCharacter")
 * @ORM\Entity(repositoryClass="GameBundle\Repository\CharacterRepository")
 */
class GameCharacter
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumn(name="currentLocation", referencedColumnName="id")
     */
    private $currentLocation;

    /**
     * @ORM\Column(name="hasRecentlyActed", type="boolean")
     */
    private $hasRecentlyActed;

    /**
     * @ORM\OneToOne(targetEntity="Inventory", inversedBy="character")
     * @ORM\JoinColumn(name="inventoryId", referencedColumnName="id")
     */
    private $inventory;
    
    /**
     * @ORM\OneToOne(targetEntity="CharacterStats", inversedBy="character")
     * @ORM\JoinColumn(name="characterStatsId", referencedColumnName="id")
     */
    private $characterStatistics;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="characters")
     * @ORM\JoinColumn(name="accountId", referencedColumnName="id")
     */
    private $account;
    
    public function __construct()
    {
        $this->isActive = true;
        
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
    }

    // Todo: Tidy up
    public function getId() {
        return $this->id;
    }

    public function getAccount() {
        return $this->account;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setAccount($account) {
        $this->account = $account;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setHasRecentlyActed($hasRecentlyActed) {
        $this->hasRecentlyActed = $hasRecentlyActed;
        return $this;
    }
    
    public function getHasRecentlyActed()
    {
        return $this->hasRecentlyActed;
    }
    
    public function setCurrentLocation($location)
    {
        $this->currentLocation = $location;
        return $this;
    }
    
    public function getCurrentLocation()
    {
        return $this->currentLocation;
    }
    
    function setInventory($inventory) {
        $this->inventory = $inventory;
    }
    
    function getInventory() {
        return $this->inventory;
    }
    
    function getCharacterStatistics() {
        return $this->characterStatistics;
    }

    function setCharacterStatistics($characterStatistics) {
        $this->characterStatistics = $characterStatistics;
    }
}