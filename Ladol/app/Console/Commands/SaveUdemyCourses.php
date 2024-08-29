<?php

namespace App\Console\Commands;
use App\Jobs\FetchUdemyCourses;
use Illuminate\Console\Command;

class SaveUdemyCourses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'udemy:save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all courses from Udemy and saves them in the database:::';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       FetchUdemyCourses::dispatch();
    }
}
