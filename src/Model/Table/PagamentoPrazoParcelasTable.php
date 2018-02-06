<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class PagamentoPrazoParcelasTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('pagamento_prazo_parcelas');
        $this->displayField('empresa_codigo');
        $this->primaryKey(['empresa_codigo', 'pagamento_prazo_codigo', 'parcela_codigo']);
    }
}
