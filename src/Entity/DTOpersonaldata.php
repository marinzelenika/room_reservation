<?php
namespace App\Entity;

class DTOpersonaldata {
    private $email;
    private $checkin;
    private $roomid;

    /**
     * @return mixed
     */
    public function getRoomid()
    {
        return $this->roomid;
    }

    /**
     * @param mixed $roomid
     */
    public function setRoomid($roomid): void
    {
        $this->roomid = $roomid;
    }

    /**
     * @return mixed
     */
    public function getCheckin()
    {
        return $this->checkin;
    }

    /**
     * @param mixed $checkin
     */
    public function setCheckin($checkin): void
    {
        $this->checkin = $checkin;
    }

    /**
     * @return mixed
     */
    public function getCheckout()
    {
        return $this->checkout;
    }

    /**
     * @param mixed $checkout
     */
    public function setCheckout($checkout): void
    {
        $this->checkout = $checkout;
    }
    private $checkout;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone): void
    {
        $this->telephone = $telephone;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }
    private $telephone;
    private $name;
}