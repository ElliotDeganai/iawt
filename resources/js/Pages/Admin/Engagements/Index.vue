<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Icon from '@/Components/Icon.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head, Link, router } from '@inertiajs/vue3';

export default {
    components: { AdminLayout, Icon, SecondaryButton, DangerButton, Modal, Head, Link },
    props: {
        engagements: Array,
    },
    data() {
        return {
            confirmingDeleteId: null,
        };
    },
    methods: {
        confirmDelete(id) {
            this.confirmingDeleteId = id;
        },
        destroy() {
            router.delete(route('admin.engagements.destroy', this.confirmingDeleteId), {
                preserveScroll: true,
                onFinish: () => (this.confirmingDeleteId = null),
            });
        },
    },
};
</script>

<template>
    <Head title="Administration — Nos engagements" />

    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Nos engagements (page d'accueil)</h1>
        </template>

        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b border-gray-100 flex justify-end">
                <Link :href="route('admin.engagements.create')" class="inline-flex items-center rounded-md bg-primary-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-primary-700">
                    Ajouter un engagement
                </Link>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Ordre</th>
                        <th class="px-4 py-3">Icône</th>
                        <th class="px-4 py-3">Titre</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="e in engagements" :key="e.id">
                        <td class="px-4 py-3 text-gray-500">{{ e.position }}</td>
                        <td class="px-4 py-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-600 text-white">
                                <Icon :name="e.icon" class="h-4 w-4" />
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-800">{{ e.title }}</td>
                        <td class="px-4 py-3 text-gray-500 max-w-xs truncate">{{ e.description }}</td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <Link :href="route('admin.engagements.edit', e.id)" class="text-primary-600 hover:underline">Modifier</Link>
                            <button class="text-red-600 hover:underline" @click="confirmDelete(e.id)">Supprimer</button>
                        </td>
                    </tr>
                    <tr v-if="!engagements.length">
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">Aucun engagement pour le moment.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Modal :show="confirmingDeleteId !== null" @close="confirmingDeleteId = null">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Supprimer cet engagement ?</h2>
                <p class="mt-1 text-sm text-gray-600">Cette action est irréversible.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="confirmingDeleteId = null">Annuler</SecondaryButton>
                    <DangerButton @click="destroy">Supprimer</DangerButton>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>
