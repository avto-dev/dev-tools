<?php

declare(strict_types=1);

namespace AvtoDev\DevTools\Tests\PHPUnit\Traits;

use PHPUnit\Framework\AssertionFailedError;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

trait AppVersionAssertionsTrait
{
    /**
     * Get the path of changelog file location.
     *
     * As usual this path looks like `%application_directory%/CHANGELOG.md`.
     *
     * @return string
     */
    abstract public function getChangeLogFileLocation(): string;

    /**
     * Get current application version.
     *
     * @return string
     */
    abstract public function getCurrentApplicationVersion(): string;

    /**
     * Get regular expression for getting application version values from changelog file.
     *
     * @see https://keepachangelog.com/en/1.0.0/
     *
     * @return string
     */
    public function getVersionRegexp(): string
    {
        return '~^##[ \\[]+[vV]?(\\d+\\.\\d+\\.\\d+).*$~m';
    }

    /**
     * Asserts that application and version from changelog are same.
     *
     * @throws AssertionFailedError
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function assertAppVersionAndVersionInChangeLogIsEquals()
    {
        static::assertSame(
            $this->getCurrentApplicationVersion(),
            $this->getLastChangeLogVersion(),
            "Application version ({$this->getCurrentApplicationVersion()}) and version in " .
            "[{$this->getChangeLogFileLocation()}] ({$this->getLastChangeLogVersion()}) is not equals"
        );
    }

    /**
     * Get last version, declared in `CHANGELOG.md` file.
     *
     * @return string|null
     */
    public function getLastChangeLogVersion()
    {
        $matches = [];

        \preg_match_all($this->getVersionRegexp(), $this->getChangeLogFileContent(), $matches);

        return $matches[1][0] ?? null;
    }

    /**
     * Get changelog file content as string.
     *
     * @return string
     */
    public function getChangeLogFileContent(): string
    {
        return \file_get_contents($this->getChangeLogFileLocation());
    }
}
