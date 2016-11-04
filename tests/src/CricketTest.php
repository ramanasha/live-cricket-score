<?php

use LiveScore\Cricket;

class CricketTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function testGetTodaysMatchesReturnType()
    {
        $cricket = new Cricket();
        $this->assertTrue(is_array($cricket->getTodaysMatches()));
    }

    public function testGetMatchScoreReturnType()
    {
        $cricket = new Cricket();
        $this->assertTrue(is_array($cricket->getMatchScore('1024749', 'domestic')));
        $this->assertTrue($cricket->getMatchScore('test', 'ini') == null);
        $this->assertTrue($cricket->getMatchScore('test', 'domestic') == null);
    }
}