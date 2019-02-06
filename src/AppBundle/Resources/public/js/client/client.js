$(document)
    .ready(function() {
        var $clientTable = $('#clientTable'),
            editLink = $clientTable.data('edit');

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
                },
                {
                    data: 'status',
                    className: "d-none",
                    bSearchable: true,
                    render: function (data, type, row) {
                        return (data != 'ACTIVE')? '<div><i class="red icon close"></i>Nieaktywny</div>'
                            : '<div><i class="green icon checkmark"></i>Aktywny</div>';
                    }
                }
            ]
        });

        $('<div id="statusBox" class="ui toggle checkbox checked">\n' +
            '<div class="ui checkbox checked">\n' +
            '<input id="active" type="checkbox">\n' +
            '<label>Tylko aktywne</label>\n' +
            '</div>\n' +
            '</div>')
            .insertBefore("#clientTable_filter");

        const statusCheckbox = $('#active');

        statusCheckbox.on('click', function(){
            if(this.checked){
                $.fn.dataTable.ext.search.push(
                    function (settings, data, dataIndex){
                        return (data[9] == 'Aktywny') ? true : false;
                    }
                );
            }
            else{
                $.fn.dataTable.ext.search.pop();
            }

            table.draw();

        });

        statusCheckbox.click();
    } );
