function confirm_delete(item){
	return confirm('Are you sure you want to delete ' + item + '?');
}

function incrementValue(index){
	var price = parseInt(document.getElementById('price_'+index).innerText, 10);
	var total = parseInt(document.getElementById('total_'+index).innerText, 10);
	var grand_total = parseInt(document.getElementById('grand_total').innerText, 10);
	// console.log(price, total);
	total += price;
	grand_total += price;
	document.getElementById('total_' + index).innerText = total;
	document.getElementById('grand_total').innerText = grand_total;
}

function decrementValue(index){
	var price = parseInt(document.getElementById('price_'+index).innerText, 10);
	var total = parseInt(document.getElementById('total_'+index).innerText, 10);
	var grand_total = parseInt(document.getElementById('grand_total').innerText, 10);
	total -= price;
	if(total < 0){
		total = 0;
	}
	else{
		grand_total -= price;
	}
	document.getElementById('total_' + index).innerText = total;
	document.getElementById('grand_total').innerText = grand_total;
}

function calculateTotal(index, count){
	var price = parseInt(document.getElementById('price_'+index).innerText, 10);
	var quantity = parseInt(document.getElementById('qty_'+index).value, 10);
	var total = price * quantity;
	var sub_total = 0;
	var grand_total = 0;
	// grand_total -= total;
	document.getElementById('total_' + index).innerText = total;
	for(i=0; i<count; i++){
		sub_total += parseInt(document.getElementById('total_'+i).innerText, 10);
	}
	grand_total = sub_total + parseInt(document.getElementById('delivery_fee').innerText, 10);
	document.getElementById('sub_total').innerText = sub_total;
	document.getElementById('grand_total').innerText = grand_total;
	// alert("Received this: "+index+","+count);
}

// Textbox checking for managefoodorder.php
function EnableDisableTextBox() {sltcartid
	var chkYes_2 = document.getElementById(SEARCH_TYPE_RESTAURANT_ID);
	var chkYes_3 = document.getElementById(SEARCH_TYPE_FOOD_ORDER_ID);
	var chkYes_4 = document.getElementById(SEARCH_TYPE_CART_ID);
	var sltrestaurantid = document.getElementById("sltrestaurantid");
	var sltfoodorderid = document.getElementById("sltfoodorderid");
	var sltcartid = document.getElementById("sltcartid");
	sltrestaurantid.disabled = chkYes_2.checked ? false : true;
	sltfoodorderid.disabled = chkYes_3.checked ? false : true;
	sltcartid.disabled = chkYes_4.checked ? false : true;
	// if (!sltfoodorderid.disabled) {
	// 	sltfoodorderid.focus();
	// }
}

function removefromcart(index){
	var xml = new XMLHttpRequest();
	xml.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			alert(this.responseText);
		}
	}
	xml.open("GET", "removefromcart.php?idx="+index, true);
	xml.send();
}

function updateStatus(cartID, mode){
	// console.log(cartID, mode);
	var xml = new XMLHttpRequest();
	xml.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			alert(this.responseText);
		}
	}
	xml.open("GET", "updatestatus.php?cartID="+cartID+"&mode="+mode, true);
	xml.send();
}

function reloadMap(){
	var latitude = document.getElementById('latitude').value;
	var longitude = document.getElementById('longitude').value;
	var iframe = document.getElementById('map');
	iframe.removeAttribute("hidden");
	// iframe.src = iframe.src;
	iframe.src = "https://maps.google.com/maps?q="+latitude+","+longitude+"&z=18&output=embed"
	// alert("latitude is "+latitude+", longitude is "+longitude);
	alert("Map reloaded!!!")
}

function getCurrentLocation(){
	var latitude = document.getElementById('latitude');
	var longitude = document.getElementById('longitude');
	if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
		alert("Location Acquired !");
  } else { 
		alert("Geolocation is not supported by this browser !");
  }

	function showPosition(position) {
		latitude.value = position.coords.latitude;
		longitude.value = position.coords.longitude;
		var iframe = document.getElementById('map');
		iframe.removeAttribute("hidden");
		iframe.src = "https://maps.google.com/maps?q="+position.coords.latitude+","+position.coords.longitude+"&z=18&output=embed"
	}
}
