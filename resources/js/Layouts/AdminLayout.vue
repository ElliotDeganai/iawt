<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: { Link },
    computed: {
        user() {
            return this.$page.props.auth.user;
        },
        permissions() {
            return this.$page.props.auth.permissions;
        },
        navSections() {
            return [
                {
                    items: [
                        { label: 'Tableau de bord', href: route('admin.dashboard'), active: route().current('admin.dashboard'), icon: 'dashboard' },
                    ],
                },
                {
                    label: "Page d'accueil",
                    items: [
                        { label: 'Hero & programme', href: route('admin.home-content.edit'), active: route().current('admin.home-content.*'), icon: 'edit', show: this.permissions.includes('home_content.manage') },
                        { label: 'Nos engagements', href: route('admin.engagements.index'), active: route().current('admin.engagements.*'), icon: 'heart', show: this.permissions.includes('home_content.manage') },
                        { label: 'Parcours 7 étapes', href: route('admin.journey-steps.index'), active: route().current('admin.journey-steps.*'), icon: 'steps', show: this.permissions.includes('home_content.manage') },
                        { label: 'Chiffres clés', href: route('admin.stats.index'), active: route().current('admin.stats.*'), icon: 'chart', show: this.permissions.includes('home_content.manage') },
                        { label: 'Partenaires', href: route('admin.partners.index'), active: route().current('admin.partners.*'), icon: 'handshake', show: this.permissions.includes('home_content.manage') },
                        { label: 'Témoignages', href: route('admin.testimonials.index'), active: route().current('admin.testimonials.*'), icon: 'quote', show: this.permissions.includes('home_content.manage') },
                    ],
                },
                {
                    label: 'Contenu',
                    items: [
                        { label: 'Pays', href: route('admin.countries.index'), active: route().current('admin.countries.*'), icon: 'globe', show: this.permissions.includes('countries.manage') },
                        { label: 'Types de lieux', href: route('admin.place-categories.index'), active: route().current('admin.place-categories.*'), icon: 'map', show: this.permissions.includes('countries.manage') },
                        { label: 'Agenda', href: route('admin.events.index'), active: route().current('admin.events.*'), icon: 'calendar', show: this.permissions.includes('events.manage') },
                    ],
                },
                {
                    label: 'Programme',
                    items: [
                        { label: 'Candidatures', href: route('admin.applications.index'), active: route().current('admin.applications.*'), icon: 'clipboard', show: this.permissions.includes('applications.manage') },
                    ],
                },
                {
                    label: 'Système',
                    items: [
                        { label: 'Utilisateurs', href: route('admin.users.index'), active: route().current('admin.users.*'), icon: 'users', show: this.permissions.includes('users.manage') },
                        { label: 'Rôles & permissions', href: route('admin.roles.index'), active: route().current('admin.roles.*'), icon: 'lock', show: this.permissions.includes('roles.manage') },
                        { label: 'Réglages', href: route('admin.settings.edit'), active: route().current('admin.settings.*'), icon: 'settings', show: this.permissions.includes('settings.manage') },
                    ],
                },
            ].map(section => ({
                ...section,
                items: section.items.filter(item => item.show !== false),
            })).filter(section => section.items.length > 0);
        },
    },
    methods: {
        iconPath(name) {
            const icons = {
                dashboard:  'M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z',
                edit:       'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                heart:      'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                steps:      'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
                globe:      'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9',
                calendar:   'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                clipboard:  'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                users:      'M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75',
                lock:       'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
                settings:   'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z',
                chart:      'M16 8v8m-4-5v5m-4-2v2m-2 4h16a2 2 0 002-2V6a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z',
                handshake:  'M7 11l4-4 4 4m-8 4l4-4 4 4',
                quote:      'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
                map:        'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l5.447 2.724A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7',
            };
            return icons[name] || '';
        },
    },
};
</script>

<template>
    <div class="min-h-screen bg-cream flex">
        <aside class="w-64 shrink-0 bg-primary-800 text-white flex flex-col">
            <div class="px-5 py-4 border-b border-primary-700">
                <Link :href="route('home')" class="text-lg font-serif font-bold">InAfrikaWeTrust</Link>
                <p class="text-[10px] text-primary-300 mt-0.5 uppercase tracking-wider">Administration</p>
            </div>

            <nav class="flex-1 px-3 py-3 space-y-1 overflow-y-auto">
                <template v-for="(section, si) in navSections" :key="si">
                    <!-- Label de section -->
                    <p v-if="section.label" class="px-3 pt-4 pb-1 text-[9px] font-medium uppercase tracking-widest text-primary-400">
                        {{ section.label }}
                    </p>

                    <!-- Items -->
                    <Link
                        v-for="item in section.items"
                        :key="item.href"
                        :href="item.href"
                        class="flex items-center gap-2.5 rounded-md px-3 py-2 text-sm transition"
                        :class="item.active ? 'bg-primary-700 text-white font-medium' : 'text-primary-100 hover:bg-primary-700/50'"
                    >
                        <svg class="h-4 w-4 shrink-0" :class="item.active ? 'opacity-80' : 'opacity-40'" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path :d="iconPath(item.icon)" />
                        </svg>
                        {{ item.label }}
                    </Link>
                </template>
            </nav>

            <div class="px-3 py-3 border-t border-primary-700 space-y-1">
                <Link :href="route('dashboard')" class="flex items-center gap-2.5 rounded-md px-3 py-2 text-sm text-primary-300 hover:bg-primary-700/50">
                    <svg class="h-4 w-4 opacity-40" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                    Retour au site
                </Link>
                <Link :href="route('logout')" method="post" as="button" class="flex w-full items-center gap-2.5 rounded-md px-3 py-2 text-sm text-primary-300 hover:bg-primary-700/50">
                    <svg class="h-4 w-4 opacity-40" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Se déconnecter
                </Link>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <div v-if="$slots.header">
                    <slot name="header" />
                </div>
                <div class="text-sm text-gray-500">Connecté en tant que <span class="font-medium text-gray-700">{{ user.full_name }}</span></div>
            </header>

            <main class="flex-1 p-6">
                <div
                    v-if="$page.props.flash.success"
                    class="mb-4 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700"
                >
                    {{ $page.props.flash.success }}
                </div>
                <div
                    v-if="$page.props.flash.error"
                    class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700"
                >
                    {{ $page.props.flash.error }}
                </div>

                <slot />
            </main>
        </div>
    </div>
</template>
