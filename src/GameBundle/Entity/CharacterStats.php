<?php

namespace GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CharacterStats
 *
 * @ORM\Table(name="characterStats")
 * @ORM\Entity(repositoryClass="GameBundle\Repository\CharacterStatsRepository")
 */
class CharacterStats
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
     * @var int
     *
     * @ORM\Column(name="currentHealth", type="integer")
     */
    private $currentHealth;

    /**
     * @var int
     *
     * @ORM\Column(name="maximumHealth", type="integer")
     */
    private $maximumHealth;
    
    /**
     * @var int
     *
     * @ORM\Column(name="currentStrength", type="integer")
     */
    private $currentStrength;
    
    /**
     * @var int
     *
     * @ORM\Column(name="maximumStrength", type="integer")
     */
    private $maximumStrength;
    
    /**
     * @ORM\OneToOne(targetEntity="GameCharacter", mappedBy="characterStatistics")
     */
    private $character;
    
    function getId() {
        return $this->id;
    }

    function getCurrentHealth() {
        return $this->currentHealth;
    }

    function getMaximumHealth() {
        return $this->maximumHealth;
    }

    function getCurrentStrength() {
        return $this->currentStrength;
    }

    function getMaximumStrength() {
        return $this->maximumStrength;
    }

    function getCharacter() {
        return $this->character;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCurrentHealth($currentHealth) {
        $this->currentHealth = $currentHealth;
    }

    function setMaximumHealth($maximumHealth) {
        $this->maximumHealth = $maximumHealth;
    }

    function setCurrentStrength($currentStrength) {
        $this->currentStrength = $currentStrength;
    }

    function setMaximumStrength($maximumStrength) {
        $this->maximumStrength = $maximumStrength;
    }

    function setCharacter($character) {
        $this->character = $character;
    }
}

