<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';

const ICONS = ['banknotes','scale','building','calculator','chart','wrench','book','heart','globe','star','megaphone','folder'];

export default {
    components: { AdminLayout, Head },
    props: { categories: Array },
    data() {
        return {
            editing: null,
            icons: ICONS,
            form: useForm({ name: '', description: '', icon: 'book', sort_order: '' }),
        };
    },
    methods: {
        startEdit(cat) {
            this.editing = cat.id;
            this.form.name = cat.name;
            this.form.description = cat.description || '';
            this.form.icon = cat.icon || 'book';
            this.form.sort_order = cat.sort_order;
        },
        cancelEdit() { this.editing = null; this.form.reset(); },
        saveNew() {
            this.form.post(route('admin.resource-categories.store'), {
                preserveScroll: true,
                onSuccess: () => this.form.reset(),
            });
        },
        saveEdit(cat) {
            this.form.transform(d => ({ ...d, _method: 'put' })).post(route('admin.resource-categories.update', cat.id), {
                preserveScroll: true,
                onSuccess: () => { this.editing = null; this.form.reset(); },
            });
        },
        destroy(cat) {
            if (confirm(`Supprimer « ${cat.name} » ? Les ressources de cette catégorie doivent être vides.`)) {
                router.delete(route('admin.resource-categories.destroy', cat.id), { preserveScroll: true });
            }
        },
    },
};
</script>
<template>
    <Head title="Administration — Catégories de ressources" />
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Catégories de ressources</h1>
        </template>

        <div class="max-w-3xl">
            <!-- Existing categories -->
            <div class="rounded-lg bg-white shadow overflow-hidden mb-6">
                <div v-if="!categories.length" class="px-6 py-8 text-center text-sm text-gray-400">Aucune catégorie.</div>
                <div v-for="cat in categories" :key="cat.id" class="border-b border-gray-100 last:border-0">
                    <!-- View mode -->
                    <div v-if="editing !== cat.id" class="flex items-center gap-3 px-5 py-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-xs font-bold text-primary-600">{{ cat.sort_order }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800">{{ cat.name }}</p>
                            <p v-if="cat.description" class="text-xs text-gray-400 truncate">{{ cat.description }}</p>
                        </div>
                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[10px] text-gray-500">{{ cat.resources_count }} ressource{{ cat.resources_count > 1 ? 's' : '' }}</span>
                        <button type="button" class="text-xs text-primary-600 hover:underline" @click="startEdit(cat)">Modifier</button>
                        <button type="button" class="text-xs text-red-600 hover:underline" @click="destroy(cat)">Supprimer</button>
                    </div>

                    <!-- Edit mode -->
                    <form v-else class="px-5 py-4 bg-primary-50/30 space-y-3" @submit.prevent="saveEdit(cat)">
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-4">
                            <div class="sm:col-span-2">
                                <label class="text-xs text-gray-500">Nom</label>
                                <input v-model="form.name" class="mt-1 w-full rounded-md border-gray-300 text-sm" required />
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">Icône</label>
                                <select v-model="form.icon" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                                    <option v-for="ic in icons" :key="ic" :value="ic">{{ ic }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">Ordre</label>
                                <input v-model="form.sort_order" type="number" class="mt-1 w-full rounded-md border-gray-300 text-sm" />
                            </div>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Description</label>
                            <input v-model="form.description" class="mt-1 w-full rounded-md border-gray-300 text-sm" />
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" :disabled="form.processing" class="rounded-md bg-primary-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-primary-700">Enregistrer</button>
                            <button type="button" class="rounded-md border border-gray-200 px-4 py-1.5 text-xs text-gray-600 hover:bg-gray-50" @click="cancelEdit">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Add new -->
            <div class="rounded-lg bg-white shadow p-5">
                <p class="text-sm font-semibold text-gray-800 mb-3">Ajouter une catégorie</p>
                <form class="space-y-3" @submit.prevent="saveNew">
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-4">
                        <div class="sm:col-span-2">
                            <label class="text-xs text-gray-500">Nom</label>
                            <input v-model="form.name" class="mt-1 w-full rounded-md border-gray-300 text-sm" placeholder="Ex : Financement" required />
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Icône</label>
                            <select v-model="form.icon" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                                <option v-for="ic in icons" :key="ic" :value="ic">{{ ic }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Ordre</label>
                            <input v-model="form.sort_order" type="number" class="mt-1 w-full rounded-md border-gray-300 text-sm" placeholder="1" />
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Description</label>
                        <input v-model="form.description" class="mt-1 w-full rounded-md border-gray-300 text-sm" placeholder="Courte description" />
                    </div>
                    <button type="submit" :disabled="form.processing" class="rounded-md bg-primary-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-primary-700">Créer</button>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
