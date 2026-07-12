<?php

if (!function_exists('tenant_route')) {
    /**
     * Generate a tenant or variant route URL.
     */
    function tenant_route($name, $parameters = [])
    {
        // 1. Check if there is an active catalog hash in session or route
        $hash = session('catalog_hash') ?? request()->route('hash');

        if ($hash) {
            // Variant routing under main domain with hash prefix
            // Map the route parameter 'hash' explicitly
            $params = array_merge(['hash' => $hash], $parameters);
            
            // Clean up slug if present to avoid pollution
            if (isset($params['slug'])) {
                unset($params['slug']);
            }
            
            return route('variant.' . $name, $params);
        }

        // 2. Standard subdomain routing
        $slug = request()->route('slug')
            ?? (app()->bound(\App\Models\User::class) ? app(\App\Models\User::class)->slug : null)
            ?? auth()->user()?->slug
            ?? $parameters['slug']
            ?? null;

        $params = array_merge(['slug' => $slug], $parameters);

        return route($name, $params);
    }
}
