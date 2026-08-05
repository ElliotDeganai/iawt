<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import TextInput from '@/Components/TextInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head, Link, router } from '@inertiajs/vue3';

export default {
    components: { AdminLayout, Pagination, TextInput, SecondaryButton, DangerButton, Modal, Head, Link },
    props: {
        users: Object,
        roles: Array,
        filters: Object,
    },
    data() {
        return {
            search: this.filters.search || '',
            role: this.filters.role || '',
            confirmingDeleteId: null,
        };
    },
    methods: {
        applyFilters() {
            router.get(route('admin.users.index'), { search: this.search, role: this.role }, {
                preserveState: true,
                replace: true,
            });
        },
        roleLabel(user) {
            return user.role ? user.role.name : 'Candidat (aucun rôle back-office)';
        },
        confirmDelete(id) {
            this.confirmingDeleteId = id;
        },
        destroy() {
            router.delete(route('admin.users.destroy', this.confirmingDeleteId), {
                preserveScroll: true,
                onFinish: () => (this.confirmingDeleteId = null),
            });
        },
    },
};
</script>

<template>
    <Head title="Administration — Utilisateurs" />

    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Utilisateurs</h1>
        </template>

        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b border-gray-100 flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-[200px]">
                    <TextInput v-model="search" placeholder="Rechercher un nom ou un e-mail" @keyup.enter="applyFilters" />
                </div>
                <select v-model="role" class="rounded-md border-gray-300 text-sm" @change="applyFilters">
                    <option value="">Tous les rôles</option>
                    <option v-for="r in roles" :key="r.id" :value="r.id">{{ r.name }}</option>
                </select>
                <SecondaryButton @click="applyFilters">Filtrer</SecondaryButton>
                <Link :href="route('admin.users.create')" class="ms-auto inline-flex items-center rounded-md bg-primary-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-primary-700">
                    Ajouter un utilisateur
                </Link>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Nom</th>
                        <th class="px-4 py-3">E-mail</th>
                        <th class="px-4 py-3">Rôle</th>
                        <th class="px-4 py-3">Statut</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="user in users.data" :key="user.id">
                        <td class="px-4 py-3 text-gray-800">{{ user.first_name }} {{ user.last_name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ user.email }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ roleLabel(user) }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs" :class="user.is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500'">
                                {{ user.is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <Link :href="route('admin.users.edit', user.id)" class="text-primary-600 hover:underline">Modifier</Link>
                            <button class="text-red-600 hover:underline" @click="confirmDelete(user.id)">Supprimer</button>
                        </td>
                    </tr>
                    <tr v-if="!users.data.length">
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">Aucun utilisateur ne correspond à ces critères.</td>
                    </tr>
                </tbody>
            </table>

            <div class="p-4">
                <Pagination :links="users.links" />
            </div>
        </div>

        <Modal :show="confirmingDeleteId !== null" @close="confirmingDeleteId = null">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Supprimer cet utilisateur ?</h2>
                <p class="mt-1 text-sm text-gray-600">Cette action est irréversible.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="confirmingDeleteId = null">Annuler</SecondaryButton>
                    <DangerButton @click="destroy">Supprimer</DangerButton>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>
