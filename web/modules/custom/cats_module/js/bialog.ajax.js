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
