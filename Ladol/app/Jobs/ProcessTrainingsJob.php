<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Traits\TrainingPlanningTrait;

class ProcessTrainingsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, TrainingPlanningTrait;
    
    public $data;
    public $type;
    public $training_plan_id;
    public $training_id;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type, $data, $training_plan_id, $training_id)
    {
        $this->data = $data;
        $this->type = $type;
        $this->training_plan_id = $training_plan_id;
        $this->training_id = $training_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->assignTraining($this->type, $this->data, $this->training_plan_id, $this->training_id);
    }
}
