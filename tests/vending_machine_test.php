<?php

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/vending_machine.php';

use \PHPUnit\Framework\TestCase;

class VendingMachineTest extends TestCase
{
    public function setUp(){
        $this->obj_vending = new VendingMachine();
    }

    public function test_item(){
        $item = array(
            'id' => 1,
            'name' => 'Canned coffee',
            'stock' => 1,
            'price' => 120
        );

        $this->obj_vending->set_item($item);

        $class_item = $this->obj_vending->get_items();

        $this->assertContains($item, $class_item);

        $item = array(
            'id' => 2,
            'name' => 'Water PET bottle',
            'stock' => 4,
            'price' => 100
        );

        $this->obj_vending->set_item($item);

        $class_item = $this->obj_vending->get_items();

        $this->assertContains($item, $class_item);
        $this->assertEquals(2, sizeof($this->obj_vending->get_items()));
    }

    public function test_coin_10(){
        $this->obj_vending->set_coin_10(5);

        $status = $this->obj_vending->check_coin_10();

        $this->assertEquals(false, $status);

        $this->obj_vending->set_coin_10(10);

        $status = $this->obj_vending->check_coin_10();

        $this->assertEquals(true, $status);
    }

    public function test_coin_100(){
        $this->obj_vending->set_coin_100(2);

        $status = $this->obj_vending->check_coin_100();

        $this->assertEquals(false, $status);

        $this->obj_vending->set_coin_100(5);

        $status = $this->obj_vending->check_coin_100();

        $this->assertEquals(true, $status);
    }

    public function test_get_return_empty(){
        $return_coin = $this->obj_vending->get_return();

        $this->assertEquals(array(
            'coin_10' => 0,
            'coin_50' => 0,
            'coin_100'=> 0,
            'coin_500'=> 0
        ), $return_coin);
    }

    public function test_store_coin(){
        $status = $this->obj_vending->store_coin(321);

        $this->assertEquals(false, $status);

        $status = $this->obj_vending->store_coin(320);

        $this->assertEquals(true, $status);
    }

    public function test_refund_coin(){
        $refund = $this->obj_vending->refund_coin(320);

        $this->assertEquals(array(
            'coin_10' => 2,
            'coin_50'=> 0,
            'coin_100'=> 3,
            'coin_500'=> 0
        ), $refund);
    }

    public function test_checkout(){
        $item = array(
            'id' => 1,
            'name' => 'Canned coffee',
            'stock' => 1,
            'price' => 120
        );

        $this->obj_vending->set_item($item);

        $item = array(
            'id' => 2,
            'name' => 'Water PET bottle',
            'stock' => 4,
            'price' => 100
        );

        $this->obj_vending->set_item($item);

        $this->obj_vending->set_coin_10(10);

        $this->obj_vending->set_coin_100(100);

        $this->obj_vending->store_coin(500);

        $return = $this->obj_vending->checkout(4, 500);

        $this->assertEquals(-2, $return);

        $return = $this->obj_vending->checkout(1, 10);

        $this->assertEquals(-4, $return);

        $return = $this->obj_vending->checkout(1, 500);

        $this->assertContains(array(
            'coin_10' => 8,
            'coin_50'=> 0,
            'coin_100'=> 3,
            'coin_500'=> 0
        ), $return);

        $return = $this->obj_vending->checkout(1, 380);

        $this->assertEquals(-3, $return);

        $return = $this->obj_vending->get_return();

        $this->assertEquals(array(
            'coin_10' => 8,
            'coin_50'=> 0,
            'coin_100'=> 3,
            'coin_500'=> 0
        ), $return);

        $this->obj_vending->store_coin(500);

        $return = $this->obj_vending->checkout(2, 500);

        $this->assertEquals(-1, $return);
    }
}