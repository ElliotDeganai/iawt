<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

export default {
    components: { AdminLayout, Head, Link },
    props: {
        stats: Object,
        pendingApplications: Array,
        pendingSteps: Array,
        pendingForumPosts: Array,
        recentUsers: Array,
    },
    methods: {
        timeAgo(date) {
            const d = new Date(date);
            const now = new Date();
            const diff = Math.floor((now - d) / 1000);
            if (diff < 60) return 'à l\'instant';
            if (diff < 3600) return Math.floor(diff / 60) + ' min';
            if (diff < 86400) return Math.floor(diff / 3600) + ' h';
            return Math.floor(diff / 86400) + ' j';
        },
        initials(user) {
            return (user?.first_name?.[0] || '') + (user?.last_name?.[0] || '');
        },
    },
};
</script>

<template>
    <Head title="Administration — Tableau de bord" />

    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Tableau de bord</h1>
        </template>

        <!-- Welcome banner -->
        <div class="rounded-2xl bg-primary-800 px-8 py-7 flex items-center justify-between mb-6">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[2px] text-gold-400">Tableau de bord</p>
                <p class="mt-1.5 font-serif text-xl text-white">Bonjour, {{ $page.props.auth.user.first_name }}</p>
            </div>
            <div class="flex gap-8">
                <div class="text-center">
                    <p class="text-2xl font-bold text-gold-400">{{ stats.totalUsers }}</p>
                    <p class="text-[10px] text-white/40">utilisateurs</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-gold-400">{{ stats.acceptedApplications || 0 }}</p>
                    <p class="text-[10px] text-white/40">en parcours</p>
                </div>
            </div>
        </div>

        <!-- Action cards -->
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 mb-6">

            <!-- Candidatures -->
            <div class="rounded-2xl bg-white overflow-hidden" :class="pendingApplications.length ? 'border-2 border-gold-400' : 'border border-gray-200'">
                <div class="flex items-center gap-2.5 px-5 py-3 border-b border-gray-100">
                    <span v-if="pendingApplications.length" class="flex h-6 w-6 items-center justify-center rounded-full bg-gold-500 text-[11px] font-bold text-white">{{ pendingApplications.length }}</span>
                    <p class="text-sm font-semibold text-gray-800">Candidatures à examiner</p>
                    <Link :href="route('admin.applications.index')" class="ml-auto text-xs text-primary-600 hover:underline">Voir tout →</Link>
                </div>
                <div class="divide-y divide-gray-50">
                    <Link v-for="app in pendingApplications" :key="app.id" :href="route('admin.applications.show', app.id)" class="flex items-center gap-3 px-5 py-3 hover:bg-cream/50 transition group">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-primary-600 to-primary-800 text-xs font-bold text-white">{{ initials(app.user) }}</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800">{{ app.user?.first_name }} {{ app.user?.last_name }}</p>
                            <p class="text-[11px] text-gray-400 truncate">{{ app.user?.email }}</p>
                        </div>
                        <span class="rounded-full bg-gold-50 px-2.5 py-0.5 text-[10px] font-semibold text-gold-700">{{ timeAgo(app.submitted_at) }}</span>
                    </Link>
                </div>
                <div v-if="!pendingApplications.length" class="px-5 py-6 text-center">
                    <p class="text-sm text-gray-400">Aucune candidature en attente</p>
                </div>
            </div>

            <!-- Étapes -->
            <div class="rounded-2xl bg-white overflow-hidden" :class="pendingSteps.length ? 'border-2 border-primary-400' : 'border border-gray-200'">
                <div class="flex items-center gap-2.5 px-5 py-3 border-b border-gray-100">
                    <span v-if="pendingSteps.length" class="flex h-6 w-6 items-center justify-center rounded-full bg-primary-600 text-[11px] font-bold text-white">{{ pendingSteps.length }}</span>
                    <p class="text-sm font-semibold text-gray-800">Étapes à valider</p>
                </div>
                <div class="divide-y divide-gray-50">
                    <Link v-for="s in pendingSteps" :key="s.id" :href="route('admin.applications.show', s.application_id)" class="flex items-center gap-3 px-5 py-3 hover:bg-cream/50 transition group">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[10px] bg-primary-50 text-sm font-bold text-primary-700">{{ s.step_number }}</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800">{{ s.user?.first_name }} {{ s.user?.last_name }}</p>
                            <p class="text-[11px] text-gray-400">Étape {{ s.step_number }}</p>
                        </div>
                        <span class="rounded-full bg-primary-50 px-2.5 py-0.5 text-[10px] font-semibold text-primary-700">{{ timeAgo(s.completed_at) }}</span>
                    </Link>
                </div>
                <div v-if="!pendingSteps.length" class="px-5 py-6 text-center">
                    <p class="text-sm text-gray-400">Aucune étape en attente</p>
                </div>
            </div>

            <!-- Forum à modérer -->
            <div class="rounded-2xl bg-white overflow-hidden lg:col-span-2" :class="pendingForumPosts.length ? 'border-2 border-gold-300' : 'border border-gray-200'">
                <div class="flex items-center gap-2.5 px-5 py-3 border-b border-gray-100">
                    <span v-if="pendingForumPosts.length" class="flex h-6 w-6 items-center justify-center rounded-full bg-gold-500 text-[11px] font-bold text-white">{{ pendingForumPosts.length }}</span>
                    <p class="text-sm font-semibold text-gray-800">Forum — publications à modérer</p>
                    <Link :href="route('admin.forum-moderation.index')" class="ml-auto text-xs text-primary-600 hover:underline">Modération →</Link>
                </div>
                <div class="divide-y divide-gray-50">
                    <Link v-for="fp in pendingForumPosts" :key="fp.id" :href="route('admin.forum-moderation.index')" class="flex items-center gap-3 px-5 py-3 hover:bg-cream/50 transition group">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-gold-400 to-gold-600 text-xs font-bold text-white">{{ initials(fp.user) }}</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-semibold text-gray-800">{{ fp.title || '(Réponse)' }}</p>
                                <span class="rounded-full bg-primary-50 px-2 py-0.5 text-[9px] font-medium text-primary-700">{{ fp.channel?.name }}</span>
                            </div>
                            <div class="mt-0.5 text-[11px] text-gray-400 line-clamp-1" v-html="fp.body"></div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="rounded-full bg-amber-50 px-2 py-0.5 text-[10px] font-semibold text-amber-700">{{ timeAgo(fp.created_at) }}</span>
                            <p class="mt-1 text-[10px] text-gray-400">{{ fp.user?.first_name }} {{ fp.user?.last_name }}</p>
                        </div>
                    </Link>
                </div>
                <div v-if="!pendingForumPosts.length" class="px-5 py-6 text-center">
                    <p class="text-sm text-gray-400">Aucune publication en attente de modération</p>
                </div>
            </div>
        </div>

        <!-- Derniers inscrits -->
        <div class="rounded-2xl bg-white overflow-hidden border border-gray-200">
            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100">
                <p class="text-sm font-semibold text-gray-800">Derniers inscrits</p>
                <Link :href="route('admin.users.index')" class="text-xs text-primary-600 hover:underline">Voir tout →</Link>
            </div>
            <div class="divide-y divide-gray-50">
                <div v-for="user in recentUsers" :key="user.id" class="flex items-center gap-3 px-5 py-2.5">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-100 text-[10px] font-semibold text-gray-500">{{ initials(user) }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700">{{ user.first_name }} {{ user.last_name }}</p>
                        <p class="text-[11px] text-gray-400 truncate">{{ user.email }}</p>
                    </div>
                    <span class="text-xs text-gray-400 shrink-0">{{ new Date(user.created_at).toLocaleDateString('fr-FR') }}</span>
                </div>
                <div v-if="!recentUsers.length" class="px-5 py-6 text-center text-sm text-gray-400">Aucun utilisateur pour le moment.</div>
            </div>
        </div>
    </AdminLayout>
</template>