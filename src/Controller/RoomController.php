<?php

namespace App\Controller;

use App\Entity\Dates;
use App\Entity\Reservation;
use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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



        return new JsonResponse($checkin);
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
