<?php

namespace GameBundle\Entity;

use GameBundle\Entity\Item;

use Doctrine\ORM\Mapping as ORM;

/**
 * EvolvedItem (also uses all same properties as found in Item)
 *
 * @ORM\Table(name="evolvedItem")
 * @ORM\Entity(repositoryClass="GameBundle\Repository\EvolvedItemRepository")
 */
class EvolvedItem 
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
     * @ORM\ManyToOne(targetEntity="Item")
     * @ORM\JoinColumn(name="originalItemId", referencedColumnName="id")
     */
    private $originalItem;
    
    /**
     * @ORM\ManyToOne(targetEntity="Inventory")
     * @ORM\JoinColumn(name="inventory", referencedColumnName="id")
     */
    private $inventory;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    function getOriginalItem() {
        return $this->originalItem;
    }

    function getInventory() {
        return $this->inventory;
    }

    function getName() {
        return $this->name;
    }

    function setOriginalItem($originalItem) {
        $this->originalItem = $originalItem;
    }

    function setInventory($inventory) {
        $this->inventory = $inventory;
    }

    function setName($name) {
        $this->name = $name;
    }
}

