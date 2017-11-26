<?php

// src/SNS/PlatformBundle/Controller/MainController.php

namespace SNS\PlateformBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use SNS\MembreBundle\Entity\Membre;
use SNS\PlateformBundle\Entity\Stage;
use SNS\PlateformBundle\Entity\Document;
use SNS\PlateformBundle\Entity\Annonce;

use SNS\PlateformBundle\Form\StageType;
use SNS\PlateformBundle\Form\DocumentType;
use SNS\PlateformBundle\Form\AnnonceType;
use SNS\PlateformBundle\Form\StageEditType;
use SNS\PlateformBundle\Form\DocumentEditType;
use SNS\PlateformBundle\Form\AnnonceEditType;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

require_once __DIR__.'/../../../../vendor/swiftmailer/swiftmailer/lib/swift_required.php'; 

class MainController extends Controller
{

	public function indexAction($limit = 5)
	{
		  // On recupere la liste des annonces en meme temps.
		  $annonce = $this
		  ->getDoctrine()
		  ->getManager()
		  ->getRepository('SNSPlateformBundle:Annonce')
		;
		
		$listAnnonces = $annonce->findBy(
			  array(),                 
			  array('date' => 'desc'),     // On trie par date de soumission décroissante
			  $limit,                         // On sélectionne $limit annonces: les 5 dernieres 
			  0                        // À partir du premier
		    );

		  return $this->render('SNSPlateformBundle:Main:index.html.twig', array(
      				'listAnnonces' => $listAnnonces
    					));
		  //return $this->render('SNSPlateformBundle:Main:index.html.twig');
	}
	
	public function masterAction()
	{
		  return $this->render('SNSPlateformBundle:Main:master.html.twig');
	}
	
	public function masterBcdAction()
	{
		  return $this->render('SNSPlateformBundle:Main:master_bcd.html.twig');
	}
	
	public function masterIdsAction()
	{
		  return $this->render('SNSPlateformBundle:Main:master_ids.html.twig');
	}
	
	public function masterPhymedAction()
	{
		  return $this->render('SNSPlateformBundle:Main:master_phymed.html.twig');
	}

	public function presentationAction()
	{
		  return $this->render('SNSPlateformBundle:Main:qui_sommes_nous.html.twig');
	}
	
	public function anciensAction()
	{
		  return $this->render('SNSPlateformBundle:Main:reseau_des_anciens.html.twig');
	}

	public function contactAction()
	{
		  return $this->render('SNSPlateformBundle:Main:contact.html.twig');
	}
	
	public function bureauAction()
	{
		  $listBureau
		   = $this->getDoctrine()
			->getManager()
			->getRepository('SNSMembreBundle:Membre')
			->findBy(
			  array('isBureau' => '1'),                 
			  array('id' => 'ASC'),     // On trie par date croissante
			  null,                  // On sélectionne $limit annonces
			  null                        // À partir du premier
		    );
		  
    		return $this->render('SNSPlateformBundle:Main:bureau.html.twig', array(
      				'listBureau' => $listBureau
    					));
	}
	
	public function membresAction()
	{
		//#################### INSERTION D'UN ROLE ##########################
		  /*$utilisateur = $this->get('fos_user.user_manager')->findUserBy(array('username' => 'abdou'));
		  //$utilisateur->setRoles(array('ROLE_ADMIN'));
		  $utilisateur->addRole('ROLE_ADMIN');
		  $this->get('fos_user.user_manager')->updateUser($utilisateur);
		  //#################################################################

		if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
			 // Sinon on déclenche une exception « Accès interdit 
      		throw new AccessDeniedException('Accès limité aux admins.');
    	}*/
		  
		  $listMembres
		   = $this->getDoctrine()
			->getManager()
			->getRepository('SNSMembreBundle:Membre')
			->findBy(
			  array(),                 
			  array('id' => 'ASC'),     // On trie par date croissante
			  null,                  // On sélectionne $limit annonces
			  null                        // À partir du premier
		    );
		  
    		return $this->render('SNSPlateformBundle:Main:membres.html.twig', array(
      				'listMembres' => $listMembres
    					));
	}
	
	
//-----------------------------------------------Stage ---------------------------------------------------------------------------------

	public function sendStageEmails($stage)
	{
		$listMembres
			   = $this->getDoctrine()
				->getManager()
				->getRepository('SNSMembreBundle:Membre')
				->findBy(
				  array(),                 
				  array('id' => 'ASC'),     // On trie par date croissante
				  null,                  // On sélectionne $limit annonces
				  null                        // À partir du premier
		    );
		$to = array();

        foreach ($listMembres as $membre) 
        {
            $to[] = $membre->getEmail();  
        }

 	  $message = \Swift_Message::newInstance();
	  $message->setSubject("[Astico] Offre de stage");
	  $message->setFrom(array('association.astico@gmail.com' => 'Association Astico'));
	  //$message->setTo(array('abdouldiallo30@yahoo.fr' => 'Satina-receiver'));
	  $message->setTo($to);
	  $message->setBody(
					$this->renderView(
				    	// app/Resources/views/Emails/registration.html.twig
				    		'Emails/stageAdded.html.twig',
				    		array('intitule' => $stage->getIntutile(),
				    			  'profile' => $stage->getParcours())
					),
					'text/html'
			  	)
			  /*
			   * If you also want to include a plaintext version of the message
			  ->addPart(
				$this->renderView(
				    'Emails/registration.txt.twig',
				    array('name' => $name)
				),
				'text/plain'
			  )
		    ;
		    $this->get('mailer')->send($message);
			  */	    
				;
	 $this->get('mailer')->send($message);	    
			
	}

	public function stageAction($page)
	{
		if ($page < 1) 		// pour la pagination
		{
      		throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    		}

	    // Ici je fixe le nombre d'annonces par page à 10
	    // Mais bien sûr il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
	    $nbPerPage = 10;
	     
	     // On récupère notre objet Paginator
		$listStages = $this
		  ->getDoctrine()
		  ->getManager()
		  ->getRepository('SNSPlateformBundle:Stage')
		  ->getStages($page, $nbPerPage)               // methode definie dans StageRepository
		;
		
		/*		// Si sans pagination
		$stage = $this
		  ->getDoctrine()
		  ->getManager()
		  ->getRepository('SNSPlateformBundle:Stage')
		;
		$listStages = $stage->findBy(   
			  array(),             	    
			  array('dateSoumission' => 'desc'),     // On trie par date de soumission décroissante
			  null,                  // On sélectionne $limit annonces
			  null                        // À partir du premier
		    );
		*/

		    
		  // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
		    $nbPages = ceil(count($listStages)/$nbPerPage);

		    // Si la page n'existe pas, on retourne une 404
		    if ($page > $nbPages) 
		    {
			   throw $this->createNotFoundException("La page ".$page." n'existe pas.");
		    }
		   
		  return $this->render('SNSPlateformBundle:Main:stages.html.twig', array(
			'listStages' => $listStages,
			'nbPages'     => $nbPages,
			'page'        => $page
		    ));
    					
	}
	
	/** 
    * @Security("has_role('ROLE_USER') ")
    */		
	public function viewStageAction($id)
	{
	   $em = $this->getDoctrine()->getManager();

	    // On récupère le stage $id
	    $stage = $em
		->getRepository('SNSPlateformBundle:Stage')
		->find($id)
	    ;

	    if (null === $stage) {
		throw new NotFoundHttpException("Le stage demandé d'id ".$id." n'existe pas.");
	    }

	    return $this->render('SNSPlateformBundle:Main:stage_view.html.twig', array(
				'stage' => $stage
			));
	}

	
	
	/** 
       * @Security("has_role('ROLE_USER') ")
       */
	public function addStageAction(Request $request)
	{
	   
	   $stage = new Stage();


	    $form = $this->get('form.factory')->create(StageType::class, $stage);
	    
	    // On fait le lien Requête <-> Formulaire
	    // À partir de maintenant, la variable $stage contient les valeurs entrées dans le formulaire par le visiteur
	    $form->handleRequest($request);

	    // On vérifie que les valeurs entrées sont correctes
	    if ($form->isValid()) 
	    {
	 	  
		      $userManager = $this->get('fos_user.user_manager');
		      //   $user = new Membre();
		      // si l'on est connecté
		      if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
		      {
		      	$authenticationUtils = $this->get('security.authentication_utils');		      		
				//$user = $this->get('security.authorization_checker')->getToken()->getUser();
		      	$user = $this->getUser();
		      	//$user = $userManager->findUserBy(array('username' => $authenticationUtils->getLastUsername()));
		      	//setAuteur($this->getUser()->getUsername());
		      	$stage->setAuteur($user);
		      }
		      else
		      {		      	
		      	$user = $userManager->findUserBy(array('username' => 'anonyme'));
		      	$stage->setAuteur($user);	
		      }		      
		      //$user = $userManager->findUserBy(array('username' => 'anonyme'));
		      //$stage->setAuteur($user);	
		      
			// On l'enregistre notre objet dans la base de données, par exemple
			$em = $this->getDoctrine()->getManager();
			$em->persist($stage);
			$em->flush();

			//$request->getSession()->getFlashBag()->add('notice', 'Stage bien enregistré.');
			
			// --------------------envoi email --------------------------------------------
			
				$this->sendStageEmails($stage);	
			//-----------------------------------------------------------------------------
			
			
			// On redirige vers la page de visualisation de l'annonce nouvellement créée
			return $this->redirect($this->generateUrl('sns_plateform_view_stage', array('id' => $stage->getId())));
	    }
	    

	    // À ce stade, le formulaire n'est pas valide car :
	    // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
	    // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
	    return $this->render('SNSPlateformBundle:Main:stage_add.html.twig', array(
					'form' => $form->createView()
	    			));
	  }
	  
	  /** 
       * @Security("has_role('ROLE_ADMIN') ")
       */	   
	  public function editStageAction($id, Request $request)
	  {
		    $em = $this->getDoctrine()->getManager();

		    // On récupère le stage $id
		    $stage = $em->getRepository('SNSPlateformBundle:Stage')->find($id);

		    if (null === $stage) 
		    {
			throw new NotFoundHttpException("Le stage ".$id." n'existe pas.");
		    }

		    $form = $this->createForm(StageEditType::class, $stage);

		    if ($form->handleRequest($request)->isValid()) 
		    {
			// Inutile de persister ici, Doctrine connait déjà notre annonce
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Stage bien modifié.');

			return $this->redirect($this->generateUrl('sns_plateform_view_stage', array('id' => $stage->getId())));
		    }

		    return $this->render('SNSPlateformBundle:Main:stage_edit.html.twig', array(
						'form'   => $form->createView(),
						'stage' => $stage // Je passe également le stage à la vue si jamais elle veut l'afficher
					    ));
	  }
	  
	  /** 
       * @Security("has_role('ROLE_ADMIN') ")
       */
	  public function deleteStageAction($id, Request $request)
	  {
		    $em = $this->getDoctrine()->getManager();

		    // On récupère le stage $id
		    $stage = $em->getRepository('SNSPlateformBundle:Stage')->find($id);

		    if (null === $stage) 
		    {
			throw new NotFoundHttpException("Le stage ".$id." n'existe pas.");
		    }
		    $form = $this->createFormBuilder()->getForm();

		    if ($form->handleRequest($request)->isValid()) 
		    {
			$em->remove($stage);
			$em->flush();

			$request->getSession()->getFlashBag()->add('info', "Le stage a bien été supprimé.");

			return $this->redirect($this->generateUrl('sns_plateform_stage'));
		    }

		    // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
		    return $this->render('SNSPlateformBundle:Main:stage_delete.html.twig', array(
						'stage' => $stage,
						'form'   => $form->createView()
					    ));
	  }

	
	
	
	
//------------------------------------------Document-------------------------------------------------------------------------------------	
	
	public function documentAction($page)
	{
		if ($page < 1) 
		{
      		throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    		}

	    // Ici je fixe le nombre d'annonces par page à 3
	    // Mais bien sûr il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
	    $nbPerPage = 10;
	     
	     // On récupère notre objet Paginator
		$listDocuments = $this
		  ->getDoctrine()
		  ->getManager()
		  ->getRepository('SNSPlateformBundle:Document')
		  ->getDocuments($page, $nbPerPage)
		;
		    
		  // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
		    $nbPages = ceil(count($listDocuments)/$nbPerPage);

		    // Si la page n'existe pas, on retourne une 404
		    if ($page > $nbPages) 
		    {
			   throw $this->createNotFoundException("La page ".$page." n'existe pas.");
		    }


		  return $this->render('SNSPlateformBundle:Main:documents.html.twig', array(
      				'listDocuments' => $listDocuments,
      				'nbPages'     => $nbPages,
					'page'        => $page
				    ));

	}
	

	  /** 
       * @Security("has_role('ROLE_USER') ")
       */
      public function addDocumentAction(Request $request)
	{
	   
	   $document = new Document();


	    $form = $this->get('form.factory')->create(DocumentType::class, $document);
	    $form->handleRequest($request);
	    if ($form->isValid()) 
	    {
		      $userManager = $this->get('fos_user.user_manager');
		      
		      // si l'on est connecté
		      if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
		      {
		      	$authenticationUtils = $this->get('security.authentication_utils');		      		
				//$user = $this->get('security.context')->getToken()->getUser();
		      	$user = $this->getUser();
		      	//$user = $userManager->findUserBy(array('username' => $authenticationUtils->getLastUsername()));
		      	//setAuteur($this->getUser()->getUsername());
		      	$document->setAuteur($user);
		      }
		            
		      
			// On l'enregistre notre objet dans la base de données
			$em = $this->getDoctrine()->getManager();
			$em->persist($document);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Document bien enregistré.');

			// On redirige vers la page de visualisation de l'annonce nouvellement créée
			return $this->redirect($this->generateUrl('sns_plateform_document'));
	    }

	    // À ce stade, le formulaire n'est pas valide
	    return $this->render('SNSPlateformBundle:Main:document_add.html.twig', array(
					'form' => $form->createView()
	    			));
	  }
	 	  
	  /** 
       * @Security("has_role('ROLE_ADMIN') ")
       */	  
	  public function editDocumentAction($id, Request $request)
	  {
		    $em = $this->getDoctrine()->getManager();

		    // On récupère le stage $id
		    $document = $em->getRepository('SNSPlateformBundle:Document')->find($id);

		    if (null === $document) 
		    {
			throw new NotFoundHttpException("Le document ".$id." n'existe pas.");
		    }

		    $form = $this->createForm(DocumentEditType::class, $document);

		    if ($form->handleRequest($request)->isValid()) 
		    {
			// Inutile de persister ici, Doctrine connait déjà notre annonce
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Document bien modifié.');

			return $this->redirect($this->generateUrl('sns_plateform_document'));
		    }

		    return $this->render('SNSPlateformBundle:Main:document_edit.html.twig', array(
						'form'   => $form->createView(),
						'document' => $document // Je passe également le document à la vue si jamais elle veut l'afficher
					    ));
	  }
	  
	  /** 
       * @Security("has_role('ROLE_ADMIN') ")
       */
	  public function deleteDocumentAction($id, Request $request)
	  {
		    $em = $this->getDoctrine()->getManager();

		    // On récupère le stage $id
		    $document = $em->getRepository('SNSPlateformBundle:Document')->find($id);

		    if (null === $document) 
		    {
			throw new NotFoundHttpException("Le document ".$id." n'existe pas.");
		    }
		    $form = $this->createFormBuilder()->getForm();

		    if ($form->handleRequest($request)->isValid()) 
		    {
			$em->remove($document);
			$em->flush();

			$request->getSession()->getFlashBag()->add('info', "Le document a bien été supprimé.");

			return $this->redirect($this->generateUrl('sns_plateform_document'));
		    }

		    // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
		    return $this->render('SNSPlateformBundle:Main:document_delete.html.twig', array(
						'document' => $document,
						'form'   => $form->createView()
					    ));
	  }
	  
//-------------------------------------------Annonce -----------------------------------------------------------------------
       
      public function annonceAction($page)
	{
		if ($page < 1) 
		{
      		throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    		}

	    // Ici je fixe le nombre d'annonces par page à 3
	    // Mais bien sûr il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
	    $nbPerPage = 10;
	     
	     // On récupère notre objet Paginator
		$listAnnonces = $this
		  ->getDoctrine()
		  ->getManager()
		  ->getRepository('SNSPlateformBundle:Annonce')
		  ->getAnnonces($page, $nbPerPage)
		;
		
		  // On calcule le nombre total de pages grâce au count qui retourne le nombre total d'annonces
		    $nbPages = ceil(count($listAnnonces)/$nbPerPage);

		    // Si la page n'existe pas, on retourne une 404
		    if ($page > $nbPages) 
		    {
			   throw $this->createNotFoundException("La page ".$page." n'existe pas.");
		    }

		/*
		$annonce = $this
		  ->getDoctrine()
		  ->getManager()
		  ->getRepository('SNSPlateformBundle:Annonce')
		;

		$listAnnonces = $annonce->findBy(
			  array(),                 
			  array('date' => 'desc'),     // On trie par date de soumission décroissante
			  null,                  // On sélectionne $limit annonces
			  null                        // À partir du premier
		    );
		*/

  
		  return $this->render('SNSPlateformBundle:Main:annonces.html.twig', array(
			'listAnnonces' => $listAnnonces,
			'nbPages'     => $nbPages,
			'page'        => $page
		    ));

	}

	public function viewAnnonceAction($id)
	{
	   $em = $this->getDoctrine()->getManager();

	    // On récupère le stage $id
	    $annonce = $em
		->getRepository('SNSPlateformBundle:Annonce')
		->find($id)
	    ;

	    if (null === $annonce) {
			throw new NotFoundHttpException("L' annonce demandé d'id ".$id." n'existe pas.");
	    }

	    return $this->render('SNSPlateformBundle:Main:annonce_view.html.twig', array(
				'annonce' => $annonce
			));
	}

  	   /** 
       * @Security("has_role('ROLE_USER') ")
       */
	 public function addAnnonceAction(Request $request)
	 {
	   
	   $annonce = new Annonce();


	    $form = $this->get('form.factory')->create(AnnonceType::class, $annonce);
	    $form->handleRequest($request);
	    if ($form->isValid()) 
	    {
		      $userManager = $this->get('fos_user.user_manager');
		      
		      // si l'on est connecté
		      if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
		      {
		      	$authenticationUtils = $this->get('security.authentication_utils');		      		
				//$user = $this->get('security.context')->getToken()->getUser();
		      	$user = $this->getUser();
		      	//$user = $userManager->findUserBy(array('username' => $authenticationUtils->getLastUsername()));
		      	//setAuteur($this->getUser()->getUsername());
		      	$annonce->setAuteur($user);
		      }
		      	      
		      
			// On l'enregistre notre objet dans la base de données
			$em = $this->getDoctrine()->getManager();
			$em->persist($annonce);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistré.');

			// On redirige vers la page d' accueil
			return $this->redirect($this->generateUrl('sns_plateform_homepage'));
	    }

	    // À ce stade, le formulaire n'est pas valide
	    return $this->render('SNSPlateformBundle:Main:annonce_add.html.twig', array(
					'form' => $form->createView()
	    			));
	  }

	  
	  /** 
       * @Security("has_role('ROLE_ADMIN') ")
       */	   
	   public function editAnnonceAction($id, Request $request)
	  {
		    $em = $this->getDoctrine()->getManager();

		    // On récupère le stage $id
		    $annonce = $em->getRepository('SNSPlateformBundle:Annonce')->find($id);

		    if (null === $annonce) 
		    {
				throw new NotFoundHttpException("L' annonce ".$id." n'existe pas.");
		    }

		    $form = $this->createForm(AnnonceEditType::class, $annonce);

		    if ($form->handleRequest($request)->isValid()) 
		    {
				// Inutile de persister ici, Doctrine connait déjà notre annonce
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifié.');

				return $this->redirect($this->generateUrl('sns_plateform_view_annonce', array('id' => $annonce->getId())));
		    }

		    return $this->render('SNSPlateformBundle:Main:annonce_edit.html.twig', array(
						'form'   => $form->createView(),
						'annonce' => $annonce // Je passe également l'annonce à la vue si jamais elle veut l'afficher
					    ));
	  }
	  
	  /** 
       * @Security("has_role('ROLE_ADMIN') ")
       */
	  public function deleteAnnonceAction($id, Request $request)
	  {
		    $em = $this->getDoctrine()->getManager();

		    // On récupère le stage $id
		    $annonce = $em->getRepository('SNSPlateformBundle:Annonce')->find($id);

		    if (null === $annonce) 
		    {
			throw new NotFoundHttpException("L'annonce ".$id." n'existe pas.");
		    }
		    $form = $this->createFormBuilder()->getForm();

		    if ($form->handleRequest($request)->isValid()) 
		    {
			$em->remove($annonce);
			$em->flush();

			$request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimé.");

			return $this->redirect($this->generateUrl('sns_plateform_homepage'));
		    }

		    // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
		    return $this->render('SNSPlateformBundle:Main:annonce_delete.html.twig', array(
						'annonce' => $annonce,
						'form'   => $form->createView()
					    ));
	  }

	  
	
}

/*

INSERT INTO `membre`VALUES (20,NULL,"Anne","Honime","anonyme","anonyme@astico.fr","anonyme",NOW(),"a:0:{}","anonyme","anonyme"," ","anonyme","anonyme",1, NULL, 0, 0, NULL, NULL, NULL, 0, NULL, 0);

*/
