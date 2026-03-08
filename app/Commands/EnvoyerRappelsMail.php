<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * COMMANDE CRON : Envoyer les rappels mail 2h avant clôture.
 * 
 * Usage : php spark mails:rappels
 * 
 * Note : cette logique est aussi dans mettreAJourStatutsVentes() du Modele,
 * cette commande permet simplement de la lancer depuis un CRON.
 */
class EnvoyerRappelsMail extends BaseCommand
{
    protected $group = 'Mails';
    protected $name = 'mails:rappels';
    protected $description = 'Envoie les rappels 2h avant la clôture des ventes.';

    public function run(array $params)
    {
        CLI::write('=== Rappels mail ===', 'yellow');

        // On réutilise la même logique que mettreAJourStatutsVentes()
        // qui s'occupe déjà d'envoyer les rappels
        $monmodel = new \App\Models\Modele();
        $monmodel->mettreAJourStatutsVentes();

        CLI::write('Terminé.', 'green');
    }
}
