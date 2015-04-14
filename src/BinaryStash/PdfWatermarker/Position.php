<?php

namespace BinaryStash\PdfWatermarker;

class Position
{
    private $name;
    private static $options = [
        'TopLeft', 'TopCenter', 'TopRight',
        'MiddleLeft', 'MiddleCenter', 'MiddleRight',
        'BottomLeft', 'BottomCenter', 'BottomRight',
    ];

    /**
     * @param $name
     *
     * @throws \Exception
     */
    public function __construct($name)
    {
        if (!array_key_exists($name, array_flip(self::$options))) {
            throw new \Exception('Unsupported position:' . $name);
        }

        $this->name = $name;
    }

    /**
     * @return string name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param WatermarkPosition $position
     *
     * @return bool
     */
    public function equals(WatermarkPosition $position)
    {
        return ($this->name === $position->getName());
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return Position
     */
    public static function __callStatic($name, $arguments)
    {
        return new self($name);
    }
}
