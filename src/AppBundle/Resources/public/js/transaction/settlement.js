var appClient = function() {

    var $content = $('#mainContent'),
        $dropdown = $content.find('.ui.dropdown'),
        $table = $content.find('#settlementTable'),
        $btnSearch = $content.find('#btnSearch'),
        $btnSettlement = $content.find('#btnSettlement'),
        routeGetSettlements = $btnSearch.data('url'),
        $inputDeduction = $content.find('#settlement_trans_form_deduction'),
        $inputSum = $content.find('#settlement_trans_form_amount'),
        $inputTopPay = $content.find('#settlement_trans_form_toPay'),
        $driverId = $content.find('#settlement_trans_form_driver'),
        $ownCards = $content.find('#settlement_trans_form_ownCards'),
        $bankTransaction = $content.find('#settlement_trans_form_bankTransaction'),
        $checkbox = $content.find('.checkbox'),
        $transactions = $content.find('#settlement_trans_form_transactions'),
        $sort = $checkbox.val()
    ;

    $dropdown.dropdown({
        fullTextSearch: true,
        minCharacters: 2,
        placeholder: null
    });
    $checkbox.checkbox({
        onChange: function (val) {
            $sort = $(this).val();
        }
    });
    $btnSearch.on('click', function () {
        table.clear().draw();
        table.ajax.reload().draw();
    });

    var table = $table.DataTable({
        "dom": 't',
        "select": {
            style: 'multi',
            info: false,
            selector: 'td:first-child'
        },
        "language": dataTablesPL,
        "ajax": {
            url: routeGetSettlements,
            type: "POST",
            data: function (post) {
                post.driverId = $driverId.val();
                post.ownCards = $ownCards.is(':checked');
                post.bankTransaction = $bankTransaction.is(':checked');
                post.sort = $sort
            }
        },
        "info": false,
        "order": [],
        "scrollY": '24vh',
        "scroller": true,
        "scrollX": true,
        "deferRender": true,
        "bLengthChange": false,
        "pageLength": 20,
        "columnDefs": [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        "columns": [
            { data: null, defaultContent: '', searchable: false },
            { data: "transaction_date", className: "text-center", searchable: false },
            { data: "transaction_type", className: "text-center", searchable: false, orderable: false },
            { data: "total_amount", className: "text-center", searchable: false },
            { data: "settled_amount", className: "text-center", searchable: false },
            { data: "to_pay", className: "text-center", searchable: false, orderable: false },
            { data: "client", className: "text-center", searchable: false, orderable: false },
        ]
    });

    table.on('select', calculateItems);
    table.on('deselect', calculateItems);


    function calculateItems() {
        var selectedItems = table.rows({selected: true}).data();
        var resultArray = [];

        var toPay = 0;
        var sum = 0;
        selectedItems.each(function (data) {
            toPay += (data.to_pay*100);
            sum += (data.for_settlement*100);
            var obj = {
                'id': data.id,
                'toPay': data.to_pay,
                'amount': data.for_settlement,
                'percentage': data.percentage,
                'forSettlement': data.for_settlement
            };
            resultArray.push(obj);
        });
        $inputDeduction.val(((sum-toPay)/100).toFixed(2));
        $inputSum.val((sum/100).toFixed(2));
        $inputTopPay.val((toPay/100).toFixed(2));

        $transactions.val(JSON.stringify(resultArray));
    }

}();


