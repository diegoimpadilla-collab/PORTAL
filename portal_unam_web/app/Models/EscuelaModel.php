<?php

namespace App\Models;

use CodeIgniter\Model;

class EscuelaModel extends Model
{
    protected $table      = 'escuelas_profesionales';
    protected $primaryKey = 'id';

    public function todas(): array
    {
        return $this->where('activo', 1)->orderBy('nombre')->findAll();
    }
}
