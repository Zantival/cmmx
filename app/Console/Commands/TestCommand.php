<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test
        {--filter= : Filter which tests to run}
        {--testsuite= : Filter by test suite}
        {--coverage : Run with test coverage}
        {--parallel : Run tests in parallel}
        {--recreate-databases : Force recreation of databases}
        {--drop-databases : Drop all test databases}
        {--without-tty : Disable output to TTY (auto-applied)}';

    /**
     * The console command description.
     */
    protected $description = 'Run the application tests (always without TTY for compatibility)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $args = ['php', 'vendor/bin/phpunit', '--testdox'];

        if ($filter = $this->option('filter')) {
            $args[] = '--filter';
            $args[] = $filter;
        }

        if ($suite = $this->option('testsuite')) {
            $args[] = '--testsuite';
            $args[] = $suite;
        }

        if ($this->option('coverage')) {
            $args[] = '--coverage-text';
        }

        $process = new Process($args, base_path(), array_merge($_SERVER, $_ENV, ['APP_ENV' => 'testing']));
        $process->setTty(false);
        $process->setTimeout(null);

        $process->run(function ($type, $buffer) {
            $this->output->write($buffer);
        });

        return $process->getExitCode();
    }
}
