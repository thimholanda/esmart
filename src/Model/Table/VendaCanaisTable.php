<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class VendaCanaisTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('venda_canais');
        $this->displayField('venda_canal_codigo');
        $this->primaryKey('venda_canal_codigo');
    }
}
