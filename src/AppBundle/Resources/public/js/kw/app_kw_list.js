var app = function () {

    var $content = $('#mainContent');
    var $table = $content.find('#kwTable');
    var $datepicker = $content.find('.datepicker');
    var $btnSearch = $content.find('#btnSearch');
    var $dateFrom = $content.find('#dateFrom'),
        $dateTo = $content.find('#dateTo');

    $datepicker.calendar({
        type: 'date',
        text: calendarText,
        formatter: calendarFormatter
    });

    $btnSearch.on('click', function () {
        table.clear();
        table.ajax.reload().draw();
    });

    var table = $table.DataTable({
        "dom": 't',
        "language": dataTablesPL,
        "ajax": {
            url: routeGetKWList,
            type: "POST",
            data: function (p) {
                p.dateFrom = $dateFrom.val(),
                p.dateTo = $dateTo.val()
            }
        },
        "bInfo": false,
        "order": [],
        "scrollY": '65vh',
        "scroller": true,
        "scrollX": true,
        "deferRender": true,
        "sSortable": false,
        "bLengthChange": false,
        "pageLength": 25,
        "columns": [
            {
                data: null,
                className:"text-center",
                bSearchable: false,
                bSortable: false,
                render: function (data, type, row) {
                    return `<button class="print-doc" id="${row.id}" data-items="${row.isSettlement}">
                        <i class="print icon"></i></button>`;
                }
            },
            { data: "transactionDate", className:"text-center" },
            { data: "cashRegisterNumber", className:"text-center", bSearchable: true },
            { data: "amount", className:"text-center", bSearchable: false },
            { data: "recipient", className:"text-center", bSearchable: true },
            { data: "title", className:"text-center", bSearchable: false },
            { data: "isSettlement", className:"text-center", bSearchable: true },
            { data: "user", className:"text-center", bSearchable: true }
        ]
    });
    $(document).on('click', '.print-doc',function (e) {
        var path = ($(this).data('items') == '0' )
            ? routePrintStandardKW.replace(/__kw__/g, $(this).attr('id'))
            : routePrintSettlementKW.replace(/__kw__/g, $(this).attr('id'));

        window.open(path);
        window.focus();
    })
}();