<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLocationToUtilisateurs extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('ville', 'utilisateurs')) {
            $this->forge->addColumn('utilisateurs', [
                'ville' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'adresse',
                ],
            ]);
        }

        if (!$this->db->fieldExists('code_postal', 'utilisateurs')) {
            $this->forge->addColumn('utilisateurs', [
                'code_postal' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 5,
                    'null'       => true,
                    'after'      => 'ville',
                ],
            ]);
        }

        $this->db->table('utilisateurs')
            ->where('est_habitant', 1)
            ->update([
                'ville'       => 'Getcet',
                'code_postal' => '99999',
            ]);
    }

    public function down()
    {
        if ($this->db->fieldExists('code_postal', 'utilisateurs')) {
            $this->forge->dropColumn('utilisateurs', 'code_postal');
        }

        if ($this->db->fieldExists('ville', 'utilisateurs')) {
            $this->forge->dropColumn('utilisateurs', 'ville');
        }
    }
}
