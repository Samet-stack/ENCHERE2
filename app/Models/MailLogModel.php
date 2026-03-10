<?php

namespace App\Models;

use CodeIgniter\Model;

class MailLogModel extends Model
{
    public $table = 'mails_log';
    public $primaryKey = 'id_mail';
    public $useAutoIncrement = true;
    public $returnType = 'array';

    public $allowedFields = [
        'id_vente',
        'type_mail',
        'destinataire',
        'statut',
        'envoye_le'
    ];
}
