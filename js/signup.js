var fname = document.getElementById("fname");
var lname = document.getElementById("lname");
var phoneno = document.getElementById("phoneno");
var email = document.getElementById("email");
var password = document.getElementById("password");
var confirmPassword = document.getElementById("confirmPassword");
var address = document.getElementById("address");

var limitText = document.getElementById('limitText');

/*
Executes when the html loads, this is the default setup
of the system/website.
*/
function defaultSetup(){
	$("#limitAlert").hide();
	$("#limitAlert").removeClass('hide');
}

/*
Shows alert with slide animation.
*/
function showAlert(){
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
Validates the whole form

return true if valid, otherwise false.
*/
function isFormValid() {
	if (!allLetter(fname) || !allLetter(lname)) {
		limitText.innerHTML = "First Name and Last Name must have alphabet characters only.";
		showAlert();

		return false;
	}

	if (!validatePhoneNo()) {
		limitText.innerHTML = "Phone number must must have number characters only.";
		showAlert();

		return false;
	}

	if (!validateEmail(email)) {
		limitText.innerHTML = "Invalid Email.";
		showAlert();

		return false;
	}

	if (password.value != confirmPassword.value) {
		limitText.innerHTML = "Password did not match.";
		showAlert();

		return false;	
	}

	if (!validateLength(password, 7, password.maxLength)) {
		limitText.innerHTML = "Password should not be empty / length must be between " + 
			7 + " to " + password.maxLength;
		showAlert();

		return false;
	}

	return true;
}

function validatePhoneNo() {
	console.log("Phone Number Length: " + phoneno.value.length);

	if (isNaN(phoneno.value)) {
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
Validates email input if it is a valid address.

@param uemail email input
@return true if email is valid, otherwise false.
*/
function validateEmail(uemail) {  
	var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;  
	if (!uemail.value.match(mailformat)) { 
		uemail.focus();
		return false;  
	}

	return true;
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