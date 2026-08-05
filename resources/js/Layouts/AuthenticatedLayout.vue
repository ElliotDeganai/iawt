<script>
import { Link } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

export default {
    components: { Link, Dropdown, DropdownLink, NavLink, ResponsiveNavLink },
    data() {
        return {
            showingNavigationDropdown: false,
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
};
</script>

<template>
    <div class="min-h-screen bg-cream">
        <nav class="border-b border-gray-100 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        <div class="flex shrink-0 items-center">
                            <Link :href="route('home')" class="text-lg font-serif font-bold text-primary-600">
                                InAfrikaWeTrust
                            </Link>
                        </div>

                        <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                            <NavLink :href="route('home')" :active="route().current('home')">Accueil</NavLink>
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')">Mon parcours</NavLink>
                        </div>
                    </div>

                    <div class="hidden sm:ms-6 sm:flex sm:items-center">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button class="inline-flex items-center rounded-md border border-transparent px-3 py-2 text-sm font-medium leading-4 text-gray-600 transition hover:text-gray-900 focus:outline-none">
                                    {{ user.full_name }}
                                    <svg class="-me-0.5 ms-2 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')">Mon compte</DropdownLink>
                                <DropdownLink v-if="isAdmin" href="/admin">Administration</DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">Se déconnecter</DropdownLink>
                            </template>
                        </Dropdown>
                    </div>

                    <div class="-me-2 flex items-center sm:hidden">
                        <button
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-500 focus:outline-none"
                            @click="showingNavigationDropdown = !showingNavigationDropdown"
                        >
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path
                                    :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
                <div class="space-y-1 pb-3 pt-2">
                    <ResponsiveNavLink :href="route('home')" :active="route().current('home')">Accueil</ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">Mon parcours</ResponsiveNavLink>
                </div>

                <div class="border-t border-gray-200 pb-1 pt-4">
                    <div class="px-4">
                        <div class="text-base font-medium text-gray-800">{{ user.full_name }}</div>
                        <div class="text-sm font-medium text-gray-500">{{ user.email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')">Mon compte</ResponsiveNavLink>
                        <ResponsiveNavLink v-if="isAdmin" href="/admin">Administration</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button">Se déconnecter</ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <header v-if="$slots.header" class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <main>
            <slot />
        </main>
    </div>
</template>
