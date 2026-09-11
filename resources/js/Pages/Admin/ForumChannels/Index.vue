<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';

export default {
    components: { AdminLayout, Head },
    props: { channels: Array },
    data() {
        return {
            editing: null,
            form: useForm({ name: '', description: '', sort_order: '', is_active: true }),
        };
    },
    methods: {
        startEdit(ch) {
            this.editing = ch.id;
            this.form.name = ch.name;
            this.form.description = ch.description || '';
            this.form.sort_order = ch.sort_order;
            this.form.is_active = ch.is_active;
        },
        cancelEdit() { this.editing = null; this.form.reset(); },
        saveNew() {
            this.form.post(route('admin.forum-channels.store'), {
                preserveScroll: true,
                onSuccess: () => this.form.reset(),
            });
        },
        saveEdit(ch) {
            this.form.transform(d => ({ ...d, _method: 'put' })).post(route('admin.forum-channels.update', ch.id), {
                preserveScroll: true,
                onSuccess: () => { this.editing = null; this.form.reset(); },
            });
        },
        destroy(ch) {
            if (confirm(`Supprimer « ${ch.name} » ? L'espace doit être vide.`)) {
                router.delete(route('admin.forum-channels.destroy', ch.id), { preserveScroll: true });
            }
        },
    },
};
</script>
<template>
    <Head title="Administration — Espaces du forum" />
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Espaces du forum</h1>
        </template>

        <div class="max-w-3xl">
            <div class="rounded-lg bg-white shadow overflow-hidden mb-6">
                <div v-if="!channels.length" class="px-6 py-8 text-center text-sm text-gray-400">Aucun espace.</div>
                <div v-for="ch in channels" :key="ch.id" class="border-b border-gray-100 last:border-0">
                    <!-- View -->
                    <div v-if="editing !== ch.id" class="flex items-center gap-3 px-5 py-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-xs font-bold text-primary-600">{{ ch.sort_order }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800">{{ ch.name }}</p>
                            <p v-if="ch.description" class="text-xs text-gray-400 truncate">{{ ch.description }}</p>
                        </div>
                        <span class="rounded-full px-2 py-0.5 text-[10px] font-medium" :class="ch.is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500'">{{ ch.is_active ? 'Actif' : 'Inactif' }}</span>
                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[10px] text-gray-500">{{ ch.topics_count }} sujet{{ ch.topics_count > 1 ? 's' : '' }}</span>
                        <button type="button" class="text-xs text-primary-600 hover:underline" @click="startEdit(ch)">Modifier</button>
                        <button type="button" class="text-xs text-red-600 hover:underline" @click="destroy(ch)">Supprimer</button>
                    </div>

                    <!-- Edit -->
                    <form v-else class="px-5 py-4 bg-primary-50/30 space-y-3" @submit.prevent="saveEdit(ch)">
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-4">
                            <div class="sm:col-span-2">
                                <label class="text-xs text-gray-500">Nom</label>
                                <input v-model="form.name" class="mt-1 w-full rounded-md border-gray-300 text-sm" required />
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">Ordre</label>
                                <input v-model="form.sort_order" type="number" class="mt-1 w-full rounded-md border-gray-300 text-sm" />
                            </div>
                            <div class="flex items-end">
                                <label class="flex items-center gap-2 py-2">
                                    <input v-model="form.is_active" type="checkbox" class="rounded text-primary-600" />
                                    <span class="text-sm text-gray-700">Actif</span>
                                </label>
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
                <p class="text-sm font-semibold text-gray-800 mb-3">Ajouter un espace</p>
                <form class="space-y-3" @submit.prevent="saveNew">
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-4">
                        <div class="sm:col-span-2">
                            <label class="text-xs text-gray-500">Nom</label>
                            <input v-model="form.name" class="mt-1 w-full rounded-md border-gray-300 text-sm" placeholder="Ex : Le Grand Cercle" required />
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Ordre</label>
                            <input v-model="form.sort_order" type="number" class="mt-1 w-full rounded-md border-gray-300 text-sm" placeholder="1" />
                        </div>
                        <div class="flex items-end">
                            <label class="flex items-center gap-2 py-2">
                                <input v-model="form.is_active" type="checkbox" class="rounded text-primary-600" />
                                <span class="text-sm text-gray-700">Actif</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Description</label>
                        <input v-model="form.description" class="mt-1 w-full rounded-md border-gray-300 text-sm" placeholder="Courte description de l'espace" />
                    </div>
                    <button type="submit" :disabled="form.processing" class="rounded-md bg-primary-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-primary-700">Créer</button>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
