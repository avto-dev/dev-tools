<?php

declare(strict_types = 1);

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Illuminate\Filesystem\Filesystem;
use PHPUnit\Framework\AssertionFailedError;
use Illuminate\Foundation\Testing\TestCase as IlluminateTestCase;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\LaravelLogFilesAssertsTrait;

/**
 * @covers \AvtoDev\DevTools\Tests\PHPUnit\Traits\LaravelLogFilesAssertsTrait<extended>
 */
class LaravelLogFilesAssertsTraitTest extends IlluminateTestCase
{
    use CreatesApplicationTrait;
    use LaravelLogFilesAssertsTrait {
        getDefaultLogsDirectoryPath as vendorGetDefaultLogsDirectoryPath;
    }

    /**
     * @var string
     */
    protected $temp_logs_path = __DIR__ . '/../../../temp/logs';

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (! ($this->files instanceof Filesystem)) {
            $this->files = new Filesystem;
        }

        if (! $this->files->isDirectory($deep = $this->temp_logs_path)) {
            $this->files->makeDirectory($deep, 0755, true);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        if ($this->files->exists($this->temp_logs_path)) {
            $this->files->deleteDirectory($this->temp_logs_path);
        }

        parent::tearDown();
    }

    /**
     * Override method for getting default logs directory path.
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

        return $this->temp_logs_path . $optional_path;
    }

    /**
     * Test logs directory cleaning.
     *
     * @return void
     */
    public function testClearLogs(): void
    {
        /**
         * This instance will create temp directory, put some files inside her, and works with it.
         */
        $test_class = new class extends IlluminateTestCase {
            use CreatesApplicationTrait,
                LaravelLogFilesAssertsTrait;

            protected $temp_logs_path = __DIR__ . '/../../../temp/logs'; // Copy&paste from current class

            public function __construct(string $name = null, array $data = [], string $dataName = '')
            {
                parent::__construct($name, $data, $dataName);

                $files = new Filesystem;

                if (! $files->isDirectory($deep = $this->temp_logs_path . '/baz')) {
                    $files->makeDirectory($deep, 0755, true);
                }

                if (! $files->isDirectory($hidden_deep = $this->temp_logs_path . '/.hidden_dir')) {
                    $files->makeDirectory($hidden_deep, 0755, true);
                }

                $files->put($this->temp_logs_path . '/laravel.log', 'foo');
                $files->put($this->temp_logs_path . '/test.log', 'bar');
                $files->put($deep . '/deep.log', 'foo bar');

                $files->put($this->temp_logs_path . '/.hidden', 'bar');
                $files->put($hidden_deep . '/.foo', 'baz');
            }

            public function getDefaultLogsDirectoryPath()
            {
                return $this->temp_logs_path;
            }
        };

        $this->assertStringEqualsFile($laravel_log = $this->temp_logs_path . '/laravel.log', 'foo');
        $this->assertStringEqualsFile($test_log = $this->temp_logs_path . '/test.log', 'bar');
        $this->assertStringEqualsFile($deep_log = $this->temp_logs_path . '/baz/deep.log', 'foo bar');
        $this->assertStringEqualsFile($hidden_file = $this->temp_logs_path . '/.hidden', 'bar');
        $this->assertStringEqualsFile($hidden_deep = $this->temp_logs_path . '/.hidden_dir/.foo', 'baz');

        $test_class->clearLaravelLogs(); // Execute

        foreach ([$laravel_log, $test_log, $deep_log] as $file_path) {
            $this->assertFileNotExists($file_path);
        }

        foreach ([$hidden_file, $hidden_deep] as $file_path) {
            $this->assertFileExists($file_path);
        }

        $this->assertDirectoryExists($this->temp_logs_path);

        $this->files->delete($hidden_file);
        $this->files->delete($hidden_deep);
        $this->files->deleteDirectory($this->temp_logs_path . '/.hidden_dir');
    }

    /**
     * Test log file content getters.
     *
     * @return void
     */
    public function testGetLogFilesContentGetters(): void
    {
        $this->files->put(
            $this->temp_logs_path . ($file_path1 = '/foo.log'), $content1 = "\n\r\n\n\rfoo\nbar\nbaz\n   \n"
        );
        $this->files->makeDirectory($this->temp_logs_path . '/bar');
        $this->files->put(
            $this->temp_logs_path . ($file_path2 = '/bar/baz.log'), $content2 = "\r\nfoo bar \rbaz\nbar"
        );

        $this->assertEquals($content1, $this->getLogFileContent($file_path1));
        $this->assertEquals($content2, $this->getLogFileContent($file_path2));
        $content_as_array1 = $this->getLogFileContentAsArray($file_path1);
        $content_as_array2 = $this->getLogFileContentAsArray($file_path2, 2);

        $this->assertCount(4, $content_as_array1);
        $this->assertEquals('foo', $content_as_array1[0]);
        $this->assertEquals('bar', $content_as_array1[1]);
        $this->assertEquals('baz', $content_as_array1[2]);
        $this->assertEquals('   ', $content_as_array1[3]);

        $this->assertCount(2, $content_as_array2);
        $this->assertEquals('baz', $content_as_array2[0]);
        $this->assertEquals('bar', $content_as_array2[1]);
    }

    /**
     * Test logs directory path getter.
     *
     * @return void
     */
    public function testVendorGetDefaultLogsDirectoryPath(): void
    {
        $this->assertEquals($this->app->storagePath() . '/logs', $this->vendorGetDefaultLogsDirectoryPath());
        $this->assertEquals($this->app->storagePath() . '/logs/foo', $this->vendorGetDefaultLogsDirectoryPath('foo'));
        $this->assertEquals($this->app->storagePath() . '/logs/bar',
            $this->vendorGetDefaultLogsDirectoryPath('/\\//bar'));
    }

    /**
     * Log file content NOT contains assert test.
     *
     * @return void
     */
    public function testAssertLogFileNotContains(): void
    {
        $find = 'hell yeah';
        $this->files->put($this->temp_logs_path . ($file_name = '/foo.log'), "\n{$find}\nfoo\nbar\nbaz\n[foo] bar\n");

        $this->assertLogFileNotContains($find, $file_name, 4);

        $throws = false;
        try {
            $this->assertLogFileNotContains($find, $file_name, 5);
        } catch (AssertionFailedError $e) {
            $throws = true;
        }
        $this->assertTrue($throws, 'Assert "assertLogFileNotContains" limitation broken');

        $throws = false;
        try {
            $this->assertLogFileNotContains($find, $file_name);
        } catch (AssertionFailedError $e) {
            $throws = true;
        }
        $this->assertTrue($throws, 'Assert "assertLogFileNotContains" broken');
    }

    /**
     * Log file content contains assert test.
     *
     * @return void
     */
    public function testAssertLogFileContains(): void
    {
        $find = 'hell yeah';
        $this->files->put($this->temp_logs_path . ($file_name = '/foo.log'), "\n{$find}\nfoo\nbar\nbaz\n[foo] bar\n");
        $this->files->put($this->temp_logs_path . ($empty_file_name = '/bar.log'), "\n");

        $this->assertLogFileContains($find, $file_name);

        $throws = false;
        try {
            $this->assertLogFileContains($find, $file_name, 4);
        } catch (AssertionFailedError $e) {
            $throws = true;
        }
        $this->assertTrue($throws, 'Assert "assertLogFileContains" limitation broken');

        $throws = false;
        try {
            $this->assertLogFileContains($find, $empty_file_name);
        } catch (AssertionFailedError $e) {
            $throws = true;
        }
        $this->assertTrue($throws, 'Assert "assertLogFileContains" broken');
    }

    /**
     * Test log file exists ot not.
     *
     * @return void
     */
    public function testAssertLogFileExists(): void
    {
        $this->files->put($this->temp_logs_path . ($file_name = '/foo.log'), null);
        $this->assertLogFileExists($file_name);

        $this->files->put($this->temp_logs_path . ($default_file_name = '/laravel.log'), null);
        $this->assertLogFileExists();

        $throws = false;
        try {
            $this->assertLogFileNotExists($file_name);
        } catch (AssertionFailedError $e) {
            $throws = true;
        }
        $this->assertTrue($throws);

        $throws = false;
        try {
            $this->files->delete($this->temp_logs_path . $default_file_name);
            $this->assertLogFileExists();
        } catch (AssertionFailedError $e) {
            $throws = true;
        }
        $this->assertTrue($throws);
    }
}
