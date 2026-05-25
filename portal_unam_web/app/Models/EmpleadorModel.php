<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpleadorModel extends Model
{
    protected $table      = 'empleadores';
    protected $primaryKey = 'id';

    public function listar(int $perPage = 12): array
    {
        $total = $this->where('activo', 1)->countAllResults(false);
        $data  = $this->where('activo', 1)->orderBy('razon_social')->paginate($perPage);
        return ['data' => $data, 'total' => $total];
    }

    public function detalle(int $id): ?array
    {
        $emp = $this->find($id);
        if (!$emp) return null;

        // Escuelas con las que trabaja
        $escuelas = $this->db->table('empleadores_escuelas ee')
            ->select('ep.nombre, ep.codigo, ee.egresados_contratados, ee.anio')
            ->join('escuelas_profesionales ep', 'ep.id = ee.escuela_id')
            ->where('ee.empleador_id', $id)
            ->orderBy('ee.egresados_contratados', 'DESC')
            ->get()->getResultArray();

        // Ofertas activas
        $ofertas = $this->db->table('ofertas_laborales')
            ->where('empleador_id', $id)
            ->where('activa', 1)
            ->orderBy('fecha_pub', 'DESC')
            ->get()->getResultArray();

        return array_merge($emp, ['escuelas' => $escuelas, 'ofertas' => $ofertas]);
    }

    public function topEmpleadores(int $limit = 5): array
    {
        return $this->db->table('empleadores_escuelas ee')
            ->select('e.razon_social, e.sector, e.ciudad, SUM(ee.egresados_contratados) AS total')
            ->join('empleadores e', 'e.id = ee.empleador_id')
            ->where('e.activo', 1)
            ->groupBy('ee.empleador_id')
            ->orderBy('total', 'DESC')
            ->limit($limit)
            ->get()->getResultArray();
    }
}
