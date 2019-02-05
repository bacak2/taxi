var appClient = function() {

    var $content = $('.main-content');
    var $tabs = $content.find('.item-tab'),
        $tabContent = $content.find('.ui .tab'),
        $dropdawn = $content.find('.ui .dropdown'),
        $datepicker = $content.find('#datepicker'),
        $clientCardsTable = $content.find('#clientCardsTable'),
        routeGetClientCards = $content.data('cards'),
        clientId = $content.data('clientid') | 0;
    ;

    // Buttons
    var $btnSave = $content.find('#save'),
        $btnAddCard = $content.find('#addNewCard'),
        $btnAddAgreement = $content.find('#addNewAgreement');


    // TABS
    $tabs.on('click', function () {
        $tabs.removeClass('active');
        $tabContent.removeClass('active');

        $(this).addClass('active');
        $('.ui .tab[data-tab="' + $(this).data('tab') + '"]').addClass('active');
        if($(this).data('tab') == 't3')
        {
            $.fn.dataTable.tables({visible: true, api:true}).columns.adjust();
        }
    });

    $btnSave.on('click', function () {
        $('form').submit();
    });
    $dropdawn.dropdown(); // ROZWIJANA LISTA PAŃSTW
    $datepicker.calendar({
        type: 'date',
        text: calendarText,
        formatter: calendarFormatter
    });

    var path = routeGetClientCards.replace(/__clientId__/g, clientId);
    var table = $('#clientCardsTable').DataTable({
        "dom": '<"ui inline form"<"ui two fields"<"field"l><"field"f>>>t<"input"p>',
        "language": dataTablesPL,
        "ajax": {
            url: path,
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
        "columns": [
            { data: "pan", className: "" },
            { data: "cardType", className: "text-center", bSearchable: true },
            { data: "department", className: "text-center", bSearchable: false },
            { data: "discount", className: "text-center", bSearchable: true },
            { data: "dailyLimit", className: "text-center", bSearchable: false },
            { data: "monthlyLimit", className: "text-center", bSearchable: false },
            { data: "validUntil", className: "text-center", bSearchable: false },
            {
                data: "isActive",
                className: "text-center",
                bSearchable: true,
                render: function (data, type, row) {
                    return data !== "AKTYWNA" ? '<div><i class="red icon close"></i>'+data+'</div>'
                        : '<div><i class="green icon checkmark"></i>'+data+'</div>';
                }
            }
        ]
    });

}();


