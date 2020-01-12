<?php

class VendingMachine {
    private $coin_10 = 0;
    private $coin_50 = 0;
    private $coin_100 = 0;
    private $coin_500 = 0;

    private $return_coin = array();
    private $items = array();

    public function __construct(){
        $this->reset_return();
    }

    public function get_items(){
        return $this->items;
    }

    public function set_item($item){
        $this->items[] = $item;
    }

    public function store_coin($amount){
        if (($amount % 10) != 0) {
            return false;
        }

        $this->coin_500 += (int)($amount / 500);
        $remaining = $amount % 500;

        $this->coin_100 += (int)($remaining / 100);
        $remaining = $remaining % 100;

        $this->coin_50 += (int)($remaining / 50);
        $remaining = $remaining % 50;

        $this->coin_10 += (int)($remaining / 10);

        return true;
    }

    public function refund_coin($amount){
        $coin_500 = (int)($amount / 500);
        $remaining = $amount % 500;

        $coin_100 = (int)($remaining / 100);
        $remaining = $remaining % 100;

        $coin_50 = (int)($remaining / 50);
        $remaining = $remaining % 50;

        $coin_10 = (int)($remaining / 10);

        $this->coin_500 -= $coin_500;
        $this->coin_100 -= $coin_100;
        $this->coin_50 -= $coin_50;
        $this->coin_10 -= $coin_10;

        $this->return_coin = array(
            'coin_10' => $coin_10,
            'coin_50'=> $coin_50,
            'coin_100'=> $coin_100,
            'coin_500'=> $coin_500
        );

        return $this->return_coin;
    }

    public function set_coin_10($count){
        $this->coin_10 += $count;
    }

    public function set_coin_100($count){
        $this->coin_100 += $count;
    }

    public function checkout($item_id, $input_amount){
        $available = $this->check_coin_10();
        $available = $available && $this->check_coin_100();
        if (!$available) {
            // no sufficient stock change coin
            return -1;
        }

        $index = $this->check_item($item_id);
        $available = $available && is_integer($index);
        if ($available) {
            if ($this->items[$index]['stock'] == 0) {
                // item out of stock
                return -3;
            }

            if ($input_amount >= $this->items[$index]['price']) {
                $this->amount_coin_reserved = $input_amount;

                 $this->items[$index]['stock']--;

                $return_amount = $input_amount - $this->items[$index]['price'];

                $change_coin_10 = $this->change_coin_10($return_amount);
                $change_coin_100 = $this->change_coin_100($return_amount);

                $this->return_coin['coin_10'] = $change_coin_10;
                $this->return_coin['coin_100'] = $change_coin_100;

                return array(
                    'return_coin' => $this->return_coin,
                    'item' => $this->items[$index]
                );
            }

            // no sufficient input amount
            return -4;
        }

        // item not found
        return -2;
    }

    public function reset_return(){
        $this->return_coin = array(
            'coin_10' => 0,
            'coin_50' => 0,
            'coin_100'=> 0,
            'coin_500'=> 0
        );
    }

    public function get_return(){
        $this->coin_10 -= $this->return_coin['coin_10'];
        $this->coin_100 -= $this->return_coin['coin_100'];

        return $this->return_coin;
    }

    public function check_coin_10(){
        if ($this->coin_10 < 10) return false;

        return true;
    }

    public function check_coin_100(){
        if ($this->coin_100 < 5) return false;

        return true;
    }

    private function change_coin_10($input_amount){
        $amount = $input_amount % 100;

        $change = (int)($amount / 10);

        return $change;
    }

    private function change_coin_100($input_amount){
        $change = (int)($input_amount / 100);

        return $change;
    }

    private function check_item($item_id){
        foreach ($this->items as $index => $item) {
            if ($item['id'] == $item_id) {
                return $index;
            }
        }

        return false;
    }
}