$(document).ready(function(){
    $('.ui .dropdown').dropdown({
        fullTextSearch: true,
        minCharacters: 2,
        placeholder: false
    });

    $('#kpform_amount').inputmask("currency", {prefix: '', groupSeparator: '', rightAlign: false});

    $('#kpform_recipient').change(function(){
        let driverId = $('#kpform_recipient').val()+1;
        getDriverNote(driverId);
    });
});

function getDriverNote(id){
    $.ajax({
        url: getDriverNoteRoute,
        type:'GET',
        data: {
            driverId: id
        },
        success: function(response){
            alert('another ajax call in paradise');
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("Status: " + textStatus); alert("Error: " + errorThrown);
        }
    });
}