var app = function() {
    var $blockadeListTable = $('#blockadeTransactionTable');
    var routeGetBlockades = $blockadeListTable.parent().data('href'),
        $transactionType = $('.transaction-type'),
        $driver = $('.driver'),
        $loader = $('#loader')
    ;
    var $totalAmount = $('#totalAmount'),
        $amountToPay = $('#amountToPay');

    $driver.dropdown({
        minCharacters: 2,
        placeholder: false,
        fullTextSearch: true,
        onChange: function(value, text, $choice){
            ajaxCall(value, $transactionType.val());
        }
    });

    $transactionType.dropdown({
        onChange: function (value, text, choice) {
            if($driver.val() !== ''){
                ajaxCall($driver.val(), value);
            }
        }
    });

    var table = $blockadeListTable.DataTable({
        "dom": 't',
        "language": dataTablesPL,
        "scrollY": "35vh",
        "scrollX": true,
        "scroller": true,
        "order": [],
        "deferRender": true,
        "pageLength": 25,
        "bInfo": false,
        "bLengthChange": true,
        "initComplete": function () {
            // $tableContent = $('#table');
            // $tableContent.find('#cardTable_filter').addClass('ui input');
        },
        "columns": [
            {data: "transactionDate", className: "text-center",bSearchable: true},
            {data: "amount", className: "text-center", bSearchable: true},
            {data: "transactionType", className: "text-center", bSearchable: true},
            {data: "toPay", className: "text-center", bSearchable: false, bSortable: false},
            {data: "amountWithFee", className: "text-center", bSearchable: false}
        ]
    });

    function ajaxCall(driver, transactionType) {
        $totalAmount.text('');
        $amountToPay.text('');
        table.rows().remove().draw();
        $.ajax({
            url: routeGetBlockades,
            type: 'POST',
            beforeSend: function(){
                $loader.addClass('active');
            },
            data: {
                driver: driver,
                transactionType: transactionType
            },
            success: function (resp) {
                if((resp.items.length > 0)){
                    table.rows.add(resp.items).draw();
                    $totalAmount.text(resp.totalAmount.toFixed(2));
                    $amountToPay.text(resp.totalAmountToPay.toFixed(2));
                }
            },
            complete: function () {
                $loader.removeClass('active');
            }
        })
    }
}();


