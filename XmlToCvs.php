<?php

$input = "CurrencyRate.xml";

$xml = new SimpleXMLElement('https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml?5105e8233f9433cf70ac379d6ccc5775', 0, TRUE);

$parse = new XmlToCsv;
$fields = $parse->parsing($xml);
$csv = $parse->generate_csv($fields, $parse->showDate($xml));

class XmlToCsv {

    public function parsing($xml_source) {

        $fields[0]['currency_code'] = 'Currency Code';
        $fields[0]['rate'] = 'Rate';

        $n = 1;

        foreach ($xml_source->children()->children()->children()->Cube as $child) {

            $currency = (string)$child['currency'];
            $rate = (string)$child['rate'];

            $fields[$n]['currency_code'] = $currency;
            $fields[$n]['rate'] = $rate;
            $n++;

        }
        return $fields;
    }

    public function showDate($xml_source){
        $date = "";
        foreach ($xml_source->children()->children() as $time){
            $date = $time['time'];
        }
        return $date;
    }

    function generate_csv($data, $date) {

        $path = "usd_currency_rates_".$date.".csv";

        $csv = fopen($path, 'w');
        foreach ($data as $field) {
            fputcsv($csv, $field, ';');
        }

        fclose($csv);

        return $csv;
    }
}


?>