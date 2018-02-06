<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class MensagemCadastrosTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('mensagem_cadastros');
        $this->displayField('mensagem_codigo');
        $this->primaryKey('mensagem_codigo');
    }
}
