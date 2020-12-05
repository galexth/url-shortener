<?php

namespace Tests\Unit;

use App\Components\Decoder\Decoder;
use App\Components\Decoder\ParserException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DecoderTest extends TestCase
{
    use WithFaker;

    private Decoder $decoder;

    private string $index = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    public function setUp(): void
    {
        parent::setUp();

        $this->decoder = new Decoder($this->index);
    }

    public function testEncodeDecode()
    {
        foreach (range(1, 1000) as $item) {
            $number = $this->faker()->numberBetween();
            $encoded = $this->decoder->encode($number);

            $this->assertNotEmpty($encoded);
            $this->assertEquals($number, $this->decoder->decode($encoded));
        }
    }

    /**
     * @dataProvider invalidCodes
     */
    public function testInvalidCode($code)
    {
        $this->expectException(ParserException::class);

        $this->decoder->decode($code);
    }

    public function invalidCodes(): array
    {
        return [
            ['sdfds$*^'],
            ['sdfdds ds'],
        ];
    }
}
