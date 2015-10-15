<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Entity\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{

public function indexAction($page)
  {
    $listAdverts = array(
      array(
        'title'   => 'Recherche développpeur Symfony2',
        'id'      => 1,
        'author'  => 'Alexandre',
        'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
        'date'    => new \Datetime()),
      array(
        'title'   => 'Mission de webmaster',
        'id'      => 2,
        'author'  => 'Hugo',
        'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
        'date'    => new \Datetime()),
      array(
        'title'   => 'Offre de stage webdesigner',
        'id'      => 3,
        'author'  => 'Mathieu',
        'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
        'date'    => new \Datetime())
    );

    // Et modifiez le 2nd argument pour injecter notre liste
    return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
      'listAdverts' => $listAdverts
    ));
      $mailer = $this->container->get('mailer');
  }

public function viewAction($id)
  {
    $em  = $this->getDoctrine()->getManager();
    $advert = $em
      ->getRepository('OCPlatformBundle:Advert')
      ->find($id);
    if(null === $advert)
    {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }
    $listApplications = $em
      ->getRepository('OCPlatformBundle:Application')
      ->findBy(array('advert' => $advert))
    ;   
    return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
       'advert' => $advert,
       'listApplications' => $listApplications
    ));
  }

  public function addAction(Request $request)
  {
    $advert = new Advert();
    $advert->setTitle('Recherche développeur Symfony2.');
    $advert->setAuthor('Alexandre');
    $advert->setDate(new \Datetime());
    $advert->setContent('Nous recherchons un développeur Symfony2 débutant sur Lyon.');

    $image = new Image();
    $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
    $image->setAlt('Job de rêve');
    $advert->setImage($image);
    
    $application1 = new Application();
    $application1->setAuthor('Marine');
    $application1->setDate(new \Datetime());
    $application1->setContent("J'ai toutes les qualités requises.");

    $application2 = new Application();
    $application2->setAuthor('Pierre');
    $application2->setDate(new \Datetime());
    $application2->setContent("Je suis très motivé.");

    $application1->setAdvert($advert);
    $application2->setAdvert($advert);

    $em = $this->getDoctrine()->getManager();
    $em->persist($advert);
    $em->persist($application1);
    $em->persist($application2);
    $em->flush();
    if($request->isMethod('POST'))
    {
      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée');
      return $this->redirect($this->generateUrl('oc_platform_view', array('id'=>$advert->getId())));
    }
    return $this->render('OCPlatformBundle:Advert:add.html.twig');
  }

  public function editAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);
    if (null === $advert)
    {
     throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }
    $listCategories = $em->getRepository('OCPlatformBundle:Category')->findAll();
    foreach($listCategories as $category)
    {
     $advert->addCategory($category);
    }
    $em->flush();
    return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
      'advert' => $advert
    ));
  }

  public function deleteAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

    if(null === $advert)
    {
     throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }
    foreach($advert->getCategories() as $category)
    {
     $advert->removeCategory($category);
    }
    $em->flush();
    return $this->render('OCPlatformBundle:Advert:delete.html.twig');
  }

 public function menuAction($limit)
  {
    // On fixe en dur une liste ici, bien entendu par la suite
    // on la récupérera depuis la BDD !
    $listAdverts = array(
      array('id' => 2, 'title' => 'Recherche développeur Symfony2'),
      array('id' => 5, 'title' => 'Mission de webmaster'),
      array('id' => 9, 'title' => 'Offre de stage webdesigner')
    );

    return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
      // Tout l'intérêt est ici : le contrôleur passe
      // les variables nécessaires au template !
      'listAdverts' => $listAdverts
    ));
  }
}
