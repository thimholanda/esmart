<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class MensagensTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('mensagens');
        $this->displayField('exibicao_data');
        $this->primaryKey(['exibicao_data', 'empresa_codigo']);
    }
}
