<?php

namespace Islamikit\Starterkit\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public ?string $app_name;
    public ?string $app_description;
    public string $date_format;

    public static function group(): string
    {
        return 'general';
    }

    public static function defaults(): array
    {
        return [
            'app_name' => config('app.name', 'Laravel'),
            'app_description' => '',
            'date_format' => 'd/m/Y',
        ];
    }
}