<?php

namespace App\Models;

use CodeIgniter\Model;

class EgresadoModel extends Model
{
    protected $table      = 'egresados';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'codigo_estudiante','dni','nombre_completo','escuela_id','sexo',
        'correo_institucional','email_personal','telefono','sede',
        'anio_ingreso','anio_egreso','semestre_egreso','anios_estudiados',
        'es_bachiller','anio_bachiller','fecha_diploma_bachiller',
        'es_titulado','anio_titulacion','fecha_diploma_titulo'
    ];

    // ─── JOIN con escuela ─────────────────────────────────────────────────────
    private function baseQuery()
    {
        return $this->db->table('egresados e')
            ->select('e.*, ep.nombre AS escuela_nombre, ep.codigo AS escuela_codigo, ep.facultad')
            ->join('escuelas_profesionales ep', 'ep.id = e.escuela_id');
    }

    // ─── KPIs ─────────────────────────────────────────────────────────────────
    public function totalEgresados(): int
    {
        return (int) $this->db->table('egresados')->countAllResults();
    }

    public function totalBachilleres(): int
    {
        return (int) $this->db->table('egresados')->where('es_bachiller', 1)->countAllResults();
    }

    public function totalTitulados(): int
    {
        return (int) $this->db->table('egresados')->where('es_titulado', 1)->countAllResults();
    }

    public function totalPorSexo(): array
    {
        return $this->db->table('egresados')
            ->select('sexo, COUNT(*) as total')
            ->groupBy('sexo')
            ->get()->getResultArray();
    }

    // ─── Por escuela ─────────────────────────────────────────────────────────
    public function porEscuela(): array
    {
        return $this->db->table('egresados e')
            ->select('ep.nombre AS escuela, ep.codigo,
                      COUNT(*) AS total_egresados,
                      SUM(e.es_bachiller) AS bachilleres,
                      SUM(e.es_titulado)  AS titulados')
            ->join('escuelas_profesionales ep', 'ep.id = e.escuela_id')
            ->groupBy('e.escuela_id')
            ->orderBy('total_egresados', 'DESC')
            ->get()->getResultArray();
    }

    // ─── Por año de egreso ────────────────────────────────────────────────────
    public function porAnioEgreso(): array
    {
        return $this->db->table('egresados')
            ->select('anio_egreso, COUNT(*) AS total,
                      SUM(es_bachiller) AS bachilleres,
                      SUM(es_titulado)  AS titulados')
            ->whereNotNull('anio_egreso')
            ->groupBy('anio_egreso')
            ->orderBy('anio_egreso', 'ASC')
            ->get()->getResultArray();
    }

    // ─── Por sede ─────────────────────────────────────────────────────────────
    public function porSede(): array
    {
        return $this->db->table('egresados')
            ->select('sede, COUNT(*) AS total,
                      SUM(es_bachiller) AS bachilleres,
                      SUM(es_titulado)  AS titulados')
            ->groupBy('sede')
            ->get()->getResultArray();
    }

    // ─── Titulados por escuela ────────────────────────────────────────────────
    public function tituladosPorEscuela(): array
    {
        return $this->db->table('egresados e')
            ->select('ep.nombre AS escuela, SUM(e.es_titulado) AS titulados, SUM(e.es_bachiller) AS bachilleres')
            ->join('escuelas_profesionales ep', 'ep.id = e.escuela_id')
            ->groupBy('e.escuela_id')
            ->orderBy('titulados', 'DESC')
            ->get()->getResultArray();
    }

    // ─── Listado con filtros ──────────────────────────────────────────────────
    public function listar(array $filtros = [], int $perPage = 20): array
    {
        $q = $this->baseQuery();

        if (!empty($filtros['busqueda'])) {
            $b = $filtros['busqueda'];
            $q->groupStart()
                ->like('e.nombre_completo', $b)
                ->orLike('e.dni', $b)
                ->orLike('e.codigo_estudiante', $b)
              ->groupEnd();
        }
        if (!empty($filtros['escuela_id'])) {
            $q->where('e.escuela_id', $filtros['escuela_id']);
        }
        if (!empty($filtros['sede'])) {
            $q->where('e.sede', $filtros['sede']);
        }
        if (!empty($filtros['sexo'])) {
            $q->where('e.sexo', $filtros['sexo']);
        }
        if (isset($filtros['es_bachiller']) && $filtros['es_bachiller'] !== '') {
            $q->where('e.es_bachiller', (int) $filtros['es_bachiller']);
        }
        if (isset($filtros['es_titulado']) && $filtros['es_titulado'] !== '') {
            $q->where('e.es_titulado', (int) $filtros['es_titulado']);
        }
        if (!empty($filtros['anio_egreso'])) {
            $q->where('e.anio_egreso', $filtros['anio_egreso']);
        }

        $q->orderBy('e.nombre_completo', 'ASC');

        $total = clone $q;
        $totalCount = $total->countAllResults(false);

        $page   = (int) ($filtros['page'] ?? 1);
        $offset = ($page - 1) * $perPage;

        $data = $q->limit($perPage, $offset)->get()->getResultArray();

        return ['data' => $data, 'total' => $totalCount, 'per_page' => $perPage, 'page' => $page];
    }

    // ─── Detalle ──────────────────────────────────────────────────────────────
    public function detalle(int $id): ?array
    {
        $row = $this->baseQuery()->where('e.id', $id)->get()->getRowArray();
        return $row ?: null;
    }
}
