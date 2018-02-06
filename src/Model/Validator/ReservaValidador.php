<?php

namespace App\Model\Validator;

class ReservaValidador {

    //Faz as validações obrigatórias para se criar uma reserva
    public function resdoccrival($documento_dados, $contratante_dados, $quarto_item_dados) {
        if ($documento_dados['inicial_data'] == null || $documento_dados['inicial_data'] == "" || $documento_dados['final_data'] == null || $documento_dados['final_data'] == "") {
            return 0;
        }
        if ($contratante_dados['contratante_nome'] == null || $contratante_dados['contratante_nome'] == "" ||
                $contratante_dados['contratante_sobrenome'] == null || $contratante_dados['contratante_sobrenome'] == "") {
            return 0;
        }
        foreach ($quarto_item_dados as $quarto_item => $quarto_item_dado) {
            if (isset($quarto_item_dado['reserva_dados'])) {
                if ($quarto_item_dado['reserva_dados']['confirmacao_codigo'] == null || $quarto_item_dado['reserva_dados']['confirmacao_codigo'] == "" ||
                        $quarto_item_dado['reserva_dados']['cancelamento_codigo'] == null || $quarto_item_dado['reserva_dados']['cancelamento_codigo'] == "" ||
                        $quarto_item_dado['reserva_dados']['tipo_tarifa_codigo'] == null || $quarto_item_dado['reserva_dados']['tipo_tarifa_codigo'] == "" ||
                        $quarto_item_dado['reserva_dados']['adulto_qtd'] == null || $quarto_item_dado['reserva_dados']['adulto_qtd'] == "" ||
                        $quarto_item_dado['reserva_dados']['crianca_qtd'] == null || $quarto_item_dado['reserva_dados']['crianca_qtd'] == "" ||
                        $quarto_item_dado['reserva_dados']['pagamento_prazo_codigo'] == null || $quarto_item_dado['reserva_dados']['pagamento_prazo_codigo'] == "") {
                    return 0;
                }

                if ($quarto_item_dado['hospede_dados']['hospede_nome'][0] == null || $quarto_item_dado['hospede_dados']['hospede_nome'][0] == "") {
                    return 0;
                }

                if ($quarto_item_dado['partida_dados']['partida_valor'][0] == null || $quarto_item_dado['partida_dados']['partida_valor'][0] == "") {
                    return 0;
                }

                /* if ($quarto_item_dado['reserva_dados']['quarto_status_codigo'] == 2) {
                  if ($quarto_item_dado['pagamento_dados'][0]['valor'] == null || $quarto_item_dado['pagamento_dados'][0]['valor'] == "" ||
                  $quarto_item_dado['pagamento_dados'][0]['pagamento_forma_codigo'] == null || $quarto_item_dado['pagamento_dados'][0]['pagamento_forma_codigo'] == "") {
                  debug("6");
                  return 0;
                  }
                  } */
            }
        }
        return 1;
    }

}
