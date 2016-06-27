(function ($) {
    $.fn.mgTextInput = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist');
            return false;
        }
    };

    var defaultSettings = {};
    var settings = {};

    var methods = {
        init: function (options) {
            return this.each(function () {
                var $e = $(this);
                settings = $.extend({}, defaultSettings, options || {});

                // Whether widget already initialized.
                if($e.data('mgTextInput')) {
                    return;
                }
                $e.data('mgTextInput', true);

                // On keyup
                $e.on('keyup', 'input', function () {
                    methods.calculate.apply($e);
                });

                // On change
                $e.on('change', 'input', function () {
                    methods.calculate.apply($e);
                });
            });
        },

        calculate: function() {
            var $e = $(this),
                $input = $e.find('input'),
                $addon = $e.find('[data-field="characters-remaining"]'),
                length = $input.val().length,
                maxLength = $input.prop('maxlength'),
                result = maxLength - length;
            $addon.html(result);
        }
    };

    // Auto initialize
    $('[data-field="count-characters"]').mgTextInput();
})(jQuery);