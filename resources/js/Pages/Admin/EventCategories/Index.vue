<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';

export default {
    components: { AdminLayout, Head, Link },
    props: { categories: Array },
    data() {
        return {
            editing: null,
            editForm: useForm({ name: '', color: '#7A1F2B' }),
            newForm: useForm({ name: '', color: '#7A1F2B' }),
        };
    },
    methods: {
        startEdit(cat) {
            this.editing = cat.id;
            this.editForm.name = cat.name;
            this.editForm.color = cat.color;
        },
        cancelEdit() { this.editing = null; },
        saveEdit(cat) {
            this.editForm.put(route('admin.event-categories.update', cat.id), {
                preserveScroll: true,
                onSuccess: () => { this.editing = null; },
            });
        },
        addCategory() {
            this.newForm.post(route('admin.event-categories.store'), {
                preserveScroll: true,
                onSuccess: () => { this.newForm.reset(); },
            });
        },
        deleteCategory(cat) {
            if (confirm(`Supprimer « ${cat.name} » ? Les événements liés perdront leur catégorie.`)) {
                router.delete(route('admin.event-categories.destroy', cat.id), { preserveScroll: true });
            }
        },
    },
};
</script>

<template>
    <Head title="Administration — Catégories d'événements" />
    <AdminLayout>
        <template #header><h1 class="text-lg font-semibold text-gray-800">Catégories d'événements</h1></template>

        <div class="max-w-2xl space-y-6">
            <!-- Liste -->
            <div class="overflow-hidden rounded-lg bg-white shadow">
                <div v-if="!categories.length" class="px-6 py-8 text-center text-sm text-gray-400">Aucune catégorie. Ajoutez-en une ci-dessous.</div>
                <div v-for="cat in categories" :key="cat.id" class="flex items-center gap-4 border-b border-gray-100 px-5 py-3 last:border-0">
                    <template v-if="editing === cat.id">
                        <input type="color" v-model="editForm.color" class="h-8 w-8 cursor-pointer rounded border-0 p-0" />
                        <input v-model="editForm.name" class="flex-1 rounded-md border-gray-200 text-sm" @keyup.enter="saveEdit(cat)" />
                        <button type="button" class="rounded-md bg-primary-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-primary-700" @click="saveEdit(cat)">OK</button>
                        <button type="button" class="text-xs text-gray-500 hover:text-gray-700" @click="cancelEdit">Annuler</button>
                    </template>
                    <template v-else>
                        <span class="h-4 w-4 rounded-full shrink-0" :style="{ background: cat.color }"></span>
                        <span class="flex-1 text-sm text-gray-800">{{ cat.name }}</span>
                        <span class="text-xs text-gray-400">{{ cat.events_count ?? 0 }} évt.</span>
                        <button type="button" class="text-xs text-primary-600 hover:underline" @click="startEdit(cat)">Modifier</button>
                        <button type="button" class="text-xs text-red-600 hover:underline" @click="deleteCategory(cat)">Supprimer</button>
                    </template>
                </div>
            </div>

            <!-- Ajouter -->
            <div class="rounded-lg bg-white p-5 shadow">
                <p class="mb-3 text-sm font-medium text-gray-800">Ajouter une catégorie</p>
                <form class="flex items-end gap-3" @submit.prevent="addCategory">
                    <input type="color" v-model="newForm.color" class="h-9 w-9 cursor-pointer rounded border-0 p-0" />
                    <div class="flex-1"><input v-model="newForm.name" class="w-full rounded-md border-gray-200 text-sm" placeholder="Nom de la catégorie" required /></div>
                    <button type="submit" :disabled="newForm.processing" class="rounded-md bg-primary-600 px-4 py-2 text-xs font-semibold text-white hover:bg-primary-700 disabled:opacity-50">Ajouter</button>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
