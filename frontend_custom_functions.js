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

function calculateTotal(index){
	var price = parseInt(document.getElementById('price_'+index).innerText, 10);
	var quantity = parseInt(document.getElementById('qty_'+index).value, 10);
	total = price * quantity;
	// grand_total -= total;
	document.getElementById('total_' + index).innerText = total;
	// document.getElementById('grand_total').innerText = grand_total;
}
