<?php
// is handig voor als je iets moet debuggen
// staat niet in klassen-diagram omdat het geen officiële onderdeel is van de applicatie
function debug($var)
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}
