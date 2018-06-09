<?php

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits\Stubs;

use Illuminate\Console\Command;

class SignatureCommand extends Command
{
    protected $signature = 'test:signature
    {argument}
    {--O|option}
    {--second_option}';
}
