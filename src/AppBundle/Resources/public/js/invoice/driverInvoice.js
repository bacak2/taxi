var app = function () {

    var $content = $('.main-content'),
        $datepicker = $content.find('.datepicker'),

        $btnCreateAllInvoice = $content.find('#btnCreateAllInvoice'),
        $btnCreateInvoice = $content.find('#btnCreateInvoice'),
        $btnPrintAll = $content.find('#btnPrintAll'),
        $btnInvoiceNumbers = $content.find('#btnInvoiceNumbers'),

        $txtYear = $content.find('#txtYear'),
        $transactionTable = $content.find('#transactionTable'),
        $invoiceTable = $content.find('#invoiceTable'),

        urlTransactions = $transactionTable.data('url'),
        urlInvoice = $invoiceTable.data('url'),
        urlPrintInvoice = $invoiceTable.data('invoice'),
        urlCreateInvoice = $content.data('createinvoice'),

        $tabs = $content.find('.item-tab'),
        $tabContent = $content.find('.tab'),
        $txtMonth = $content.find('#txtMonth'),
        clients = []
    ;

    $btnPrintAll.on('click', function (e) {
        alert('PrintAll');
    });
    $btnInvoiceNumbers.on('click', function (e) {
        alert('Numery kont kierowcow');
    });
    $btnCreateAllInvoice.on('click', function (e) {
        alert('Create all');
    });
    $btnCreateInvoice.on('click', function () {

        var items = transactionTable.rows({selected: true}).data();
        items.each(function (data) {
            clients.push(data.id);
        });

        $.ajax({
            url: urlCreateInvoice,
            type: 'POST',
            data: {
                clients: JSON.stringify(clients),
                year: $txtYear.val(),
                month: $txtMonth.val()
            },
            success: function (res) {
                clients = [];
                transactionTable.ajax.reload().draw();
                invoiceTable.ajax.reload().draw();
            }
        })
    });


    // Datepicker
    $datepicker.calendar({
        type: 'month',
        text: calendarText,
        onChange: function (date, text, mode) {
            $txtMonth.val(text);
            transactionTable.ajax.reload().draw();
            invoiceTable.ajax.reload().draw();
        },
        formatter: {
            date: function (date, settings) {
                if (!date) return '';
                var month = (date.getMonth() + 1) + '';
                if (month.length < 2) {
                    month = '0' + month;
                }
                return month;
            }
        }
    });
    // Tabs
    $tabs.on('click', function () {
        $tabs.removeClass('active');
        $tabContent.removeClass('active');

        $(this).addClass('active');
        $('.ui .tab[data-tab="' + $(this).data('tab') + '"]').addClass('active');

        $.fn.dataTable.tables({visible: true, api:true}).columns.adjust();

        if($(this).data('tab') == 't2')
        {
            $btnCreateInvoice.show();
        }else{
            $btnCreateInvoice.hide();
        }
    });



    // $btnSearch.on('click', function () {
    //     table.clear();
    //     table.ajax.reload().draw();
    // });

    var invoiceTable = $invoiceTable.DataTable({
        "dom": 't',
        "language": dataTablesPL,
        "ajax": {
            url: urlInvoice,
            type: "POST",
            data: function (post) {
                post.txtMonth = $txtMonth.val();
                post.txtYear = $txtYear.val();
            }
        },
        "bInfo": false,
        "order": [],
        "scrollY": '45vh',
        "scroller": true,
        "scrollX": true,
        "deferRender": true,
        "sSortable": false,
        "bLengthChange": false,
        "pageLength": 25,
        "initComplete": function () {},
        "select": {
            style: 'multi',
            info: false,
            selector: 'td:first-child'
        },
        "columnDefs": [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        "columns": [
            { data: null, defaultContent:'', searchable: false },
            { data: "printBtn", searchable: false, className: 'text-center' },
            { data: "licenseNumber", className:"text-center" },
            { data: "transactionType", className:"text-center", bSearchable: true },
            { data: "invoiceNumber", className:"text-center", bSearchable: false },
            { data: "documentType", className:"text-center", bSearchable: false },
            { data: "seller", className:"text-center", bSearchable: false },
            { data: "buyer", className:"text-center", bSearchable: false },
            { data: "type", className:"text-center", bSearchable: false },
            { data: "amountToPay", className:"text-center", bSearchable: false },
        ]
    });

    var transactionTable = $transactionTable.DataTable({
        "dom": 't',
        "language": dataTablesPL,
        "ajax": {
            url: urlTransactions,
            type: "POST",
            data: function (post) {
                post.txtMonth = $txtMonth.val();
                post.txtYear = $txtYear.val();
            }
        },
        "bInfo": false,
        "order": [],
        "scrollY": '45vh',
        "scroller": true,
        "scrollX": true,
        "deferRender": true,
        "sSortable": false,
        "bLengthChange": false,
        "pageLength": 25,
        "initComplete": function () {},
        "select": {
            style: 'multi',
            info: false,
            selector: 'td:first-child'
        },
        "columnDefs": [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        "columns": [
            { data: null, defaultContent:'', searchable: false },
            { data: "licenseNumber", className:"text-center" },
            { data: "driver", className:"text-center", bSearchable: true },
            { data: "vatPayer", className:"text-center", bSearchable: false },
            { data: "vat", className:"text-center", bSearchable: false },
            { data: "isBaggage", className:"text-center", bSearchable: false },
            { data: "transactionType", className:"text-center", bSearchable: false },
            { data: "totalAmount", className:"text-center", bSearchable: false },
            { data: "amountWithFee", className:"text-center", bSearchable: false },
            { data: "transactionNumber", className:"text-center", bSearchable: false },
            { data: "driverCompany", className:"text-center", bSearchable: false },
            { data: "nextInvoiceNumber", className:"text-center", bSearchable: false },
            { data: "firstTransaction", className:"text-center", bSearchable: false },
            { data: "lastTransaction", className:"text-center", bSearchable: false },
        ]
    });



    $(document).on('click', '.print-invoice',function (e) {
        var path = urlPrintInvoice.replace(/__id__/g, $(this).attr('id'));
        window.open(path);
        window.focus();
    })



}();