<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class MotivoQuartoItemTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('motivo_quarto_item');
        $this->primaryKey(['empresa_codigo', 'documento_numero', 'quarto_item', 'motivo_tipo_codigo', 'motivo_codigo']);
    }
}
