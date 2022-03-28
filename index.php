<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>LAB 4</title>

        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="../jqwidgets/jqwidgets/styles/jqx.base.css">
        <link rel="stylesheet" href="default.css">

        <script type="text/javascript" src="../jqwidgets/scripts/jquery-1.12.4.min.js"></script>

        <script type="text/javascript" src="script.js"></script>
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
    </head>

    <nav>
        <div class="w3-container w3-bar w3-light-grey">
            <a href="index.php" class="w3-bar-item w3-button">Remise à zéro</a>
        </div>
    </nav>

    <body style="margin-left: 10%; margin-right: 10%">
        <?php
        require_once 'classThermistor.php';

        $obj1 = new Thermistor();
        ?>


        <div id='content'>
            <form style="width: 550px; float: left">

                <div class="lineContainer">
                    <span>VCC</span>
                    <div class="inputContainer" id='VCC'></div>
                </div>

                <div class="lineContainer">
                    <span>RDIV</span>
                    <div class="inputContainer" id='RDIV'></div>
                </div>

                <div class="lineContainer">
                    <span>VADC</span>
                    <div class="inputContainer" id='VADC' style="float: right"></div>
                </div>

                <div id="UnitList" class="lineContainter">
                    <div class="cUnit" id="UNIT_C">Celsius</div>
                    <div class="cUnit" id="UNIT_F">Fahraneint</div>
                    <div class="cUnit" id="UNIT_K">Kelvin</div>
                </div>
            </form>
            <div id="tempGauge" style="float: left; padding: 10px"></div>
        </div>
    </body>

</html>
