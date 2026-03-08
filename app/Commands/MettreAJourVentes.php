<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * COMMANDE CRON : Mettre à jour les statuts des ventes.
 * 
 * Cette commande est destinée à être exécutée périodiquement via un CRON job
 * pour que les statuts des ventes soient mis à jour même sans visite sur le site.
 * 
 * Usage : php spark ventes:maj-statuts
 * CRON  : * /5 * * * * cd /chemin/vers/projet && php spark ventes:maj-statuts
 */
class MettreAJourVentes extends BaseCommand
{
    // Groupe de commandes (affiché dans l'aide)
    protected $group = 'Ventes';

    // Nom de la commande (utilisé en ligne de commande)
    protected $name = 'ventes:maj-statuts';

    // Description affichée dans l'aide
    protected $description = 'Met à jour les statuts des ventes (a_venir -> en_cours -> cloturee) et envoie les mails de rappel.';

    /**
     * Exécution de la commande.
     * Appelle la méthode existante mettreAJourStatutsVentes() du modèle.
     */
    public function run(array $params)
    {
        CLI::write('=== Mise à jour des statuts des ventes ===', 'yellow');
        CLI::write('Date/Heure : ' . date('Y-m-d H:i:s'), 'light_gray');

        $monmodel = new \App\Models\Modele();
        $monmodel->mettreAJourStatutsVentes();

        CLI::write('✅ Statuts mis à jour avec succès.', 'green');
        CLI::write('Les mails de rappel (2h avant clôture) et gagnants ont été envoyés si nécessaire.', 'light_gray');
    }
}
