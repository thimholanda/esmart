<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class PagamentoPrazosTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('pagamento_prazos');
        $this->displayField('empresa_codigo');
        $this->primaryKey(['empresa_codigo', 'pagamento_prazo_codigo']);
    }
}
