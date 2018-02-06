<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * ProdutoEmpresaGrupos Model
 *
 * @method \App\Model\Entity\ProdutoEmpresaGrupo get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProdutoEmpresaGrupo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProdutoEmpresaGrupo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProdutoEmpresaGrupo|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProdutoEmpresaGrupo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProdutoEmpresaGrupo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProdutoEmpresaGrupo findOrCreate($search, callable $callback = null, $options = [])
 */
class ProdutoEmpresaGruposTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('produto_empresa_grupos');
        $this->setDisplayField('empresa_grupo_codigo');
        $this->setPrimaryKey(['empresa_grupo_codigo', 'produto_codigo']);
    }

    public function findProdutoGrupo(Query $query, array $options) {
        return $query
            ->where(['produto_codigo' => $options['produto']])
            ->where(['empresa_grupo_codigo' => $options['grupo']]);
    }

    // Retorna o próximo código de produto da empresa
    public function procadprx($empresa_grupo_codigo) {
        $resultado = $this->find('all')
            ->select(['produto_codigo'])
            ->where(['empresa_grupo_codigo' => $empresa_grupo_codigo])
            ->order(['produto_codigo' => 'DESC']);

        $proximo = $resultado->first()['produto_codigo'];
        $proximo++;
        return $proximo;
    }

    // Cria ou modifica um cadastro de produto
    public function procadmod($produto_codigo, $modelo) {
        $modelo->produto_codigo = $produto_codigo;
        $entidade = TableRegistry::get('ProdutoEmpresaGrupos');
        if ($entidade->save($modelo)) {
            return $modelo->produto_codigo;
        }

        return false;
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('empresa_grupo_codigo')
            ->allowEmpty('empresa_grupo_codigo', 'create');

        $validator
            ->allowEmpty('produto_codigo', 'create');

        $validator
            ->requirePresence('nome', 'create')
            ->notEmpty('nome');

        $validator
            ->requirePresence('descricao', 'create')
            ->notEmpty('descricao');

        $validator
            ->requirePresence('produto_tipo_codigo', 'create')
            ->notEmpty('produto_tipo_codigo');

        $validator
            ->integer('imagem')
            ->allowEmpty('imagem');

        $validator
            ->decimal('preco')
            ->requirePresence('preco', 'create')
            ->notEmpty('preco');

        $validator
            ->integer('preco_fator_codigo')
            ->requirePresence('preco_fator_codigo', 'create')
            ->notEmpty('preco_fator_codigo');

        $validator
            ->integer('variavel_fator_codigo')
            ->requirePresence('variavel_fator_codigo', 'create')
            ->notEmpty('variavel_fator_codigo');

        $validator
            ->integer('fixo_fator_codigo')
            ->requirePresence('fixo_fator_codigo', 'create')
            ->notEmpty('fixo_fator_codigo');

        $validator
            ->allowEmpty('servico_taxa_incide');

        $validator
            ->requirePresence('contabil_tipo', 'create')
            ->notEmpty('contabil_tipo');

        $validator
            ->boolean('excluido')
            ->requirePresence('excluido', 'create')
            ->notEmpty('excluido');

        $validator
            ->dateTime('criacao_data')
            ->requirePresence('criacao_data', 'create')
            ->notEmpty('criacao_data');

        $validator
            ->integer('criacao_usuario')
            ->requirePresence('criacao_usuario', 'create')
            ->notEmpty('criacao_usuario');

        $validator
            ->dateTime('modificacao_data')
            ->allowEmpty('modificacao_data');

        $validator
            ->integer('modificacao_usuario')
            ->allowEmpty('modificacao_usuario');

        return $validator;
    }
}
