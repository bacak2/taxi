$(document).ready(function(){
    $('.message .close')
        .on('click', function() {
            $(this)
                .closest('.message')
                .transition('fade')
            ;
        })
    ;
    $('.ui .dropdown').dropdown({
        fullTextSearch: true,
        minCharacters: 2,
        placeholder: false
    });
    $('#kwform_amount').inputmask("currency", {prefix: '', groupSeparator: '', rightAlign: false});

    $('#kwform_recipient').change(function(){
        let data = $('#kwform_recipient ~ .text').text();
        if(data.charAt(0) === "F"){
            data = data.substring(7);
            url = getCompanyNoteRoute;
        }
        else if(data.charAt(0) === "K"){
            data = data.substring(10, 15);
            url = getDriverNoteRoute;
        }
        getNote(data, url);
    });
});


function getNote(data, url){
    $.ajax({
        url: url,
        type:'GET',
        data: {
            data: "'"+data+"'"
        },
        success: function(response){
            let note = response.data.comment;
            if(note !== null){
                $("#note").show();
                $("#note > div").text(note);
            }
            else{
                $("#note").hide();
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            console.log("Error: " + errorThrown);
        }
    });
}