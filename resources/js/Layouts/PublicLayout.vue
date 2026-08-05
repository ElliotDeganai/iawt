<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: { Link },
    props: {
        transparentNav: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            dropdownOpen: false,
        };
    },
    computed: {
        user() {
            return this.$page.props.auth.user;
        },
        isAdmin() {
            return this.$page.props.auth.isAdmin;
        },
    },
    mounted() {
        this._closeHandler = (e) => {
            if (!this.$el.contains(e.target)) this.dropdownOpen = false;
        };
        document.addEventListener('click', this._closeHandler);
    },
    beforeUnmount() {
        document.removeEventListener('click', this._closeHandler);
    },
};
</script>

<template>
    <div class="min-h-screen bg-cream">
        <nav class="sticky top-0 z-40 border-b border-gray-100 bg-white">
            <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <!-- Logo -->
                <Link :href="route('home')" class="flex items-center gap-2">
                    <img :src="$page.props.siteSettings.logo" class="h-9 w-9 rounded-full object-cover" alt="Logo" />
                    <span>
                        <span class="block font-serif text-lg font-bold leading-tight text-primary-700">InAfrikaWeTrust</span>
                        <span class="block text-[11px] uppercase tracking-wide text-gray-400">Incubateur · Tourisme · Agriculture</span>
                    </span>
                </Link>

                <!-- Nav desktop -->
                <div class="hidden items-center gap-8 lg:flex">
                    <Link :href="route('home')" class="border-b-2 pb-1 text-sm font-medium" :class="route().current('home') ? 'border-primary-600 text-primary-700' : 'border-transparent text-gray-600 hover:text-primary-700'">
                        Accueil
                    </Link>
                    <Link :href="route('countries.current')" class="border-b-2 pb-1 text-sm font-medium" :class="route().current('countries.*') ? 'border-primary-600 text-primary-700' : 'border-transparent text-gray-600 hover:text-primary-700'">Le pays à l'honneur</Link>
                    <Link :href="route('journey.index')" class="border-b-2 pb-1 text-sm font-medium" :class="route().current('journey.*') ? 'border-primary-600 text-primary-700' : 'border-transparent text-gray-600 hover:text-primary-700'">Le parcours</Link>
                    <Link :href="route('agenda.index')" class="border-b-2 pb-1 text-sm font-medium" :class="route().current('agenda.*') ? 'border-primary-600 text-primary-700' : 'border-transparent text-gray-600 hover:text-primary-700'">Agenda</Link>
                    <Link :href="route('gallery.index')" class="border-b-2 pb-1 text-sm font-medium" :class="route().current('gallery.*') ? 'border-primary-600 text-primary-700' : 'border-transparent text-gray-600 hover:text-primary-700'">Galerie</Link>
                    <Link :href="route('application.index')" class="border-b-2 pb-1 text-sm font-medium" :class="route().current('application.*') ? 'border-primary-600 text-primary-700' : 'border-transparent text-gray-600 hover:text-primary-700'">Candidater</Link>
                </div>

                <!-- Zone utilisateur -->
                <div class="flex items-center gap-4">
                    <template v-if="user">
                        <div class="relative">
                            <button
                                type="button"
                                class="flex items-center gap-2 rounded-full border border-gray-200 bg-white py-1.5 pl-3 pr-4 text-sm font-medium text-gray-700 shadow-sm hover:border-primary-300 hover:text-primary-700 transition"
                                @click="dropdownOpen = !dropdownOpen"
                            >
                                <img v-if="user.avatar" :src="`/storage/${user.avatar}`" class="h-7 w-7 rounded-full object-cover" alt="" />
                                <span v-else class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-xs font-bold text-white uppercase">
                                    {{ user.first_name?.[0] }}{{ user.last_name?.[0] }}
                                </span>
                                <span class="max-w-[120px] truncate">{{ user.first_name }} {{ user.last_name }}</span>
                                <svg class="h-4 w-4 text-gray-400 transition" :class="{ 'rotate-180': dropdownOpen }" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <transition
                                enter-active-class="transition ease-out duration-100"
                                enter-from-class="opacity-0 scale-95"
                                enter-to-class="opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="opacity-100 scale-100"
                                leave-to-class="opacity-0 scale-95"
                            >
                                <div
                                    v-show="dropdownOpen"
                                    class="absolute right-0 mt-2 w-52 rounded-xl border border-gray-100 bg-white py-1 shadow-lg"
                                    @click="dropdownOpen = false"
                                >
                                    <div class="border-b border-gray-100 px-4 py-2.5">
                                        <p class="text-xs font-semibold text-gray-900 truncate">{{ user.first_name }} {{ user.last_name }}</p>
                                        <p class="text-xs text-gray-400 truncate">{{ user.email }}</p>
                                    </div>

                                    <Link :href="route('profile.edit')" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
                                        Mon profil
                                    </Link>

                                    <Link :href="route('dashboard')" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                        Mon parcours
                                    </Link>

                                    <Link v-if="isAdmin" href="/admin" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Administration
                                    </Link>

                                    <div class="my-1 border-t border-gray-100"></div>

                                    <Link :href="route('logout')" method="post" as="button" class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Se déconnecter
                                    </Link>
                                </div>
                            </transition>
                        </div>
                    </template>

                    <template v-else>
                        <Link :href="route('login')" class="rounded-full border border-primary-200 px-5 py-2.5 text-sm font-semibold text-primary-700 hover:border-primary-400 hover:bg-primary-50 transition">
                            Connexion
                        </Link>
                    </template>
                </div>
            </div>
        </nav>

        <main>
            <slot />
        </main>

        <footer class="border-t border-primary-800 bg-primary-900 py-6 text-center text-sm text-primary-200">
            © {{ new Date().getFullYear() }} {{ $page.props.settings?.site_name || 'InAfrikaWeTrust' }} — Tous droits réservés.
        </footer>
    </div>
</template>
