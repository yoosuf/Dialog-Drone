<?php

namespace App\Utils;

use Illuminate\Support\Facades\Input;

class Helper {

    // other functions

    public static function oldRadio($name, $value, $default = false) {
        if(empty($name) || empty($value) || !is_bool($default))
            return '';

        if(null !== Input::old($name)) {
            if(Input::old($name) == $value) {
                return 'checked';
            } else {
                return '';
            }
        } else {
            if($default) {
                return 'checked';
            } else {
                return '';
            }
        }

        // Or, short version:
//        return null !== Input::old($name) ? (Input::old($name) == $value ? 'checked' : '') : ($default ? 'checked' : '');
    }
}