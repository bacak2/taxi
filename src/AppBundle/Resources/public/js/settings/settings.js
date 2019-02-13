const app = function () {

    var $content = $('.main-content');
    var $tabs = $content.find('.item-tab'),
        $tabContent = $content.find('.ui .tab')
    ;
    var $buttonAdd = $('#addNewParam');

    // TABS
    $tabs.on('click', function () {
        $tabs.removeClass('active');
        $tabContent.removeClass('active');

        $(this).addClass('active');
        $('.ui .tab[data-tab="' + $(this).data('tab') + '"]').addClass('active');
    });

    $("a[data-tab=t2]").on('click', function () {
        table.ajax.reload().draw();
    });

    $("#addNewParam").on('click', function (e) {
        e.preventDefault();
        let data = $('form[name=form_dictionary]').serializeArray();
        $.ajax({
            url: routeAddDictionary,
            beforeSend: function(){
                $buttonAdd.addClass('loading');
            },
            type: 'POST',
            data: {
                'formData': data
            },
            success: function () {
                $('#form_dictionary_name').val('');
            },
            complete: function () {
                $buttonAdd.removeClass('loading');
                table.ajax.reload().draw();
            }
        });
    });

    var table = $('#paramsTable').DataTable({
        "dom": 't',
        "language": dataTablesPL,
        "ajax": {
            url: routeGetDictionary,
            type: "POST"
        },
        "bInfo": false,
        "order": [],
        "scrollY": '60vh',
        "scroller": true,
        "scrollX": true,
        "deferRender": true,
        "sSortable": false,
        "bLengthChange": false,
        "pageLength": 20,
        "columns": [
            {
                data: "category",
                className: "text-center",
                bSearchable: false
            },
            {
                data: "name",
                className: "text-center",
                bSearchable: false
            }
        ]
    });

}();