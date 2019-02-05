var app = function () {

    var $mainContent = $('#mainContent'),
        $table = $mainContent.find('#kpTable'),
        $datepicker = $mainContent.find('.datepicker'),
        $btnSearch = $mainContent.find('#btnSearch'),
        $dateFrom = $mainContent.find('#dateFrom'),
        $dateTo = $mainContent.find('#dateTo');

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
            url: routeGetKPList,
            type: "POST",
            data: function (post) {
                post.dateFrom = $dateFrom.val();
                post.dateTo = $dateTo.val();
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
        "pageLength": 20,
        "columns": [
            {
                data: null,
                className:"text-center",
                bSearchable: false,
                bSortable: false,
                render: function (data, type, row) {
                    return `<button class="print-doc" data-items="${row.items}" id="${row.id}" >
                        <i class="print icon"></i></button>`;
                }
            },
            { data: "transactionDate", className:"text-center" },
            { data: "cashRegisterNumber", className:"text-center", bSearchable: true },
            { data: "amount", className:"text-center", bSearchable: false },
            { data: "recipient", className:"text-center", bSearchable: true },
            { data: "title", className:"text-center", bSearchable: false },
            // { data: "itemName", className:"text-center", bSearchable: false },
            { data: "user", className:"text-center", bSearchable: true }
        ]
    });
    $(document).on('click', '.print-doc',function (e) {
        var path = ($(this).data('items') == '1')
            ? routePrintWarehouseKP.replace(/__kp__/g, $(this).attr('id'))
            : routePrintStandardsKP.replace(/__kp__/g, $(this).attr('id'));

        window.open(path);
        window.focus();
    })
}();