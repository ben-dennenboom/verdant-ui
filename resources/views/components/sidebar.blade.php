@props([
    'brand' => null,
    'collapsed' => false,
    'collapsible' => true,
    'collapseIcon' => 'angles-left',
    'expandIcon' => 'angles-right',
    'persist' => 'verdant-sidebar-collapsed',
    'dashboardUrl' => null,
    'dashboardLabel' => null,
    'dashboardIcon' => 'tachometer-alt',
    'user' => null,
    'userName' => null,
    'userImage' => null,
    'profileUrl' => null,
    'settingsUrl' => null,
    'logoutUrl' => null,
    'logoutMethod' => null,
])

@php
    use Dennenboom\VerdantUI\Sidebar\SidebarResolver;

    $brand = $brand ?? config('verdant.sidebar.brand', 'Verdant');
    $dashboardUrl = $dashboardUrl ?? SidebarResolver::rootUrl();
    $dashboardLabel = $dashboardLabel ?? __('Dashboard');

    $user = $user ?? auth()->user();
    $userName = $userName ?? SidebarResolver::userName($user);
    $userImage = $userImage ?? SidebarResolver::userImage($user);

    $profileRoutes = config('verdant.sidebar.profile_routes', []);
    $settingsRoutes = config('verdant.sidebar.settings_routes', []);
    $logoutRoutes = config('verdant.sidebar.logout_routes', ['logout']);

    $profileUrl = $profileUrl ?? SidebarResolver::routeUrl($profileRoutes);
    $settingsUrl = $settingsUrl ?? SidebarResolver::routeUrl($settingsRoutes);
    $logoutUrl = $logoutUrl ?? SidebarResolver::routeUrl($logoutRoutes);
    $logoutMethod = $logoutMethod ?? SidebarResolver::routeMethod($logoutRoutes);

    $collapseIconClass = str_starts_with($collapseIcon, 'brands:')
        ? 'fa-brands fa-' . substr($collapseIcon, 7)
        : 'fa-solid fa-' . $collapseIcon;

    $expandIconClass = str_starts_with($expandIcon, 'brands:')
        ? 'fa-brands fa-' . substr($expandIcon, 7)
        : 'fa-solid fa-' . $expandIcon;

    $hasSubmenuLinks = $profileUrl || $settingsUrl || isset($menu);
    $hasSubmenu = $hasSubmenuLinks || $logoutUrl;
@endphp

<aside x-data="{
           collapsed: @js((bool) $collapsed),
           init() {
               @if($collapsible && $persist)
                   try {
                       const storedCollapsed = localStorage.getItem(@js($persist));

                       if (storedCollapsed !== null) {
                           this.collapsed = storedCollapsed === 'true';
                       }
                   } catch (e) {}

                   this.$watch('collapsed', value => {
                       try { localStorage.setItem(@js($persist), value); } catch (e) {}
                   });
               @endif
           }
       }"
       @v-sidebar-expand="collapsed = false"
       data-collapsed="{{ $collapsed ? 'true' : 'false' }}"
       :data-collapsed="collapsed"
    {{ $attributes->merge(['class' => 'v-sidebar v-group/sidebar v-flex v-flex-col v-flex-none v-h-full v-w-72 data-[collapsed=true]:v-w-20 v-bg-surface v-border-r v-border-gray-200 dark:v-border-gray-700 v-transition-[width] v-duration-200']) }}>
    <div class="v-flex v-items-center v-justify-between v-gap-2 v-px-4 v-py-5 group-data-[collapsed=true]/sidebar:v-flex-col group-data-[collapsed=true]/sidebar:v-justify-center">
        <a @if($dashboardUrl) href="{{ $dashboardUrl }}" @endif class="v-flex v-items-center v-min-w-0">
            <span class="v-sidebar-expanded-only v-min-w-0">
                @if(isset($logo))
                    {{ $logo }}
                @else
                    <span class="v-block v-truncate v-text-2xl v-font-bold v-tracking-tight v-text-primary-600 dark:v-text-primary-400">{{ $brand }}</span>
                @endif
            </span>
            <span class="v-sidebar-collapsed-only">
                @if(isset($logoCollapsed))
                    {{ $logoCollapsed }}
                @else
                    <span class="v-flex v-items-center v-justify-center v-w-10 v-h-10 v-rounded-lg v-bg-primary-600 v-text-lg v-font-bold v-text-white">{{ mb_substr($brand, 0, 1) }}</span>
                @endif
            </span>
        </a>

        @if($collapsible)
            <button type="button"
                    @click="collapsed = !collapsed"
                    :aria-label="collapsed ? @js(__('Expand menu')) : @js(__('Collapse menu'))"
                    :aria-expanded="!collapsed"
                    class="v-flex v-flex-none v-items-center v-justify-center v-w-8 v-h-8 v-rounded v-text-gray-400 hover:v-text-gray-700 dark:hover:v-text-gray-200 hover:v-bg-gray-100 dark:hover:v-bg-gray-700">
                <i class="v-sidebar-expanded-only {{ $collapseIconClass }}"></i>
                <i class="v-sidebar-collapsed-only {{ $expandIconClass }}"></i>
            </button>
        @endif
    </div>

    <nav class="v-flex-1 v-overflow-y-auto v-overflow-x-hidden v-px-3">
        <ul class="v-flex v-flex-col v-gap-1">
            @if($dashboardUrl)
                <x-v-sidebar.item :icon="$dashboardIcon" :label="$dashboardLabel" :href="$dashboardUrl" :active="request()->is('/')"/>
            @endif

            {{ $slot }}
        </ul>
    </nav>

    @if(isset($footer))
        {{ $footer }}
    @elseif($user || $userName)
        <div x-data="{ menuOpen: false }"
             @click.outside="menuOpen = false"
             @keydown.escape.window="menuOpen = false"
             class="v-relative v-p-3 v-border-t v-border-gray-200 dark:v-border-gray-700">
            <button type="button"
                    @if($hasSubmenu)
                        @click="menuOpen = !menuOpen"
                        aria-haspopup="menu"
                        :aria-expanded="menuOpen"
                    @endif
                    class="v-flex v-items-center v-gap-3 v-w-full v-p-2 v-rounded v-text-left v-text-gray-700 dark:v-text-gray-200 hover:v-bg-gray-100 dark:hover:v-bg-gray-700 group-data-[collapsed=true]/sidebar:v-justify-center">
                @if($userImage)
                    <img src="{{ $userImage }}" alt="{{ $userName }}" class="v-flex-none v-w-9 v-h-9 v-rounded-full v-object-cover">
                @else
                    <span class="v-flex v-flex-none v-items-center v-justify-center v-w-9 v-h-9 v-rounded-full v-bg-primary-100 dark:v-bg-primary-900 v-text-sm v-font-semibold v-text-primary-700 dark:v-text-primary-200">
                        {{ SidebarResolver::initials($userName) }}
                    </span>
                @endif

                @if($userName)
                    <span class="v-sidebar-label v-flex-1 v-min-w-0 v-truncate v-text-sm v-font-medium">{{ $userName }}</span>
                @endif

                @if($hasSubmenu)
                    <i class="v-sidebar-chevron fa-solid fa-chevron-up v-text-xs v-text-gray-400 v-transition-transform" :class="menuOpen ? 'v-rotate-180' : ''"></i>
                @endif
            </button>

            @if($hasSubmenu)
                <div x-show="menuOpen"
                     style="display: none"
                     x-transition.origin.bottom
                     role="menu"
                     :class="collapsed ? 'v-left-full v-bottom-3 v-ml-2 v-w-56' : 'v-bottom-full v-left-3 v-right-3 v-mb-1'"
                     class="v-absolute v-z-50 v-p-1 v-rounded-lg v-shadow-lg v-bg-floating v-border v-border-gray-200 dark:v-border-gray-700">
                    @if($profileUrl)
                        <x-v-sidebar.submenu-item icon="user" :label="__('Profile')" :href="$profileUrl"/>
                    @endif

                    @if($settingsUrl)
                        <x-v-sidebar.submenu-item icon="gear" :label="__('Settings')" :href="$settingsUrl"/>
                    @endif

                    {{ $menu ?? '' }}

                    @if($logoutUrl)
                        @if($hasSubmenuLinks)
                            <div class="v-my-1 v-border-t v-border-gray-200 dark:v-border-gray-700"></div>
                        @endif

                        <x-v-sidebar.submenu-item icon="right-from-bracket" :label="__('Logout')" :href="$logoutUrl" :method="$logoutMethod"/>
                    @endif
                </div>
            @endif
        </div>
    @endif
</aside>
