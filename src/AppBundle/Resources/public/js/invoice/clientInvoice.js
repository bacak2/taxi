var app = function () {

    var $content = $('.main-content'),
        $datepicker = $content.find('.datepicker'),
        $btnPrintEnvelop = $content.find('#btnPrintEnvelop'),
        $btnBook = $content.find('#btnBook'),
        $btnSendAllInvoices = $content.find('#btnSendAllInvoices'),
        $btnGeneratePDF = $content.find('#btnGeneratePDF'),

        $btnCreateInvoice = $content.find('#btnCreateInvoice'),
        $txtYear = $content.find('#txtYear'),
        $transactionTable = $content.find('#transactionTable'),
        $invoiceTable = $content.find('#invoiceTable'),
        urlTransactions = $transactionTable.data('url'),
        urlInvoice = $invoiceTable.data('url'),
        urlPrintInvoice = $invoiceTable.data('invoice'),
        urlEditInvoice = $content.data('editinvoice'),
        urlCreateInvoice = $content.data('createinvoice'),
        $tabs = $content.find('.item-tab'),
        $tabContent = $content.find('.tab'),
        $txtMonth = $content.find('#txtMonth'),
        clients = []
        ;

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

    // TABS
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

    // $btnSearch.on('click', function () {
    //     table.clear();
    //     table.ajax.reload().draw();
    // });


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
            { data: "clientName", className:"text-center" },
            { data: "agreementNumber", className:"text-center", bSearchable: true },
            { data: "firstTransaction", className:"text-center", bSearchable: false },
            { data: "lastTransaction", className:"text-center", bSearchable: false },
            { data: "transactionNumber", className:"text-center", bSearchable: false },
            { data: "totalAmount", className:"text-center", bSearchable: false },
            // {
            //     data: null,
            //     className:"text-center",
            //     bSearchable: false,
            //     bSortable: false,
            //     render: function (data, type, row) {
            //         return `<button class="print-doc" id="${row.id}"
            //             data-details="${row.withDetails}"><i class="print icon"></i></button>`;
            //     }
            // }
        ]
    });

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
            { data: "editBtn", searchable: false, className: 'text-center' },
            { data: "invoiceNumber", className:"text-center" },
            { data: "name", className:"text-center", bSearchable: true },
            { data: "agreementNumber", className:"text-center", bSearchable: false },
            { data: "nip", className:"text-center", bSearchable: false },
            { data: "amountNetto", className:"text-center", bSearchable: false },
            { data: "vat", className:"text-center", bSearchable: false },
            { data: "amountBrutto", className:"text-center", bSearchable: false },
            // {
            //     data: null,
            //     className:"text-center",
            //     bSearchable: false,
            //     bSortable: false,
            //     render: function (data, type, row) {
            //         return `<button class="print-doc" id="${row.id}"
            //             data-details="${row.withDetails}"><i class="print icon"></i></button>`;
            //     }
            // }
        ]
    });

    $(document).on('click', '.printInvoice',function (e) {
        var path = urlPrintInvoice.replace(/__id__/g, $(this).attr('id'));
        window.open(path);
        window.focus();
    })
    $(document).on('click', '.editInvoice',function (e) {
        var path = urlEditInvoice.replace(/__id__/g, $(this).attr('id'));
        document.location.href = path;
    })



}();