<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
export default {
    components: { AdminLayout, Head, Link },
    props: { sheets: Array },
    methods: {
        destroy(s) {
            if (confirm(`Supprimer la fiche « ${s.country_name} » ?`)) {
                router.delete(route('admin.country-sheets.destroy', s.id));
            }
        },
    },
};
</script>
<template>
    <Head title="Administration — Fiches pays (Étape 7)" />
    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-800">Fiches techniques pays</h1>
                <Link :href="route('admin.country-sheets.create')" class="inline-flex items-center gap-1.5 rounded-md bg-primary-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-primary-700">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                    Nouvelle fiche
                </Link>
            </div>
        </template>
        <div class="overflow-hidden rounded-lg bg-white shadow">
            <div v-if="!sheets.length" class="px-6 py-8 text-center text-sm text-gray-400">Aucune fiche pays. Créez-en une pour qu'elle apparaisse dans l'étape 7 du parcours.</div>
            <div v-for="s in sheets" :key="s.id" class="flex items-center gap-4 border-b border-gray-100 px-5 py-3 last:border-0">
                <span v-if="s.flag_code" :class="`fi fi-${s.flag_code}`" class="h-5 w-7 rounded-sm"></span>
                <span v-else class="flex h-5 w-8 items-center justify-center rounded bg-gray-100 text-[10px] text-gray-400">?</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800">{{ s.country_name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ s.title }}</p>
                </div>
                <span class="rounded-full px-2 py-0.5 text-[10px] font-medium" :class="s.is_published ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500'">{{ s.is_published ? 'Publié' : 'Brouillon' }}</span>
                <Link :href="route('admin.country-sheets.edit', s.id)" class="text-xs text-primary-600 hover:underline">Modifier</Link>
                <button type="button" class="text-xs text-red-600 hover:underline" @click="destroy(s)">Supprimer</button>
            </div>
        </div>
    </AdminLayout>
</template>