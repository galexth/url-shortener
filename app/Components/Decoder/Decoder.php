<?php

namespace App\Components\Decoder;

class Decoder implements DecoderInterface
{
    private string $index;

    private int $base;

    /**
     * Decoder constructor.
     *
     * @param string $index
     */
    public function __construct(string $index)
    {
        $this->index = $index;
        $this->base = strlen($index);
    }

    /**
     * @param int $value
     *
     * @return string
     */
    public function encode(int $value): string
    {
        if ($value === 0) {
            return $this->index[$value];
        }

        $keys = [];

        while ($value) {
            $keys[] = $this->index[$value % $this->base];

            $value = (int) ($value / $this->base);
        }

        return implode('', array_reverse($keys));
    }

    /**
     * @param string $value
     *
     * @return string
     * @throws \Exception
     */
    public function decode(string $value): string
    {
        $sum = 0;

        foreach (str_split($value) as $key => $item) {
            if ($item === '' || ($idx = strpos($this->index, $item)) === false) {
                throw new ParserException("'{$value}' code is invalid.");
            }

            $sum = $sum * $this->base + $idx;
        }

        return (int) $sum;
    }
}
