<?php

namespace App\Controllers;

use App\Models\EgresadoModel;
use App\Models\EmpleadorModel;
use App\Models\OfertaModel;
use App\Models\EscuelaModel;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    private EgresadoModel  $egresadoModel;
    private EmpleadorModel $empleadorModel;
    private OfertaModel    $ofertaModel;
    private EscuelaModel   $escuelaModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request,
                                   ResponseInterface $response,
                                   \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->egresadoModel  = new EgresadoModel();
        $this->empleadorModel = new EmpleadorModel();
        $this->ofertaModel    = new OfertaModel();
        $this->escuelaModel   = new EscuelaModel();
    }

    // ─── Vista principal del dashboard ───────────────────────────────────────
    public function index(): string
    {
        $data = [
            'title'           => 'Dashboard – Portal de Egresados UNAM',
            'total_egresados' => $this->egresadoModel->totalEgresados(),
            'total_bachilleres' => $this->egresadoModel->totalBachilleres(),
            'total_titulados' => $this->egresadoModel->totalTitulados(),
            'total_ofertas'   => $this->ofertaModel->totalActivas(),
            'ofertas_recientes' => $this->ofertaModel->recientes(4),
            'top_empleadores' => $this->empleadorModel->topEmpleadores(5),
            'escuelas'        => $this->escuelaModel->todas(),
        ];

        return view('layouts/main', $data + ['content' => view('dashboard/index', $data)]);
    }

    // ─── API: resumen general ─────────────────────────────────────────────────
    public function kpiResumen(): ResponseInterface
    {
        return $this->response->setJSON([
            'egresados'   => $this->egresadoModel->totalEgresados(),
            'bachilleres' => $this->egresadoModel->totalBachilleres(),
            'titulados'   => $this->egresadoModel->totalTitulados(),
            'ofertas'     => $this->ofertaModel->totalActivas(),
        ]);
    }

    // ─── API: por escuela ─────────────────────────────────────────────────────
    public function kpiPorEscuela(): ResponseInterface
    {
        return $this->response->setJSON($this->egresadoModel->porEscuela());
    }

    // ─── API: por año ─────────────────────────────────────────────────────────
    public function kpiPorAnio(): ResponseInterface
    {
        return $this->response->setJSON($this->egresadoModel->porAnioEgreso());
    }

    // ─── API: por sede ────────────────────────────────────────────────────────
    public function kpiPorSede(): ResponseInterface
    {
        return $this->response->setJSON($this->egresadoModel->porSede());
    }

    // ─── API: por sexo ────────────────────────────────────────────────────────
    public function kpiPorSexo(): ResponseInterface
    {
        return $this->response->setJSON($this->egresadoModel->totalPorSexo());
    }

    // ─── API: titulados por escuela ───────────────────────────────────────────
    public function kpiTituladosPorEscuela(): ResponseInterface
    {
        return $this->response->setJSON($this->egresadoModel->tituladosPorEscuela());
    }
}
