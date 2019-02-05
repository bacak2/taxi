var app = function () {
    var $mainContent = $('#mainContent'),
        $itemTab = $mainContent.find('.bank-item-tab'),
        $btnGenerate = $('#btnGenerate'),
        $btnFile = $('#btnFile'),
        $forSettlement = $('#forSettlement'),
        $responseDiv = $('#responseDiv')
    ;

    $btnFile.on('click', function (e) {
        e.preventDefault();
        document.location.href = $(this).data('href');
    });

    $btnGenerate.on('click', function (e) {
        e.preventDefault();
        var self = $(this);

        var data = $('form').serializeArray();

        $.ajax({
            url: $(this).data('href'),
            type: 'POST',
            beforeSend: function(){
                self.addClass('loading');
                $responseDiv.html('');
            },
            data: {
                'formData': data
            },
            success: function (resp) {
                try {
                    Object.values(resp['response']).forEach(function (item) {
                        if(item.amount != 0){
                            $responseDiv.append(`<div class="ui segment">
                            ${item.description}: <span id="forSettlement" style="font-weight: bolder; color: red;">  ${item.amount.toFixed(2)}</span> zł
                        </div>`);
                        }
                    })
                }catch (e) {
                    
                }
            },
            complete: function () {
                self.removeClass('loading');
            }
        });
    });

    $('.ui .checkbox').checkbox();

    $('.ui .datepicker').calendar({
        type: 'date',
        text: calendarText,
        formatter: calendarFormatter
    });

    $('.ui .dropdown').dropdown({
        minCharacters: 2,
        clearable: true,
        placeholder: 'Wybierz kierowcę',
        fullTextSearch: true,
        onChange: function(value, text, $choice){

        }
    });
    $('.ui.dropdown .remove.icon').on('click', function(e){
        $(this).parent('.dropdown').dropdown('clear');
        e.stopPropagation();
    });

    $('.ui .toggle').on('change', function (e) {
        if(e.target.id == 'bank_nonPeriodic')
        {
            if(e.target.checked == false && $mainContent.find('#bank_periodic')[0].checked == false){
                $mainContent.find('#bank_periodic')[0].checked = true;
            }
        }
        else if(e.target.id == 'bank_periodic')
        {
            if(e.target.checked == false && $mainContent.find('#bank_nonPeriodic')[0].checked == false){
                $mainContent.find('#bank_nonPeriodic')[0].checked = true;
            }
        }
        else if(e.target.id == 'bank_bezgotowka')
        {
            if(e.target.checked == false && $mainContent.find('#bank_importpko')[0].checked == false){
                $mainContent.find('#bank_importpko')[0].checked = true;
            }
        }
        else if(e.target.id == 'bank_importpko')
        {
            if(e.target.checked == false && $mainContent.find('#bank_bezgotowka')[0].checked == false){
                $mainContent.find('#bank_bezgotowka')[0].checked = true;
            }
        }
    })
}();