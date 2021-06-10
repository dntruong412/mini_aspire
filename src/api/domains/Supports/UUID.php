<?php 

namespace Domains\Supports;

use Illuminate\Support\Str;
use DB;

class UUID {

    public static function generate($table) {
        $uuid = '';
        do {
            $uuid = (string) Str::uuid();
            if (DB::table($table)->where('id', $uuid)->count() > 0) {
                $uuid = '';
            }
        } while($uuid == '');

        return $uuid;
    }
}