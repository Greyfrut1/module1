// (function ($, Drupal) {
//   Drupal.behaviors.catsModule = {
//     attach: function (context, settings) {
//       $('.use-ajax', context).once('cats-module-ajax').each(function () {
//         var element = $(this);
//         element.on('click', function (e) {
//           e.preventDefault();
//           var url = element.attr('href');
//           var options = {
//             dialogType: 'modal',
//             dialogOptions: {
//               width: 400,
//             },
//           };
//           Drupal.ajax({url: url}, element, options).execute();
//         });
//       });
//     }
//   };
// })(jQuery, Drupal);
