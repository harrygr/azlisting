<?php

class HomeContollerTest extends TestCase
{
    /** @test **/
    public function it_shows_the_front_page()
    {
        $this->visit('/')
             ->see('A-Z Programmes');
    }
}
