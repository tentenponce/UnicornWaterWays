var submitReserve = document.getElementById("submitReserve");
var accNumber = document.getElementById("accNumber");
var pinNumber = document.getElementById("pinNumber");
var errorBank = document.getElementById("errorBank");
var successBank = document.getElementById("successBank");

$("#errorBank").hide();
$("#errorBank").removeClass('hide');

$("#successBank").hide();
$("#successBank").removeClass('hide');

/*
Shows alert with slide animation.
*/
function errorBankAlert(){
    $("#errorBank").addClass("in"); //adds fade in animation
    /*
    Alert with timer
    http://stackoverflow.com/questions/23101966/bootstrap-alert-auto-close
    */
    $("#errorBank").fadeTo(2000, 500).slideUp(500, function(){
        $("#errorBank").hide(); //hide it (not alert) to show it again for error purposes.
    });
}

/*
Shows alert with slide animation.
*/
function successBankAlert(){
    $("#successBank").addClass("in"); //adds fade in animation
    /*
    Alert with timer
    http://stackoverflow.com/questions/23101966/bootstrap-alert-auto-close
    */
    $("#successBank").fadeTo(2000, 500).slideUp(500, function(){
        $("#successBank").hide(); //hide it (not alert) to show it again for error purposes.
    });
}

function verifyBankAcc() {
	var xmlhttp = new XMLHttpRequest();

	var btn = $("#submitReserve");

	btn.button('loading'); //loading button

	xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var result = parseInt(xmlhttp.responseText);

            if (result == 0) {
            	errorBankAlert();

     			btn.button('reset');
            } else if (result == 1){
            	successBankAlert();
				window.location.href = 'process.php';
            }
        }
    };

    xmlhttp.open("GET", "verifyBank.php?accNumber=" + accNumber.value + 
    	"&pinNumber=" + pinNumber.value, true);
    xmlhttp.send();
}