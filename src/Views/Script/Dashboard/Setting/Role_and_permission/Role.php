$('#<?= \CI4Xpander_AdminLTE\View\Component\Form::getInputID('permissions[]', [
    'type' => \CI4Xpander_AdminLTE\View\Component\Form\Type::DROPDOWN_AUTOCOMPLETE
]); ?>').change(function () {
    var crudTemplate = '<?= \CI4Xpander_AdminLTE\View\Component\Form::getInputID('crudTemplate', [
        'type' => \CI4Xpander_AdminLTE\View\Component\Form\Type::CHECKBOX
    ]); ?>';

    var crudAttr = $('#' + crudTemplate + '_container').attr('data-crud');
    var crudList = [];
    if (typeof crudAttr !== typeof undefined && crudAttr !== false) {
        crudList = $('#' + crudTemplate + '_container').data('crud');
        $.each(crudList, function (index, value) {
            console.log(value);
            $('#' + value + '_container').remove();
        });
        crudList = [];
    }

    $.each($(this).find(':selected'), function (index, value) {
        var option = $(this);
        var optionName = crudTemplate + option.val();

        if ($('#' + optionName + '_container').length) {
        } else {
            var optionCRUD = $('#' + crudTemplate + '_container').clone().removeClass('hidden').attr('id', optionName + '_container').removeAttr('data-crud');
            optionCRUD.insertBefore('#<?= \CI4Xpander_AdminLTE\View\Component\Form::getInputID('action', [
                'type' => \CI4Xpander_AdminLTE\View\Component\Form\Type::BUTTON_GROUP
            ]); ?>_container');

            optionCRUD.find('#' + crudTemplate + '_label').text(option.text() + ' permission').attr('for', optionName).attr('id', optionName + '_label');

            optionCRUD.find('#' + crudTemplate + 'Create').attr('name', 'crud[' + option.val() + '][]').attr('id', optionName + 'Create');
            optionCRUD.find('#' + crudTemplate + 'Read').attr('name', 'crud[' + option.val() + '][]').attr('id', optionName + 'Read');
            optionCRUD.find('#' + crudTemplate + 'Update').attr('name', 'crud[' + option.val() + '][]').attr('id', optionName + 'Update');
            optionCRUD.find('#' + crudTemplate + 'Delete').attr('name', 'crud[' + option.val() + '][]').attr('id', optionName + 'Delete');

            if (crudList.indexOf(optionName) !== -1) {

            } else {
                crudList[crudList.length] = optionName;
            }
        }
    });

    $('#' + crudTemplate + '_container').data('crud', crudList);
});