<header class="v-w-full v-border-b v-border-gray-200 v-bg-white">
    <div class="v-flex v-items-center v-justify-between v-py-2 v-px-4">
        <div class="v-flex v-items-center">
            <button @click="openMenu = !openMenu" class="v-mr-4 v-p-2 v-text-xl v-text-gray-700 hover:v-text-gray-900">
                <i class="fas fa-bars"></i>
                <span class="v-ml-3 v-hidden md:v-inline-block" x-show="!mobileMenu">@yield('title')</span>
            </button>
        </div>

        <div class="v-flex v-items-center v-space-x-4">
            <x-v-customer-selector :selected="active_customer(true)"/>

            <x-v-user-profile-menu/>
        </div>
    </div>
</header>
