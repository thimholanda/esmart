<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ElementoIdiomasTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->belongsTo('Elementos', [
            'foreignKey' => 'elemento_codigo'            
        ]);
        $this->table('elemento_idiomas');
        $this->displayField('elemento_codigo');
        $this->primaryKey(['elemento_codigo', 'idioma']);
    }
}
