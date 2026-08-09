<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

export default {
    components: { AdminLayout, InputError, Modal, Head },
    props: { partners: Array },
    data() {
        return {
            editing: null,
            showAdd: false,
            addForm: useForm({ name: '', logo: null, url: '', position: (this.partners.length + 1) }),
            editForm: useForm({ name: '', logo: null, remove_logo: false, url: '', position: 0 }),
            confirmingDeleteId: null,
            logoPreview: null,
        };
    },
    methods: {
        openEdit(p) {
            this.editing = p.id;
            this.editForm.name = p.name;
            this.editForm.url = p.url || '';
            this.editForm.position = p.position;
            this.editForm.remove_logo = false;
            this.editForm.logo = null;
        },
        saveEdit(id) {
            this.editForm.transform((d) => ({ ...d, _method: 'put' }))
                .post(route('admin.partners.update', id), {
                    preserveScroll: true,
                    onSuccess: () => { this.editing = null; },
                });
        },
        onAddLogo(e) { this.addForm.logo = e.target.files[0]; },
        onEditLogo(e) { this.editForm.logo = e.target.files[0]; this.editForm.remove_logo = false; },
        submitAdd() {
            this.addForm.post(route('admin.partners.store'), {
                preserveScroll: true,
                onSuccess: () => { this.showAdd = false; this.addForm.reset(); this.addForm.position = this.partners.length + 1; },
            });
        },
        destroy() {
            router.delete(route('admin.partners.destroy', this.confirmingDeleteId), {
                preserveScroll: true,
                onFinish: () => { this.confirmingDeleteId = null; },
            });
        },
    },
};
</script>

<template>
    <Head title="Administration — Partenaires" />
    <AdminLayout>
        <template #header><h1 class="text-lg font-semibold text-gray-800">Ils nous soutiennent — Partenaires</h1></template>

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <div class="flex flex-col gap-3 border-b border-gray-100 p-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-gray-500">Logos et noms des partenaires affichés sur la page d'accueil.</p>
                <button type="button" class="rounded-md bg-primary-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-primary-700" @click="showAdd = true">
                    Ajouter
                </button>
            </div>

            <table class="min-w-[600px] w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Ordre</th>
                        <th class="px-4 py-3">Logo</th>
                        <th class="px-4 py-3">Nom</th>
                        <th class="px-4 py-3">Lien</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="p in partners" :key="p.id">
                        <template v-if="editing === p.id">
                            <td class="px-4 py-2"><input v-model.number="editForm.position" type="number" min="1" class="w-16 rounded border-gray-200 text-sm" /></td>
                            <td class="px-4 py-2">
                                <img v-if="p.logo && !editForm.remove_logo" :src="`/storage/${p.logo}`" class="h-8 w-auto rounded" />
                                <input type="file" accept="image/*" class="text-xs mt-1" @change="onEditLogo" />
                            </td>
                            <td class="px-4 py-2"><input v-model="editForm.name" class="w-full rounded border-gray-200 text-sm" /></td>
                            <td class="px-4 py-2"><input v-model="editForm.url" class="w-full rounded border-gray-200 text-sm" placeholder="https://" /></td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <button class="text-primary-600 hover:underline text-xs" @click="saveEdit(p.id)">Enregistrer</button>
                                <button class="text-gray-400 hover:underline text-xs" @click="editing = null">Annuler</button>
                            </td>
                        </template>
                        <template v-else>
                            <td class="px-4 py-3 text-gray-400">{{ p.position }}</td>
                            <td class="px-4 py-3">
                                <img v-if="p.logo" :src="`/storage/${p.logo}`" class="h-8 w-auto rounded grayscale" />
                                <span v-else class="text-xs text-gray-300">—</span>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ p.name }}</td>
                            <td class="px-4 py-3">
                                <a v-if="p.url" :href="p.url" target="_blank" class="text-xs text-primary-600 hover:underline truncate block max-w-[200px]">{{ p.url }}</a>
                                <span v-else class="text-xs text-gray-300">—</span>
                            </td>
                            <td class="px-4 py-3 text-right space-x-3">
                                <button class="text-primary-600 hover:underline" @click="openEdit(p)">Modifier</button>
                                <button class="text-red-600 hover:underline" @click="confirmingDeleteId = p.id">Supprimer</button>
                            </td>
                        </template>
                    </tr>
                    <tr v-if="!partners.length">
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">Aucun partenaire pour le moment.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modale ajout -->
        <Modal :show="showAdd" @close="showAdd = false">
            <form class="p-6" @submit.prevent="submitAdd">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Ajouter un partenaire</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                        <input v-model="addForm.name" class="w-full rounded-md border-gray-200 text-sm" autofocus />
                        <InputError class="mt-1" :message="addForm.errors.name" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Logo <span class="text-gray-400 font-normal">(optionnel)</span></label>
                        <input type="file" accept="image/*" class="text-sm" @change="onAddLogo" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lien <span class="text-gray-400 font-normal">(optionnel)</span></label>
                        <input v-model="addForm.url" type="url" class="w-full rounded-md border-gray-200 text-sm" placeholder="https://" />
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
                <h2 class="text-lg font-medium text-gray-900">Supprimer ce partenaire ?</h2>
                <div class="mt-6 flex justify-end gap-3">
                    <button class="rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50" @click="confirmingDeleteId = null">Annuler</button>
                    <button class="rounded-md bg-red-600 px-4 py-2 text-xs font-semibold text-white hover:bg-red-700" @click="destroy">Supprimer</button>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>
