<?php

namespace App\Controllers;

use App\Models\EmpleadorModel;

class EmpleadoresController extends BaseController
{
    private EmpleadorModel $model;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request,
                                   \CodeIgniter\HTTP\ResponseInterface $response,
                                   \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->model = new EmpleadorModel();
    }

    public function index(): string
    {
        $resultado = $this->model->listar(12);
        $data = [
            'title'       => 'Empleadores – Portal UNAM',
            'empleadores' => $resultado['data'],
            'total'       => $resultado['total'],
        ];
        return view('layouts/main', $data + ['content' => view('empleadores/index', $data)]);
    }

    public function detalle(int $id): string
    {
        $empleador = $this->model->detalle($id);
        if (!$empleador) {
            return view('layouts/main', [
                'title'   => 'No encontrado',
                'content' => view('errors/not_found')
            ]);
        }
        return view('layouts/main', [
            'title'     => $empleador['razon_social'] . ' – Portal UNAM',
            'empleador' => $empleador,
            'content'   => view('empleadores/detalle', ['empleador' => $empleador])
        ]);
    }
}
