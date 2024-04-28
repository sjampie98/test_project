<?php

namespace App\Service;

use App\Entity\Data;
use App\Form\DataType;
use App\Repository\DataRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class DataService extends AbstractController
{
    private DataRepository $dataRepository;
    private EntityManagerInterface $entityManager;

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
     * @param $request
     * @return array
     */
    public function create($request): array
    {
        $data = new Data();
        $form = $this->createForm(DataType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($data);
            $this->entityManager->flush();

            return [];
        }

        return ['data' => $data, 'form' => $form];
    }

}
