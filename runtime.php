<?php

require 'vending_machine.php';
require 'lib/command.php';
require 'data/items.php';

$obj_vending = new VendingMachine();

// setup phase
// reading database item
foreach ($items as $item) {
    $obj_vending->set_item($item);
}

$obj_vending->set_coin_10(11);
$obj_vending->set_coin_100(6);

$input_amount = 0;
$purchased_item = array();
$return = $obj_vending->get_return();

// runtime phase
system('clear');
echo input_amount($input_amount);
echo check_change($obj_vending->check_coin_10(), $obj_vending->check_coin_100());
echo return_gate($return);
echo print_items($obj_vending->get_items());
echo display_outlet($purchased_item);

$handle = fopen ("php://stdin","r");
$reset_return = false;
while (true) {
    $instruction = fgets($handle);

    $instruction = explode(" ", $instruction);

    if (trim($instruction[0]) == "1") {
        if ($reset_return) {
            $return = $obj_vending->get_return();
            $purchased_item = array();
        }

        system('clear');
        $status = $obj_vending->store_coin((int)$instruction[1]);

        if ($status == false) {
            echo "Not a valid coin !" . PHP_EOL;
            continue;
        }

        $input_amount += (int)trim($instruction[1]);
    } elseif (trim($instruction[0]) == "2") {
        $reset_return = false;
        system('clear');
        $item_id = trim($instruction[1]);

        $result = $obj_vending->checkout($item_id, $input_amount);
        if (is_array($result)) {
            $input_amount = $result['return_coin']['coin_10'] * 10;
            $input_amount += $result['return_coin']['coin_100'] * 100;

            $purchased_item[] = $result['item'];
        } else {
            if ($result == -1) {
                // doesn't fit preconditions
                echo "OUR MACHINE DOESN'T HAVE CHANGES";
                $return = $obj_vending->refund_coin($input_amount);
                sleep(2);

                system('clear');
                $input_amount = 0;
                $reset_return = true;
                $obj_vending->reset_return();
            } elseif ($result == -2) {
                // item not found
                echo "CANNOT CHECKOUT, ITEM IS NOT FOUND!";
                sleep(2);
                system('clear');
            } elseif ($result == -3) {
                // item out of stock
                echo "CANNOT CHECKOUT, ITEM IS OUT OF STOCK!";
                sleep(2);
                system('clear');
            } else {
                // no sufficient input amount
                // DO NOTHING
            }
        }
    } elseif (trim($instruction[0]) == "3") {
        $reset_return = false;
        // get state
        system('clear');
    } elseif (trim($instruction[0]) == "4") {
        $reset_return = false;
        // pull state
        system('clear');
        $input_amount = 0;
    } else {
        $reset_return = true;
        // get return coin
        system('clear');
        $return = $obj_vending->get_return();
        $obj_vending->reset_return();
    }

    echo input_amount($input_amount);
    echo check_change($obj_vending->check_coin_10(), $obj_vending->check_coin_100());
    echo return_gate($return);
    echo print_items($obj_vending->get_items(), $input_amount);
    echo display_outlet($purchased_item);
}
