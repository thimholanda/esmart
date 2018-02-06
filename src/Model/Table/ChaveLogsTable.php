<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ChaveLogsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('chave_logs');
        $this->displayField('empresa_codigo');
        $this->primaryKey(['empresa_codigo', 'objeto_codigo', 'modificacao_data']);
    }
}
