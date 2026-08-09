<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head, Link, router } from '@inertiajs/vue3';

export default {
    components: { AdminLayout, SecondaryButton, DangerButton, Modal, Head, Link },
    props: {
        countries: Array,
    },
    data() {
        return {
            confirmingDeleteSlug: null,
        };
    },
    methods: {
        formatMonth(date) {
            return new Date(date).toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
        },
        setCurrent(country) {
            router.put(route('admin.countries.set-current', country.slug), {}, { preserveScroll: true });
        },
        confirmDelete(slug) {
            this.confirmingDeleteSlug = slug;
        },
        destroy() {
            router.delete(route('admin.countries.destroy', this.confirmingDeleteSlug), {
                preserveScroll: true,
                onFinish: () => (this.confirmingDeleteSlug = null),
            });
        },
    },
};
</script>

<template>
    <Head title="Administration — Pays à l'honneur" />

    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Pays à l'honneur</h1>
        </template>

        <p class="mb-4 text-sm text-gray-500">
            Un seul pays peut être « à l'honneur » à la fois : c'est celui-là qui s'affiche sur la page d'accueil
            et vers lequel pointe le lien « Le pays à l'honneur » du menu.
        </p>

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <div class="p-4 border-b border-gray-100 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <Link :href="route('admin.countries.create')" class="inline-flex items-center rounded-md bg-primary-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-primary-700">
                    Ajouter un pays
                </Link>
            </div>

            <table class="min-w-[600px] w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3">À l'honneur</th>
                        <th class="px-4 py-3">Mois</th>
                        <th class="px-4 py-3">Pays</th>
                        <th class="px-4 py-3">Statut</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="c in countries" :key="c.id" :class="{ 'bg-primary-50': c.is_current }">
                        <td class="px-4 py-3">
                            <button
                                v-if="!c.is_current"
                                class="rounded-full border border-gray-300 px-3 py-1 text-xs text-gray-600 hover:border-primary-400 hover:text-primary-700"
                                @click="setCurrent(c)"
                            >
                                Choisir
                            </button>
                            <span v-else class="inline-flex items-center gap-1 rounded-full bg-gold-500 px-3 py-1 text-xs font-semibold text-primary-900">
                                ★ Actif
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 capitalize">{{ formatMonth(c.featured_month) }}</td>
                        <td class="px-4 py-3 text-gray-800">
                            <span class="inline-flex items-center gap-2">
                                <img v-if="c.flag_image" :src="`/storage/${c.flag_image}`" class="h-5 w-8 rounded object-cover" alt="" />
                                <span v-else-if="c.flag_code" :class="`fi fi-${c.flag_code}`" class="h-5 w-8 rounded-sm"></span>
                                <span v-else-if="c.flag_emoji">{{ c.flag_emoji }}</span>
                                {{ c.name }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs" :class="c.is_published ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500'">
                                {{ c.is_published ? 'Publié' : 'Brouillon' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <Link :href="route('admin.countries.edit', c.slug)" class="text-primary-600 hover:underline">Modifier</Link>
                            <button class="text-red-600 hover:underline" @click="confirmDelete(c.slug)">Supprimer</button>
                        </td>
                    </tr>
                    <tr v-if="!countries.length">
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">Aucun pays à l'honneur pour le moment.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Modal :show="confirmingDeleteSlug !== null" @close="confirmingDeleteSlug = null">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Supprimer ce pays à l'honneur ?</h2>
                <p class="mt-1 text-sm text-gray-600">Cette action est irréversible.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="confirmingDeleteSlug = null">Annuler</SecondaryButton>
                    <DangerButton @click="destroy">Supprimer</DangerButton>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>
