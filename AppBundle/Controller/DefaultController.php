<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\ClassLoader;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="login")
     */
    public function loginAction(Request $request)
    {
        // replace this example with login so Gaia do it!
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/campagne", name="campagne")
     */
    public function campagneAction(Request $request)
    {
        return $this->render('AppBundle::campagne.html.twig');
    }

    /**
     * @Route("/contatti", name="contatti")
     */
    public function contattiAction(Request $request)
    {
        return $this->render('AppBundle::contatti.html.twig');
    }

    /**
     * @Route("/feedback", name="feedback")
     */
    public function feedbackAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('Positivo', 'choice', [ 'choices'=>[ 'interessato' => 'interessato', 'altro' => 'altro' ], 'multiple' => false, 'required'=> true, ]) 
            ->add('Altro', 'text') 
            ->add('Negativo', 'choice', [ 'choices'=>[ 'Non interessato' => 'Non interessato', 'Non richiamare' => 'Non richiamare', 'altro' => 'altro' ], 'multiple' => false, 'required'=> true, ]) 
            ->add('Altro', 'text') 
            ->add('send', 'submit', ['label'=> 'submit']) 
            ->getForm(); 

        return $this->render('AppBundle::feedback.html.twig', array( 'form' => $form->createView(), ));
    }

    /**
     * @Route("/report", name="report")
     */
    public function reportAction(Request $request)
    {
        return $this->render('AppBundle::report.html.twig');
    }

    /**
     * @Route("/operatori", name="operatori")
     */
    public function operatoriAction(Request $request)
    {
        return $this->render('AppBundle::operatori.html.twig');
    }

    /**
     * @Route("/modifica", name="modifica")
     */
    public function modificaAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('Email', 'email', ['label' => 'E-mail'])
            ->add('Nome', 'text', ['label' => 'Nome'])
            ->add('Password', 'password', ['label' => 'Password'])
            ->add('Cognome', 'text', ['label' => 'Cognome'])
            ->add('Status', 'choice', ['choices'=>[ 'attivo' => 'Attivo', 'inattivo' => 'Inattivo' ], 'multiple' => false, 'expanded' => true, 'required'=> true, ])
            ->add('Salva', 'submit', ['label'=> 'Salva'])
            ->getForm();
        return $this->render('AppBundle::modifica.html.twig', array( 'form' => $form->createView(), ));
    }

    /**
     * @Route("/nuovo", name="nuovo")
     */
    public function nuovoAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('Email', 'email', ['label' => 'E-mail'])
            ->add('Nome', 'text', ['label' => 'Nome'])
            ->add('Password', 'password', ['label' => 'Password'])
            ->add('Cognome', 'text', ['label' => 'Cognome'])
            ->add('Status', 'choice', ['choices'=>[ 'attivo' => 'Attivo', 'inattivo' => 'Inattivo' ], 'multiple' => false, 'expanded' => true, 'required'=> true, ]) 
            ->add('Salva', 'submit', ['label'=> 'Salva'])
            ->getForm();
        return $this->render('AppBundle::nuovo.html.twig', array( 'form' => $form->createView(), ));
    }
}
