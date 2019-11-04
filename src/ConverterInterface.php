<?php

namespace RozbehSharahi\SvgConvert;

interface ConverterInterface
{
    public function getBlob(Svg $svg, Configuration $configuration): string;
}