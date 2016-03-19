var priceInput = document.getElementsByClassName('price-input');

function priceChange(index) {
	if (isNaN(priceInput[index].value) || 
		priceInput[index].value.length > 5) { //if not a number (special char) or greater 99999
		priceInput[index].value = priceInput[index].value.substring(0, 
			priceInput[index].value.length - 1); //delete last input char
	}

	if (priceInput[index].value == "") { //if empty, change to 0
		priceInput[index].value = 0;
	}

	if (priceInput[index].value.length >= 2) { //check if length is 2 or more
		if (priceInput[index].value.substring(0, 1) == "0") { //check if first char is 0
			priceInput[index].value = priceInput[index].value.substring(1, 
				priceInput[index].value.length); //delete the first char (0)
		}
	}
}