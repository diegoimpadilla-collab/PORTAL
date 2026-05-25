<?php

namespace App\Controllers;

use App\Models\EmpleadorModel;
use App\Models\EscuelaModel;

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
            return view('layouts/main', ['title' => 'No encontrado', 'content' => view('errors/not_found')]);
        }
        return view('layouts/main', [
            'title'     => $empleador['razon_social'] . ' – Portal UNAM',
            'empleador' => $empleador,
            'content'   => view('empleadores/detalle', ['empleador' => $empleador])
        ]);
    }

    /** GET /empleadores/registrar */
    public function registrar(): string
    {
        return view('layouts/main', [
            'title'   => 'Registrar Empresa – Portal UNAM',
            'flash'   => session()->getFlashdata('success'),
            'errors'  => session()->getFlashdata('errors'),
            'content' => view('empleadores/registrar')
        ]);
    }

    /** POST /empleadores/registrar */
    public function guardar(): \CodeIgniter\HTTP\RedirectResponse
    {
        $rules = [
            'razon_social' => 'required|min_length[3]|max_length[200]',
            'sector'       => 'required|max_length[100]',
            'ciudad'       => 'required|max_length[100]',
            'email'        => 'permit_empty|valid_email',
            'telefono'     => 'permit_empty|max_length[20]',
            'web'          => 'permit_empty|max_length[255]',
            'descripcion'  => 'permit_empty|max_length[1000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->model->insert([
            'razon_social' => $this->request->getPost('razon_social'),
            'sector'       => $this->request->getPost('sector'),
            'ciudad'       => $this->request->getPost('ciudad'),
            'email'        => $this->request->getPost('email'),
            'telefono'     => $this->request->getPost('telefono'),
            'web'          => $this->request->getPost('web'),
            'descripcion'  => $this->request->getPost('descripcion'),
            'activo'       => 1,
        ]);

        return redirect()->to('/empleadores')
            ->with('success', '✅ Empresa registrada exitosamente.');
    }

    /** GET /api/empleadores */
    public function apiListar(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->response->setJSON(
            $this->model->where('activo', 1)->orderBy('razon_social')->findAll()
        );
    }
}
