<?php

namespace GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EvolvedItem (also uses all same properties as found in Item)
 *
 * @ORM\Table(name="lootTable")
 * @ORM\Entity(repositoryClass="GameBundle\Repository\LootGroupRepository")
 */
class LootTable
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
     * @ORM\ManyToOne(targetEntity="Item")
     * @ORM\JoinColumn(name="originalItemId", referencedColumnName="id")
     */
    private $originalItem;
    
    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;
    
    /**
     * @ORM\ManyToOne(targetEntity="LootGroup", inversedBy="lootTables")
     * @ORM\JoinColumn(name="lootGroup", referencedColumnName="id")
     */
    private $lootGroup;
    
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
    
    function setOriginalItem($originalItem) {
        $this->originalItem = $originalItem;
    }
    
    function getOriginalItem() {
        return $this->originalItem;
    }
    
    function getQuantity() {
        return $this->quantity;
    }

    function getLootGroup() {
        return $this->lootGroup;
    }

    function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    function setLootGroup($lootGroup) {
        $this->lootGroup = $lootGroup;
    }
}

