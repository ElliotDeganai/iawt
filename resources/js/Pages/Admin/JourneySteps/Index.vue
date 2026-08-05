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
        steps: Array,
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
            router.delete(route('admin.journey-steps.destroy', this.confirmingDeleteId), {
                preserveScroll: true,
                onFinish: () => (this.confirmingDeleteId = null),
            });
        },
    },
};
</script>

<template>
    <Head title="Administration — Parcours entrepreneur" />

    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Parcours entrepreneur</h1>
        </template>

        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b border-gray-100 flex justify-end">
                <Link :href="route('admin.journey-steps.create')" class="inline-flex items-center rounded-md bg-primary-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-primary-700">
                    Ajouter une étape
                </Link>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Ordre</th>
                        <th class="px-4 py-3">Icône</th>
                        <th class="px-4 py-3">Libellé</th>
                        <th class="px-4 py-3">Image</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="s in steps" :key="s.id">
                        <td class="px-4 py-3 text-gray-500">{{ s.position }}</td>
                        <td class="px-4 py-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-primary-600 text-primary-700">
                                <Icon :name="s.icon" class="h-4 w-4" />
                            </span>
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ s.label }}</td>
                        <td class="px-4 py-3">
                            <img v-if="s.image" :src="`/storage/${s.image}`" class="h-10 w-14 rounded object-cover" alt="" />
                            <span v-else class="text-xs text-gray-400">—</span>
                        </td>
                        <td class="px-4 py-3 max-w-xs">
                            <span v-if="s.description" class="line-clamp-2 text-xs text-gray-500" v-html="s.description"></span>
                            <span v-else class="text-xs text-gray-400">—</span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <Link :href="route('admin.journey-steps.edit', s.id)" class="text-primary-600 hover:underline">Modifier</Link>
                            <button class="text-red-600 hover:underline" @click="confirmDelete(s.id)">Supprimer</button>
                        </td>
                    </tr>
                    <tr v-if="!steps.length">
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">Aucune étape pour le moment.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Modal :show="confirmingDeleteId !== null" @close="confirmingDeleteId = null">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Supprimer cette étape ?</h2>
                <p class="mt-1 text-sm text-gray-600">Cette action est irréversible.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="confirmingDeleteId = null">Annuler</SecondaryButton>
                    <DangerButton @click="destroy">Supprimer</DangerButton>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>
