<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class PaisesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('paises');
        $this->displayField('pais_codigo');
        $this->primaryKey('pais_codigo');
    }

     public function findByPaisNome($pais_nome) {
        return $this->find()->where(['pais_nome' => $pais_nome])->first();
    }
}
