<?php

namespace App\Controller;

use App\Entity\Dates;
use App\Entity\DTOpersonaldata;
use App\Entity\DTOroom;
use App\Entity\Reservation;
use App\Entity\Room;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RoomController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/api/r", name="room", methods={"GET"})
     */
    public function index()
    {
        $conn = $this->getDoctrine()->getConnection();
        $sql = 'SELECT * FROM room';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $rooms = $stmt->fetchAll();
        $response = new JsonResponse();
        $response->setData($rooms);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api/getDates", name="getDates", methods={"POST", "GET"})
     */
    public function showRoomsAction(Request $request, SerializerInterface $serializer)
    {
        $dates = $request->getContent();

        $data = $serializer->deserialize($dates,Dates::class, 'json');
        $checkin =  $data->getCheckin();
        $checkout = $data->getCheckout();

        $conn = $this->getDoctrine()->getConnection();
        $sql = 'SELECT id, title, beds, thumbnail, updated_at, quantity FROM room
        WHERE room.id NOT IN(SELECT room_id FROM reservation_room JOIN reservation ON reservation_room.reservation_id=reservation.id 
        WHERE :checkin < date2 AND :checkout > date1)';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['checkin' => $checkin, 'checkout' => $checkout]);
        $rooms = $stmt->fetchAll();




        $response = new JsonResponse();
        $response->setData($rooms);
        $cookie = new Cookie('myCookie', 'contentOfMyCookie');
        $response->headers->setCookie($cookie);

        return $response;
    }



    /**
     * @Route("/api/getRoom", name="getRoom", methods={"POST", "GET"})
     */
    public function getRoom(Request $request, SerializerInterface $serializer){
        $room = $request->getContent();
        $data = $serializer->deserialize($room, DTOroom::class, 'json');
        $roomid = $data->getRoomid();
        $response = new JsonResponse();
        $response->setData($roomid);
        $response->headers->setCookie(Cookie::create('roomid', $room));
        return $response;
    }


    /**
     * @Route("/api/postPersonalData", name="postpersdata", methods={"POST", "GET"})
     */
    function postPersdata(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager){
        $persData = $request->getContent();
        $data = $serializer->deserialize($persData, DTOpersonaldata::class, 'json');
        $email = $data->getEmail();
        $telephone = $data->getTelephone();
        $name = $data->getName();
        $checkin = $data->getCheckin();
        $checkout = $data->getCheckout();
        $roomid = $data->getRoomid();

        $checkin = date_create_from_format('Y-m-d', $checkin);
        $checkout = date_create_from_format('Y-m-d', $checkout);

        $reservation = new Reservation();
        $reservation->setDate1($checkin);
        $reservation->setDate2($checkout);
        $reservation->setEmail($email);
        $reservation->setName($name);
        $reservation->setTelephone($telephone);
        $reservation->addRoom($roomid);


        return new JsonResponse([$email, $telephone, $checkin, $name, $checkout, $roomid]);
    }




}
