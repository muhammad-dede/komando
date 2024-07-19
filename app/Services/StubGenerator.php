<?php

namespace App\Services;

use RuntimeException;

/*
 * Original source file: https://github.com/sebastiaanluca/php-stub-generator/blob/develop/src/StubGenerator.php
 */

class StubGenerator
{
    /**
     * @var string
     */
    protected $source;

    protected $contents;

    /**
     * @param string $source
     */
    public function __construct($source)
    {
        $this->source = $source;
    }

    /**
     * @param array $replacements
     *
     * @throws \RuntimeException
     */
    public function compile($replacements)
    {
        $this->contents = file_get_contents($this->source);

        // Standard replacements
        collect($replacements)->each(function ($replacement, $tag) {
            $this->contents = str_replace($tag, $replacement, $this->contents);
        });

        return $this;
    }

    public function save($target, $overwrite = false)
    {
        if (file_exists($target) && ! $overwrite) {
            throw new RuntimeException('Cannot generate file. Target '.$target.' already exists.');
        }
        $path = pathinfo($target, PATHINFO_DIRNAME);

        if (! file_exists($path)) {
            mkdir($path, 0776, true);
        }

        file_put_contents($target, $this->contents);
    }

    public function getContents()
    {
        return $this->contents;
    }
}
