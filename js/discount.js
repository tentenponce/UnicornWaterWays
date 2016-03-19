var discountButton = document.getElementById("discountButton");
var discount = document.getElementById("discount");

discountPercentage();

function discountPercentage() {
	var discVal = parseInt(discount.value); //get discount value

	if(discVal < 0 || discVal > 100 || isNaN(discount.value) || discount.value == "") { //if greater 100, not a number, or null, disable button and change to 0
		discountButton.disabled = true;
	} else { //otherwise, enable the button
		discountButton.disabled = false;
	}
}