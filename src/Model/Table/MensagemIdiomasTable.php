<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class MensagemIdiomasTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('mensagem_idiomas');
        $this->displayField('mensagem_codigo');
        $this->primaryKey(['mensagem_codigo', 'idioma']);
    }

}
