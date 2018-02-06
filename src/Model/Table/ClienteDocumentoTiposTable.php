<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ClienteDocumentoTiposTable extends Table
{   
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('cliente_documento_tipos');
        $this->displayField('pais_codigo');
        $this->primaryKey(['pais_codigo', 'cliente_documento_tipo']);
    }
}
