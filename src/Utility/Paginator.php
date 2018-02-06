<?php

namespace App\Utility;

class Paginator {

    public function __construct($tamanho_pagina) {
        $this->tamanho_pagina = $tamanho_pagina;
    }

    public function gera_paginacao($total_filtrado, $pagina_atual, $formulario_id, $total_em_exibicao = null) {
        if ($total_em_exibicao == null && $total_em_exibicao != 0)
            $total_em_exibicao = 10;
        $anterior = $pagina_atual - 1;
        $proximo = $pagina_atual + 1;
        $paginacao = "";
        $numPaginas = ceil($total_filtrado / $this->tamanho_pagina);

        if ($numPaginas > 1) {
            $paginacao .= "<nav class='nav-pagination'>";
            $paginacao .= "	<ul class='pagination pagination-sm'>";

            if ($anterior > 0) {
                $paginacao .= "<li>";
                $paginacao .= "	<a class='page-link' href='#' aria-label='Previous' aria-form-id='$formulario_id' aria-pagina-numero='$anterior'>";
                $paginacao .= "		<span aria-hidden='true'>«</span>";
                $paginacao .= "	</a>";
                $paginacao .= "</li>";
            } else {
                $paginacao .= "<li class='disabled'>";
                $paginacao .= "	<span aria-hidden='true'>«</span>";
                $paginacao .= "</li>";
            }

            for ($i = 1; $i <= $numPaginas; $i++) {
                if ($i < ($pagina_atual - 4) && $i == 1) {
                    $paginacao .= "<li><a class='page-link' href='#' aria-form-id='$formulario_id' aria-pagina-numero='$i' >" . $i . "</a></li>";
                    $paginacao .= "<li><a class='page-link'>...</a></li>";
                }

                if ($i >= ($pagina_atual - 4) && $i <= ($pagina_atual + 4)) {
                    if ($i == $pagina_atual) {
                        $paginacao .= "<li class='active'><a class='page-link' href='#'  aria-form-id='$formulario_id'"
                                . "aria-pagina-numero='$i'>" . $i . "</a></li>";
                    } else {
                        $paginacao .= "<li><a class='page-link' href='#' aria-form-id='$formulario_id' aria-pagina-numero='$i'>" . $i . "</a></li>";
                    }
                }

                if ($i > ($pagina_atual + 4) && $i == $numPaginas) {
                    $paginacao .= "<li><a class='page-link'>...</a></li>";
                    $paginacao .= "<li><a class='page-link' href='#' aria-form-id='$formulario_id' aria-pagina-numero='$i'>" . $i . "</a></li>";
                }
            }

            if ($pagina_atual < $numPaginas) {
                $paginacao .= "<li>";
                $paginacao .= "	<a class='page-link' aria-pagina-numero='$proximo' href='#' aria-form-id='$formulario_id' aria-label='Next'>";
                $paginacao .= "		<span aria-hidden='true'>»</span>";
                $paginacao .= "	</a>";
                $paginacao .= "</li>";
            } else {
                $paginacao .= "<li class='disabled'>";
                $paginacao .= "	<span aria-hidden='true'>»</span>";
                $paginacao .= "</li>";
            }

            $paginacao .= "  </ul>";
            $paginacao .= "</nav>";
        }

        $paginacao .= "<div class='resultados-encontrados'>Exibindo $total_em_exibicao de $total_filtrado resultados </div>";

        return $paginacao;
    }

}
