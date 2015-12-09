<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\ClassLoader;
use Doctrine\DBAL\DriverManager;
use PairPI\Bundle\RecallcrmBundle\Entity\Operatori;
use PairPI\Bundle\RecallcrmBundle\Entity\Report;
use FOS\UserBundle\Model\UserManagerInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/campagne", name="campagne")
     */
    public function campagneAction(Request $request)
    {
        $repository = $this->getDoctrine()
            ->getRepository('PairPI\Bundle\RecallcrmBundle\Entity\Report');
        
        $usr = $this->get('security.token_storage')->getToken()->getUser();

        $campagne = $repository->createQueryBuilder('c')
            ->where('c.operator = :id')
            ->setParameter('id', $usr->getid())
            ->select('c.campaign')
            ->distinct()
            ->addOrderBy('c.campaign')
            ->getQuery()
            ->getResult();

        foreach ($campagne as $key => $value) {
            $report = $repository->createQueryBuilder('r')
                ->where('r.operator = :id')
                ->setParameter('id', $usr->getid())
                ->andWhere('r.campaign = :campaign')
                ->setParameter('campaign', $value['campaign'])
                ->getQuery()
                ->getResult();
            $nonChiamato = 0;
            $daRichiamare = 0;
            $chiamato = 0;
            $value['non_chiamato'] = 0;
            $value['da_richiamare'] = 0;
            $value['chiamato'] = 0;

            foreach ($report as $nkey => $reply) {
                switch ($reply->getReply()) {
                    case 'Non chiamato':
                        $value['non_chiamato'] = ++$nonChiamato;
                        break;
                    case 'Da richiamare':
                        $value['da_richiamare'] = ++$daRichiamare;
                        break;
                    default:
                        $value['chiamato'] = ++$chiamato;
                        break;
                }
            }
            $campagne[$key] = $value;
        }

        return $this->render('AppBundle::campagne.html.twig', array(
            'campagne' => $campagne,
            )
        );
    }

    /**
     * @Route("/contatti/{campaign}", name="contatti")
     */
    public function contattiAction(Request $request, $campaign)
    {
        $repository = $this->getDoctrine()
            ->getRepository('PairPI\Bundle\RecallcrmBundle\Entity\Report');

        $usr = $this->get('security.token_storage')->getToken()->getUser();
        
        $report = $repository->createQueryBuilder('r')
            ->where('r.operator = :id')
            ->setParameter('id', $usr->getid())
            ->andWhere('r.campaign = :campaign')
            ->setParameter('campaign', $campaign)
            ->andWhere('r.comment != :comm')
            ->setParameter('comm', 'hidden')
            ->orderBy('FIELD(r.reply, :r1, :r2, :r3, :r4)')
            ->setParameter(':r1', 'Da richiamare')
            ->setParameter(':r2', 'Non chiamato')
            ->setParameter(':r3', 'Positivo')
            ->setParameter(':r4', 'Negativo')
            ->addOrderBy('r.dateTime')
            ->getQuery()
            ->getResult();

        return $this->render('AppBundle::contatti.html.twig', array(
            'report' => $report,
            'campaign' => $campaign,
            )
        );
    }

    /**
     * @Route("/feedback/{id}", name="feedback")
     */
    public function feedbackAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()
            ->getRepository('PairPI\Bundle\RecallcrmBundle\Entity\Report');

        $report = $repository->find($id);

        if($report->getComment() == 'recall'){
            $reply = $report->getReply();
            $operator = $report->getOperator();
            $campaign = $report->getCampaign();
            $email = $report->getEmail();
            $name = $report->getName();
            $phone = $report->getPhone();
            $date = $report->getDateTime();
            $report->setComment('hidden');
            
            $em->persist($report);

            $report = new Report;
                        
            $report
                ->setReply($reply)
                ->setOperator($operator)
                ->setCampaign($campaign)
                ->setName($name)
                ->setEmail($email)
                ->setPhone($phone)
                ->setDateTime($date)
                ->setComment('');
        }
        
        $form = $this->createFormBuilder($report)
            ->add('comment', 'choice', [ 
                'choices'=>[ 
                    'interessato' => 'Interessato',
                    'altro_positivo' => 'Altro positivo',
                    'non_interessato' => 'Non interessato',
                    'non_richiamare' => 'Non richiamare',
                    'altro_negativo' => 'Altro negativo',
                    'richiamare' => 'Da richiamare'
                ], 
               'multiple' => false, 
               'required'=> true, 
               'expanded' => true,
            ]) 
            ->add('positiveComment', 'text', [
                'required' => false,
            ]) 
            ->add('negativeComment', 'text', [
                'required' => false,
            ])
            ->add('note', 'textarea', [
                'required' => false,
                'attr' => [
                    'cols' => '77', 
                    'rows' => '7',
                ],
            ])
            ->add('dateTime', 'datetime', [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd H:m',
                'html5'   => false,
            ]) 
            ->add('save', 'submit', ['label'=> 'Salva']) 
            ->getForm(); 

        $form->handleRequest($request);
        
        if($form->isSubmitted()) {
            $today = date_create_from_format('Y-m-d H:i', date('Y-m-d H:i')); 
            
            $data = $form->getData();
            switch ($data->getComment()) {
                case 'interessato':
                    $report
                        ->setReply('Positivo')
                        ->setDateTime($today);
                    break;
                case 'altro_positivo':
                    $report
                        ->setReply('Positivo')
                        ->setDateTime($today);
                    break;
                case 'non_interessato':
                    $report
                        ->setReply('Negativo')
                        ->setDateTime($today);
                    break;
                case 'non_richiamare':
                    $report
                        ->setReply('Negativo')
                        ->setDateTime($today);
                    break;
                case 'altro_negativo':
                    $report
                        ->setReply('Negativo')
                        ->setDateTime($today);
                    break;
                case 'richiamare':
                    $report->setReply('Da richiamare');
                    $report->setComment('recall');
                    break;
            }
            
            $em->persist($report);
            $em->flush();
            
            return $this->redirect('/contatti/'.$report->getCampaign());
        }

        return $this->render('AppBundle::feedback.html.twig', array( 
            'form' => $form->createView(),
            'report' => $report, 
            )
        );
    }

    /**
     * @Route("/report", name="report")
     */
    public function reportAction(Request $request)
    {
        $repository = $this->getDoctrine()
            ->getRepository('PairPI\Bundle\RecallcrmBundle\Entity\Report');
        
        $report = $repository->findAll();

        return $this->render('AppBundle::report.html.twig', array(
            'report' => $report,
            )
        );
    }

    /**
     * @Route("/operatori", name="operatori")
     */
    public function operatoriAction(Request $request)
    {
        $repository = $this->getDoctrine()
            ->getRepository('PairPI\Bundle\RecallcrmBundle\Entity\Operatori');
        
        $operatori = $repository->createQueryBuilder('o')
            ->where('o.enabled = 1')
            ->andWhere('o.roles LIKE :role')
            ->setParameter('role', '%ROLE_OPERATORE%')
            ->getQuery()
            ->getResult();         

        return $this->render('AppBundle::operatori.html.twig', array(
            'operatori' => $operatori,
            )
        );
    }

    /**
     * @Route("/modifica/{id}", name="modifica")
     */
    public function modificaAction(Request $request, $id)
    {
        $userManager = $this->get('fos_user.user_manager');
        $operatore = $userManager->findUserBy(array('id'=>$id));

        $form = $this->createFormBuilder($operatore)
            ->add('email', 'email', [
                'label' => 'E-mail'
            ])
            ->add('firstname', 'text', [
                'label' => 'Nome'
            ])
            ->add('plainPassword', 'password', [
                'label' => 'Password', 
                'attr' => [
                    'placeholder' => 'invariata'
                ], 
                'required' => false, 
                'empty_data' => null
            ])
            ->add('lastname', 'text', [
                'label' => 'Cognome'
            ])
            ->add('status', 'choice', [
                'choices'=>[ 
                    'attivo' => 'Attivo', 
                    'inattivo' => 'Inattivo'
                ],
                'multiple' => false, 
                'expanded' => true, 
                'required'=> true, 
            ])
            ->add('save', 'submit', [
                'label'=> 'Salva'
            ])
            ->add('remove', 'submit', [
                'label'=> 'Elimina'
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->get('remove')->isClicked()){
                $operatore
                    ->setEnabled(false);
                $userManager->updateUser($operatore);
                
                return $this->redirectToRoute('operatori');
            }
            
            $userManager->updateUser($operatore);

            return $this->redirectToRoute('operatori');
        }

        return $this->render('AppBundle::modifica.html.twig', array(
            'form' => $form->createView(),
            'operatore' => $operatore,
            )
        );
    }

    /**
     * @Route("/nuovo", name="nuovo")
     */
    public function nuovoAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
        $operatore = $userManager->createUser();

        $form = $this->createFormBuilder($operatore)
            ->add('email', 'email', [
                'label' => 'E-mail'
            ])
            ->add('firstname', 'text', [
                'label' => 'Nome'
            ])
            ->add('plainPassword', 'password', [
                'label' => 'Password'
            ])
            ->add('lastname', 'text', [
                'label' => 'Cognome'
            ])
            ->add('status', 'choice', [
                'choices'=>[ 
                    'attivo' => 'Attivo', 
                    'inattivo' => 'Inattivo' 
                ], 
                'multiple' => false, 
                'expanded' => true, 
                'required'=> true, 
            ])
            ->add('save', 'submit', [
                'label'=> 'Salva'
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $data = $form->getData();

            $operatore
                ->setUsername($data->getFirstname() . $data->getLastname())
                ->setAssignedCalls()
                ->addRole('ROLE_OPERATORE')
                ->setEnabled(true);

            $userManager->updateUser($operatore);

            return $this->redirectToRoute('operatori');
        }

        return $this->render('AppBundle::nuovo.html.twig', array( 
            'form' => $form->createView(), 
            )
        );
    }
}
