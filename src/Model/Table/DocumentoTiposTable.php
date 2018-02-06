<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class DocumentoTiposTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('documento_tipos');
        $this->displayField('documento_tipo_codigo');
        $this->primaryKey('documento_tipo_codigo');
    }
}
