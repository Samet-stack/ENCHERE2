<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id_role';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['libelle'];
}
