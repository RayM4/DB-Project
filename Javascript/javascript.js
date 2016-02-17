/*document.menuChange = function(data) {
    alert (data.value);
}*/

/*var trigger = $('.dropdown');
var list = $('.dropdown-menu');*/

/*trigger.click(function() {
    trigger.toggleClass('active');
    list.slideToggle(200);    
});*/

var text;

$(document).on('click', '.dropdown-menu li a', function() {
    $('#inputbox').val($(this).text());
    var text = $(this).text();
    //$('#interestBox').val(text);
}); 



