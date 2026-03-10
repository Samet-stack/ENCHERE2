<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    public $table = 'roles';
    public $primaryKey = 'id_role';
    public $useAutoIncrement = true;
    public $returnType = 'array';
    public $allowedFields = ['libelle'];
}
