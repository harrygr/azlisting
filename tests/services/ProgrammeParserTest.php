<?php

use App\Programme;
use App\Services\ProgrammeParser;
use Illuminate\Support\Collection;

class ProgrammeParserTest extends TestCase
{
    /** @test **/
    public function it_is_instantiable()
    {
        $parser = app(ProgrammeParser::class);
        $this->assertInstanceOf(ProgrammeParser::class, $parser);
    }

    /** @test **/
    public function it_parses_an_array_of_programmes_into_models()
    {
        $parser = new ProgrammeParser;

        // Get a dummy json response
        $json = file_get_contents(base_path('tests/resources/programmes.json'));

        $programmes = $parser->parse(json_decode($json, true));

        // our dummy json contained 3 programmes
        $this->assertCount(3, $programmes);
        $this->assertInstanceOf(Collection::class, $programmes);
        $this->assertInstanceOf(Programme::class, $programmes->first());
    }
}
