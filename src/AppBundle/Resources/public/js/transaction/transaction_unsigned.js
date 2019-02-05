var appClient = function() {

    var $content = $('#mainContent');
    var $unsignedTable = $content.find('#unsignedTable'),
        routeGetUnsigned = $content.data('unsigned'),
        $btnAssign = $content.find('#btnAssign'),
        $dropdown = $content.find('.ui.dropdown')
    ;

    $dropdown.dropdown({
        fullTextSearch: true,
        minCharacters: 2,
        placeholder: null
    });
    $btnAssign.on('click', function (e) {
        e.preventDefault();

        var items = table.rows({selected: true}).data();
        var transactions = {
            'driverId': $content.find('#unassigned_driver_form_driver').val() | 0,
            'items': []
        };
        items.each(function (data) {
            transactions.items.push(data.id);
        });

        if(transactions.items.length > 0 && transactions.driverId != 0)
        {
            swal({
                title: 'Czy chcesz przypisać transakcje do kierowcy?',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tak, przypisz!',
                cancelButtonText: 'Anuluj'
            }).then((result) => {
                if (result.value) {
                    var formData = JSON.stringify(transactions);
                    $.ajax({
                        url: $(this).data('action'),
                        type: 'POST',
                        data: {
                            'formData': formData
                        },
                        success: function (res) {
                            table.ajax.reload();
                            swal(
                                'Przypisano!','','success'
                            )
                        }
                    });
                }
            });
        }
    });

    var table = $unsignedTable.DataTable({
        "dom": 't',
        "select": {
            style: 'multi',
            info: false,
            selector: 'td:first-child'
        },
        "language": dataTablesPL,
        "ajax": {
            url: routeGetUnsigned,
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
        "columnDefs": [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        "columns": [
            { data: null, defaultContent:'', searchable: false },
            { data: "transactionDate", className: "text-center", searchable: false },
            { data: "posTidNumber", className: "text-center", searchable: false },
            { data: "totalAmount", className: "text-center", searchable: false },
            { data: "amountWithFee", className: "text-center", searchable: false, orderable: false },
            { data: "cardType", className: "text-center", searchable: false, orderable: false },
        ]
    });

}();


