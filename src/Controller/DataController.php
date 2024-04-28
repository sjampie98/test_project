<?php

namespace App\Controller;

use App\Entity\Data;
use App\Form\DataType;
use App\Repository\DataRepository;
use App\Service\DataService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/data')]
class DataController extends AbstractController
{
    private DataService $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    #[Route('/', name: 'app_data_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('data/index.html.twig', [
            'data' => $this->dataService->getAll(),
        ]);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/new', name: 'app_data_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $new_data = $this->dataService->create($request);
        if (!isset($new_data['data']) && !isset($new_data['form'])) {
            return $this->redirectToRoute('app_data_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('data/new.html.twig', [
            'data' => $new_data['data'],
            'form' => $new_data['form'],
        ]);
    }

    #[Route('/{id}', name: 'app_data_show', methods: ['GET'])]
    public function show(Data $data): Response
    {
        return $this->render('data/show.html.twig', [
            'data' => $data,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_data_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Data $data): Response
    {
        $new_data= $this->dataService->edit($request, $data);

        if (!isset($new_data['data']) && !isset($new_data['form'])) {
            return $this->redirectToRoute('app_data_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('data/edit.html.twig', [
            'data' => $new_data['data'],
            'form' => $new_data['form'],
        ]);
    }

    #[Route('/{id}', name: 'app_data_delete', methods: ['POST'])]
    public function delete(Request $request, Data $data, EntityManagerInterface $entityManager): Response
    {
        $this->dataService->delete($request, $data);
        if ($this->isCsrfTokenValid('delete'.$data->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($data);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_data_index', [], Response::HTTP_SEE_OTHER);
    }
}
