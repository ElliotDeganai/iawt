<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head, Link, router } from '@inertiajs/vue3';

export default {
    components: { AdminLayout, SecondaryButton, DangerButton, Modal, Head, Link },
    props: { events: Array },
    data() { return { confirmingDeleteId: null }; },
    methods: {
        formatDate(d) {
            return new Date(d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' });
        },
        confirmDelete(id) { this.confirmingDeleteId = id; },
        destroy() {
            router.delete(route('admin.events.destroy', this.confirmingDeleteId), {
                preserveScroll: true,
                onFinish: () => (this.confirmingDeleteId = null),
            });
        },
    },
};
</script>

<template>
    <Head title="Administration — Agenda" />
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Agenda — Événements</h1>
        </template>

        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b border-gray-100 flex justify-end">
                <Link :href="route('admin.events.create')" class="inline-flex items-center rounded-md bg-primary-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-primary-700">
                    Ajouter un événement
                </Link>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Heure</th>
                        <th class="px-4 py-3">Titre</th>
                        <th class="px-4 py-3">Statut</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="e in events" :key="e.id">
                        <td class="px-4 py-3 text-gray-600">{{ formatDate(e.date) }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ e.time?.slice(0,5) || '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="font-medium text-gray-800">{{ e.title }}</span>
                            <span v-if="e.is_featured" class="ml-2 inline-flex items-center rounded-full bg-gold-100 px-2 py-0.5 text-[10px] font-semibold text-gold-700">★ Vedette</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs" :class="e.is_published ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500'">
                                {{ e.is_published ? 'Publié' : 'Brouillon' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <Link :href="route('admin.events.edit', e.id)" class="text-primary-600 hover:underline">Modifier</Link>
                            <button class="text-red-600 hover:underline" @click="confirmDelete(e.id)">Supprimer</button>
                        </td>
                    </tr>
                    <tr v-if="!events.length">
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">Aucun événement pour le moment.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Modal :show="confirmingDeleteId !== null" @close="confirmingDeleteId = null">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Supprimer cet événement ?</h2>
                <p class="mt-1 text-sm text-gray-600">Cette action est irréversible.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="confirmingDeleteId = null">Annuler</SecondaryButton>
                    <DangerButton @click="destroy">Supprimer</DangerButton>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>
