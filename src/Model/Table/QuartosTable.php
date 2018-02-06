<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class QuartosTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('quartos');
        $this->displayField('quarto_codigo');
        $this->primaryKey(['quarto_codigo', 'empresa_codigo']);
    }

}
