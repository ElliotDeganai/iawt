<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

export default {
    components: { AdminLayout, Head, Link },
    props: {
        stats: Object,
        recentUsers: Array,
    },
};
</script>

<template>
    <Head title="Administration — Tableau de bord" />

    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Tableau de bord</h1>
        </template>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="bg-white rounded-lg shadow p-5">
                <p class="text-sm text-gray-500">Utilisateurs au total</p>
                <p class="mt-1 text-2xl font-semibold text-primary-700">{{ stats.totalUsers }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-5">
                <p class="text-sm text-gray-500">Candidats / porteurs de projet</p>
                <p class="mt-1 text-2xl font-semibold text-primary-700">{{ stats.totalCandidates }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-5">
                <p class="text-sm text-gray-500">Membres de l'équipe (back-office)</p>
                <p class="mt-1 text-2xl font-semibold text-primary-700">{{ stats.totalStaff }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-5">
                <p class="text-sm text-gray-500">Rôles configurés</p>
                <p class="mt-1 text-2xl font-semibold text-primary-700">{{ stats.totalRoles }}</p>
            </div>
        </div>

        <div class="mt-6 bg-white rounded-lg shadow">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-medium text-gray-800">Derniers utilisateurs inscrits</h2>
                <Link :href="route('admin.users.index')" class="text-sm text-primary-600 hover:underline">Voir tout</Link>
            </div>
            <ul class="divide-y divide-gray-100">
                <li v-for="user in recentUsers" :key="user.id" class="px-5 py-3 flex items-center justify-between">
                    <span class="text-sm text-gray-700">{{ user.first_name }} {{ user.last_name }} — {{ user.email }}</span>
                    <span class="text-xs text-gray-400">{{ new Date(user.created_at).toLocaleDateString('fr-FR') }}</span>
                </li>
                <li v-if="!recentUsers.length" class="px-5 py-3 text-sm text-gray-400">Aucun utilisateur pour le moment.</li>
            </ul>
        </div>
    </AdminLayout>
</template>
