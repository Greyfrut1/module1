// (function ($) {
//   $(document).ready(function () {
//     // Attach a click event handler to the "Delete" button.
//     $('.delete-button-class').click(function () {
//       // Your JavaScript code here to handle the button click.
//       // You can make an AJAX request or perform any other actions.
//       var element = $(this);
//       element.on('click', function (e) {
//         e.preventDefault();
//         var url = element.attr('href');
//         var options = {
//           dialogType: 'modal',
//           dialogOptions: {
//             width: 400,
//           },
//         };
//         Drupal.ajax({url: url}, element, options).execute();
//       });
//     });
//   });
// })(jQuery);
// (function ($, Drupal) {
//   Drupal.behaviors.myModule = {
//     attach: function (context, settings) {
//       // Слухаємо зміни чекбоксів з класом 'entity-select'.
//       $('.entity-select', context).on('change', function () {
//         var isChecked = $(this).prop('checked');
//         if (isChecked) {
//           // Викликаємо Ajax запит, щоб вивести повідомлення.
//           var entityId = $(this).closest('tr').data('drupal-entity-id');
//           Drupal.ajax({
//             url: '/my-module/ajax/callback', // Замініть шлях на свій.
//             submit: { entity_id: entityId },
//           }).execute();
//         }
//       });
//     }
//   };
// })(jQuery, Drupal);
