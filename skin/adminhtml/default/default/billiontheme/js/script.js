//
/*global jQuery*/
jQuery.noConflict();

(function ($) {
    'use strict';

    $(function () {
        $('.bd-customtpl')
            .on('click', '[data-event="click"]', updateTemplate)
            .on('change', '[data-event="change"]', updateTemplate);
    });

    function updateTemplate(event) {
        /* jshint validthis:true */

        event.preventDefault();

        if (!window.ThemeTemplates) return;

        var el = $(this);

        var type = el.data('type'),
            action = el.data('action'),
            templateId = el.data('template'),
            option = el.closest('tr').find('option:selected'),
            optionId = parseFloat(option.val()),
            optionName = option.text(),
            source = el.data('source');

        var data = window.ThemeTemplates.data;
        var tpl = data[templateId];

        var hasChanges = false,
            onComplete = function () {};

        switch (action) {
            case 'add':
                tpl[source] = tpl[source] || [];
                if (!isNaN(optionId) && tpl[source].indexOf(optionId) === -1) {
                    tpl[source].push(optionId);
                    onComplete = function () {
                        var newItem = $('<div class="bd-customtpl-item"></div>');
                        newItem.append('<span>' + optionName + '</span>');
                        newItem.append('<a title="Remove" ' +
                            'data-source="' + source + '" ' +
                            'data-type="' + type + '"' +
                            'data-action="remove"' +
                            'data-event="click"' +
                            'data-item="' + optionId + '"' +
                            'data-template="' + templateId + '"' +
                            'class="remove" href="#"></a>');
                        $(el).closest('tr').find('.bd-customtpl-add').append(newItem);
                    };
                    hasChanges = true;
                }
                break;
            case 'remove':
                var removeId = el.data('item');
                if (tpl[source].indexOf(removeId) !== -1) {
                    tpl[source].splice(tpl[source].indexOf(removeId), 1);
                    onComplete = function () {
                        $(el).closest('.bd-customtpl-item').remove();
                    };
                    hasChanges = true;
                }
                break;
            case 'select':
                Object.keys(data).forEach(function (keyId) {
                    if (data[keyId].type === type) {
                        data[keyId].active = false;
                    }
                });
                if (optionId && data[optionId]) {
                    data[optionId].active = true;
                }
                hasChanges = true;
                break;
        }

        if (hasChanges) {
            $('.bd-templates').addClass('bd-ajax-disabled');
            $.ajax({
                url: window.ThemeTemplates.updateAction,
                method: 'post',
                dataType: 'json',
                data: {
                    theme: window.ThemeTemplates.themeName,
                    info: JSON.stringify(data),
                    form_key: window.ThemeTemplates.formKey
                }
            }).always(function () {
                $('.bd-templates').removeClass('bd-ajax-disabled');
            }).done(function () {
                onComplete();
            }).fail(function (xhr) {
                console.log(xhr.responseText);
            });
        }
    }

})(jQuery);

//