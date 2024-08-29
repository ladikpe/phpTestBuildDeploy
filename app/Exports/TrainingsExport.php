<?php

namespace App\Exports;

use App\UserTrainingPlan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;


class TrainingsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $arg;

    public function __construct($arg)
    {
        $this->arg = $arg;
    }

    public function headings(): array
    {
        if($this->arg == "users"){
            return [
                'Name',
                'Email',
                'Gender',
                'Job',
                'Training',
                'Status',
                'Type',
                'Start Date',
                'End Date'
            ];
        }else if($this->arg == "departments"){
            return [
                'Job',
                'Training',
                'Training Plan Status',
                'Type',
                'Mode',
                'Start Date',
                'End Date'
            ];
        }
        
    }

    public function collection()
    {
        if($this->arg == "users"){
            return DB::table('user_training_plans')
            ->join('users', 'user_training_plans.user_id', '=', 'users.id')
            ->join('jobroles', 'users.job_id', '=', 'jobroles.id')
            ->join('training_plans', 'user_training_plans.training_plan_id', '=', 'training_plans.id')
            ->join('training_datas', 'training_datas.id', '=', 'user_training_plans.training_id')
            ->select('users.name as Name', 'users.email', 'users.sex', 'jobroles.title','training_datas.name as training_name', 'user_training_plans.status', 'training_plans.type','training_plans.start_date', 'training_plans.end_date')
            ->get();
        }else if($this->arg == "departments"){
            return DB::table('user_training_plans')
            ->join('users', 'user_training_plans.user_id', '=', 'users.id')
            ->join('jobroles', 'users.job_id', '=', 'jobroles.id')
            ->join('training_plans', 'user_training_plans.training_plan_id', '=', 'training_plans.id')
            ->join('training_datas', 'training_datas.id', '=', 'user_training_plans.training_id')
            ->select('jobroles.title','training_datas.name as training_name', 'user_training_plans.status', 'training_plans.type','training_plans.mode','training_plans.start_date', 'training_plans.end_date')
            ->get();
        }
        
    }
}
