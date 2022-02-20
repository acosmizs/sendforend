<?php

namespace App\Models;

use CodeIgniter\Model;

class CorModel extends Model
{
    protected $table            = 'cores';
    protected $returnType       = 'object'; // array, object, entity
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['nome', 'descricao'];
}
