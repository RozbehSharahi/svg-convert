<?php

namespace RozbehSharahi\SvgConvert;

class GraphicsMagickConverter extends ImageMagickConverter
{

    static protected $command = 'gm';

    protected function getCommand(): string
    {
        return parent::getCommand() . ' convert';
    }

}