<?php

namespace App\Models;

use CodeIgniter\Model;

class MailLogModel extends Model
{
    // Configuration de base pour l'historique des emails envoyés
    public $table = 'mails_log'; // Table contenant l'historique
    public $primaryKey = 'id_mail'; // Identifiant unique d'un log
    public $useAutoIncrement = true;
    public $returnType = 'array'; // On manipule les lignes retournées sous forme de tableaux

    // Liste des colonnes qu'on a le droit de remplir
    public $allowedFields = [
        'id_vente',
        'type_mail',
        'destinataire',
        'statut',
        'envoye_le'
    ];
}
