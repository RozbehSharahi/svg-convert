<?php

namespace RozbehSharahi\SvgConvert;

use InvalidArgumentException;

class Assert extends \Webmozart\Assert\Assert
{

    static public function commandExists($command, $message = ''): void
    {
        $message = !empty($message) ? $message : "Command `$command` does not exist";

        Assert::notContains($command, ' ', $message);
        Assert::notEmpty(shell_exec("which " . $command), $message);
    }

    static public function base64($value, $message): void
    {
        $message = !empty($message) ? $message : "Value `$value` is not a valid base64";

        // Check if there are valid base64 characters
        if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $value)) {
            throw new InvalidArgumentException($message);
        }

        // Decode the string in strict mode and check the results
        $decoded = base64_decode($value, true);
        if (false === $decoded) {
            throw new InvalidArgumentException($message);
        }

        // Encode the string again
        if (base64_encode($decoded) != $value) {
            throw new InvalidArgumentException($message);
        }
    }

    static public function svg(string $svg, string $message): void
    {
        // not yet implemented, hard?
    }

}