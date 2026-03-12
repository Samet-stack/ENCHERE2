<?php

use CodeIgniter\Test\CIUnitTestCase;

/**
 * Suite de tests metier simplifiee pour presentation.
 *
 * Repartition :
 * - 5 tests secretaire
 * - 5 tests habitant
 * - 5 tests benevole
 */
final class EnchereReglesMetierTest extends CIUnitTestCase
{
    // ==================== SECRETAIRE ====================

    public function testSecretairePeutCreerUneVente(): void
    {
        $this->assertTrue($this->peutCreerVente('secretaire'));
    }

    public function testSecretaireNePeutPasEncherir(): void
    {
        $this->assertFalse($this->peutEncherir('secretaire', true, 'en_cours'));
    }

    public function testSecretairePeutAjouterArticleSurVenteAVenir(): void
    {
        $this->assertTrue($this->peutAjouterArticle('secretaire', 'a_venir'));
    }

    public function testSecretaireNePeutPasAjouterArticleSurVenteEnCours(): void
    {
        $this->assertFalse($this->peutAjouterArticle('secretaire', 'en_cours'));
    }

    public function testSecretairePeutCloturerVenteEnCours(): void
    {
        $this->assertTrue($this->peutCloturerVente('secretaire', 'en_cours'));
    }

    // ==================== HABITANT ====================

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

    public function testHabitantInscritPeutEncherirQuandVenteEnCours(): void
    {
        $this->assertTrue($this->peutEncherir('habitant', true, 'en_cours'));
    }

    public function testHabitantNonInscritNePeutPasEncherir(): void
    {
        $this->assertFalse($this->peutEncherir('habitant', false, 'en_cours'));
    }

    // ==================== BENEVOLE ====================

    public function testBenevolePeutConsulterArticles(): void
    {
        $this->assertTrue($this->peutConsulterArticles('benevole'));
    }

    public function testBenevolePeutCreerArticle(): void
    {
        $this->assertTrue($this->peutCreerArticle('benevole'));
    }

    public function testBenevolePeutAjouterArticleSurVenteAVenir(): void
    {
        $this->assertTrue($this->peutAjouterArticle('benevole', 'a_venir'));
    }

    public function testBenevoleNePeutPasAjouterArticleSurVenteEnCours(): void
    {
        $this->assertFalse($this->peutAjouterArticle('benevole', 'en_cours'));
    }

    public function testBenevoleNePeutPasEncherir(): void
    {
        $this->assertFalse($this->peutEncherir('benevole', true, 'en_cours'));
    }

    // ==================== REGLES SIMPLIFIEES ====================

    private function estHabitantValide(string $ville, string $codePostal): bool
    {
        return trim(strtolower($ville)) === 'getcet' && trim($codePostal) === '99999';
    }

    private function peutCreerVente(string $role): bool
    {
        return $role === 'secretaire';
    }

    private function peutCreerArticle(string $role): bool
    {
        return in_array($role, ['benevole', 'secretaire'], true);
    }

    private function peutConsulterArticles(string $role): bool
    {
        return in_array($role, ['benevole', 'secretaire'], true);
    }

    private function peutAjouterArticle(string $role, string $etatVente): bool
    {
        return in_array($role, ['benevole', 'secretaire'], true) && $etatVente === 'a_venir';
    }

    private function peutCloturerVente(string $role, string $etatVente): bool
    {
        return $role === 'secretaire' && $etatVente === 'en_cours';
    }

    private function peutEncherir(string $role, bool $estInscrit, string $etatVente): bool
    {
        return $role === 'habitant' && $estInscrit && $etatVente === 'en_cours';
    }
}
