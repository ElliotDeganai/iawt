<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

export default {
    components: { AdminLayout, InputError, Modal, Head },
    props: { testimonials: Array },
    data() {
        return {
            showAdd: false,
            editing: null,
            confirmingDeleteId: null,
            addForm: useForm({
                author_name: '',
                author_meta: '',
                author_photo: null,
                quote: '',
                is_approved: true,
                position: this.testimonials.length + 1,
            }),
            editForm: useForm({
                author_name: '',
                author_meta: '',
                author_photo: null,
                remove_photo: false,
                quote: '',
                is_approved: true,
                position: 0,
            }),
        };
    },
    methods: {
        onAddPhoto(e) { this.addForm.author_photo = e.target.files[0]; },
        onEditPhoto(e) { this.editForm.author_photo = e.target.files[0]; this.editForm.remove_photo = false; },
        submitAdd() {
            this.addForm.post(route('admin.testimonials.store'), {
                preserveScroll: true,
                onSuccess: () => { this.showAdd = false; this.addForm.reset(); this.addForm.position = this.testimonials.length + 1; },
            });
        },
        openEdit(t) {
            this.editing = t.id;
            this.editForm.author_name = t.author_name;
            this.editForm.author_meta = t.author_meta || '';
            this.editForm.quote = t.quote;
            this.editForm.is_approved = t.is_approved;
            this.editForm.position = t.position;
            this.editForm.author_photo = null;
            this.editForm.remove_photo = false;
        },
        saveEdit(id) {
            this.editForm.transform((d) => ({ ...d, _method: 'put' }))
                .post(route('admin.testimonials.update', id), {
                    preserveScroll: true,
                    onSuccess: () => { this.editing = null; },
                });
        },
        toggleApproval(id) {
            router.put(route('admin.testimonials.toggle', id), {}, { preserveScroll: true });
        },
        destroy() {
            router.delete(route('admin.testimonials.destroy', this.confirmingDeleteId), {
                preserveScroll: true,
                onFinish: () => { this.confirmingDeleteId = null; },
            });
        },
    },
};
</script>

<template>
    <Head title="Administration — Témoignages" />
    <AdminLayout>
        <template #header><h1 class="text-lg font-semibold text-gray-800">Témoignages</h1></template>

        <div class="bg-white rounded-lg shadow">
            <div class="flex items-center justify-between border-b border-gray-100 p-4">
                <p class="text-sm text-gray-500">Avis affichés sur la page d'accueil.</p>
                <button type="button" class="rounded-md bg-primary-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-primary-700" @click="showAdd = true">
                    Ajouter
                </button>
            </div>

            <!-- Liste -->
            <div class="divide-y divide-gray-100">
                <div v-for="t in testimonials" :key="t.id" class="px-5 py-4">

                    <!-- Mode lecture -->
                    <template v-if="editing !== t.id">
                        <div class="flex items-start gap-4">
                            <!-- Photo -->
                            <div class="h-12 w-12 shrink-0 rounded-full bg-primary-100 flex items-center justify-center overflow-hidden">
                                <img v-if="t.author_photo" :src="`/storage/${t.author_photo}`" class="h-full w-full object-cover" />
                                <span v-else class="text-lg text-primary-400">{{ t.author_name?.[0] }}</span>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <p class="text-sm font-medium text-gray-900">{{ t.author_name }}</p>
                                    <span v-if="t.author_meta" class="text-xs text-gray-400">· {{ t.author_meta }}</span>
                                    <span
                                        class="ml-auto inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold"
                                        :class="t.is_approved ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700'"
                                    >{{ t.is_approved ? 'Publié' : 'Masqué' }}</span>
                                </div>
                                <p class="text-sm text-gray-600 italic leading-relaxed">« {{ t.quote }} »</p>
                                <div class="mt-2 flex items-center gap-3">
                                    <span class="text-xs text-gray-400">Position : {{ t.position }}</span>
                                    <button class="text-xs text-primary-600 hover:underline" @click="openEdit(t)">Modifier</button>
                                    <button class="text-xs text-primary-600 hover:underline" @click="toggleApproval(t.id)">
                                        {{ t.is_approved ? 'Masquer' : 'Publier' }}
                                    </button>
                                    <button class="text-xs text-red-600 hover:underline" @click="confirmingDeleteId = t.id">Supprimer</button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Mode édition inline -->
                    <template v-else>
                        <div class="space-y-3 rounded-lg border border-primary-100 bg-primary-50/30 p-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Nom</label>
                                    <input v-model="editForm.author_name" class="w-full rounded-md border-gray-200 text-sm" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Sous-titre</label>
                                    <input v-model="editForm.author_meta" class="w-full rounded-md border-gray-200 text-sm" placeholder="Promotion 2024 · Projet" />
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Citation</label>
                                <textarea v-model="editForm.quote" rows="3" class="w-full rounded-md border-gray-200 text-sm"></textarea>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Photo</label>
                                    <input type="file" accept="image/*" class="text-xs" @change="onEditPhoto" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Position</label>
                                    <input v-model.number="editForm.position" type="number" min="1" class="w-20 rounded-md border-gray-200 text-sm" />
                                </div>
                                <div class="flex items-end">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input v-model="editForm.is_approved" type="checkbox" class="rounded text-primary-600" />
                                        <span class="text-xs text-gray-700">Publié</span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex justify-end gap-2 pt-1">
                                <button class="text-xs text-gray-400 hover:underline" @click="editing = null">Annuler</button>
                                <button class="rounded-md bg-primary-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-primary-700" @click="saveEdit(t.id)">Enregistrer</button>
                            </div>
                        </div>
                    </template>
                </div>

                <div v-if="!testimonials.length" class="px-5 py-8 text-center text-gray-400">
                    Aucun témoignage pour le moment.
                </div>
            </div>
        </div>

        <!-- Modale ajout -->
        <Modal :show="showAdd" @close="showAdd = false">
            <form class="p-6" @submit.prevent="submitAdd">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Ajouter un témoignage</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom de l'auteur</label>
                            <input v-model="addForm.author_name" class="w-full rounded-md border-gray-200 text-sm" autofocus />
                            <InputError class="mt-1" :message="addForm.errors.author_name" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sous-titre <span class="text-gray-400 font-normal">(optionnel)</span></label>
                            <input v-model="addForm.author_meta" class="w-full rounded-md border-gray-200 text-sm" placeholder="Promotion 2024 · Nom du projet" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Citation</label>
                        <textarea v-model="addForm.quote" rows="4" class="w-full rounded-md border-gray-200 text-sm"></textarea>
                        <InputError class="mt-1" :message="addForm.errors.quote" />
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Photo <span class="text-gray-400 font-normal">(optionnel)</span></label>
                            <input type="file" accept="image/*" class="text-sm" @change="onAddPhoto" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                            <input v-model.number="addForm.position" type="number" min="1" class="w-24 rounded-md border-gray-200 text-sm" />
                        </div>
                        <div class="flex items-end">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input v-model="addForm.is_approved" type="checkbox" class="rounded text-primary-600" />
                                <span class="text-sm text-gray-700">Publier immédiatement</span>
                            </label>
                        </div>
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
                <h2 class="text-lg font-medium text-gray-900">Supprimer ce témoignage ?</h2>
                <p class="mt-1 text-sm text-gray-600">Cette action est irréversible.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button class="rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50" @click="confirmingDeleteId = null">Annuler</button>
                    <button class="rounded-md bg-red-600 px-4 py-2 text-xs font-semibold text-white hover:bg-red-700" @click="destroy">Supprimer</button>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>
