# Sidebar

`v-sidebar` is a complete application sidebar: logo, navigation, a collapse toggle and a user menu at the bottom. Everything it shows by default is resolved automatically and can be overridden.

## Components

- **`v-sidebar`** – The sidebar itself
- **`v-sidebar.item`** – A navigation link
- **`v-sidebar.item-group`** – A collapsible group of navigation links
- **`v-sidebar.submenu-item`** – An entry in the user menu (link or form button)

## Basic Usage

```blade
<body class="flex h-screen">
    <x-v-sidebar>
        <x-v-sidebar.item icon="users" label="Users" route="users.index" />

        <x-v-sidebar.item-group icon="cogs" label="System">
            <x-v-sidebar.item icon="list" label="Logs" route="logs.index" />
        </x-v-sidebar.item-group>
    </x-v-sidebar>

    <main class="flex-1 overflow-y-auto">
        ...
    </main>
</body>
```

The sidebar takes the full height of its parent, so give its container a height. Use your app's own (unprefixed) Tailwind classes for your layout and slot content: verdant's stylesheet only contains the `v-` classes its own components use.

## What's Included by Default

| Part         | Shown when                                                         |
|--------------|--------------------------------------------------------------------|
| Logo         | Always. "Verdant", or "V" when collapsed                           |
| Dashboard    | A `GET /` route exists                                             |
| User button  | A user is logged in (`auth()->user()`), or `user-name` is passed   |
| Profile      | One of `verdant.sidebar.profile_routes` exists                     |
| Settings     | One of `verdant.sidebar.settings_routes` exists                    |
| Logout       | One of `verdant.sidebar.logout_routes` exists (POSTs with CSRF)    |

The user's image is read from the first filled attribute in `verdant.sidebar.user_image_attributes` (`profile_photo_url`, `avatar_url`, `avatar`). Without an image, their initials are shown.

## Props

| Prop             | Default                       | Description                                                   |
|------------------|-------------------------------|---------------------------------------------------------------|
| `brand`          | `config('verdant.sidebar.brand')` | Text logo; its first letter is used when collapsed        |
| `collapsed`      | `false`                       | Initial collapsed state                                       |
| `collapsible`    | `true`                        | Show the collapse toggle                                      |
| `collapse-icon`  | `'angles-left'`               | Toggle icon while expanded (Font Awesome name, `brands:` prefix supported) |
| `expand-icon`    | `'angles-right'`              | Toggle icon while collapsed                                   |
| `persist`        | `'verdant-sidebar-collapsed'` | localStorage key to remember the collapsed state. `false` disables |
| `dashboard-url`  | auto                          | URL of the dashboard link, `false` hides it                   |
| `dashboard-label`| `__('Dashboard')`             | Label of the dashboard link                                   |
| `dashboard-icon` | `'tachometer-alt'`            | Icon of the dashboard link                                    |
| `user`           | `auth()->user()`              | The user shown at the bottom                                  |
| `user-name`      | `$user->name`                 | Name next to the avatar                                       |
| `user-image`     | auto                          | Avatar URL                                                    |
| `profile-url`    | auto                          | `false` hides it                                              |
| `settings-url`   | auto                          | `false` hides it                                              |
| `logout-url`     | auto                          | `false` hides it                                              |
| `logout-method`  | auto (`post` unless the route is GET-only) | HTTP method used for logout                      |

## Slots

| Slot             | Replaces                                                   |
|------------------|------------------------------------------------------------|
| default          | Navigation items, rendered below the dashboard link        |
| `logo`           | The expanded logo                                          |
| `logoCollapsed`  | The collapsed logo                                         |
| `menu`           | Extra entries in the user menu, between Settings and Logout |
| `footer`         | The whole bottom area (user button and menu)               |

## Examples

### Custom logo

```blade
<x-v-sidebar>
    <x-slot:logo>
        <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="max-w-[13rem]">
    </x-slot:logo>

    <x-slot:logoCollapsed>
        <img src="{{ asset('images/logo-icon.png') }}" alt="{{ config('app.name') }}" class="w-10">
    </x-slot:logoCollapsed>

    ...
</x-v-sidebar>
```

### Extra user menu entries

```blade
<x-v-sidebar :settings-url="false">
    <x-slot:menu>
        <x-v-sidebar.submenu-item icon="bell" label="Notifications" route="notifications.index" />
        <x-v-sidebar.submenu-item icon="user-secret" label="Stop impersonating" route="impersonate.leave" method="delete" />
    </x-slot:menu>

    ...
</x-v-sidebar>
```

`v-sidebar.submenu-item` accepts `icon`, `label`, `href` or `route` + `route-params`, and `method`. A method other than `get` renders a form with CSRF token and method spoofing.

### Links that aren't named routes

`v-sidebar.item` accepts `href` next to `route`. Pass `active` yourself in that case:

```blade
<x-v-sidebar.item icon="book" label="Docs" href="https://verdant.dennenboom.be" :active="false" />
```

## Configuration

```php
// config/verdant.php
'sidebar' => [
    'brand' => 'Verdant',
    'profile_routes' => ['profile.show', 'profile.edit', 'profile.index', 'profile'],
    'settings_routes' => ['settings.index', 'settings.edit', 'settings.show', 'settings'],
    'logout_routes' => ['logout'],
    'user_image_attributes' => ['profile_photo_url', 'avatar_url', 'avatar'],
],
```

The first existing route of each list is used. Routes that require parameters are skipped; pass the URL as a prop instead.

## Collapsed State

When collapsed, the sidebar shrinks to 80px, hides labels and group children, and opens the user menu to the right. Clicking a group while collapsed expands the sidebar. The state is stored in localStorage under the `persist` key, so it survives navigation.

The collapsed styling hooks are plain classes you can use in custom items:

- `v-sidebar-label` – hidden when collapsed
- `v-sidebar-expanded-only` – hidden when collapsed
- `v-sidebar-collapsed-only` – only shown when collapsed
