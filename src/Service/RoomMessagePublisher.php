<?php

namespace App\Service;

use App\Entity\Room;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

readonly class RoomMessagePublisher
{
    public function __construct(
        private HubInterface $hub,
    )
    {
    }

    public function sendRoomChoicesCount(Room $room): void
    {
        $update = new Update(
            sprintf('room-%d', $room->getId()),
            $room->getChoices()->count()
        );

        $this->hub->publish($update);
    }

    public function sendRoomClosed(Room $room): void
    {
        $update = new Update(
            sprintf('room-close-%d', $room->getId())
        );

        $this->hub->publish($update);
    }
}
