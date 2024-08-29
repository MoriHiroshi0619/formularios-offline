<?php
if (! function_exists('string')) {
    function string(): \Illuminate\Support\Str {
        return resolve(\Illuminate\Support\Str::class);
    }
}
