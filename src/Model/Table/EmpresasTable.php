<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class EmpresasTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('empresas');
        $this->displayField('empresa_codigo');
        $this->primaryKey(['empresa_codigo', 'pais_codigo']);
        $this->belongsTo('Paises', [
            'foreignKey' => 'pais_codigo'            
        ]);
    }
    
     /*
     * Retorna os dados de um documento pelo seu numero
     */

    public function findByEmpresaCodigo($empresa_codigo) {
        return $this->find()->where(['empresa_codigo' => $empresa_codigo])->first();
    }

}
