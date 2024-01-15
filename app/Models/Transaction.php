<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory;

    public static function generateSerialNumber(): string
    {
        $count = DB::table('transactions')->count();
        $serial = 'TRX-'.str_pad(strval($count + 1), 7, '0', STR_PAD_LEFT);

        return $serial;
    }
}
