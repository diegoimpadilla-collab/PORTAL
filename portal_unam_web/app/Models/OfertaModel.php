<?php

namespace App\Models;

use CodeIgniter\Model;

class OfertaModel extends Model
{
    protected $table      = 'ofertas_laborales';
    protected $primaryKey = 'id';

    private function baseQuery()
    {
        return $this->db->table('ofertas_laborales o')
            ->select('o.*, emp.razon_social AS empresa, emp.sector, emp.ciudad AS ciudad_empresa,
                      ep.nombre AS escuela_nombre, ep.codigo AS escuela_codigo')
            ->join('empleadores emp', 'emp.id = o.empleador_id')
            ->join('escuelas_profesionales ep', 'ep.id = o.escuela_id', 'left');
    }

    public function listar(array $filtros = [], int $perPage = 10): array
    {
        $q = $this->baseQuery()->where('o.activa', 1);

        if (!empty($filtros['escuela_id'])) {
            $q->where('o.escuela_id', $filtros['escuela_id']);
        }
        if (!empty($filtros['modalidad'])) {
            $q->where('o.modalidad', $filtros['modalidad']);
        }
        if (!empty($filtros['busqueda'])) {
            $b = $filtros['busqueda'];
            $q->groupStart()
                ->like('o.titulo', $b)
                ->orLike('emp.razon_social', $b)
                ->orLike('o.ubicacion', $b)
              ->groupEnd();
        }

        $q->orderBy('o.fecha_pub', 'DESC');

        $total  = clone $q;
        $count  = $total->countAllResults(false);
        $page   = (int) ($filtros['page'] ?? 1);
        $offset = ($page - 1) * $perPage;
        $data   = $q->limit($perPage, $offset)->get()->getResultArray();

        return ['data' => $data, 'total' => $count, 'per_page' => $perPage, 'page' => $page];
    }

    public function detalle(int $id): ?array
    {
        return $this->baseQuery()->where('o.id', $id)->get()->getRowArray() ?: null;
    }

    public function recientes(int $limit = 4): array
    {
        return $this->baseQuery()
            ->where('o.activa', 1)
            ->orderBy('o.fecha_pub', 'DESC')
            ->limit($limit)
            ->get()->getResultArray();
    }

    public function totalActivas(): int
    {
        return (int) $this->db->table('ofertas_laborales')->where('activa', 1)->countAllResults();
    }
}
