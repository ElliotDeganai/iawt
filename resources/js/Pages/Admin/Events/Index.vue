<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

export default {
    components: { AdminLayout, Head, Link },
    props: { events: Array },
    methods: {
        formatDate(d) { return new Date(d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' }); },
        destroy(event) {
            if (confirm(`Supprimer « ${event.title} » ?`)) {
                router.delete(route('admin.events.destroy', event.id));
            }
        },
    },
};
</script>

<template>
    <Head title="Administration — Événements" />
    <AdminLayout>
        <template #header>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h1 class="text-lg font-semibold text-gray-800">Événements</h1>
                <div class="flex gap-2">
                    <Link :href="route('admin.event-categories.index')" class="inline-flex items-center gap-1.5 rounded-md border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        Catégories
                    </Link>
                    <Link :href="route('admin.events.create')" class="inline-flex items-center gap-1.5 rounded-md bg-primary-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-primary-700">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                        Nouvel événement
                    </Link>
                </div>
            </div>
        </template>

        <div class="overflow-x-auto rounded-lg bg-white shadow">
            <table class="min-w-[700px] w-full text-sm">
                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Image</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Titre</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Catégorie</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Statut</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="event in events" :key="event.id" class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <img v-if="event.image" :src="`/storage/${event.image}`" class="h-10 w-14 rounded object-cover" alt="" />
                            <div v-else class="flex h-10 w-14 items-center justify-center rounded bg-gray-100"><svg class="h-5 w-5 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.41a2.25 2.25 0 013.182 0l2.909 2.91M3.75 21h16.5"/></svg></div>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ event.title }}</p>
                            <p v-if="event.description" class="mt-0.5 text-xs text-gray-400 line-clamp-1">{{ event.description }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span v-if="event.category" class="inline-flex items-center gap-1.5 rounded-full px-2 py-0.5 text-[10px] font-medium max-w-[140px]" :style="{ background: event.category.color + '18', color: event.category.color }" :title="event.category.name">
                                <span class="h-1.5 w-1.5 rounded-full shrink-0" :style="{ background: event.category.color }"></span>
                                <span class="truncate">{{ event.category.name }}</span>
                            </span>
                            <span v-else class="text-xs text-gray-400">—</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ formatDate(event.date) }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <span class="rounded-full px-2 py-0.5 text-[10px] font-medium" :class="event.is_published ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500'">{{ event.is_published ? 'Publié' : 'Brouillon' }}</span>
                                <span v-if="event.is_featured" class="rounded-full bg-gold-50 px-2 py-0.5 text-[10px] font-medium text-gold-700">Vedette</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <Link :href="route('admin.events.edit', event.id)" class="text-xs text-primary-600 hover:underline">Modifier</Link>
                                <button type="button" class="text-xs text-red-600 hover:underline" @click="destroy(event)">Supprimer</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p v-if="!events.length" class="px-6 py-8 text-center text-sm text-gray-400">Aucun événement.</p>
        </div>
    </AdminLayout>
</template>