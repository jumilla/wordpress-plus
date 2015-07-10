<?php

use Mockery as m;

class AdminRoutingTest extends ConsoleCommandTestCase
{
    public function testDashboard()
    {
    	$this->visit('/wp-admin');
    }
}
