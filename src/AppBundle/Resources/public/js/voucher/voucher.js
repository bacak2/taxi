var appClient = function() {

    var $content = $('#main-content');
    var $tabs = $content.find('.item-tab'),
        $tabContent = $content.find('.ui.tab'),
        $dropdawn = $content.find('.ui .dropdown'),
        $datepicker = $content.find('.datepicker'),
        $voucherTable = $content.find('#voucherTable'),
        $amount = $content.find('#add_voucher_form_totalAmount'),
        $provision = $content.find('#add_voucher_form_provision'),
        routeGetVouchers = $content.data('vouchers'),
        $inputDateFrom = $content.find('#dateFrom'),
        $inputDateTo = $content.find('#dateTo'),
        $btnSearch = $content.find('#btnSearch'),
        $btnReset = $content.find('#btnReset')
    ;

    // TABS
    $tabs.on('click', function () {
        $tabs.removeClass('active');
        $tabContent.removeClass('active');

        $(this).addClass('active');
        $('.ui .tab[data-tab="' + $(this).data('tab') + '"]').addClass('active');
        if($(this).data('tab') === 't2')
        {
            $.fn.dataTable.tables({visible: true, api:true}).columns.adjust();
        }
    });
    $btnSearch.on('click', function () {
        table.ajax.reload().draw();
    });
    $btnReset.on('click', function (e) {
       $dropdawn.dropdown('clear');
    });

    $amount.inputmask('*{1,20}[.99]');
    $provision.inputmask('9{1,3}[.9]');
    $dropdawn.dropdown({
        fullTextSearch: true,
        minCharacters: 2,
        placeholder: null
    });
    $datepicker.calendar({
        type: 'date',
        text: calendarText,
        formatter: calendarFormatter
    });

    var table = $voucherTable.DataTable({
        "dom": 't',
        "language": dataTablesPL,
        "ajax": {
            url: routeGetVouchers,
            data: function (post) {
                post.dateFrom = $inputDateFrom.val();
                post.dateTo = $inputDateTo.val();
                return post;
            },
            type: "POST"
        },
        "info": false,
        "order": [],
        "scrollY": '55vh',
        "scroller": true,
        "scrollX": true,
        "deferRender": true,
        "bLengthChange": false,
        "pageLength": 25,
        "initComplete": function () {},
        "columns": [
            { data: "driver", className: "text-center" },
            { data: "licenseNumber", className: "text-center", bSearchable: false },
            { data: "client", className: "text-center", bSearchable: false },
            { data: "transactionDate", className: "text-center", bSearchable: false },
            { data: "totalAmount", className: "text-center", bSearchable: false },
            { data: "percentage", className: "text-center", bSearchable: false },
            { data: "voucherDescription", className: "text-center", bSearchable: false },
            { data: "voucherNumber", className: "text-center", bSearchable: false },
            { data: "updateDate", className: "text-center", bSearchable: false },
        ]
    });

}();


