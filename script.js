var AJAX_VCC;
var AJAX_UNIT;
var tMin = -30;
var tMax = 110; 

// Convert temperature from one unit to an other
function convertUnit(temp, sendUnit, getUnit)
{
	temp = parseFloat(temp);

	switch (sendUnit){
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
			temp = (temp * 1.8) - 459.67;
		break;
	default:
		if (getUnit == "F")
			temp = (temp * 1.8) + 32;
		else if(getUnit == "K"){
			temp += 273.15;
		}
		break;
	};
	return temp.toFixed(2);
}

// Reset all used table
function reset(){
	$.ajax({
		url	:	"script/resetTable.php",
		data	:	{'DB': "schema"},
	});
}

// Update Gauge
function getCurrentTemp(){
	$.getJSON(
		"script/lastTemp.php",
		{'DB':"schema", 'NUM':1, 'TABLE':'adc'},
		function(result){
			$('#tempGauge').jqxGauge({
				caption: {value: convertUnit(result[0].Temperature, "C", AJAX_UNIT) + '°' + AJAX_UNIT},
				value: convertUnit(result[0].Temperature, "C", AJAX_UNIT),
			});
	});

}

// Update Chart
function updateChart(){
	$.getJSON(
		"script/lastTemp.php",
		{'DB':"schema", 'NUM':$('#sampleNumber').jqxSlider('val'), 'TABLE':'adc'},
		function(json) {
			for (let i = 0; i < json.length; i++){
				json[i].Temperature = convertUnit(json[i].Temperature, json[i].Unit, AJAX_UNIT);
				json[i].Unit = AJAX_UNIT;
			}
			
			$('#tempChart').jqxChart({
				source: json
			});
	});
}

// Write temperature and targeted temperature to their respective database
// Also perform gauge update and check if target temperature as been reach
var LiveFeed = window.setInterval(function(){
	$.ajax({
		url 	: 	"script/sendData.php",
		data 	: 	{'DB':"schema", 'TARGET':$('#target').jqxSlider('val'), 'TARGET_STATE': $("#ThermSlider").is(":checked")},
	});

	if ( $("#ThermSlider").is(":checked") )
		$("#ThermState").html("État: Actif");
	else
		$("#ThermState").html("État: Désactivé");

	getCurrentTemp();
}, 500)

// Chart update interval
var intervalChart = window.setInterval(function(){
	updateChart();
}, 2000)

$(document).ready(function () {
	// Unit radios dimensions
	$(".cUnit").jqxRadioButton({ width: 140, height: 25 });

	$("#tempGauge").jqxGauge();
	$("#tempChart").jqxChart({
		title: "Températures",
		description: "Compilation des dernières mesures",
		categoryAxis: { dataField: 'Time' },
		seriesGroups:
		[{
		            type: 'line',
		            series: [{ dataField: 'Temperature' }]
		}]
	});
	
	$('#target').jqxSlider({
		width: '200px',
		min: tMin, max: tMax,
		mode: "fixed",
		ticksFrequency: 1,
		value: 21
	});

	$("#target").on('change', function(event) {
		$("#targeted").html( event.args.value + "°C");
	});

	$("#sampleNumber").jqxSlider({
		width: '200px',
		min: 3, max: 30,
		mode: "fixed",
		ticksFrequency: 1,
		value: 5
	});

	$("#sampleNumber").on('change', function(event) {
		$("#sampleCount").html( event.args.value );
	});
	
	$('.cUnit').on('checked', function () {

		if ($('#UNIT_F').jqxRadioButton('checked')) {
			AJAX_UNIT = "F";
			tMax = 230;
			tMin = -22;
		} else if ($('#UNIT_K').jqxRadioButton('checked')) {
			AJAX_UNIT = "K";
			tMax = 383.15;
			tMin = 243.15;
		} else {
			AJAX_UNIT = "C";
			tMax = 110;
			tMin = -30;
		}

		// For gauge color strokes
		var gRange = tMax - tMin;
		var stop1 = gRange / 1.56 + tMin;
		var stop2 = gRange / 2.33 + tMin;
		var stop3 = gRange / 7 + tMin;
		
		$('#tempGauge').jqxGauge({
			ranges: [{startValue: tMin, endValue: stop3 - 1, style: {fill: '#00bbff', stroke: '#00bbff'}, endWidth: 8, startWidth: 15, startDistance: 10, endDistance: 10},
				{startValue: stop3, endValue: stop2 - 1, style: {fill: '#2ab315', stroke: '#2ab315'}, endWidth: 8, startWidth: 8, startDistance: 10, endDistance: 10},
				{startValue: stop2, endValue: stop1 - 1, style: {fill: '#ffd000', stroke: '#ffd000'}, endWidth: 15, startWidth: 8, startDistance: 10, endDistance: 10},
				{startValue: stop1, endValue: tMax - 1, style: {fill: '#ff0000', stroke: '#ff0000'}, endWidth: 20, startWidth: 15, startDistance: 10, endDistance: 10}],
			min: tMin, max: tMax
		});

		updateChart();
	});

	$('#UNIT_C').jqxRadioButton({checked: true});
	$("#sampleCount").html( $('#sampleNumber').jqxSlider('val') );
	$("#targeted").html( $('#target').jqxSlider('val') + "°C");
});
