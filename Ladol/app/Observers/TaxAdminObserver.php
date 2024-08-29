<?php

namespace App\Observers;

use App\TaxAdmin;

class TaxAdminObserver
{
    /**
     * Handle the tax admin "created" event.
     *
     * @param  \App\TaxAdmin  $taxAdmin
     * @return void
     */
    public function created(TaxAdmin $taxAdmin)
    {
        //
    }

    /**
     * Handle the tax admin "updated" event.
     *
     * @param  \App\TaxAdmin  $taxAdmin
     * @return void
     */
    public function updating(TaxAdmin $taxAdmin)
    {
        //
         // Get the updated value
         $updatedValue = $taxAdmin->name;
        
         // Access the related users
         $users = $taxAdmin->users;
 
         // Update the related users based on the updated value
      
        $users->each(function ($user) use ($updatedValue) {
            $user->tax_authority = $updatedValue;
            $user->save();
        });
    }

    /**
     * Handle the tax admin "deleted" event.
     *
     * @param  \App\TaxAdmin  $taxAdmin
     * @return void
     */
    public function deleted(TaxAdmin $taxAdmin)
    {
        //
    }

    /**
     * Handle the tax admin "restored" event.
     *
     * @param  \App\TaxAdmin  $taxAdmin
     * @return void
     */
    public function restored(TaxAdmin $taxAdmin)
    {
        //
    }

    /**
     * Handle the tax admin "force deleted" event.
     *
     * @param  \App\TaxAdmin  $taxAdmin
     * @return void
     */
    public function forceDeleted(TaxAdmin $taxAdmin)
    {
        //
    }
}
