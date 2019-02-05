var app = function() {
    var $content = $('#mainContent'),
        $inpDateFrom = $content.find('#dateFrom'),
        $inpDateTo = $content.find('#dateTo'),
        $inpLicenseNumber = $content.find('#licenseNumber'),
        $inpClientNumber = $content.find('#clientNumber'),
        $inpCardNumber = $content.find('#cardNumber'),
        $table = $content.find('#transactionTable'),
        $urlChangePercentage = $content.data('changepercentage'),
        $urlResetTransaction = $content.data('resettransaction'),
        $datepicker = $content.find('.datepicker'),
        $btnSearch = $content.find('#btnSearch'),
        $btnExportCSV =$content.find('#btnExportCSV');

    $datepicker.calendar({
        type: 'date',
        text: calendarText,
        formatter: calendarFormatter
    });
    $btnSearch.on('click', function (e) {
        e.preventDefault();
        table.ajax.reload().draw();
    });

    $(document).on('click','.btnPercentage', function () {
        var _settlementId = $(this).data('id');
        swal({
            title: 'Podaj nowy procent dla transakcji',
            input: 'text',
            inputPlaceholder: '0-100%',
            showCancelButton: true,
            inputValidator: (value) => {
                return !value && 'Musisz podać wartość lub anulować!'
            }
        }).then(
            (result) => {
                if(typeof(result.value) !== 'undefined')
                {
                    $.ajax({
                        url: $urlChangePercentage,
                        type: 'POST',
                        data: {
                            'settlementId': _settlementId,
                            'percentage': result.value
                        },
                        success: function (resp) {
                            table.ajax.reload();
                        }
                    })
                }
            }
        );
    });

    $(document).on('click','.btnResetAmount', function () {
        var _settlementId = $(this).data('id');
        swal({
            title: 'Czy chcesz wyzerować/przywrócić transakcje?',
            type: 'question',
            showCancelButton: true,
            inputValidator: (value) => {
                return !value && 'Musisz podać wartość lub anulować!'
            }
        }).then(
            (result) => {
                if(typeof(result.value) !== 'undefined') {
                    $.ajax({
                        url: $urlResetTransaction,
                        type: 'POST',
                        data: {
                            'settlementId': _settlementId
                        },
                        success: function (resp) {
                            table.ajax.reload();
                            console.log(resp);
                        }
                    })
                }
            }
        );
    });


    var table = $table.DataTable({
        "dom": '<"ui inline form"<"ui six fields"<"field"><"field"><"field"><"field"><"field"><"field">>>t',
        "language": dataTablesPL,
        "ajax": {
            url: routeGetTransactions,
            beforeSend: function(){
                $btnSearch.addClass('loading');
            },
            data: function (post) {
              post.dateFrom = $inpDateFrom.val();
              post.dateTo = $inpDateTo.val();
              post.licenseNumber = $inpLicenseNumber.val();
              post.clientNumber = $inpClientNumber.val();
              post.cardNumber = $inpCardNumber.val();
            },
            type: "POST",
            complete: function () {
                $btnSearch.removeClass('loading');
            }
        },
        "bInfo": false,
        "scrollY": '60vh',
        "scroller": true,
        "scrollX": true,
        "order": [],
        "deferRender": true,
        "bLengthChange": false,
        "pageLength": 25,
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
        "rowCallback": function (row, data, index) {
            if(data.is_reseted == "1")
            {
                $(row).addClass('gray');
            }
        },
        "columns": [
            { data: null, defaultContent:'', searchable: false },
            {data: "transactionDate", className: "", bSearchable: false},
            {data: "totalAmount", className: "text-center", bSearchable: false},
            {data: "settledAmount", className: "text-center", bSearchable: false},
            {data: "amountWithFee", className: "text-center", bSearchable: false},
            {data: "transactionType", className: "text-center", bSearchable: true},
            {data: "licenseNumber", className: "text-center", bSearchable: false},
            {data: "driver", className: "text-center", bSearchable: false},
            {data: "originalPan", className: "text-center", bSearchable: false},
            {data: "cardType", className: "text-center", bSearchable: true},
            {data: "settlement", className: "text-center", bSearchable: false, sortable: false},
            {data: "percentage", className: "text-center", bSearchable: false},
            {data: "agreementNumber", className: "text-center", bSearchable: false},
            {data: "client", className: "text-center", bSearchable: false}
        ]
    });
}();