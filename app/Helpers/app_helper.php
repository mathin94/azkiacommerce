<?php

if (!function_exists('store_outlet')) {
    function store_outlet()
    {
        return cache()->rememberForever('store_outlet', function () {
            return DB::connection('mysql_pos')->table('outlets')->first();
        });
    }
}

if (!function_exists('store_full_address')) {
    function store_full_address()
    {
        $outlet = store_outlet();

        $district = cache()->rememberForever('store_district', function () use ($outlet) {
            return \App\Models\Backoffice\Subdistrict::find($outlet->subdistrict_id);
        });

        return "$outlet->address, Kec. $district->name, $district->city_type $district->city_name, $district->province_name";
    }
}

if (!function_exists('series_to_filename')) {
    function series_to_filename(string $series_number)
    {
        return Str::of($series_number)
            ->replace('/', '-')
            ->replace(' ', '-')
            ->toString();
    }
}

if (!function_exists('site')) {
    function site()
    {
        return app(App\Settings\SiteSettings::class);
    }
}
