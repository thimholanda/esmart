<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class QuartoTiposTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('quarto_tipos');
        $this->displayField('quarto_tipo_codigo');
        $this->primaryKey(['quarto_tipo_codigo', 'empresa_codigo']);
    }
}
