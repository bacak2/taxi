var app = function () {
    var $content = $('#main-content'),
        $itemTab = $content.find('.driver-item-tab'),
        $dropdown = $content.find('.ui .dropdown'),
        $isVat = $content.find('#driver_form_vatPayer'),
        $vatInput = $content.find('#driver_form_vat'),
        // buttons
        $btnAddBlockade = $content.find('#btnAddBlockade'),
        $btnSave = $content.find('#save-driver'),
        // Collection : terminals | blockades
        $blockadeCollection = $content.find('#blockadeList'),
        $noDataInfoB = $content.find('#no-data-info-b'),
        $deleteBlockadeRoute = $blockadeCollection.data('delete');

    /**
     * Events
     */
    $itemTab.on('click', function() {
        $itemTab.removeClass('active');
        $('.driver-tab').removeClass('active');

        $(this).addClass('active');
        $('.driver-tab[data-tab="'+$(this).data('tab')+'"]').addClass('active');
    });
    $isVat.on('change', function () {
        var $vat = $vatInput.closest('.field');
        if($(this).val() == 1){
            $vat.removeClass('disabled');
            $vatInput.focus();
        }else{
            $vat.addClass('disabled');
        }

    });
    $btnSave.on('click', function () {
        $('form').submit();
    });

    /**
     * Settings
     */
    $dropdown.dropdown();
    setCalendar();
    $content.find('#driver_form_accountNumber').inputmask('99 9999 9999 9999 9999 9999 9999');
    if($blockadeCollection.data('index') > 0){
        $btnAddBlockade.hide();
    }

    /**
     * Dodanie nowej blokady
     */
    $btnAddBlockade.on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        if($noDataInfoB){
            $noDataInfoB.remove();
        }
        $btnAddBlockade.hide();

        var $prototype = $blockadeCollection.data('prototype');
        var $index = $blockadeCollection.data('index');
        var newForm = $prototype.replace(/__name__/g, $index);

        $blockadeCollection.data('index', $index+1);
        $blockadeCollection.append(newForm);
        setCalendar();
        $('.ui .dropdown').dropdown();
    });
    $blockadeCollection.on('click', '.btn-delete-blockade', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $this = $(this);
        $tr = $this.closest('tr');
        $nextTr = $tr.next('tr');

        swal({
            title: 'Czy napewno chcesz usunąć blokade?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tak, usuń!',
            cancelButtonText: 'Anuluj'
        }).then((result) => {
            if (result.value) {
                if($this.data('hash')){
                    $.ajax({
                        url: $deleteBlockadeRoute
                            .replace(/__bid__/g, $this.attr('id')).replace(/__bhash__/g,$this.data('hash')),
                        success: function (res) {
                            if(res.status == "ok")
                            {
                                swal(
                                    'Usunięto!',
                                    'Blokada usunięta z systemu.',
                                    'success'
                                );
                                $tr.remove();
                                $nextTr.remove();
                            }else{
                                swal(
                                    'Błąd!',
                                    'Błąd usuwania blokady.',
                                    'warning'
                                );
                            }
                        }
                    });

                }else{
                    $tr.remove();
                    $nextTr.remove();
                }
            }
        });
    });

    function setCalendar() {
        $('.datepicker').calendar({
            type: 'date',
            text: calendarText,
            formatter: calendarFormatter
        })
    }
}();



