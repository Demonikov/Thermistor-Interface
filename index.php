<!DOCTYPE html>

<html>
<head>
	<meta charset="UTF-8">
	<title>Thermistor Simulation</title>

	<script type="text/javascript" src="../jqwidgets/scripts/jquery-1.12.4.min.js"></script>
	
	<link rel="stylesheet" href="w3.css">
	<link rel="stylesheet" href="../jqwidgets/jqwidgets/styles/jqx.base.css">
	<link rel="stylesheet" href="default.css">

	<script type="text/javascript" src="../jqwidgets/jqwidgets/jqxcore.js"></script>
	<script type="text/javascript" src="../jqwidgets/jqwidgets/jqxbuttons.js"></script>
	<script type="text/javascript" src="../jqwidgets/jqwidgets/jqxslider.js"></script>
	<script type="text/javascript" src="../jqwidgets/jqwidgets/jqxdropdownlist.js"></script>
	<script type="text/javascript" src="../jqwidgets/jqwidgets/jqxscrollbar.js"></script>
	<script type="text/javascript" src="../jqwidgets/jqwidgets/jqxlistbox.js"></script>
	<script type="text/javascript" src="../jqwidgets/jqwidgets/jqxcombobox.js"></script>
	<script type="text/javascript" src="../jqwidgets/jqwidgets/jqxnumberinput.js"></script>
	<script type="text/javascript" src="../jqwidgets/jqwidgets/jqxradiobutton.js"></script>
	<script type="text/javascript" src="../jqwidgets/jqwidgets/jqxgauge.js"></script>
	<script type="text/javascript" src="../jqwidgets/jqwidgets/jqxchart.js"></script>
	<script type="text/javascript" src="../jqwidgets/jqwidgets/jqxdraw.js"></script>
	<script type="text/javascript" src="../jqwidgets/jqwidgets/jqxdata.js"></script>

	<script type="text/javascript" src="script.js"></script>
</head>

<nav>
	<div class="w3-container w3-bar w3-light-grey">
		<input type="button" class="w3-bar-item w3-button" value="Remise à zéro" onclick="reset()">
		<input type="button" class="w3-bar-item w3-button" value="Affichage" onclick="changeDisplay()">
	</div>
</nav>

<body style="margin:0 5%">
	<div id='content'>
		<div id=inputSection>
			<div class="lineContainer">
				<span>Thermostat: </span>
				<span id="targeted"></span>
				<div class="inputContainer" id='target' style="float: right"></div>
			</div>
			<div class="lineContainer">
				<span>State: </span>
				<label class="switch">
					<input type="checkbox" id="ThermState" style="float: right">
					<span class="slider round"></span>
				</label>
			</div>
			<div id="UnitList" class="lineContainter">
				<div class="cUnit" id="UNIT_C">Celsius</div>
				<div class="cUnit" id="UNIT_F">Fahraneint</div>
				<div class="cUnit" id="UNIT_K">Kelvin</div>
			</div>
			<div class="lineContainer">
				<span>Nombre d'échantillon: </span>
				<span id="sampleCount"></span>
				<div class="inputContainer" id="sampleNumber" style="float: right"></div>
			</div>
		</div>
		<div id="tempGauge"></div>
		<div id="tempChart"></div>

	</div>
</body>
</html>
