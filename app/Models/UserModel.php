<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'informacionpersonas';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $allowedFields = ['nombre', 'apellido', 'email', 'cel', 'image'];

    protected bool $allowEmptyInserts = false;
}




 
