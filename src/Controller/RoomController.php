<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RoomController extends AbstractController
{
    /**
     * @Route("/rr", name="room")
     */
    public function index()
    {
        $rooms = $this->getDoctrine()->getManager()->getRepository(Room::class)->findAll();

        return $this->render('room/index.html.twig', [
            'rooms' => $rooms,
        ]);
    }

    /**
     * @Route("/rooms", name="findroom")
     */
    public function showRoomsAction($roomId, Request $request)
    {
        $room = $this->getDoctrine()
            ->getRepository(Room::class)
            ->find($roomId);

        $reservations = $room->getReservations();
        $rooms = $this->getDoctrine()->getRepository(Room::class)->findAll();

        $reservation = new Reservation();
        $form = $this->createFormBuilder($reservation)
            ->add('date1', DateType::class, array('label' => 'Arrival date', 'attr'=>array('class'=>'form-control mb-3')))
            ->add('date2', DateType::class, array('label' => 'End date', 'attr'=>array('class'=>'form-control mb-3')))
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

        return $this->render('room/rooms.html.twig', array('reservations' => $reservations,'room'=>$room, 'rooms' => $rooms, 'form' => $form->createView()));
    }



    /**
     * @Route("/rooms/{dateFirst}/{dateSecond}", name="is_available")
     */
    public function listreservations($dateFirst, $dateSecond, Request $request)
    {
        $conn = $this->getDoctrine()->getConnection();
        $sql = 'SELECT * FROM reservation
                WHERE NOT :dateFirst BETWEEN date1 AND date2';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['dateFirst' => $dateFirst]);
        $rooms = $stmt->fetchAll();
        if ($rooms) {
            throw new \Exception('Something went wrong!');
        } else {

            return $this->render('default/show.html.twig', array('rooms' => $rooms));

        }
    }


}
