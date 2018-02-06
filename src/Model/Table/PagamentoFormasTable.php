<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class PagamentoFormasTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('pagamento_formas');
        $this->displayField('pagamento_forma_codigo');
        $this->primaryKey('pagamento_forma_codigo');
    }
    
    /*
     * Retorna os dados de um pagamento forma  pelo seu codigo
     */

    public function findByPagamentoFormaCodigo($pagamento_forma_codigo) {
        return $this->find()->where(['pagamento_forma_codigo' => $pagamento_forma_codigo])->first();
    }
}
