<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class PublicCache
{
    public const WELCOME = 'welcome_page_data';
    public const FOOTER_PROGRAMS = 'footer_programs';
    public const PROGRAMS_API = 'public_api_programs';
    public const SETTINGS_API = 'public_api_settings';
    public const NEWS_EVENTS_API = 'public_api_news_events';
    public const NEWS_EVENTS_PAGE = 'news_events_page_data';

    public static function ttl(): int
    {
        return max(60, (int) env('PUBLIC_CACHE_TTL', 600));
    }

    public static function clear(): void
    {
        foreach ([
            self::WELCOME,
            self::FOOTER_PROGRAMS,
            self::PROGRAMS_API,
            self::SETTINGS_API,
            self::NEWS_EVENTS_API,
            self::NEWS_EVENTS_PAGE,
        ] as $key) {
            Cache::forget($key);
        }
    }
}
