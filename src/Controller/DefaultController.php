<?php

namespace App\Controller;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="default")
     */
    public function index(Request $request)
    {
        $reservation = new Reservation();
        $form = $this->createFormBuilder($reservation)
            ->add('date1', DateType::class, array('label' => 'Arrival date', 'attr'=>array('class'=>'form-control mb-3')))
            ->add('date2', DateType::class, array('label' => 'End date', 'attr'=>array('class'=>'form-control mb-3')))
            ->add('beds', IntegerType::class, array('label' => 'Beds', 'attr'=>array('class'=>'form-control mb-3')))
            ->add('save', SubmitType::class, array('label' => 'Check', 'attr' => array('class' => 'btn btn-primary mt-4')))
            ->getForm();


        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $reservation = $form->getData();

            //  $entityManager->persist($reservation);
            // $entityManager->flush();

                $dateFirst = $form->get('date1')->getData()->format('Y-m-d');
                $dateSecond = $form->get('date2')->getData()->format('Y-m-d');

            $response = $this->forward('App\Controller\DefaultController::listreservations', [
                'dateFirst'  => $dateFirst,
                'dateSecond' => $dateSecond,
            ]);
            return $response;


        }



        return $this->render('default/index.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/reservations/{dateFirst}/{dateSecond}", name="display_res")
     */
    public function listreservations($dateFirst, $dateSecond, Request $request){
        $conn = $this->getDoctrine()->getConnection();
        $sql = 'SELECT * FROM Reservation
                WHERE :dateFirst BETWEEN date1 AND date2';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['dateFirst' => $dateFirst]);
        $reservations = $stmt->fetchAll();

        return $this->render('default/show.html.twig', array('reservations' => $reservations));

    }




}
