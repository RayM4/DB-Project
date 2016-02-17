
/*$(document).on('click', '.dropdown-menu li a', function() {
    var groupData = $(this).html();
    var parsedata = groupData.split("-");
    $('#box1').val(parsedata[0]);
    //$('#box2').val(parsedata[1]);
});*/

/*$('#groupData li').on('click', function(){
    var groupdata = ($(this).text());
    var parsedata = groupdata.split("-");
    $('#box1').val(parsedata[0]);
});*/

$(document).on('click', '#groupData li', function() {
    var groupdata = $(this).text();
    var parsedata = groupdata.split("-")
    $('#box1').val(parsedata[0]);
    var temp = document.getElementById("bttn1");
    temp.textContent = parsedata[1];
}); 


$(document).on('click', '#locData li', function() {
    var locData = $(this).text();
    var parsedata = locData.split("-")
    $('#box2').val(parsedata[0]);
    $('#box3').val(parsedata[1]);
    var temp = document.getElementById("bttn2");
    temp.textContent = parsedata[0];
}); 






