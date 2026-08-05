<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Icon from '@/Components/Icon.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

const ICONS = ['leaf', 'palm', 'rocket', 'lightbulb', 'search', 'chart', 'currency', 'scale', 'target', 'building'];

export default {
    components: { AdminLayout, Icon, InputError, Modal, Head },
    props: { categories: Array },
    data() {
        return {
            icons: ICONS,
            showAdd: false,
            editing: null,
            confirmingDeleteId: null,
            addForm: useForm({ name: '', icon: 'building', position: this.categories.length + 1 }),
            editForm: useForm({ name: '', icon: '', position: 0 }),
        };
    },
    methods: {
        submitAdd() {
            this.addForm.post(route('admin.place-categories.store'), {
                preserveScroll: true,
                onSuccess: () => { this.showAdd = false; this.addForm.reset(); this.addForm.icon = 'building'; this.addForm.position = this.categories.length + 1; },
            });
        },
        openEdit(cat) {
            this.editing = cat.id;
            this.editForm.name = cat.name;
            this.editForm.icon = cat.icon;
            this.editForm.position = cat.position;
        },
        saveEdit(id) {
            this.editForm.transform((d) => ({ ...d, _method: 'put' }))
                .post(route('admin.place-categories.update', id), {
                    preserveScroll: true,
                    onSuccess: () => { this.editing = null; },
                });
        },
        destroy() {
            router.delete(route('admin.place-categories.destroy', this.confirmingDeleteId), {
                preserveScroll: true,
                onFinish: () => { this.confirmingDeleteId = null; },
            });
        },
    },
};
</script>

<template>
    <Head title="Administration — Types de lieux" />
    <AdminLayout>
        <template #header><h1 class="text-lg font-semibold text-gray-800">Types de lieux</h1></template>

        <div class="bg-white rounded-lg shadow">
            <div class="flex items-center justify-between border-b border-gray-100 p-4">
                <p class="text-sm text-gray-500">Catégories de lieux utilisées dans les fiches pays (Où manger, Où se loger…).</p>
                <button type="button" class="rounded-md bg-primary-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-primary-700" @click="showAdd = true">
                    Ajouter
                </button>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Ordre</th>
                        <th class="px-4 py-3">Icône</th>
                        <th class="px-4 py-3">Nom</th>
                        <th class="px-4 py-3">Slug</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="cat in categories" :key="cat.id">
                        <template v-if="editing === cat.id">
                            <td class="px-4 py-2"><input v-model.number="editForm.position" type="number" min="1" class="w-16 rounded border-gray-200 text-sm" /></td>
                            <td class="px-4 py-2">
                                <div class="flex flex-wrap gap-1">
                                    <button
                                        v-for="ic in icons" :key="ic" type="button"
                                        class="flex h-7 w-7 items-center justify-center rounded-full border transition"
                                        :class="editForm.icon === ic ? 'border-primary-600 bg-primary-600 text-white' : 'border-gray-200 text-gray-400'"
                                        @click="editForm.icon = ic"
                                    ><Icon :name="ic" class="h-3.5 w-3.5" /></button>
                                </div>
                            </td>
                            <td class="px-4 py-2"><input v-model="editForm.name" class="w-full rounded border-gray-200 text-sm" /></td>
                            <td class="px-4 py-2 text-gray-400">{{ cat.slug }}</td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <button class="text-primary-600 hover:underline text-xs" @click="saveEdit(cat.id)">Enregistrer</button>
                                <button class="text-gray-400 hover:underline text-xs" @click="editing = null">Annuler</button>
                            </td>
                        </template>
                        <template v-else>
                            <td class="px-4 py-3 text-gray-400">{{ cat.position }}</td>
                            <td class="px-4 py-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-primary-600 text-primary-700">
                                    <Icon :name="cat.icon" class="h-4 w-4" />
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ cat.name }}</td>
                            <td class="px-4 py-3 text-gray-400">{{ cat.slug }}</td>
                            <td class="px-4 py-3 text-right space-x-3">
                                <button class="text-primary-600 hover:underline" @click="openEdit(cat)">Modifier</button>
                                <button class="text-red-600 hover:underline" @click="confirmingDeleteId = cat.id">Supprimer</button>
                            </td>
                        </template>
                    </tr>
                    <tr v-if="!categories.length">
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">Aucune catégorie de lieu.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modale ajout -->
        <Modal :show="showAdd" @close="showAdd = false">
            <form class="p-6" @submit.prevent="submitAdd">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Ajouter un type de lieu</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                        <input v-model="addForm.name" class="w-full rounded-md border-gray-200 text-sm" placeholder="Ex : Où se divertir" autofocus />
                        <InputError class="mt-1" :message="addForm.errors.name" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Icône</label>
                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="ic in icons" :key="ic" type="button"
                                class="flex h-9 w-9 items-center justify-center rounded-full border-2 transition"
                                :class="addForm.icon === ic ? 'border-primary-600 bg-primary-600 text-white' : 'border-gray-200 text-gray-400 hover:border-primary-300'"
                                @click="addForm.icon = ic"
                            ><Icon :name="ic" class="h-4 w-4" /></button>
                        </div>
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
                <h2 class="text-lg font-medium text-gray-900">Supprimer cette catégorie ?</h2>
                <p class="mt-1 text-sm text-gray-600">Tous les lieux de ce type seront également supprimés.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button class="rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50" @click="confirmingDeleteId = null">Annuler</button>
                    <button class="rounded-md bg-red-600 px-4 py-2 text-xs font-semibold text-white hover:bg-red-700" @click="destroy">Supprimer</button>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>
