<?php
// src/OC/UserBundle/DataFixtures/ORM/LoadUser.php

namespace SNS\PlateformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SNS\MembreBundle\Entity\Membre;

class LoadMembre implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $listNoms = array('Fraschini', 'Testard', 'Bordères', 'Helou', 'Danesh', 'Vinchent', 'Pagès', 'Boudries', 'Toukounou', 'Diallo');
    $listPrenoms = array('Andreas', 'Quentin', 'Marianne', 'Laura', 'Gonché', 'Alexandra', 'Lucie', 'Zinedine', 'Mégane', 'Abdoulaye');
    $listPostes = array('Président', 'Vice-Président', 'Trésorière', 'Vice-Trésorière', 'Secrétaire', 'Vice-Secrétaire', 'Organisatrice de soirée', 'Responsable communication', 'Coordinatrice d\'événements', 'Maintenance du site web');
    $listSpecialites = array('IDS', 'BCD', 'BCD', 'BCD', 'BCD', 'BCD', 'IDS', 'IDS', 'IDS', 'BCD');
    
    $i=0;
    foreach ($listNoms as $name) {
      // On crée l'utilisateur
      $user = new Membre;

      
      $user->setNom($name);
      $user->setPrenom($listPrenoms[$i]);
	$user->setPoste($listPostes[$i]);
	$user->setusername($listPrenoms[$i].$listNoms[$i]);
	$user->setPassword($listPrenoms[$i].'.'.$listNoms[$i]);
	$user->setSpecialite($listSpecialites[$i]);	
	$user->setEnabled(true);	
	$user->setLocked(false); $user->setIsInBureau(true);
	$user->setExpired(false); $user->setCredentialsExpired(false);			
	$user->setEmail($listPrenoms[$i].'@'.$listNoms[$i].'.sns');
	$user->setAnniversaire(mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")));
	$user->setSalt('');	
	// On définit uniquement le role ROLE_USER qui est le role de base
      $user->setRoles(array('ROLE_USER'));

      // On le persiste
      $manager->persist($user);
    	$i = $i+1;
    }

    // On déclenche l'enregistrement
    $manager->flush();
  }
}
