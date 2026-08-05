<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const STATUS_CONFIG = {
    draft:     { label: 'Brouillon', class: 'bg-gray-100 text-gray-600' },
    submitted: { label: 'Soumise',   class: 'bg-blue-50 text-blue-700' },
    accepted:  { label: 'Acceptée',  class: 'bg-green-50 text-green-700' },
    rejected:  { label: 'Refusée',   class: 'bg-red-50 text-red-700' },
};

export default {
    components: { AdminLayout, Head, Link },
    props: {
        applications: Array,
        filters: Object,
    },
    data() {
        return {
            search: this.filters.search ?? '',
            status: this.filters.status ?? '',
        };
    },
    methods: {
        statusConf(s) { return STATUS_CONFIG[s] ?? STATUS_CONFIG.draft; },
        formatDate(d) {
            if (!d) return '—';
            return new Date(d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' });
        },
        applyFilters() {
            router.get(route('admin.applications.index'), {
                search: this.search || undefined,
                status: this.status || undefined,
            }, { preserveState: true, replace: true });
        },
        initials(user) {
            return `${user.first_name?.[0] ?? ''}${user.last_name?.[0] ?? ''}`.toUpperCase();
        },
    },
};
</script>

<template>
    <Head title="Administration — Candidatures" />

    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Candidatures</h1>
        </template>

        <div class="bg-white rounded-lg shadow">
            <!-- Filtres -->
            <div class="flex flex-wrap items-center gap-3 border-b border-gray-100 p-4">
                <input
                    v-model="search"
                    type="text"
                    placeholder="Rechercher un candidat ou un projet…"
                    class="flex-1 min-w-[200px] rounded-md border-gray-200 text-sm"
                    @input="applyFilters"
                />
                <select v-model="status" class="rounded-md border-gray-200 text-sm w-40" @change="applyFilters">
                    <option value="">Tous les statuts</option>
                    <option value="submitted">Soumises</option>
                    <option value="accepted">Acceptées</option>
                    <option value="rejected">Refusées</option>
                    <option value="draft">Brouillons</option>
                </select>
                <span class="text-xs text-gray-400">{{ applications.length }} résultat(s)</span>
            </div>

            <!-- Tableau -->
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Candidat</th>
                        <th class="px-4 py-3">Projet</th>
                        <th class="px-4 py-3">Secteur</th>
                        <th class="px-4 py-3">Soumise le</th>
                        <th class="px-4 py-3">Statut</th>
                        <th class="px-4 py-3">Parcours</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="a in applications" :key="a.id" class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <img v-if="a.user.avatar" :src="`/storage/${a.user.avatar}`" class="h-8 w-8 rounded-full object-cover" alt="" />
                                <span v-else class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary-600 text-xs font-semibold text-white">
                                    {{ initials(a.user) }}
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate font-medium text-gray-800">{{ a.user.first_name }} {{ a.user.last_name }}</p>
                                    <p class="truncate text-xs text-gray-400">{{ a.user.email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-700">{{ a.project_name || '—' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ a.project_sector || '—' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ formatDate(a.submitted_at) }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold" :class="statusConf(a.status).class">
                                {{ statusConf(a.status).label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            <span v-if="a.status === 'accepted'">{{ a.journey_current_step }} / 7</span>
                            <span v-else class="text-gray-300">—</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <Link :href="route('admin.applications.show', a.id)" class="text-primary-600 hover:underline">Voir</Link>
                        </td>
                    </tr>
                    <tr v-if="!applications.length">
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">Aucune candidature trouvée.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
