var app = function() {

    var table = $('#cardTable').DataTable({
        "dom": '<f>t',
        "language": dataTablesPL,
        "ajax": {
            url: routeGetCards,
            type: "POST"
        },
        "scrollY": "65vh",
        "scrollX": true,
        "scroller": true,
        "order": [],
        "deferRender": true,
        "pageLength": 25,
        "bInfo": false,
        "bLengthChange": true,
        "initComplete": function () {
            $tableContent = $('#table');
            $tableContent.find('#cardTable_filter').addClass('ui input');
        },
        "columns": [
            {
                data: "pan", bSearchable: true,
                render: function (data, type, row) {
                    return `<a href='${routeEditCard.replace(/__id__/g, row.id)}'
                            title="Kliknij aby przejść do edycji">${data}</a>`;
                }
            },
            {data: "firm_name", bSearchable: true},
            {data: "department", className: "text-center", bSearchable: false},
            {data: "name", className: "text-center", bSearchable: false, bSortable: false},
            {data: "type", className: "text-center", bSearchable: false},
            {
                data: "status",
                className: "text-center",
                bSearchable: false,
                render: function (data, type, row) {
                    return row.is_active == 0 ? '<div><i class="red icon close"></i>'+data+'</div>'
                        : '<div><i class="green icon checkmark"></i>'+data+'</div>';
                }
            },
            {data: "valid_until", className: "text-center", bSearchable: false},
            {
                data: "discount",
                className: "text-center",
                bSearchable: false, bSortable: false,
                render: function (data, type, row) {
                    var i = data * 100;
                    return i + '%'
                }
            },
            {
                data: "comment",
                className: "text-center",
                bSearchable: false, bSortable: false,
                render: function (data, type, row) {
                    return (data) ? ' <div class="popup" title="' + data + '">' +
                        '<i class="info icon"></i></div>' : '';
                }
            }
        ]
    });

}();


