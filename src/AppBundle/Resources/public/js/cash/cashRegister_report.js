var app = function () {

    var $mainContent = $('#mainContent'),
        $kwTable = $mainContent.find('#kwTable'),
        $kpTable = $mainContent.find('#kpTable'),
        $dropdown = $mainContent.find('.ui.dropdown'),
        $datepicker = $mainContent.find('.datepicker'),
        $txtDate = $mainContent.find('#crr_form_reportForDate'),
        $txtPeriod = $mainContent.find('#crr_form_reportType'),
        $txtCountKP = $mainContent.find('#crr_form_kpCount'),
        $txtCountKW = $mainContent.find('#crr_form_kwCount'),
        $txtAmountKP = $mainContent.find('#crr_form_kpAmount'),
        $txtAmountKW = $mainContent.find('#crr_form_kwAmount'),
        $txtCRAmount = $mainContent.find('#crr_form_amount'),
        $txtChange = $mainContent.find('#crr_form_changeAmount'),
        $txtPrevAmount = $mainContent.find('#crr_form_prevAmount'),
        $txtSettlements = $mainContent.find('#crr_form_settlements'),
        $btnSearch = $mainContent.find('#btnSearch'),
        $btnSave = $mainContent.find('#btnSave'),
        $btnPrint = $mainContent.find('#btnPrint'),
        cashReportId = null
    ;

    $txtCRAmount.val($txtPrevAmount.val());
    $dropdown.dropdown({
        onChange: function () {
            kpt.rows().remove().draw();
            kwt.rows().remove().draw();

            $btnPrint.css('display','none');
            $btnSave.css('display', 'none');
            $txtCountKW.val(0);
            $txtAmountKW.val(0);
            $txtCountKP.val(0);
            $txtAmountKP.val(0);

            $txtPrevAmount.val(0);
            $txtChange.val(0);
            $txtCRAmount.val(0);
        }
    });
    $datepicker.calendar({
        type: 'date',
        text: calendarText,
        formatter: calendarFormatter
    });

    $btnSave.on('click', function (e) {
        $('#crr_form').submit();
    });

    $btnPrint.on('click', function (e) {
        e.preventDefault();

        var url = printCashReport.replace(/__id__/g, cashReportId);
        var win = window.open(url, '_blank');
        win.focus();
    });

    $btnSearch.on('click', function (e) {
        e.preventDefault();
        kpt.rows().remove().draw();
        kwt.rows().remove().draw();

        $btnPrint.css('display','none');
        $btnSave.css('display', 'none');
        $txtCountKW.val(0);
        $txtAmountKW.val(0);
        $txtCountKP.val(0);
        $txtAmountKP.val(0);

        $txtPrevAmount.val(0);
        $txtChange.val(0);
        $txtCRAmount.val(0);

        $.ajax({
            url: apiGetKwKpList,
            type:'POST',
            data: {
                date: $txtDate.val(),
                period: $txtPeriod.val()
            },
            success: function (resp) {
                $txtCountKW.val(resp.kw.count);
                $txtAmountKW.val(resp.kw.amount.toFixed(2));
                $txtCountKP.val(resp.kp.count);
                $txtAmountKP.val(resp.kp.amount.toFixed(2));

                $txtPrevAmount.val(resp.prev.toFixed(2));
                $txtChange.val(resp.change.toFixed(2));
                $txtCRAmount.val(resp.curr.toFixed(2));

                $txtSettlements.val(JSON.stringify(resp.settlements));

                if(resp.change < 0){
                    $txtChange.css({
                        'border': '1px solid red',
                        'background': '#ffcccc',
                        'color': 'red'
                    });
                }else{
                    $txtChange.css({
                        'border': '1px solid green',
                        'color': 'green',
                        'background': '#ccffcc',
                    });
                }

                if(resp.kp.count != 0){
                    kpt.rows.add(resp.kp.items);
                    kpt.draw();
                }

                if(resp.kw.count != 0){
                    kwt.rows.add(resp.kw.items);
                    kwt.draw();
                }

                if(resp.cashReportId !== null)
                {
                    cashReportId = resp.cashReportId;
                    $btnPrint.css('display','block');
                }
                if(resp.kp.count != 0 || resp.kw.count != 0)
                {
                    $btnSave.css('display','block');
                }
            }
        });
    });

    var kwt = $kwTable.DataTable({
        "dom": 't',
        "language": dataTablesPL,

        "bInfo": false,
        "order": [],
        "scrollY": '20vh',
        "scroller": true,
        "scrollX": true,
        "deferRender": true,
        "sSortable": false,
        "bLengthChange": false,
        "pageLength": 25,
        "columns": [
            { data: "position", className:"text-center" },
            { data: "cashRegisterNumber", className:"text-center", bSearchable: false },
            { data: "amount", className:"text-center", bSearchable: false },
            { data: "cashboxDate", className:"text-center", bSearchable: false },
            { data: "recipient", className:"text-center", bSearchable: false },
            { data: "item", className:"text-center", bSearchable: false },
            { data: "title", className:"text-center", bSearchable: false },
        ]
    });
    var kpt = $kpTable.DataTable({
        "dom": 't',
        "language": dataTablesPL,
        "bInfo": false,
        "order": [],
        "scrollY": '20vh',
        "scroller": true,
        "scrollX": true,
        "deferRender": true,
        "sSortable": false,
        "bLengthChange": false,
        "pageLength": 25,
        "columns": [
            { data: "user", className:"text-center" },
            { data: "cashRegisterNumber", className:"text-center", bSearchable: false },
            { data: "amount", className:"text-center", bSearchable: false },
            { data: "cashboxDate", className:"text-center", bSearchable: false },
            { data: "recipient", className:"text-center", bSearchable: false },
            { data: "item", className:"text-center", bSearchable: false },
            { data: "title", className:"text-center", bSearchable: false }
        ]
    });

}();