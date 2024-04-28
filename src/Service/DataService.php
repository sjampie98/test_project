<?php

namespace App\Service;

use App\Entity\Data;
use App\Form\DataType;
use App\Repository\DataRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class DataService
 */
class DataService extends AbstractController
{
    /**
     * @var DataRepository
     */
    private DataRepository $dataRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param DataRepository $dataRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(DataRepository $dataRepository, EntityManagerInterface $entityManager)
    {
        $this->dataRepository = $dataRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return \App\Entity\Data[]|array|object[]
     */
    public function getAll(): array
    {
        return $this->dataRepository->findAll();
    }

    /**
     * @param Request $request
     * @return array
     */
    public function create(Request $request): array
    {
        $data = new Data();
        $data->setText($request->get('text'));
        $form = $this->createForm(DataType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($data);
            $this->entityManager->flush();

            return [];
        }

        return ['data' => $data, 'form' => $form];
    }

    /**
     * @param Request $request
     * @param Data $data
     * @return array
     */
    public function edit(Request $request,Data $data): array
    {
        $data->setText($request->get('text'));
        $form = $this->createForm(DataType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return [];
        }

        return ['data' => $data, 'form' => $form];
    }

    /**
     * @param Request $request
     * @param Data $data
     * @return void
     */
    public function delete(Request $request,Data $data): void
    {
        if ($this->isCsrfTokenValid('delete'.$data->getId(), $request->getPayload()->get('_token'))) {
            $this->entityManager->remove($data);
            $this->entityManager->flush();
        }
    }
}
