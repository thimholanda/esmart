<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProdutoEmpresaGrupo Entity
 *
 * @property int $empresa_grupo_codigo
 * @property string $produto_codigo
 * @property string $nome
 * @property string $descricao
 * @property string $produto_tipo_codigo
 * @property int $imagem
 * @property float $preco
 * @property int $preco_fator_codigo
 * @property int $variavel_fator_codigo
 * @property int $fixo_fator_codigo
 * @property string $servico_taxa_incide
 * @property string $contabil_tipo
 * @property bool $excluido
 * @property \Cake\I18n\FrozenTime $criacao_data
 * @property int $criacao_usuario
 * @property \Cake\I18n\FrozenTime $modificacao_data
 * @property int $modificacao_usuario
 */
class ProdutoEmpresaGrupo extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'empresa_grupo_codigo' => false,
        'produto_codigo' => false
    ];
}
