<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class PadraoValoresTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('padrao_valores');
        $this->displayField('usuario_codigo');
        $this->primaryKey(['usuario_codigo', 'elemento_codigo', 'tela_codigo']);
    }
}
