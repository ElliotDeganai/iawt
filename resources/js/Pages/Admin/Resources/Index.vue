<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
export default {
    components: { AdminLayout, Head, Link },
    props: { categories: Array, resources: Array },
    methods: {
        destroy(r) { if (confirm(`Supprimer « ${r.title} » ?`)) router.delete(route('admin.resources.destroy', r.id)); },
    },
};
</script>
<template>
    <Head title="Administration — Ressources documentaires" />
    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-800">Ressources documentaires</h1>
                <Link :href="route('admin.resources.create')" class="inline-flex items-center gap-1.5 rounded-md bg-primary-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-primary-700">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>Ajouter
                </Link>
            </div>
        </template>
        <div class="overflow-hidden rounded-lg bg-white shadow">
            <div v-if="!resources.length" class="px-6 py-8 text-center text-sm text-gray-400">Aucune ressource.</div>
            <div v-for="r in resources" :key="r.id" class="flex items-center gap-3 border-b border-gray-100 px-5 py-3 last:border-0">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-xs font-bold" :class="r.type === 'pdf' ? 'bg-red-50 text-red-600' : r.type === 'video' ? 'bg-purple-50 text-purple-600' : 'bg-gray-50 text-gray-500'">{{ r.type.toUpperCase().slice(0,3) }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ r.title }}</p>
                    <p class="text-xs text-gray-400">{{ r.category?.name }}</p>
                </div>
                <span class="rounded-full px-2 py-0.5 text-[10px] font-medium" :class="r.is_published ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500'">{{ r.is_published ? 'Publié' : 'Brouillon' }}</span>
                <Link :href="route('admin.resources.edit', r.id)" class="text-xs text-primary-600 hover:underline">Modifier</Link>
                <button type="button" class="text-xs text-red-600 hover:underline" @click="destroy(r)">Supprimer</button>
            </div>
        </div>
    </AdminLayout>
</template>
