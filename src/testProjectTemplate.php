<?php
/**
 * Created by PhpStorm.
 * User: elminsondeoleobaez
 * Date: 10/3/18
 * Time: 1:52 PM
 */
require __DIR__ . '/../vendor/autoload.php';


use {!namespace!};
use PHPUnit\Framework\TestCase;

class test{!projectname!} extends TestCase
{

    /**
     *
     */
    function testFirstTestCase()
    {
        ${!loweclass!} = new {!projectname!}();
        $this->assertEquals("index", ${!loweclass!}->index);
    }

}
