function incrementValue(index, price){
	var value = parseInt(document.getElementById('qty_'+index).value, 10);
	value = isNaN(value) ? 0 : value;
	value++;
	// console.log(value);
	total = price * value;
	document.getElementById('total_' + index).innerText = total;
}

function decrementValue(index, price){
	var value = parseInt(document.getElementById('qty_'+index).value, 10);
	value = isNaN(value) ? 0 : value;
	value--;
	if(value < 0){
		value = 0;
	}
	// console.log(value);
	total = price * value;
	document.getElementById('total_' + index).innerText = total;
}

function calculateTotal(index, price){
	var value = parseInt(document.getElementById('qty_'+index).value, 10);
	total = price * value;
	document.getElementById('total_' + index).innerText = total;
}
