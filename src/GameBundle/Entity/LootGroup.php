<?php

namespace GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EvolvedItem (also uses all same properties as found in Item)
 *
 * @ORM\Table(name="lootGroup")
 * @ORM\Entity(repositoryClass="GameBundle\Repository\LootGroupRepository")
 */
class LootGroup
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @ORM\OneToMany(targetEntity="LootTable", mappedBy="lootGroup")
     */
    private $lootTables;
    
    function getId() {
        return $this->id;
    }

    function setId($id) {
        $this->id = $id;
        return $this;
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
    
    public function __construct() {
        $this->lootTables = new ArrayCollection();
    }
    
    function getLootTables() {
        return $this->lootTables;
    }

    function setLootTables($lootTables) {
        $this->lootTables = $lootTables;
    }


}

