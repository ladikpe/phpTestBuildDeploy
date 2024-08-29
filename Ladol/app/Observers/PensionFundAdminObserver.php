<?php

namespace App\Observers;

use App\PensionFundAdmin;

class PensionFundAdminObserver
{
    /**
     * Handle the pension fund admin "created" event.
     *
     * @param  \App\PensionFundAdmin  $pensionFundAdmin
     * @return void
     */
    public function created(PensionFundAdmin $pensionFundAdmin)
    {
        //
    }

    /**
     * Handle the pension fund admin "updated" event.
     *
     * @param  \App\PensionFundAdmin  $pensionFundAdmin
     * @return void
     */
    public function updated(PensionFundAdmin $pensionFundAdmin)
    {
        //
         // Get the updated value
         $updatedValue = $pensionFundAdmin->name;
        
         // Access the related users
         $users = $pensionFundAdmin->users;
 
         // Update the related users based on the updated value
      
         $users->each(function ($user) use ($updatedValue) {
            $user->pension_administrator = $updatedValue;
            $user->save();
        });
    }

    /**
     * Handle the pension fund admin "deleted" event.
     *
     * @param  \App\PensionFundAdmin  $pensionFundAdmin
     * @return void
     */
    public function deleted(PensionFundAdmin $pensionFundAdmin)
    {
        //
    }

    /**
     * Handle the pension fund admin "restored" event.
     *
     * @param  \App\PensionFundAdmin  $pensionFundAdmin
     * @return void
     */
    public function restored(PensionFundAdmin $pensionFundAdmin)
    {
        //
    }

    /**
     * Handle the pension fund admin "force deleted" event.
     *
     * @param  \App\PensionFundAdmin  $pensionFundAdmin
     * @return void
     */
    public function forceDeleted(PensionFundAdmin $pensionFundAdmin)
    {
        //
    }
}
