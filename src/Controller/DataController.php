<?php

namespace App\Controller;

use App\Entity\Data;
use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class DataController
 */
#[Route('/data')]
class DataController extends AbstractController
{
    /**
     * @var DataService
     */
    private DataService $dataService;

    /**
     * @param DataService $dataService
     */
    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    /**
     * @return Response
     */
    #[Route('/', name: 'app_data_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('data/index.html.twig', [
            'data' => $this->dataService->getAll(),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
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
        $new_data = $this->dataService->edit($request, $data);

        if (!isset($new_data['data']) && !isset($new_data['form'])) {
            return $this->redirectToRoute('app_data_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('data/edit.html.twig', [
            'data' => $new_data['data'],
            'form' => $new_data['form'],
        ]);
    }

    #[Route('/{id}', name: 'app_data_delete', methods: ['POST'])]
    public function delete(Request $request, Data $data): Response
    {
        $this->dataService->delete($request, $data);

        return $this->redirectToRoute('app_data_index', [], Response::HTTP_SEE_OTHER);
    }
}
