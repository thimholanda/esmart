// var $modal = $('.modal'),
//         $modalContent = $modal.find('.content');

// function fechaModal() {

//     $('td.marcavel.hover')
//             .removeClass('hover');

//     $('td.marcavel.bloqueio')
//             .removeClass('bloqueio');

//     $modal
//             .fadeOut();

//     $modalContent
//             .html('');

// }

// function abreModal(tipo, msg, quarto_tipo_codigo, quarto_codigo, data_inicial, data_final, documento_tipo_codigo) {

//     $boxHover
//             .hide()
//             .html('');

//   if ( tipo == 'mensagem') {

//         var content = msg;

//         $modal
//                 .on('click', fechaModal);

//   } else if ( tipo == "cria_reserva" ) {

//         var content =

//                 'Cria reserva<br><br>' +
//                 '<form action="javascript:respdrcri(' + quarto_tipo_codigo + ', ' + quarto_codigo + ', ' + data_inicial + ', ' + data_final + ')">' +
//                 '<input type="submit" value="Salvar"><br><br>' +
//                 '</form>' +
//                 '<button onclick="javascript:fechaModal()">Cancelar</button>';

//   } else if ( tipo == "cria_bloqueio" ) {

//         var content =

//                 'Cria bloqueio<br><br>' +
//                 '<form action="javascript:docpdrcri(' + quarto_codigo + ', ' + documento_tipo_codigo + ', ' + data_inicial + ', ' + data_final + ')">' +
//                 '<input type="submit" value="Salvar"><br><br>' +
//                 '</form>' +
//                 '<button onclick="javascript:fechaModal()">Cancelar</button>';

//     }

//   if ( !$modal.is(':visible') ) {

//         $modalContent
//                 .html(content);

//     setTimeout(function() {

//             $modal
//                     .fadeIn();

//         }, 250);

//     }

// }
