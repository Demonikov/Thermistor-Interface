var AJAX_VCC = 5;
var AJAX_RDIV = 10000;
var AJAX_VADC = 2.5;
var AJAX_UNIT = "C";

function convertUnit(temp, sendUnit, getUnit)
{
	if (sendUnit == getUnit)
		return temp;

	switch (sendUnit){
	case "C":
		if (getUnit == "F")
			temp = (temp * 1.8) + 32;
		else if(getUnit == "K")
			temp += 273.15;
		break;
	case "F":
		if (getUnit == "C")
			temp = (5 * (temp - 32)) / 9;
		else if(getUnit == "K")
			temp = (temp + 459.67) / 1.8;
		break;
	case "K":
		if (getUnit == "C")
			temp -= 273.15;
		else if(getUnit == "F")
			temp = temp * 1.8 - 459.67;
		break;
	};
	
	return temp;
}

function reset(){
	$.ajax({
		url	:	"script/resetTable.php",
		data	:	{'DB': "schema", 'TABLE':"temperature"},
	});
}

function getLastTemp(){
	$.getJSON(
		"script/lastTemp.php",
		{'DB':"schema", 'NUM':1, 'TABLE':'temperature'},
		function(result){
			$('#tempGauge').jqxGauge({
				caption: {value: result[0].Temperature + '°' + AJAX_UNIT},
				value: result[0].Temperature,
			});
	});
}

function sendParameters(){
	$.ajax({
		url 	: 	"script/sendData.php",
		data 	: 	{'VCC': AJAX_VCC, 'VADC': AJAX_VADC, 'RDIV': AJAX_RDIV, 'UNIT': AJAX_UNIT},
		success :	getLastTemp(),
	});
}


var intervalId = window.setInterval(function(){
	sendParameters();
}, 500)

var graphUpdate = window.setInterval(function(){
	$.getJSON(
		"script/lastTemp.php",
		{'DB':"schema", 'NUM':$('#sampleNumber').jqxSlider('val'), 'TABLE':'temperature'},
		function(json) {
			for (let i = 0; i < json.length; i++){
				json[i].Temperature = convertUnit(json[i].Temperature, json[i].Unit, AJAX_UNIT);
				json[i].Unit = AJAX_UNIT;
			}
			
			$('#tempChart').jqxChart({
				source: json
			});
		});
}, 2000)

$(document).ready(function () {
	// Objects creation
	$('#VADC').jqxSlider({
		width: '200px',
		tooltip: true,
		min: 0.01, max: AJAX_VCC,
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
	$('#tempChart').jqxChart({
		title: "Graphique des températures",
		description: "Compilation des dernières mesures",
		//source: AJAX_RESULT,
		categoryAxis: { dataField: 'Time' },
		colorScheme: 'scheme01',
		seriesGroups:
		[{
		            type: 'line',
		            columnsGapPercent: 30,
		            seriesGapPercent: 0,
		            series: [{ dataField: 'Temperature' }]
		}]
	});
	
	$('#sampleNumber').jqxSlider({
		width: '200px',
		tooltip: true,
		min: 3, max: 30,
		mode: "fixed",
		ticksFrequency: 1,
		value: 5
	});

	/**************************************
	 * Function déclenché par interaction *
	 **************************************/
	
	$('#VADC').on('change', function (event) {
		AJAX_VADC = event.args.value;
	});

	// bind to 'select' event.
	$('#VCC').bind('select', function () {
		var vcc = $('#VCC').val();

		if ($('#VADC').val() > vcc) {
			$('#VADC').jqxSlider({ value: vcc });
		}

		$('#VADC').jqxSlider({ max: vcc });
		AJAX_VCC = vcc;
	});

	$('#RDIV').on('valueChanged', function () {
		AJAX_RDIV = $('#RDIV').val();
	});

	$('.cUnit').on('checked', function () {

		if ($('#UNIT_F').jqxRadioButton('checked')) {
			AJAX_UNIT = "F";
			var gMax = 230;
			var gMin = -22;
		} else if ($('#UNIT_K').jqxRadioButton('checked')) {
			AJAX_UNIT = "K";
			var gMax = 383.15;
			var gMin = 243.15;
		} else {
			AJAX_UNIT = "C";
			var gMax = 110;
			var gMin = -30;
		}

		// For gauge color strokes
		var gRange = gMax - gMin;
		var stop1 = gRange / 1.56 + gMin;
		var stop2 = gRange / 2.33 + gMin;
		var stop3 = gRange / 7 + gMin;

		sendParameters();
		
		$('#tempChart').jqxChart({
			valueAxis: {
				minValue: gMin,
				maxValue: gMax
			}
		});
		
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
