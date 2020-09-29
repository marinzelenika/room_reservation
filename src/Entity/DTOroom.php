<?php
namespace App\Entity;

class DTOroom {
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


}