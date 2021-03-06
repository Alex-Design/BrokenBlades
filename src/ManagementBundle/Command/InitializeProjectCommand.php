<?php

namespace ManagementBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use GameBundle\Entity\Account;
use GameBundle\Entity\GameCharacter;
use GameBundle\Entity\Location;

class InitializeProjectCommand extends Command
{
    protected $entityManager;
    protected $container;
    
    protected function configure()
    {
        $this
        // the name of the command (the part after "bin/console")
        ->setName('manage:initialize-project')
        // the short description shown while running "php bin/console list"
        ->setDescription('Initializes the project. Intended for first-time installation.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setup();
        $this->createLocations();
        $this->createCharacters();
        $this->createAccounts();
        
        print_r(PHP_EOL);
        print_r('Project Initialized!');
        print_r(PHP_EOL);
    }
    
    protected function createLocations()
    {
        $startingLocations = [
            1 => [
                'name' => 'Graveyard Of Souls', 
                'reference' => 'GraveyardOfSouls',
                'shortDescription' => 'Souls weave between the cracked gravestones. '
                    . 'Dead trees surround the area, concealing it from outsiders.',
                'description' => 'The last cries of the fallen echo around you as they '
                    . 'have done for the past year. Or was it only a few months? You cannot tell, '
                    . 'having lost all track of time. It all started when the Maldorians attacked... ',
                'internalDescription' =>  'The starting area of the game.',
                'north' => 'OvergrownTower',
                'east' => null,
                'south' => null,
                'west' => null,
            ],
            2 => [
                'name' => 'Overgrown Tower',
                'reference' => 'OvergrownTower',
                'shortDescription' => 'Ivy and nettle surround the stonework of this once-grand'
                    . 'tower. The chants of ancient lessons still echo around the halls.',
                'description' => 'The entrance of the tower betrays nothing of the secrets inside. '
                . 'The halls are naught but a ruin compared to their former selves, though '
                . 'the expensive-looking paintings lining the walls demonstrated that if '
                . 'nothing else, those that passed through here had enough respect to leave them '
                . 'behind. That, or they feared the wrath of who once lived, learned and taught here.',
                'internalDescription' =>  'Players teleport to here from GraveyardOfSouls.',
                'north' => null,
                'east' => null,
                'south' => 'GraveyardOfSouls',
                'west' => null,
            ],
        ];
        
        foreach ($startingLocations as $startingLocation) {
            $location = new Location;
            $location->setName($startingLocation['name']);
            $location->setReference($startingLocation['reference']);
            $location->setShortDescription($startingLocation['shortDescription']);
            $location->setDescription($startingLocation['description']);
            $location->setInternalDescription($startingLocation['internalDescription']);
            $this->entityManager->persist($location);
            $this->entityManager->flush();
        } 
        
        // now add directional movement
        foreach ($startingLocations as $startingLocation) {
            $databaseLocation = $this->entityManager->getRepository('GameBundle:Location')->findOneByReference($startingLocation['reference']);
            $databaseLocation->setNorth(($startingLocation['north']) ? $this->entityManager->getRepository('GameBundle:Location')->findOneByReference($startingLocation['north']) : null);
            $databaseLocation->setEast(($startingLocation['east']) ? $this->entityManager->getRepository('GameBundle:Location')->findOneByReference($startingLocation['east']) : null);
            $databaseLocation->setSouth(($startingLocation['south']) ? $this->entityManager->getRepository('GameBundle:Location')->findOneByReference($startingLocation['south']) : null);
            $databaseLocation->setWest(($startingLocation['west']) ? $this->entityManager->getRepository('GameBundle:Location')->findOneByReference($startingLocation['west']) : null);
            $this->entityManager->persist($databaseLocation);
            $this->entityManager->flush();
        }
    }
    
    // Named 'createAccounts' in the event of more accounts being added here
    protected function createAccounts()
    {  
        $account = new Account;
        
        $account->setUsername('test-account');
        $account->setPassword('test-account');
        $account->setEmail('test@test.com');
        $account->setIsActive(1);
        
        $this->entityManager->persist($account);
        $this->entityManager->flush();
    }
    
    // Named 'createCharacters' in the event of more characters being added here
    protected function createCharacters() 
    {  
        $startingLocation = $this->entityManager->getRepository('GameBundle:Location')->findOneByReference('GraveyardOfSouls');
        $testAccount = $this->entityManager->getRepository('GameBundle:Account')->findOneByUsername('test-account');
        
        $character = new GameCharacter;  
        
        $character->setCurrentLocation($startingLocation);
        $character->setHasRecentlyActed(true);
        $character->setAccount($testAccount);
        
        $this->entityManager->persist($character);
        $this->entityManager->flush();
    }
    
    protected function setup()
    {
        $this->container = $this->getApplication()->getKernel()->getContainer();
        $this->entityManager = $this->container->get('doctrine')->getManager();
    }
    
    // TODO:
    /*
     * Error reporting for creating stuff (future locations may be added,
     * existing locations will cause error due to unique id, etc)
     */
}