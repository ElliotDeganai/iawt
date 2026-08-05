<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head, Link, router } from '@inertiajs/vue3';

export default {
    components: { AdminLayout, SecondaryButton, DangerButton, Modal, Head, Link },
    props: {
        roles: Array,
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
            router.delete(route('admin.roles.destroy', this.confirmingDeleteId), {
                preserveScroll: true,
                onFinish: () => (this.confirmingDeleteId = null),
            });
        },
    },
};
</script>

<template>
    <Head title="Administration — Rôles & permissions" />

    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Rôles & permissions</h1>
        </template>

        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b border-gray-100 flex justify-end">
                <Link :href="route('admin.roles.create')" class="inline-flex items-center rounded-md bg-primary-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-primary-700">
                    Ajouter un rôle
                </Link>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Nom</th>
                        <th class="px-4 py-3">Slug</th>
                        <th class="px-4 py-3">Permissions</th>
                        <th class="px-4 py-3">Utilisateurs</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="r in roles" :key="r.id">
                        <td class="px-4 py-3 text-gray-800">{{ r.name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ r.slug }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ r.permissions.length }} permission(s)</td>
                        <td class="px-4 py-3 text-gray-600">{{ r.users_count }}</td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <Link :href="route('admin.roles.edit', r.id)" class="text-primary-600 hover:underline">Modifier</Link>
                            <button class="text-red-600 hover:underline" @click="confirmDelete(r.id)">Supprimer</button>
                        </td>
                    </tr>
                    <tr v-if="!roles.length">
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">Aucun rôle pour le moment.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Modal :show="confirmingDeleteId !== null" @close="confirmingDeleteId = null">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Supprimer ce rôle ?</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Cette action est irréversible. Impossible si des utilisateurs y sont encore rattachés.
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="confirmingDeleteId = null">Annuler</SecondaryButton>
                    <DangerButton @click="destroy">Supprimer</DangerButton>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>
