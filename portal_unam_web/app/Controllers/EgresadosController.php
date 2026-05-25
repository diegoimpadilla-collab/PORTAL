<?php

namespace App\Controllers;

use App\Models\EgresadoModel;
use App\Models\EscuelaModel;

class EgresadosController extends BaseController
{
    private EgresadoModel $model;
    private EscuelaModel  $escuelaModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request,
                                   \CodeIgniter\HTTP\ResponseInterface $response,
                                   \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->model        = new EgresadoModel();
        $this->escuelaModel = new EscuelaModel();
    }

    public function index(): string
    {
        return $this->buscar();
    }

    public function buscar(): string
    {
        $filtros = [
            'busqueda'   => $this->request->getGet('q') ?? '',
            'escuela_id' => $this->request->getGet('escuela') ?? '',
            'sede'       => $this->request->getGet('sede') ?? '',
            'sexo'       => $this->request->getGet('sexo') ?? '',
            'es_bachiller' => $this->request->getGet('bachiller') ?? '',
            'es_titulado'  => $this->request->getGet('titulado') ?? '',
            'anio_egreso'  => $this->request->getGet('anio') ?? '',
            'page'         => $this->request->getGet('page') ?? 1,
        ];

        $resultado = $this->model->listar($filtros, 20);

        $data = [
            'title'    => 'Egresados – Portal UNAM',
            'filtros'  => $filtros,
            'egresados' => $resultado['data'],
            'total'    => $resultado['total'],
            'per_page' => $resultado['per_page'],
            'page'     => $resultado['page'],
            'escuelas' => $this->escuelaModel->todas(),
            'anios'    => range(2015, 2025),
        ];

        return view('layouts/main', $data + ['content' => view('egresados/index', $data)]);
    }

    public function detalle(int $id): string
    {
        $egresado = $this->model->detalle($id);

        if (!$egresado) {
            return view('layouts/main', [
                'title'   => 'No encontrado',
                'content' => view('errors/not_found')
            ]);
        }

        return view('layouts/main', [
            'title'    => $egresado['nombre_completo'] . ' – Portal UNAM',
            'egresado' => $egresado,
            'content'  => view('egresados/detalle', ['egresado' => $egresado])
        ]);
    }
}
