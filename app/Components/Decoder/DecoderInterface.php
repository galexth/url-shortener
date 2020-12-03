<?php

namespace App\Components\Decoder;

interface DecoderInterface
{
    /**
     * @param int $value
     *
     * @return string
     */
    public function encode(int $value): string;

    /**
     * @param string $value
     *
     * @return string
     */
    public function decode(string $value): string;
}
