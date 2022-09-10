function confirm_delete(item){
	return confirm('Are you sure you want to delete ' + item);
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

function reloadMap(){
	var latitude = document.getElementById('latitude').value;
	var longitude = document.getElementById('longitude').value;
	var iframe = document.getElementById('map');
	iframe.removeAttribute("hidden");
	// iframe.src = iframe.src;
	iframe.src = "https://www.google.com/maps/embed/v1/view?key=AIzaSyAC7Rj163G5vNrR5_1AEncatw3OHcjTock&center="+latitude+","+longitude+"&zoom=18"
	// alert("latitude is "+latitude+", longitude is "+longitude);
	alert("Map reloaded!!!")
}