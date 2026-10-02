<?php

namespace Dennenboom\VerdantUI\Sidebar;

use Illuminate\Routing\Exceptions\UrlGenerationException;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;

class SidebarResolver
{
    public static function rootUrl(): ?string
    {
        $hasRootRoute = collect(RouteFacade::getRoutes()->getRoutes())
            ->contains(fn (Route $route) => $route->uri() === '/' && in_array('GET', $route->methods()));

        return $hasRootRoute ? url('/') : null;
    }

    /** @param array<int, string> $routeNames */
    public static function routeUrl(array $routeNames): ?string
    {
        $route = self::firstExistingRoute($routeNames);

        if (! $route) {
            return null;
        }

        try {
            return route($route->getName());
        } catch (UrlGenerationException) {
            return null;
        }
    }

    /** @param array<int, string> $routeNames */
    public static function routeMethod(array $routeNames): string
    {
        $route = self::firstExistingRoute($routeNames);

        if (! $route) {
            return 'post';
        }

        return in_array('POST', $route->methods()) ? 'post' : 'get';
    }

    public static function userName(mixed $user): ?string
    {
        if (! $user) {
            return null;
        }

        return data_get($user, 'name') ?: data_get($user, 'email');
    }

    public static function userImage(mixed $user): ?string
    {
        if (! $user) {
            return null;
        }

        $imageAttributes = config('verdant.sidebar.user_image_attributes', ['profile_photo_url', 'avatar_url', 'avatar']);

        foreach ($imageAttributes as $attribute) {
            $image = data_get($user, $attribute);

            if ($image) {
                return $image;
            }
        }

        return null;
    }

    public static function initials(?string $name): string
    {
        if (! $name) {
            return '?';
        }

        return collect(preg_split('/[\s@._-]+/u', trim($name), -1, PREG_SPLIT_NO_EMPTY))
            ->take(2)
            ->map(fn (string $word) => mb_strtoupper(mb_substr($word, 0, 1)))
            ->implode('');
    }

    /** @param array<int, string> $routeNames */
    private static function firstExistingRoute(array $routeNames): ?Route
    {
        foreach ($routeNames as $routeName) {
            $route = RouteFacade::getRoutes()->getByName($routeName);

            if ($route) {
                return $route;
            }
        }

        return null;
    }
}
