<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    public static function generateSerialNumber(): string
    {
        $count = DB::table('transactions')->count();
        $serial = 'TRX-' . generateRandomString(5) . str_pad(strval($count + 1), 5, '0', STR_PAD_LEFT);

        return $serial;
    }
}
