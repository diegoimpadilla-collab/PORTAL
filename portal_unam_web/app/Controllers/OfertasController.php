<?php

namespace App\Controllers;

use App\Models\OfertaModel;
use App\Models\EscuelaModel;

class OfertasController extends BaseController
{
    private OfertaModel  $model;
    private EscuelaModel $escuelaModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request,
                                   \CodeIgniter\HTTP\ResponseInterface $response,
                                   \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->model        = new OfertaModel();
        $this->escuelaModel = new EscuelaModel();
    }

    public function index(): string
    {
        $filtros = [
            'busqueda'   => $this->request->getGet('q') ?? '',
            'escuela_id' => $this->request->getGet('escuela') ?? '',
            'modalidad'  => $this->request->getGet('modalidad') ?? '',
            'page'       => $this->request->getGet('page') ?? 1,
        ];

        $resultado = $this->model->listar($filtros, 10);
        $data = [
            'title'    => 'Bolsa de Trabajo – Portal UNAM',
            'filtros'  => $filtros,
            'ofertas'  => $resultado['data'],
            'total'    => $resultado['total'],
            'per_page' => $resultado['per_page'],
            'page'     => $resultado['page'],
            'escuelas' => $this->escuelaModel->todas(),
        ];
        return view('layouts/main', $data + ['content' => view('ofertas/index', $data)]);
    }

    public function detalle(int $id): string
    {
        $oferta = $this->model->detalle($id);
        if (!$oferta) {
            return view('layouts/main', [
                'title'   => 'No encontrado',
                'content' => view('errors/not_found')
            ]);
        }
        return view('layouts/main', [
            'title'   => $oferta['titulo'] . ' – Portal UNAM',
            'oferta'  => $oferta,
            'content' => view('ofertas/detalle', ['oferta' => $oferta])
        ]);
    }
}
