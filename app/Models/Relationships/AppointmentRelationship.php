<?php
namespace App\Models\Relationships;

use App\Models\Client;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait AppointmentRelationship {
    public function client() : BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
