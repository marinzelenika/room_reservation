<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Room;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/", name="default")
     */
    public function index(Request $request)
    {
        $reservation = new Reservation();
        $form = $this->createFormBuilder($reservation)
            ->add('date1', DateType::class, array('label' => 'Dolazak (Check in)', 'attr'=>array('class'=>'form-control mb-3'),'widget' => 'single_text'))
            ->add('date2', DateType::class, array('label' => 'Odlazak (Check out)', 'attr'=>array('class'=>'form-control mb-3'),'widget' => 'single_text'))
            ->add('save', SubmitType::class, array('label' => 'Check', 'attr' => array('class' => 'btn btn-primary mt-4')))
            ->getForm();


        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $reservation = $form->getData();

            //  $entityManager->persist($reservation);
            // $entityManager->flush();

            $dateFirst = $form->get('date1')->getData()->format('Y-m-d');
            $dateSecond = $form->get('date2')->getData()->format('Y-m-d');
            $dateNow = new \DateTime('@'.strtotime('now'));
            $dateNow = $dateNow->format('Y-m-d');
            $this->session->set('dateFirst', $dateFirst);

            if ($dateSecond <= $dateFirst) {
                $this->addFlash(
                    'warning',
                    'Drugi datum veći od prvog !'
                );
                return $this->redirectToRoute('default');
            }
                if ($dateSecond < $dateNow || $dateFirst < $dateNow) {
                    $this->addFlash(
                        'warning',
                        'Datumi su u prošlosti !'
                    );
                    return $this->redirectToRoute('default');
                }

             else {

                $response = $this->forward('App\Controller\DefaultController::listreservations', [
                    'dateFirst' => $dateFirst,
                    'dateSecond' => $dateSecond,
                ]);

                return $response;
            }
        }

            return $this->render('default/index.html.twig', array('form' => $form->createView()));

    }

    /**
     * @Route("/reservations", name="display_res")
     */
    public function listreservations($dateFirst, $dateSecond, Request $request){
        $conn = $this->getDoctrine()->getConnection();
        $sql = 'SELECT * FROM room
        WHERE room.id NOT IN(SELECT room_id FROM reservation_room JOIN reservation ON reservation_room.reservation_id=reservation.id 
        WHERE :dateFirst < date2 AND :dateSecond > date1)';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['dateFirst' => $dateFirst, 'dateSecond' => $dateSecond]);
        $rooms = $stmt->fetchAll();
        if (!$rooms){
            $this->addFlash(
                'warning',
                'Nema slobodnih soba na traženi datum !'
            );
            return $this->redirectToRoute('default');
        }else {

            return $this->render('default/show.html.twig', array('rooms' => $rooms,'dateFirst' => $dateFirst, 'dateSecond' => $dateSecond));
        }






    }



    /**
     * @Route("/reservations/proceed/{roomId}", name="proceedToBasket")
     */
    public function proceedToBasket($dateFirst, $dateSecond, Request $request, $roomId){

        $room = $this->getDoctrine()
            ->getRepository(Room::class)
            ->find($roomId);

        $reservation = new Reservation();
        $form = $this->createFormBuilder($reservation)
            ->add('date1', HiddenType::class, [
                'data' => $dateFirst,
            ])
            ->add('date2', HiddenType::class, [
                'data' => $dateSecond,
            ])
            ->add('email', EmailType::class, array('label' => 'Email', 'attr'=>array('class'=>'form-control mb-3')))
            ->add('telephone', TextType::class, array('label' => 'Broj telefona:', 'attr'=>array('class'=>'form-control mb-3')))
            ->add('name', TextType::class, array('label' => 'Ime i prezime', 'attr'=>array('class'=>'form-control mb-3')))
            ->add('save', SubmitType::class, array('label' => 'Potvrdi', 'attr' => array('class' => 'btn btn-primary mt-4')))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted()){
            return $this->redirectToRoute('default');
        }


        return $this->render('default/proceed.html.twig', array('form' => $form->createView(), 'room'=>$room));

    }


}
