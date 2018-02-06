<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class DocumentoSessoesTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('documento_sessoes');
        $this->displayField('empresa_codigo');
        $this->primaryKey(['empresa_codigo', 'documento_numero']);
    }
}