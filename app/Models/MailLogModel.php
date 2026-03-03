<?php

namespace App\Models;

use CodeIgniter\Model;

class MailLogModel extends Model
{
    protected $table = 'mails_log';
    protected $primaryKey = 'id_mail';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_vente',
        'type_mail',
        'destinataire',
        'statut',
        'envoye_le'
    ];
}
