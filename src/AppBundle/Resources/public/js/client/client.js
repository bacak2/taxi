$(document)
    .ready(function() {
        var $clientTable = $('#clientTable'),
            editLink = $clientTable.data('edit');

        $clientTable.on('processing.dt', function () {
            console.log('szukam');
        });

        var table = $clientTable.DataTable({
            "dom": '<"ui inline form"<"ui two fields"<"field"l><"field"f>>>t<"input"p>',
            "language": dataTablesPL,
            "ajax": {
                url: $clientTable.data('get'),
                type: "POST",
            },
            "bInfo": false,
            "scrollY": '65vh',
            "scroller": true,
            "scrollX": true,
            "order": [],
            "deferRender": true,
            "bLengthChange": false,
            "pageLength": 25,
            "initComplete": function () {
                $('.client-link').popup();
            },
            "columns": [
                {
                    data: "name",
                    className:"",
                    render: function(data, type, row){
                        return `<a href="${editLink.replace(/__id__/g, row.id)}"
                            data-content="Kliknij aby przejść do edycji danych firmy"
                            class="firm-link">${data}</a>`;
                    }
                },
                {
                    data: "nip",
                    className:"text-center",
                    bSearchable: false
                },
                {
                    data: "phone",
                    className:"text-center",
                    bSearchable: false
                },
                {
                    data: "agreementNumber",
                    className:"text-center",
                    bSearchable: true
                },
                {
                    data: "agreementUntil",
                    className:"text-center",
                    render: function(d){
                        return moment(d).format("YYYY-MM-DD");
                    },
                    bSearchable: false
                },
                {
                    data: "street",
                    className:"text-center",
                    bSearchable: false
                },
                {
                    data: "pcode",
                    className:"text-center",
                    bSearchable: false
                },
                {
                    data: "town",
                    className:"text-center",
                    bSearchable: true
                },
                {
                    data: "country",
                    className:"text-center",
                    bSearchable: false
                }
            ]
        });
} );
