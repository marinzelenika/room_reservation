<?php
namespace App\Entity;

class Dates {
    private $checkin;

    private $checkout;

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
}