<?php

namespace App\Observers;

use App\PayScale;

class PayScaleObserver
{
    /**
     * Handle the pay scale "created" event.
     *
     * @param  \App\PayScale  $payScale
     * @return void
     */
    public function created(PayScale $payScale)
    {
        //
    }

    /**
     * Handle the pay scale "updated" event.
     *
     * @param  \App\PayScale  $payScale
     * @return void
     */
    public function updating(PayScale $payScale)
    {
        
         // Get the updated value
         $updatedValue = $payScale->level_code;
        
         // Access the related grades
         $grades = $payScale->grades;
 
         // Update the related grades based on the updated value
      
        $grades->each(function ($grade) use ($updatedValue) {
            $grade->level = $updatedValue;
            $grade->save();
        });
    }
    /**
     * Handle the pay scale "deleted" event.
     *
     * @param  \App\PayScale  $payScale
     * @return void
     */
    public function deleted(PayScale $payScale)
    {
        //
    }

    /**
     * Handle the pay scale "restored" event.
     *
     * @param  \App\PayScale  $payScale
     * @return void
     */
    public function restored(PayScale $payScale)
    {
        //
    }

    /**
     * Handle the pay scale "force deleted" event.
     *
     * @param  \App\PayScale  $payScale
     * @return void
     */
    public function forceDeleted(PayScale $payScale)
    {
        //
    }
}
