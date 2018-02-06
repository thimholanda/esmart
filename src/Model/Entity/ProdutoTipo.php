<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProdutoTipo Entity
 *
 * @property int $empresa_grupo_codigo
 * @property string $produto_tipo_codigo
 * @property string $produto_tipo_nome
 * @property bool $excluido
 * @property \Cake\I18n\FrozenTime $criacao_data
 * @property string $criacao_usuario
 * @property \Cake\I18n\FrozenTime $modificacao_data
 * @property string $modificacao_usuario
 */
class ProdutoTipo extends Entity
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
        'produto_tipo_codigo' => false
    ];
}
