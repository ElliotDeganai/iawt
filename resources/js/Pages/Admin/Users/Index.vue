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
            trashed: this.filters.trashed || '',
            confirmingDeleteId: null,
        };
    },
    methods: {
        applyFilters() {
            router.get(route('admin.users.index'), {
                search: this.search || undefined,
                role: this.role || undefined,
                trashed: this.trashed || undefined,
            }, { preserveState: true, replace: true });
        },
        roleLabel(user) {
            return user.role ? user.role.name : 'Candidat';
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
        restore(id) {
            router.put(route('admin.users.restore', id), {}, { preserveScroll: true });
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

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <div class="flex flex-col gap-3 border-b border-gray-100 p-4 sm:flex-row sm:flex-wrap sm:items-end">
                <div class="flex-1 min-w-[200px]">
                    <TextInput v-model="search" placeholder="Rechercher un nom ou un e-mail" @keyup.enter="applyFilters" />
                </div>
                <select v-model="role" class="rounded-md border-gray-300 text-sm" @change="applyFilters">
                    <option value="">Tous les rôles</option>
                    <option v-for="r in roles" :key="r.id" :value="r.id">{{ r.name }}</option>
                </select>
                <select v-model="trashed" class="rounded-md border-gray-300 text-sm" @change="applyFilters">
                    <option value="">Actifs uniquement</option>
                    <option value="with">Actifs + supprimés</option>
                    <option value="only">Supprimés uniquement</option>
                </select>
                <SecondaryButton @click="applyFilters">Filtrer</SecondaryButton>
                <Link :href="route('admin.users.create')" class="sm:ms-auto inline-flex items-center rounded-md bg-primary-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-primary-700">
                    Ajouter un utilisateur
                </Link>
            </div>

            <table class="min-w-[600px] w-full text-sm">
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
                    <tr v-for="user in users.data" :key="user.id" :class="user.deleted_at ? 'bg-red-50/50' : ''">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <img v-if="user.avatar" :src="`/storage/${user.avatar}`" class="h-8 w-8 rounded-full object-cover" :class="user.deleted_at ? 'opacity-40 grayscale' : ''" alt="" />
                                <span v-else class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs font-semibold text-white" :class="user.deleted_at ? 'bg-gray-400' : 'bg-primary-600'">
                                    {{ (user.first_name?.[0] || '') + (user.last_name?.[0] || '') }}
                                </span>
                                <span class="font-medium" :class="user.deleted_at ? 'text-gray-400 line-through' : 'text-gray-800'">{{ user.first_name }} {{ user.last_name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ user.email }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ roleLabel(user) }}</td>
                        <td class="px-4 py-3">
                            <span v-if="user.deleted_at" class="inline-flex items-center rounded-full bg-red-50 px-2 py-0.5 text-xs font-medium text-red-600">
                                Supprimé
                            </span>
                            <span v-else class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium" :class="user.is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500'">
                                {{ user.is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <template v-if="user.deleted_at">
                                <button class="text-green-600 hover:underline" @click="restore(user.id)">Réactiver</button>
                            </template>
                            <template v-else>
                                <Link :href="route('admin.users.edit', user.id)" class="text-primary-600 hover:underline">Modifier</Link>
                                <button class="text-red-600 hover:underline" @click="confirmDelete(user.id)">Supprimer</button>
                            </template>
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
                <h2 class="text-lg font-medium text-gray-900">Désactiver cet utilisateur ?</h2>
                <p class="mt-1 text-sm text-gray-600">L'utilisateur ne pourra plus se connecter mais ses données (candidature, parcours) seront conservées. Vous pourrez le réactiver à tout moment.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="confirmingDeleteId = null">Annuler</SecondaryButton>
                    <DangerButton @click="destroy">Désactiver</DangerButton>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>