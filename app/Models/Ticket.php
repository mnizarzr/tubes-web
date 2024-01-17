<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Ticket extends Model
{
    use HasFactory;

    public static function generateSerialNumber($eventId): string
    {
        $q = DB::table('tickets');

        if (isset($eventId)) {
            $q->where('event_id', $eventId);
        }

        $count = $q->count();

        $serial = 'PJRTTX-'.generateRandomString(5).str_pad(strval($count + 1), 4, '0', STR_PAD_LEFT);

        return $serial;
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
