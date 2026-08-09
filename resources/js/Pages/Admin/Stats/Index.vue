<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

export default {
    components: { AdminLayout, InputError, Modal, Head },
    props: { stats: Array },
    data() {
        return {
            editing: null,
            showAdd: false,
            addForm: useForm({ value: '', label: '', position: (this.stats.length + 1) }),
            editForm: useForm({ value: '', label: '', position: 0 }),
            confirmingDeleteId: null,
        };
    },
    methods: {
        openEdit(stat) {
            this.editing = stat.id;
            this.editForm.value = stat.value;
            this.editForm.label = stat.label;
            this.editForm.position = stat.position;
        },
        saveEdit(id) {
            this.editForm.transform((d) => ({ ...d, _method: 'put' }))
                .post(route('admin.stats.update', id), {
                    preserveScroll: true,
                    onSuccess: () => { this.editing = null; },
                });
        },
        submitAdd() {
            this.addForm.post(route('admin.stats.store'), {
                preserveScroll: true,
                onSuccess: () => { this.showAdd = false; this.addForm.reset(); this.addForm.position = this.stats.length + 1; },
            });
        },
        destroy() {
            router.delete(route('admin.stats.destroy', this.confirmingDeleteId), {
                preserveScroll: true,
                onFinish: () => { this.confirmingDeleteId = null; },
            });
        },
    },
};
</script>

<template>
    <Head title="Administration — Chiffres clés" />
    <AdminLayout>
        <template #header><h1 class="text-lg font-semibold text-gray-800">Chiffres clés</h1></template>

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <div class="flex flex-col gap-3 border-b border-gray-100 p-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-gray-500">Les chiffres affichés sur la page d'accueil.</p>
                <button type="button" class="rounded-md bg-primary-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-primary-700" @click="showAdd = true">
                    Ajouter
                </button>
            </div>

            <table class="min-w-[600px] w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Ordre</th>
                        <th class="px-4 py-3">Valeur</th>
                        <th class="px-4 py-3">Libellé</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="s in stats" :key="s.id">
                        <template v-if="editing === s.id">
                            <td class="px-4 py-2"><input v-model.number="editForm.position" type="number" min="1" class="w-16 rounded border-gray-200 text-sm" /></td>
                            <td class="px-4 py-2"><input v-model="editForm.value" class="w-24 rounded border-gray-200 text-sm" /></td>
                            <td class="px-4 py-2"><input v-model="editForm.label" class="w-full rounded border-gray-200 text-sm" /></td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <button class="text-primary-600 hover:underline text-xs" @click="saveEdit(s.id)">Enregistrer</button>
                                <button class="text-gray-400 hover:underline text-xs" @click="editing = null">Annuler</button>
                            </td>
                        </template>
                        <template v-else>
                            <td class="px-4 py-3 text-gray-400">{{ s.position }}</td>
                            <td class="px-4 py-3 font-serif text-lg font-bold text-primary-700">{{ s.value }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ s.label }}</td>
                            <td class="px-4 py-3 text-right space-x-3">
                                <button class="text-primary-600 hover:underline" @click="openEdit(s)">Modifier</button>
                                <button class="text-red-600 hover:underline" @click="confirmingDeleteId = s.id">Supprimer</button>
                            </td>
                        </template>
                    </tr>
                    <tr v-if="!stats.length">
                        <td colspan="4" class="px-4 py-8 text-center text-gray-400">Aucun chiffre clé pour le moment.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modale ajout -->
        <Modal :show="showAdd" @close="showAdd = false">
            <form class="p-6" @submit.prevent="submitAdd">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Ajouter un chiffre clé</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Valeur</label>
                        <input v-model="addForm.value" class="w-full rounded-md border-gray-200 text-sm" placeholder="Ex : 100+" autofocus />
                        <InputError class="mt-1" :message="addForm.errors.value" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Libellé</label>
                        <input v-model="addForm.label" class="w-full rounded-md border-gray-200 text-sm" placeholder="Ex : Mentors & experts engagés" />
                        <InputError class="mt-1" :message="addForm.errors.label" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ordre</label>
                        <input v-model.number="addForm.position" type="number" min="1" class="w-24 rounded-md border-gray-200 text-sm" />
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" class="rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50" @click="showAdd = false">Annuler</button>
                    <button type="submit" :disabled="addForm.processing" class="rounded-md bg-primary-600 px-4 py-2 text-xs font-semibold text-white hover:bg-primary-700">Ajouter</button>
                </div>
            </form>
        </Modal>

        <!-- Modale suppression -->
        <Modal :show="confirmingDeleteId !== null" @close="confirmingDeleteId = null">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Supprimer ce chiffre clé ?</h2>
                <div class="mt-6 flex justify-end gap-3">
                    <button class="rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50" @click="confirmingDeleteId = null">Annuler</button>
                    <button class="rounded-md bg-red-600 px-4 py-2 text-xs font-semibold text-white hover:bg-red-700" @click="destroy">Supprimer</button>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>
