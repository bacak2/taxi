var app = function() {
    var $content = $('#mainContent'),
        $inpDateFrom = $content.find('#dateFrom'),
        $inpDateTo = $content.find('#dateTo'),
        $table = $content.find('#reportListTable'),
        $urlChangePercentage = $content.data('changepercentage'),
        $datepicker = $content.find('.datepicker'),
        $btnSearch = $content.find('#btnSearch');

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

    var table = $table.DataTable({
        "dom": '<"ui inline form"<"ui six fields"<"field"><"field"><"field"><"field"><"field"><"field">>>t',
        "language": dataTablesPL,
        "ajax": {
            url: routeGetRaports,
            beforeSend: function(){
                $btnSearch.addClass('loading');
            },
            data: function (post) {
              post.dateFrom = $inpDateFrom.val();
              post.dateTo = $inpDateTo.val();
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
            {data: "reportForDate", className: "", bSearchable: false},
            {data: "reportNumber", className: "text-center", bSearchable: false},
            {data: "kpCount", className: "text-center", bSearchable: false},
            {data: "kwCount", className: "text-center", bSearchable: false},
            {data: "amount", className: "text-center", bSearchable: true},
            {data: "prevAmount", className: "text-center", bSearchable: false},
            {data: "changeAmount", className: "text-center", bSearchable: false},
            {data: "kpAmount", className: "text-center", bSearchable: false},
            {data: "kpAmount", className: "text-center", bSearchable: true}
        ]
    });
}();