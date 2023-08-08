<?php

namespace App\Controller;

use App\Dto\RoomCreateDto;
use App\Entity\Room;
use App\Entity\RoomChoice;
use App\Exception\ChoicesSuiteNotFoundException;
use App\Repository\RoomRepository;
use App\Service\ChoicesSuiteManager;
use App\Service\RoomMessagePublisher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class RoomController extends AbstractController
{
    public function __construct(
        private readonly RoomRepository $roomRepository,
        private readonly ChoicesSuiteManager $choicesSuiteManager,
        private readonly ValidatorInterface $validator,
        private readonly RoomMessagePublisher $roomMessagePublisher,
        private readonly ChartBuilderInterface $chartBuilder,
    )
    {
    }

    /**
     * @throws ChoicesSuiteNotFoundException
     */
    #[Route('/room/{id}', name: 'app_show_room')]
    public function showRoom(Request $request, Room $room): Response
    {
        $cookie = json_decode(
            $request->cookies->get(
                sprintf('room-%d', $room->getId())
            ) ?? '',
            true
        );

        $choice_uuid = $cookie['uuid'] ?? null;
        $choice_value = $cookie['choice'] ?? null;

        return $this->render('room/index.html.twig', [
            'controller_name' => 'RoomController',
            'room' => $room,
            'choices' => $this->choicesSuiteManager->getSuiteByName($room->getChoicesSuite())->getValues(),
            'selected_choice_uuid' => $choice_uuid,
            'selected_choice' => $choice_value,
            'choices_count' => $room->getChoices()->count(),
            'chart' => ($room->isClosed() ? $this->createChart($room) : null),
        ]);
    }

    #[Route('/room', name: 'app_create_room', methods: ['POST'])]
    public function createRoom(
        #[MapRequestPayload] RoomCreateDto $roomCreateDTO,
    ): Response
    {
        $room = new Room();
        $room->setName($roomCreateDTO->name);
        $room->setChoicesSuite($roomCreateDTO->choicesSuite);

        $errors = $this->validator->validate($room);
        if (count($errors) > 0) {
            throw new UnprocessableEntityHttpException('Cannot create room: ' . $errors);
        }

        $roomId = $this->roomRepository->save($room);

        return $this->redirect(
            sprintf('/room/%d', $roomId),
        );
    }

    #[Route('/room/{id}/close', name: 'app_close_room')]
    public function closeRoom(Room $room): Response
    {
        $room->setIsClosed(true);

        $this->roomRepository->save($room);

        $this->roomMessagePublisher->sendRoomClosed($room);

        return $this->redirect(
            sprintf('/room/%d', $room->getId())
        );
    }

    private function createChart(Room $room): Chart
    {
        $values = array_filter(
            array_map(
                fn (RoomChoice $roomChoice) => $roomChoice->getValue(),
                $room->getChoices()->getValues()
            ),
            fn ($value) => !is_null($value)
        );

        $valuesCountArray = array_count_values($values);

        $chart = $this->chartBuilder->createChart(Chart::TYPE_BAR);

        $chart->setData([
            'labels' => array_keys($valuesCountArray),
            'datasets' => [
                [
                    'label' => 'Votes',
                    'data' => array_values($valuesCountArray),
                    'backgroundColor' => 'rgba(107, 33, 168, 0.5)',
                ]
            ],
        ]);

        $chart->setOptions([
            'maintainAspectRatio' => false,
            'scales' => [
                'yAxes' => [
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ]);

        return $chart;
    }
}
