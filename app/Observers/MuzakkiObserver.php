<?php

namespace App\Observers;

use App\Models\Muzakki;

class MuzakkiObserver
{
    
    public function creating(Muzakki $muzakki): void
    {
        
        if ($muzakki->email && empty($muzakki->campaign_url)) {
            $muzakki->campaign_url = url('/campaigner/' . $muzakki->email);
        }
    }

    
    public function updating(Muzakki $muzakki): void
    {
        
        if ($muzakki->isDirty('email') && $muzakki->email) {
            $muzakki->campaign_url = url('/campaigner/' . $muzakki->email);
        }
        
        
        if (empty($muzakki->campaign_url) && $muzakki->email) {
            $muzakki->campaign_url = url('/campaigner/' . $muzakki->email);
        }
    }

    
    public function created(Muzakki $muzakki): void
    {
        
    }

    
    public function updated(Muzakki $muzakki): void
    {
        
    }

    
    public function deleted(Muzakki $muzakki): void
    {
        
    }

    
    public function restored(Muzakki $muzakki): void
    {
        
    }

    
    public function forceDeleted(Muzakki $muzakki): void
    {
        
    }
}

