<?php

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits\Stubs;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class StructureCommand extends Command
{
    protected $name        = 'test:structure';

    protected $description = 'Three words description';

    protected function getOptions()
    {
        return [
            ['option', 'O', InputOption::VALUE_OPTIONAL],
            ['second_option', null, InputOption::VALUE_OPTIONAL],
        ];
    }

    protected function getArguments()
    {
        return [
            ['argument', InputArgument::OPTIONAL],
        ];
    }
}
