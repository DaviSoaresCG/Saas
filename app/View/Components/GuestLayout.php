<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    public function __construct(
        public ?string $title = null,
    ) {

    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $slug = Request::route('slug');
        $base = config('app.domain', env('APP_DOMAIN', 'lvh.me'));
        if (!$slug) {
            $host = Request::getHost();
            $slug = str_replace('.' . $base, '', $host);
            if ($slug == $host || $slug === 'www' || in_array($host, ['localhost', '127.0.0.1'])) {
                $slug = null;
            }
        }

        $logoUrl = null;
        if ($slug) {
            $user = User::where('slug', $slug)->first();
            $theme = $user?->theme_name ?? 'cyber';
            $logoUrl = $user?->logo_url;
            $storeName = $user?->store_name;
        } else {
            $theme = session('theme', 'cyber');
            $storeName = config('app.name', 'ZapCatálogo');
        }

        $title = !empty($this->title)
            ? ($storeName ? "{$this->title} - {$storeName}" : "{$this->title}")
            : ($storeName ?? config('app.name', 'ZapCatálogo'));

        return view('layouts.guest', compact('theme', 'logoUrl', 'title'));
    }
}
