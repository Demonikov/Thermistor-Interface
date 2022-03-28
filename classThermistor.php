<?php

/**
 * Description of classThermistor
 *
 * @author Vincent D.
 */
class Thermistor {

    protected $VCC = 5;
    protected $Rdiv = 10000;
    protected $Vadc = 2.5;
    protected $Temp;
    // Unité accepté: C, F, K
    protected $Unit = "C";
    private $tblConvert = array(array(111.3, -30.0),
        array(86.39, -25), array(67.74, -20), array(53.39, -15),
        array(42.45, -10), array(33.89, -5), array(27.28, 0),
        array(22.05, 5), array(17.96, 10), array(14.68, 15),
        array(12.09, 20), array(10, 25), array(8.313, 30),
        array(6.941, 35), array(5.828, 40), array(4.912, 45),
        array(4.161, 50), array(3.537, 55), array(3.021, 60),
        array(2.589, 65), array(2.229, 70), array(1.924, 75),
        array(1.669, 80), array(1.451, 85), array(1.266, 90),
        array(1.108, 95), array(0.9735, 100), array(0.8574, 105),
        array(0.7579, 110));

    function __construct() {
        $this->Unit = $_GET['UNIT'];
        if (filter_input(INPUT_GET, 'VCC', FILTER_VALIDATE_FLOAT)) {
            $this->VCC = $_GET['VCC'];
        }
        if (filter_input(INPUT_GET, 'RDIV', FILTER_VALIDATE_FLOAT)) {
            $this->Rdiv = $_GET['RDIV'];
        }
        if (filter_input(INPUT_GET, 'VADC', FILTER_VALIDATE_FLOAT)) {
            $this->Vadc = $_GET['VADC'];
        }
        if (filter_input(INPUT_GET, 'UNIT', FILTER_VALIDATE_TEXT)) {
            alert("PHPUNIT" + $this->Unit);
            $this->Unit = $_GET['UNIT'];
        }

        $this->calcTemp();
    }

    // Valeur des composantes et température
    public function printPars() {
        echo '<pre>';
        printf("Vcc:\t %.2f V<br>", $this->VCC);
        printf("Rdiv:\t %d Ohms<br>", $this->Rdiv);
        printf("Vadc:\t %.2f V<br><br>", $this->Vadc);

        printf("Température:\t %.2f°", $this->getTemp());
        printf('%s', $this->Unit);
        echo '</pre>';
    }

    // Estime la température capté par le transistor selon les équivalences Rt / T connues
    public function calcTemp() {
        $trueRt = ($this->Vadc * $this->Rdiv) / ($this->VCC - $this->Vadc) / 1000;
        if ($trueRt > 111) {
            $this->Temp = -30;
            return;
        } else if ($trueRt < 0.76) {
            $this->Temp = 110;
            return;
        }


        // On situe Rt par rapport a la table des valeurs connues
        for ($i = 0; $i < 28; $i++) {
            if ($trueRt < $this->tblConvert[$i][0]) {
                continue;
            } else {
                // Calcul de pente: delta Y / delta X
                $M = ( ($this->tblConvert[$i][1] - $this->tblConvert[$i - 1][1]) /
                        ($this->tblConvert[$i][0] - $this->tblConvert[$i - 1][0]) );

                // Calcul de la base: y lorsque x @ 0
                $B = ($this->tblConvert[$i][1] - ($M * $this->tblConvert[$i][0]));

                // Variable calculés
                /*
                  echo '<pre>';
                  printf("Rt:\t %.2f K<br>", $trueRt);
                  printf("Pente:\t%.2f<br>", $M);
                  printf("Base:\t %.2f<br>", $B);
                  echo '</pre>';
                 */

                $this->Temp = $M * $trueRt + $B;
                return;
            }
        }
    }

    // Retourne la température dans l'unité configuré
    public function getTemp() {
        // En kelvin
        if ($this->Unit == 'K') {
            $this->Temp += 273.15;
        }
        // En fahrenheit
        else if ($this->Unit == 'F') {
            $this->Temp = ($this->Temp * 9 / 5) + 32;
        }
        // En celsius
        return $this->Temp;
    }

    /*
      public function setVCC($v) {
      alert("AHH");
      $this->VCC = $v;
      //calcTemp();
      }
      public function getVCC() {
      return $this->VCC;
      }
      public function getRdiv() {
      return $this->Rdiv;
      }
      public function getVadc() {
      return $this->Vadc;
      } */
}