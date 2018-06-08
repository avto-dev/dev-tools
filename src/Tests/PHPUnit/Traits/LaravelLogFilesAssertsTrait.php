<?php

declare(strict_types = 1);

namespace AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Illuminate\Filesystem\Filesystem;
use PHPUnit\Framework\AssertionFailedError;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * @mixin \Illuminate\Foundation\Testing\TestCase
 */
trait LaravelLogFilesAssertsTrait
{
    /**
     * Make logs directory cleaning (remove all files and directories inside).
     *
     * @param string|null $logs_directory_path
     *
     * @return void
     */
    public function clearLaravelLogs($logs_directory_path = null)
    {
        (new Filesystem)->cleanDirectory($logs_directory_path ?? $this->getDefaultLogsDirectoryPath());
    }

    /**
     * Assert that log file exists.
     *
     * @param string $file_name
     *
     * @throws AssertionFailedError
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function assertLogFileExists(string $file_name = 'laravel.log')
    {
        $this->assertFileExists($this->getDefaultLogsDirectoryPath($file_name));
    }

    /**
     * Assert that log file exists.
     *
     * @param string $file_name
     *
     * @throws AssertionFailedError
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function assertLogFileNotExists(string $file_name = 'laravel.log')
    {
        $this->assertFileNotExists($this->getDefaultLogsDirectoryPath($file_name));
    }

    /**
     * Assert that log file contains passed substring.
     *
     * @param string   $substring
     * @param string   $file
     * @param int|null $lines_limit Make search only in N last log files lines. Pass null to disable this limitation
     *
     * @throws AssertionFailedError
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function assertLogFileContains(string $substring, string $file = 'laravel.log', $lines_limit = null)
    {
        $lines = $this->getLogFileContentAsArray($file, $lines_limit === null
            ? $lines_limit
            : $lines_limit + 1);

        $this->assertGreaterThanOrEqual(1, \count($lines), "File {$file} is empty");
        $this->assertContains($substring, implode("\n", $lines), "Log file [{$file}] does not contains [{$substring}].");
    }

    /**
     * Assert that log file NOT contains passed substring.
     *
     * @param string   $substring
     * @param string   $file
     * @param int|null $lines_limit Make search only in N last log files lines. Pass null to disable this limitation
     *
     * @throws AssertionFailedError
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function assertLogFileNotContains(string $substring, string $file = 'laravel.log', $lines_limit = null)
    {
        $lines = $this->getLogFileContentAsArray($file, $lines_limit === null
            ? $lines_limit
            : $lines_limit + 1);

        $this->assertNotContains($substring, implode("\n", $lines), "Log file [{$file}] contains [{$substring}].");
    }

    /**
     * Get default logs directory path.
     *
     * @param string|null $optional_path
     *
     * @return string
     */
    public function getDefaultLogsDirectoryPath($optional_path = null): string
    {
        $optional_path = $optional_path === null
            ? ''
            : DIRECTORY_SEPARATOR . ltrim($optional_path, '\\/');

        return rtrim($this->app->storagePath(), '\\/') . DIRECTORY_SEPARATOR . 'logs' . $optional_path;
    }

    /**
     * Get the log file content.
     *
     * @param string $file_name
     *
     * @return bool|string
     */
    public function getLogFileContent(string $file_name = 'laravel.log')
    {
        $file_path = $this->getDefaultLogsDirectoryPath($file_name);

        $this->assertFileExists($file_path, "Log file [{$file_path}] does not exists.");

        return \file_get_contents($file_path);
    }

    /**
     * Get log files last lines an an array of strings.
     *
     * @param string   $file_name
     * @param int|null $lines_limit Lines limit from file end
     *
     * @return string[]
     */
    public function getLogFileContentAsArray(string $file_name = 'laravel.log', $lines_limit = null): array
    {
        $content     = $this->getLogFileContent($file_name);
        $lines       = \preg_split("/\\r|\\n/", $content);
        $lines_count = \count($lines);

        if ($lines_limit !== null && $lines_limit > 0 && $lines_count >= $lines_limit) {
            $lines = \array_splice($lines, $lines_count - $lines_limit);
        }

        return \array_values(\array_filter($lines));
    }
}
