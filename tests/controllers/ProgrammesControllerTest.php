<?php

class ProgrammesContollerTest extends TestCase
{
    /** @test **/
    public function it_gets_some_programmes()
    {
        $this->get('api/programmes/a', ['page' => 1])
             ->seeJson(['page' => 1]);
    }
}
