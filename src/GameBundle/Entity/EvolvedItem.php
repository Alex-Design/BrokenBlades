<?php

namespace GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Item
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
     * @ORM\JoinColumn(name="inventoryId", referencedColumnName="id")
     */
    private $inventory;
}

