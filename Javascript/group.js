$(document).on('click', '#Interest li', function() {
    var interestData= $(this).text();
    $('#box1').val(interestData);
    var temp = document.getElementById("bttn");
    temp.textContent = interestData;
}); 