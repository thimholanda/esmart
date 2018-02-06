<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class OrdensTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('ordens');
        $this->displayField('elemento_codigo');
        $this->primaryKey(['elemento_codigo', 'ordem_item']);
    }

}
