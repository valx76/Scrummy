<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\RoomChoice;
use App\Exception\RoomClosedException;
use App\Repository\RoomChoiceRepository;
use App\Service\RoomMessagePublisher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RoomChoiceController extends AbstractController
{
    public function __construct(
        private readonly RoomChoiceRepository $roomChoiceRepository,
        private readonly RoomMessagePublisher $roomMessagePublisher,
        private readonly ValidatorInterface $validator,
    )
    {
    }

    /**
     * @throws RoomClosedException
     */
    #[Route('/room/{id}/choice/{choice}', name: 'app_room_choose')]
    public function choose(
        #[MapQueryParameter] ?string $uuid,
        Room $room,
        string $choice
    ): Response
    {
        if ($room->isClosed()) {
            throw new RoomClosedException();
        }

        $choice = rawurldecode($choice);

        if ($uuid !== null && strlen($uuid) > 0) {
            $roomChoice = $this->roomChoiceRepository->findOneBy(['uuid' => $uuid]);
        } else {
            $roomChoice = new RoomChoice();
            $roomChoice->setRoom($room);
        }

        $roomChoice->setValue($choice);

        $errors = $this->validator->validate($roomChoice);
        if (count($errors) > 0) {
            throw new UnprocessableEntityHttpException('Cannot create choice: ' . $errors);
        }

        $this->roomChoiceRepository->save($roomChoice);

        // Will only add the roomChoice if not already present
        // The value will be incorrect if already present and user selected a different one
        // -> But we don't care since we only want the count in the Mercure message
        $room->addChoice($roomChoice);

        $this->roomMessagePublisher->sendRoomChoicesCount($room);

        $response = $this->redirect(
            sprintf('/room/%d', $room->getId())
        );

        $cookie = Cookie::create(
            sprintf('room-%d', $room->getId()),
            json_encode([
                'uuid' => $roomChoice->getUuid()->toRfc4122(),
                'choice' => $choice,
            ]),
            time() + 7 * 24 * 60 * 60
        );

        $response->headers->setCookie($cookie);

        return $response;
    }
}
