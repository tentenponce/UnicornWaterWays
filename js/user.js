var fname = document.getElementById("fname");
var lname = document.getElementById("lname");

var passwordForm = document.getElementById("passwordForm");
var oldPassword = document.getElementById("oldPass");
var password = document.getElementById("newPass");
var confirmPassword = document.getElementById("confirmNewPass");

var limitText = document.getElementById('limitText');

$("#successAlert").hide();
$("#successAlert").removeClass('hide');

$("#limitAlert").hide();
$("#limitAlert").removeClass('hide');

showAlert();
/*
Shows alert with slide animation.
*/
function showAlert(){
	$("#successAlert").addClass("in"); //adds fade in animation
	/*
	Alert with timer
	http://stackoverflow.com/questions/23101966/bootstrap-alert-auto-close
	*/
	$("#successAlert").fadeTo(2000, 500).slideUp(500, function(){
    	$("#successAlert").hide(); //hide it (not alert) to show it again for error purposes.
	});

	$("#errorAlert").addClass("in"); //adds fade in animation
	/*
	Alert with timer
	http://stackoverflow.com/questions/23101966/bootstrap-alert-auto-close
	*/
	$("#errorAlert").fadeTo(2000, 500).slideUp(500, function(){
    	$("#errorAlert").hide(); //hide it (not alert) to show it again for error purposes.
	});
}

/*
Shows alert with slide animation.
*/
function limitAlert(){
	$("#limitAlert").addClass("in"); //adds fade in animation
	/*
	Alert with timer
	http://stackoverflow.com/questions/23101966/bootstrap-alert-auto-close
	*/
	$("#limitAlert").fadeTo(2000, 500).slideUp(500, function(){
    	$("#limitAlert").hide(); //hide it (not alert) to show it again for error purposes.
	});
}

/*
Validates input if it is alphabets only.

@param uname name input
@return true if characters only, otherwise false.
*/
function allLetter(uname) {   
	var letters = /^[A-Za-z]+$/;  
	if(!uname.value.match(letters)) {    
		uname.focus();  
		return false;  
	}  

	return true;
}

/*
Validates password length.

@param passid password input
@param mx max value of number
@param my

@return true if between mx and my and not 0, otherwise false.
*/
function validateLength(passid,mx,my) {  
	var passid_len = passid.value.length;
	if (passid_len == 0 ||passid_len > my || passid_len < mx) {  
		passid.focus();  
		return false;
	}  
	
	return true;  
}  

/*
Validates first name and last name if all leters

return false if there's special or number,
else if all alphabet, return true.
*/
function isNameValid() {
	if (!allLetter(fname) || !allLetter(lname)) {
		limitText.innerHTML = "First Name and Last Name must have alphabet characters only.";
		limitAlert();

		return false;
	}

	return true;
}

/*
Validates the whole form

return true if valid, otherwise false.
*/
function isPassValid() {
	if (password.value != confirmPassword.value) {
		limitText.innerHTML = "Password did not match.";
		limitAlert();
		return false;
	}

	if (!validateLength(password, 7, password.maxLength)) {
		limitText.innerHTML = "Password should not be empty / length must be between " + 
			7 + " to " + password.maxLength;
		limitAlert();

		return false;
	}

	var xmlhttp = new XMLHttpRequest();

	var btn = $("#changePassButton");

	btn.button('loading'); //loading button

	xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var result = parseInt(xmlhttp.responseText);

            if (result == 0) {
            	limitText.innerHTML = "Old Password did not match.";
				limitAlert();

     			btn.button('reset');
            } else if (result == 1){
            	passwordForm.submit();
            }
        }
    };

    xmlhttp.open("GET", "oldPass.php?oldPass=" + oldPassword.value, true);
    xmlhttp.send();

	return false;
}