<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class FixPollTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-poll-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix stuck poll tables for migration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Schema::dropIfExists('poll_votes');
        Schema::dropIfExists('poll_options');
        $this->info('Tables poll_votes and poll_options have been dropped successfully!');
    }
}
