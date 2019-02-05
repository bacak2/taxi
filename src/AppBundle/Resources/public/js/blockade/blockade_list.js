var app = function() {
    var $blockadeListTable = $('#blockadeListTable'),
        $loader = $('#loader');
    var routeGetBlockades = $blockadeListTable.parent().data('href');
    var routeDeleteBlockade = $blockadeListTable.parent().data('deleteblockade');

    var table = $blockadeListTable.DataTable({
        "dom": '<"ui inline form"<"ui two fields"<"field"l><"field"f>>>t<"input"p>',
        "language": dataTablesPL,
        "ajax": {
            url: routeGetBlockades,
            beforeSend: function(){
                $loader.addClass('active');
            },
            type: "POST",
            complete: function () {
                $loader.removeClass('active');
            }
        },
        "scrollY": "65vh",
        "scrollX": true,
        "scroller": true,
        "order": [],
        "deferRender": true,
        "pageLength": 25,
        "bInfo": false,
        "bLengthChange": true,
        "columns": [
            {data: "edit", searchable: false, sortable: false},
            {data: "firstName", bSearchable: true},
            {data: "lastName", className: "", bSearchable: true},
            {data: "licenseNumber", className: "text-center", bSearchable: true},
            {data: "isActive", className: "text-center", bSearchable: false, bSortable: false},
            {data: "blockadeType", className: "text-center", bSearchable: false},
            {data: "blockadeDate", className: "text-center", bSearchable: false},
            {data: "totalAmount", className: "text-center", bSearchable: false},
            {data: "comment",     className: "text-center", bSearchable: false, bSortable: false}
        ]
    });

    $(document).on('click', '.remove-blockade', function (e) {
        e.preventDefault();
        var _driverId = $(this).data('driver');
        var _bockadeId = $(this).data('id');

        swal({
            title: 'Czy chcesz zdjąć blokadę dla tego kierowcy?',
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tak, usuń!',
            cancelButtonText: 'Anuluj'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: routeDeleteBlockade,
                    type: 'POST',
                    data: {
                        'blockadeId': _bockadeId,
                        'driverId': _driverId
                    },
                    success: function () {
                        table.ajax.reload();
                    }
                });
            }
        });
    });
}();


