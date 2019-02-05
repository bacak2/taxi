var calendarText = {
    days: ['N', 'P', 'W', 'Ś', 'C', 'P', 'S'],
    months: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień',
        'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
    monthsShort: ['St', 'Lu', 'Ma', 'Kw', 'Ma', 'Cz', 'Lip', 'Si', 'Wrz', 'Pa', 'Li', 'Gru'],
};

var calendarFormatter = {
    date: function (date, settings) {
        if (!date) return '';
        var day = date.getDate() + '';
        if (day.length < 2) {
            day = '0' + day;
        }
        var month = (date.getMonth() + 1) + '';
        if (month.length < 2) {
            month = '0' + month;
        }
        var year = date.getFullYear();

        return year+ '-' + month + '-' + day;
    }
};