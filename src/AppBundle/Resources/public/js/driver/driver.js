$(document)
    .ready(function() {
        dataTablesPL.search = "";
        var table = $('#firmTable').DataTable({
            "dom": '<"ui inline form"<"ui two fields"<"field"l><"field"f>>>t<"input"p>',
            "language": dataTablesPL,
            "ajax": {
                url: routeGetDrivers,
                type: "POST"
            },
            "bInfo": false,
            "scroller": true,
            "scrollX": true,
            "order": [],
            "scrollY": '65vh',
            "deferRender": true,
            "bLengthChange": false,
            "pageLength": 25,
            "initComplete": function () {
                $('.driver-link').popup();
            },
            "columns": [
                {
                    data: "driverName",
                    className:"",
                    render: function (data, type, row) {
                        return `<a href='${getEditRoute(routeEditDriver, row.id)}' 
                            class="driver-link"
                            data-content="Kliknij aby przejść do edycji kierowcy">${data}</a>`;
                    }
                },
                {
                    data: "licenseNumber",
                    className: "text-center",
                    bSearchable: false
                },
                {
                    data: "firmName",
                    bSearchable: true
                },
                {
                    data: "mobileNumber",
                    className: "text-center",
                    bSearchable: false
                },
                {
                    data: "email",
                    className: "text-center",
                    bSearchable: false
                },
                {
                    data: 'status',
                    className: "text-center",
                    bSearchable: false,
                    render: function (data, type, row) {
                        return (data != 'ACTIVE')? '<div><i class="red icon close"></i>Nieaktywny</div>'
                            : '<div><i class="green icon checkmark"></i>Aktywny</div>';
                    }
                }
            ]
        });
    } );

function getEditRoute(route ,id) {
    return route.replace(/__id__/g, id);
}

// table.clear().draw();
// table.ajax.reload();
// table.draw();