<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class QuartoConsumosTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('quarto_consumos');
        $this->displayField('documento_codigo');
        $this->primaryKey(['documento_codigo', 'empresa_codigo', 'data']);
    }
}
