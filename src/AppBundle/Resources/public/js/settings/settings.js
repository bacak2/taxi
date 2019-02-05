const app = function () {

    var $content = $('.main-content');
    var $tabs = $content.find('.item-tab'),
        $tabContent = $content.find('.ui .tab')
    ;

    // TABS
    $tabs.on('click', function () {
        $tabs.removeClass('active');
        $tabContent.removeClass('active');

        $(this).addClass('active');
        $('.ui .tab[data-tab="' + $(this).data('tab') + '"]').addClass('active');
    });

    var table = $('#paramsTable').DataTable({
        "dom": '<"ui inline form"<"ui two fields"<"field"l><"field"f>>>t<"input"p>',
        "language": '',
        "ajax": {
            url: '',
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
        "initComplete": function () {},
        "columns": [
            {
                data: "category",
                className: "text-center",
                bSearchable: true
            },
            {
                data: "name",
                className: "text-center",
                bSearchable: true
            }
        ]
    });

}();