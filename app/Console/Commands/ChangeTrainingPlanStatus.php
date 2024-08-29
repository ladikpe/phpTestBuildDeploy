<?php

namespace App\Console\Commands;

use App\Traits\TrainPlanTrait;
use Illuminate\Console\Command;

class ChangeTrainingPlanStatus extends Command
{
    use TrainPlanTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:trainingplans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command changes the status of all training plans every minutes and show some update';

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
        $this->changeTrainingStatus();
    }
}
