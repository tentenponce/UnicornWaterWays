var loveBtn = document.getElementsByClassName("love-button");

function processLove(index, clientID) {
	var xmlhttp = new XMLHttpRequest();

	xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var isLove = parseInt(xmlhttp.responseText);

            if (isLove == 0) {
            	loveBtn[index].className = loveBtn[index].className.replace
     			 ( /(?:^|\s)btn-danger(?!\S)/g , '');
     			 loveBtn[index].className += " btn-default";
            } else if (isLove == 1){
            	loveBtn[index].className = loveBtn[index].className.replace
     			 ( /(?:^|\s)btn-default(?!\S)/g , '');
     			 loveBtn[index].className += " btn-danger";
            }
        }
    };

    xmlhttp.open("GET", "getlove.php?clientID=" + clientID, true);
    xmlhttp.send();
}