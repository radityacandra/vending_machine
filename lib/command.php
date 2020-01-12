<?php

function check_change($stock_10, $stock_100){
    $string = "[Change]\t\t10 JPY\t";

    $available_change = "Change";
    if ($stock_10 == false) {
        $available_change = "No Change";
    }

    $string .= $available_change . PHP_EOL;
    $string .= "\t\t\t100 JPY\t";

    $available_change = "Change";
    if ($stock_100 == false) {
        $available_change = "No Change";
    }

    $string .= $available_change . PHP_EOL;

    return $string;
}

function print_items($items, $input_amount = 0){
    $string = "[Items For Sale]\t";

    if (!empty($items)) {
        $first = true;
        foreach ($items as $index => $item) {
            $availability = "";
            if ($item['price'] <= $input_amount) $availability = "Available for purchase";
            if ($item['stock'] == 0) $availability = "Sold out";

            if (!$first) $string .= "\t\t\t";

            $string .= "$item[id]. $item[name]\t\t$item[price] JPY\t$availability" . PHP_EOL;

            $first = false;
        }
    } else {
        $string .= "Empty" . PHP_EOL;
    }

    return $string;
}

function input_amount($amount){
    $string = "[Input amount]\t\t$amount JPY" . PHP_EOL;

    return $string;
}

function return_gate($change){
    $string = "[Return Gate]\t\t";

    $return = "Empty" . PHP_EOL;
    if ($change['coin_10'] != 0 || $change['coin_100'] != 0 || $change['coin_50'] != 0 || $change['coin_500'] != 0) {
        $return = "";
        $first = true;
        for ($i=0; $i < $change['coin_10']; $i++) {
            if (!$first) $return .= "\t\t\t";
            $return .= "10 JPY" . PHP_EOL;

            $first = false;
        }

        for ($i=0; $i < $change['coin_100']; $i++) {
            if (!$first) $return .= "\t\t\t";
            $return .= "100 JPY" . PHP_EOL;

            $first = false;
        }

        // for refund purpose
        if (isset($change['coin_50'])) {
            for ($i=0; $i < $change['coin_50']; $i++) {
                if (!$first) $return .= "\t\t\t";
                $return .= "50 JPY" . PHP_EOL;

                $first = false;
            }
        }

        if (isset($change['coin_500'])) {
            for ($i=0; $i < $change['coin_500']; $i++) {
                if (!$first) $return .= "\t\t\t";
                $return .= "500 JPY" . PHP_EOL;

                $first = false;
            }
        }
    }

    $string .= $return;

    return $string;
}

function display_outlet($purchased_item){
    $string = "[Outlet]\t\t";

    $purchased = "Empty" . PHP_EOL;
    if (!empty($purchased_item)) {
        $purchased = "";
        $first = true;
        foreach ($purchased_item as $item) {
            if (!$first) {
                $purchased .= "\t\t\t";
            }

            $purchased .= "$item[name]" . PHP_EOL;

            $first = false;
        }
    }

    $string .= $purchased;

    return $string;
}