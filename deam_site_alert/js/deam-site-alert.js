/**
 * @file
 * Defines the behavior of the alert.
 */

(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.deamSiteAlert = {
    attach: function (context) {

      /**
       * Gets cookie by name.
       *
       * @param {string} cname
       *   Cookie name.
       * @return {*}
       *   Empty string or cookie value.
       */
      function getCookie(cname) {
        var name = cname + '=';
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
          var c = ca[i];
          while (c.charAt(0) === ' ') {
            c = c.substring(1);
          }
          if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
          }
        }
        return '';
      }

      if (context.doctype) {

        var showAlert = true;
        $.get('/ui_blocks/status', function (data) {
          var serverHash = data.status;
          // Get status if popup was closed before.
          var blockHash = getCookie('cookie-popup-hash');
          if (blockHash === serverHash && blockHash !== '0') {
            showAlert = false;
            $('body,html').addClass('alert-closed');
          }
          if (showAlert) {
            // Get HTML.
            $.get('/ui_blocks/alert', function (data) {
              $('.element-alert--container').append(data.block);
              var $alert = $('.deam-site-alert');
              var $closeButton = $('.deam-alert-close');

              var closeAlert = function () {
                $alert.remove();
                $('body,html').removeClass('alert-open');
                document.cookie = 'cookie-popup-hash=' + serverHash + ';expires=0;path=/';
              };

              $('body,html').addClass('alert-open');

              // Bind Click to close.
              $closeButton
                .on('click', closeAlert)
                .on('keydown', function (event) {
                  // enter key to toggle menu
                  if (event.keyCode === 13) {
                    event.preventDefault();
                    closeAlert();
                  }
                });
            });
          }
        });
      }
    }
  };

})(jQuery, Drupal);
