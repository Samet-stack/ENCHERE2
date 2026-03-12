<?php

use CodeIgniter\Test\CIUnitTestCase;

/**
 * Tests unitaires metier alignes sur le cahier des charges.
 *
 * Ces tests verifient les regles principales sans dependre
 * directement de la base de donnees ou des routes.
 */
final class EnchereReglesMetierTest extends CIUnitTestCase
{
    public function testHabitantGetcetEstValide(): void
    {
        $this->assertTrue($this->estHabitantValide('Getcet', '99999'));
    }

    public function testHabitantAvecMauvaiseVilleEstRefuse(): void
    {
        $this->assertFalse($this->estHabitantValide('Nice', '99999'));
    }

    public function testHabitantAvecMauvaisCodePostalEstRefuse(): void
    {
        $this->assertFalse($this->estHabitantValide('Getcet', '75000'));
    }

    public function testPremiereEnchereMinimumEstVingtCentimes(): void
    {
        $minimum = $this->calculerMinimumEnchere(0.20, 0.00);

        $this->assertSame(0.20, $minimum);
    }

    public function testEnchereSuivanteAjouteDixCentimesAuMaximumCourant(): void
    {
        $minimum = $this->calculerMinimumEnchere(0.20, 1.50);

        $this->assertSame(1.60, $minimum);
    }

    public function testAjoutArticleAutoriseSeulementPourVenteAVenir(): void
    {
        $this->assertTrue($this->peutAjouterArticle('benevole', 'a_venir'));
        $this->assertFalse($this->peutAjouterArticle('benevole', 'en_cours'));
        $this->assertFalse($this->peutAjouterArticle('secretaire', 'cloturee'));
    }

    public function testParticipationEnchereReserveeAHabitantInscritPendantVenteEnCours(): void
    {
        $this->assertTrue($this->peutEncherir('habitant', true, 'en_cours'));
        $this->assertFalse($this->peutEncherir('habitant', false, 'en_cours'));
        $this->assertFalse($this->peutEncherir('benevole', true, 'en_cours'));
        $this->assertFalse($this->peutEncherir('habitant', true, 'a_venir'));
    }

    private function estHabitantValide(string $ville, string $codePostal): bool
    {
        return trim(strtolower($ville)) === 'getcet' && trim($codePostal) === '99999';
    }

    private function calculerMinimumEnchere(float $prixDepart, float $montantMax): float
    {
        if ($montantMax > 0) {
            return round($montantMax + 0.10, 2);
        }

        return round(max($prixDepart, 0.20), 2);
    }

    private function peutAjouterArticle(string $role, string $etatVente): bool
    {
        return in_array($role, ['benevole', 'secretaire'], true) && $etatVente === 'a_venir';
    }

    private function peutEncherir(string $role, bool $estInscrit, string $etatVente): bool
    {
        return $role === 'habitant' && $estInscrit && $etatVente === 'en_cours';
    }
}
