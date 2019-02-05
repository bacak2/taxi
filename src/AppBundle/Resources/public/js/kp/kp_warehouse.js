var app = function () {

    var $mianContent = $('#mainContainer'),
        $btnAdd = $mianContent.find('#btnAdd'),
        $detailCollection = $mianContent.find('#detailCollection'),
        $txtBrutto = $mianContent.find('#txtBrutto'),
        $txtVat = $mianContent.find('#txtVat'),
        $txtQuantity = $mianContent.find('#txtQuantity'),
        $txtArticle = $mianContent.find('#kww_form_kp_towar'),
        $txtSumBrutto = $mianContent.find('#kww_form_amount'),
        $txtSumNetto = $mianContent.find('#txtNetto'),
        sum = {
            brutto: 0,
            netto: 0
        };

    $txtQuantity.on('change', function () {
        $btnAdd.focus();
    });
    $('.ui .dropdown').dropdown({
        fullTextSearch: true,
        minCharacters: 2,
        placeholder: false
    });
    $txtBrutto.inputmask("currency", {prefix: '', groupSeparator: '', rightAlign: false});
    $txtVat.inputmask("[99][.9]", {prefix: '', groupSeparator: '', rightAlign: false});

    $btnAdd.on('click', function (e) {
        e.preventDefault();
        if($txtVat.val() == '' || $txtQuantity.val() == '')
        {
            return false;
        }

        var prototype = $detailCollection.data('prototype'),
            index = $detailCollection.data('index'),
            newForm = prototype.replace(/__name__/g, index),
            netto = 0,
            brutto = $txtBrutto.val()*1,
            vat = $txtVat.val()/100,
            quantity = $txtQuantity.val()*1
        ;
        netto = ((brutto)/(vat+1));
        sum.netto += (netto*quantity);
        sum.brutto += (brutto*quantity);
        $txtSumBrutto.val(sum.brutto.toFixed(2));
        $txtSumNetto.val(sum.netto.toFixed(2));

        $detailCollection.append(newForm);
        var formName = '#kww_form_cashRegisterDetails_'+index;
        $detailCollection
            .find(formName+'_param')
            .val($txtArticle.val())
            .dropdown()
            .css({'width': '100%', 'opacity':'1'});

        //TODO: do sprawdzenia czy znajduje te elementy
        $detailCollection.find(formName+'_netto').val((netto).toFixed(2));
        $detailCollection.find(formName+'_vat').val($txtVat.val());
        $detailCollection.find(formName+'_brutto').val(brutto.toFixed(2));
        $detailCollection.find(formName+'_quantity').val($txtQuantity.val());

        $detailCollection.data('index', index +1);
        $txtBrutto.val('');
        $txtVat.val('');
        $txtQuantity.val('');
        $txtArticle.val('');
        var $towar = $('#kww_form_kp_towar');
        $towar.parent().dropdown('clear');
        $towar.focus();
    });

    $(document).on('click', '.btnDelete', function () {
        var _tr = $(this).closest('tr');

        sum.netto -= _tr.find('.td-netto').val()*1;
        sum.brutto -= _tr.find('.td-brutto').val()*1;
        $txtSumNetto.val(sum.netto.toFixed(2));
        $txtSumBrutto.val(sum.brutto.toFixed(2));
        _tr.remove();
    })

}();