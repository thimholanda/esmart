<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Quarto Entity
 *
 * @property int $empresa_codigo
 * @property string $quarto_codigo
 * @property string $quarto_nome
 * @property int $quarto_tipo_codigo
 * @property string $excluido
 * @property string $quarto_grupo
 * @property string $modificacao_usuario
 * @property \Cake\I18n\FrozenTime $criacao_data
 * @property string $criacao_usuario
 * @property \Cake\I18n\FrozenTime $modificacao_data
 */
class Quarto extends Entity
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
        'quarto_codigo' => false,
        'empresa_codigo' => false
    ];
}
