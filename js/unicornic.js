//Variables that are ALL CAPS are constants.

var COTTAGE = 0; 
var REGULAR_ROOM = 1; 
var GRANDE_ROOM = 2; 
var SUPREME_ROOM = 3; 

var COTTAGE_MAX_VALUE = 5;
var REGULAR_ROOM_MAX_VALUE = 10;
var GRANDE_ROOM_MAX_VALUE = 15;
var SUPREME_ROOM_MAX_VALUE = 30;

var COTTAGE_PRICE_DAY = document.getElementById('cottageDayPrice').innerHTML;
var COTTAGE_PRICE_NIGHT = document.getElementById('cottageNightPrice').innerHTML;

var REGULAR_PRICE_DAY = document.getElementById('regDayPrice').innerHTML;
var REGULAR_PRICE_NIGHT = document.getElementById('regNightPrice').innerHTML;

var GRANDE_PRICE_DAY = document.getElementById('grandDayPrice').innerHTML;
var GRANDE_PRICE_NIGHT = document.getElementById('grandNightPrice').innerHTML;

var SUPREME_PRICE_DAY_NIGHT = document.getElementById('supPrice').innerHTML;

var ADULT_PRICE = document.getElementById('adultPrice').innerHTML;
var CHILD_DISCOUNT = document.getElementById('childDiscount').innerHTML / 100; //percentage

var CHILD_PRICE = ADULT_PRICE - (ADULT_PRICE * CHILD_DISCOUNT);

var roomChosen;
var maxPersonLimit;
var roomDayPrice;
var roomNightPrice;
var roomTotalPrice = 0;

var adult = document.getElementById('adult');
var child = document.getElementById('child');
var totalperson = document.getElementById('totalperson');
var totalprice = document.getElementById('totalprice');
var roomtype = document.getElementById('roomtype');
var limitText = document.getElementById('limitText');
var checkIn = document.getElementById('checkIn');
var checkOut = document.getElementById('checkOut');

var datePrice = document.getElementById('datePrice');

function isValidForm() {
	var totalTemp = parseInt(adult.value) + parseInt(child.value);
	if (totalTemp <= 0) {
		limitText.innerHTML = " Adult or Child Count must be atleast 1.";
		showAlert();

		return false;
	} 

	return true;
}

/*
Executes when the html loads, this is the default setup
of the system/website.
*/
function defaultSetup(){
	roomTypeChange();

	$("#limitAlert").hide();
	$("#limitAlert").removeClass('hide');

	$("#dateAlert").hide();
	$("#dateAlert").removeClass('hide');

	$(document).ready(function(){
    	$('[data-toggle="popover"]').popover('show'); 
    	setTimeout(function () {
    		$('[data-toggle="popover"]').popover('destroy');
    	}, 5000);
	});
}

/*
Resets the datas from reservation form.
*/
function resetFields(){
	checkIn.value = "";
	checkOut.value = "";
	datePrice.value = roundOff(0) + " PHP";
	adult.value = 0;
	child.value = 0;
	totalperson.innerHTML = "<b>" + 0 + "</b> Person (s)";
	totalprice.innerHTML = "<b>" + roundOff(0) + "</b> PHP";
}

/*
This method executes when room type combo box (list box/select)
changes its value. It assigns the proper max limit of room on a
variable.
*/
function roomTypeChange(){
	var indexTemp = roomtype.selectedIndex;
	if (indexTemp == COTTAGE) {
		maxPersonLimit = parseInt(COTTAGE_MAX_VALUE);
		roomChosen = "Cottage";
		roomDayPrice = COTTAGE_PRICE_DAY;
		roomNightPrice = COTTAGE_PRICE_NIGHT;
	} else if (indexTemp == REGULAR_ROOM) {
		maxPersonLimit = parseInt(REGULAR_ROOM_MAX_VALUE);
		roomChosen = "Regular Room";
		roomDayPrice = REGULAR_PRICE_DAY;
		roomNightPrice = REGULAR_PRICE_NIGHT;
	} else if (indexTemp == GRANDE_ROOM) {
		maxPersonLimit = parseInt(GRANDE_ROOM_MAX_VALUE);
		roomChosen = "Grande Room";
		roomDayPrice = GRANDE_PRICE_DAY;
		roomNightPrice = GRANDE_PRICE_NIGHT;
	} else if (indexTemp == SUPREME_ROOM) {
		maxPersonLimit = parseInt(SUPREME_ROOM_MAX_VALUE);
		roomChosen = "Supreme Room";
		roomDayPrice = 0; //because 4000 whole day
		roomNightPrice = 0; //because 4000 whole day
	}

	console.log("Room Chosen: " + roomChosen);
	resetFields(); //reset datas to avoid bugsssss.
}

/*
When the user clicks or changes the time and date check in (input).
It also computes how many day and night on the interval of check in and out.
*/
function computeDatePrice(){
	var fromDate = checkIn.value;
	var toDate = checkOut.value;

	var dayCount = 0;
	var nightCount = 0;

	if(Date.parse(fromDate) > Date.parse(toDate)) { //checks if check in is bigger than check out
		datePrice.value = "0.00 PHP"; //reset to 0 because of error
		roomTotalPrice = 0; //reset to 0 because of error
		$("#dateAlert").addClass("in"); //adds fade in animation
		/*
		Alert with timer
		http://stackoverflow.com/questions/23101966/bootstrap-alert-auto-close
		*/
		$("#dateAlert").fadeTo(4000, 500).slideUp(500, function(){
	    	$("#dateAlert").hide(); //hide it (not alert) to show it again for error purposes.
		});
	} else {
		if(getTimeHour(fromDate) <= 5) { //CHECK IN:  12 AM - 5 AM
			nightCount += 1;
			if(getTimeHour(toDate) <= 5) { //if 12 AM - 5 AM
				if (getTimeHour(fromDate) > getTimeHour(toDate)) { //if loops 1 day
					dayCount += 1;
					nightCount += 1;
				}
			} else if (getTimeHour(toDate) <= 18) { //if 6 AM - 6 PM
				dayCount += 1;
			} else if (getTimeHour(toDate) <= 23){ //7PM onwards
				dayCount += 1;
				nightCount += 1;
			}	
		} else if (getTimeHour(fromDate) <= 18) { //CHECK IN: if 6 AM - 6 PM
			dayCount += 1;
			if(getTimeHour(toDate) <= 5) { //if 12 AM - 5 AM
				nightCount += 1;
			} else if (getTimeHour(toDate) <= 18) { //if 6 AM - 6 PM
				if (getTimeHour(fromDate) > getTimeHour(toDate)) { //if loops 1 day
					dayCount += 1;
					nightCount += 1;
				}
			} else if (getTimeHour(toDate) <= 23) { //7PM onwards
				nightCount += 1;
			}
		} else { //CHECK IN: 7PM onwards
			nightCount += 1;
			if(getTimeHour(toDate) <= 5) { //if 12 AM - 5 AM		
				//do nothing
			} else if (getTimeHour(toDate) <= 18) { //if 6 AM - 6 PM
				dayCount += 1;
			} else if (getTimeHour(toDate) <= 23) { //7 PM 11 PM
				if (getTimeHour(fromDate) > getTimeHour(toDate)) {//if loops 1 day
					dayCount += 1;
					nightCount += 1;
				}
			}
		}

		dayCount += dayDiff(fromDate, toDate); //add day interval
		nightCount += dayDiff(fromDate, toDate); //add day interval

		setDatePrice(dayCount, nightCount); //sets the price to the tag according to the date.
 	}

	updatePrice(); //updates the price.
}

/*
Returns difference between days
Original Link: http://stackoverflow.com/questions/3224834/get-difference-between-2-dates-in-javascript
*/
function dayDiff (fromDate, toDate) {
	var date1 = new Date(fromDate);
	var date2 = new Date(toDate);
	var timeDiff = Math.abs(date2.getTime() - date1.getTime());
	console.log("Day Difference: " + Math.floor(timeDiff / (1000 * 3600 * 24)));
	return Math.floor(timeDiff / (1000 * 3600 * 24));
}

/*
Gets the hours of the time string. (input type=datetime-local)
*/
function getTimeHour (timeString) {
	var timeIndex = parseInt(timeString.indexOf("T"));
	console.log("Time Hour: " + parseInt(timeString.substring(timeIndex + 1, timeIndex + 3)));
	return parseInt(timeString.substring(timeIndex + 1, timeIndex + 3));
}

/*
Gets the day value from the time string. (input type=datetime-local)
*/
function getDayDate (dateString) {
	var dateIndex = parseInt(dateString.indexOf("T"));
	return parseInt(dateString.substring(dateIndex - 2, dateIndex));
}

/*
Computes the day and night price properly.
*/
function setDatePrice(dayCount, nightCount){
	var priceTemp = roundOff((dayCount * roomDayPrice) + 
					(nightCount * roomNightPrice));
	console.log(priceTemp);
	if(checkIn.value != "" && checkOut.value != "") {
		if(roomChosen == "Supreme Room") { //check if supreme room
			priceTemp = SUPREME_PRICE_DAY_NIGHT * dayDiff(checkIn.value, checkOut.value);
			priceTemp = roundOff(priceTemp + parseInt(SUPREME_PRICE_DAY_NIGHT)); //add 4000 initially
		}

		datePrice.value = priceTemp + " PHP";
		roomTotalPrice = priceTemp;
	}	
}

/*
When the user clicks or changes the adult count (input)
*/
function adultChange(){
	updateTotalPerson(adult);
}

/*
When the user clicks or changes the child count (input)
*/
function childChange(){
	updateTotalPerson(child);
}

/*
computes the total person and total price of the input (adult and child)
*/
function updateTotalPerson(obj){
	if(isNaN(obj.value) || 
		obj.value.length > 2) { //to avoid NaN value or null and 3 digit number
		obj.value = obj.value.substring(0, obj.value.length - 1); //delete last input character
		return;
	}

	if (obj.value == "") { //check if null or value is empty
		obj.value = 0; //put 0 if it is empty.
		return;
	}

	var totalTemp = parseInt(adult.value) + parseInt(child.value); //compute the total person

	if (totalTemp > maxPersonLimit) {
		limitText.innerHTML = " Maximum of " + maxPersonLimit + " persons is allowed " +
					" on " + roomChosen;

		showAlert();

		if(obj == adult) {
			console.log("ADULT");
			adult.value = maxPersonLimit - parseInt(child.value);
		}else {
			console.log("CHILD");
			child.value = maxPersonLimit - parseInt(adult.value);			
		}
	}

	var totalPersons = parseInt(adult.value) + parseInt(child.value);
	totalperson.innerHTML = "<b>" + totalPersons + "</b> Person (s)";
	updatePrice(); //updates the price.
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
Determines and computes the total price from the input.
(adult, child, room type).
*/
function updatePrice(){
	var adultPrice = parseInt(adult.value) * ADULT_PRICE;
	var childPrice = parseInt(child.value) * CHILD_PRICE;
	var roomPrice = parseInt(roomTotalPrice);
	totalprice.innerHTML = "<b>" + roundOff(adultPrice + childPrice + roomPrice) + "</b> PHP";
}

/*
Rounds off the number to 2 decimal places.
@param num {Integer} number to be rounded off.
*/
function roundOff(num){
  return parseFloat(Math.round(num * 100) / 100).toFixed(2);
}

/*
Change the selected room to be reserve according to
the clicked link (cottage, regular, grande, supreme)
*/
function changeSelRoom (selRoom) {
	switch (selRoom) {
		case COTTAGE:
			console.log("COTTAGE Selected");
			roomtype.selectedIndex = COTTAGE;
			break;
		case REGULAR_ROOM:
			console.log("REGULAR_ROOM Selected");
			roomtype.selectedIndex = REGULAR_ROOM;
			break;
		case GRANDE_ROOM:
			console.log("GRANDE_ROOM Selected");
			roomtype.selectedIndex = GRANDE_ROOM;
			break;
		case SUPREME_ROOM:
			console.log("SUPREME_ROOM Selected");
			roomtype.selectedIndex = SUPREME_ROOM;
			break;
	}

	roomTypeChange();
}

/*
Scroll Animation to the destination of the link clicked on the same page. (GOOGLE)
*/
$(".roomInfo p a[href^='#'], #nav ul li a[href^='#'], #link-effect a[href^='#'], #scroll-effect a[href^='#']")
			.on('click', function(e) {
	// prevent default anchor click behavior
   	e.preventDefault();

   	// store hash
   	var hash = this.hash;

   	// animate
   	$('html, body').animate({
       scrollTop: $(hash).offset().top
    }, 500, function(){

   	// when done, add hash to url
   	// (default click behaviour)
   	window.location.hash = hash;
	});
});