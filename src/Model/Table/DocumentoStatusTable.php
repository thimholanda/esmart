<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class DocumentoStatusTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('documento_status');
        $this->displayField('documento_tipo_codigo');
        $this->primaryKey(['documento_tipo_codigo', 'documento_status_codigo']);
    }
}
