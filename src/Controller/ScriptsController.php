<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\Servico;
use App\Utility\Util;
class ScriptsController extends AppController {

    //Script para criação de bloqueios automatizados
    public function criacao_bloqueios_comerciais() {
        $servico = new Servico();
        $lista_quartos = array('AL1', 'AL2', 'AL3', 'AL4', 'AL4', 'AL5', 'AL6', 'EP1', 'EP2', 'EP3', 'EP4', 'EP5', 'EP6');

        $data_inicio = Util::convertDataSql('01/06/2018');
        $data_fim = Util::convertDataSql('08/06/2018');
        $motivo_codigo = 3;

        foreach ($lista_quartos as $quarto) {
            $servico->serdoccri(3, 'bc', $data_inicio, $data_fim, $quarto, 1, $motivo_codigo);
        }
    }

}
