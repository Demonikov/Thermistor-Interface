var AJAX_VCC = 5;
var AJAX_RDIV = 10000;
var AJAX_VADC = 2.5;
var AJAX_UNIT = 'C';

function reset(){
	$.ajax({
		url: "resetTable.php",
		data: {'DB': "schema"}
	});
}

function changeDisplay(){
	$.getJSON("lastTemp.php", {'DB': "schema"}, function(json) {
		$('#tempGauge').jqxGauge({
			caption: {value: json.Temperature + '°' + json.Unit},
			value: json.Temperature
				});
	});
}


var intervalId = window.setInterval(function(){
	$.ajax({
			url: "ajax.php",
			data: {'VCC': AJAX_VCC, 'VADC': AJAX_VADC, 'RDIV': AJAX_RDIV, 'UNIT': AJAX_UNIT}

		});
	changeDisplay();
}, 5000)

$(document).ready(function () {
	// Objects creation
	$('#VADC').jqxSlider({
		width: '200px',
		tooltip: true,
		min: 0.01, max: AJAX_VCC,
		//mode: "fixed",
		//ticksFrequency: 0.001,
		value: 2.5
	});

	// VCC
	$("#VCC").jqxComboBox({
		autoComplete: true,
		source: [3.3, 5, 10, 12, 24],
		selectedIndex: 1,
		dropDownHeight: 120,
		width: '200px'
	});

	// RDIV
	$('#RDIV').jqxNumberInput({
		//unit: "ohm",
		min: 100, max: 100000,
		decimal: AJAX_RDIV
	});

	// Unit radios
	$('.cUnit').jqxRadioButton({ width: 140, height: 25 });

	$('#tempGauge').jqxGauge({ value: 25 });
	$('tempChart').jqxChart(settings)
	
	/* Function déclenché par interaction */
	$('#VADC').on('change', function (event) {
		AJAX_VADC = event.args.value;
	});

	// bind to 'select' event.
	$('#VCC').bind('select', function () {
		var vcc = $('#VCC').val();

		if ($('#VADC').val() > vcc) {
			$('#VADC').jqxSlider({value: vcc});
		}

		$('#VADC').jqxSlider({max: vcc});
		AJAX_VCC = vcc;
	});

	$('#RDIV').on('valueChanged', function () {
		AJAX_RDIV = $('#RDIV').val();
	});

	$('.cUnit').on('checked', function () {
		var gMax = 110;
		var gMin = -30;

		if ($('#UNIT_F').jqxRadioButton('checked')) {
			gMax = 230;
			gMin = -22;
			AJAX_UNIT = 'F';
		} else if ($('#UNIT_K').jqxRadioButton('checked')) {
			gMax = 383.15;
			gMin = 243.15;
			AJAX_UNIT = 'K';
		} else {
			AJAX_UNIT = 'C';
		}

		// For gauge color strokes
		var gRange = gMax - gMin;
		var stop1 = gRange / 1.56 + gMin;
		var stop2 = gRange / 2.33 + gMin;
		var stop3 = gRange / 7 + gMin;

		$('#tempGauge').jqxGauge({
			ranges: [{startValue: gMin, endValue: stop3 - 1, style: {fill: '#00bbff', stroke: '#00bbff'}, endWidth: 8, startWidth: 15, startDistance: 10, endDistance: 10},
				{startValue: stop3, endValue: stop2 - 1, style: {fill: '#2ab315', stroke: '#2ab315'}, endWidth: 8, startWidth: 8, startDistance: 10, endDistance: 10},
				{startValue: stop2, endValue: stop1 - 1, style: {fill: '#ffd000', stroke: '#ffd000'}, endWidth: 15, startWidth: 8, startDistance: 10, endDistance: 10},
				{startValue: stop1, endValue: gMax - 1, style: {fill: '#ff0000', stroke: '#ff0000'}, endWidth: 20, startWidth: 15, startDistance: 10, endDistance: 10}],
			min: gMin, max: gMax
		});
	});
	

	$('#UNIT_C').jqxRadioButton({checked: true});
});
