<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class FuncaoAcessosTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('funcao_acessos');
        $this->displayField('funcao_codigo');
        $this->primaryKey(['funcao_codigo', 'objeto_codigo']);
    }
}
