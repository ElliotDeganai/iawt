<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import Icon from '@/Components/Icon.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const ICONS = ['leaf', 'palm', 'rocket', 'lightbulb', 'search', 'chart', 'currency', 'scale', 'target', 'building'];

export default {
    components: { AdminLayout, InputLabel, InputError, TextInput, Icon, PrimaryButton, Head, Link },
    data() {
        return {
            icons: ICONS,
            form: useForm({
                icon: 'leaf',
                title: '',
                description: '',
                position: 1,
            }),
        };
    },
    methods: {
        submit() {
            this.form.post(route('admin.engagements.store'));
        },
    },
};
</script>

<template>
    <Head title="Administration — Nouvel engagement" />

    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Ajouter un engagement</h1>
        </template>

        <form class="max-w-2xl w-full bg-white rounded-lg shadow p-6 space-y-4" @submit.prevent="submit">
            <div>
                <InputLabel value="Icône" />
                <div class="mt-2 flex flex-wrap gap-2">
                    <button
                        v-for="i in icons" :key="i" type="button"
                        class="flex h-10 w-10 items-center justify-center rounded-full border-2"
                        :class="form.icon === i ? 'border-primary-600 bg-primary-600 text-white' : 'border-gray-200 text-gray-500 hover:border-primary-300'"
                        @click="form.icon = i"
                    >
                        <Icon :name="i" class="h-5 w-5" />
                    </button>
                </div>
                <InputError class="mt-2" :message="form.errors.icon" />
            </div>

            <div>
                <InputLabel value="Titre" />
                <TextInput v-model="form.title" class="mt-1" required autofocus />
                <InputError class="mt-2" :message="form.errors.title" />
            </div>

            <div>
                <InputLabel value="Description" />
                <textarea v-model="form.description" rows="3" maxlength="500" class="mt-1 w-full rounded-md border-gray-300 text-sm" />
                <InputError class="mt-2" :message="form.errors.description" />
            </div>

            <div>
                <InputLabel value="Ordre d'affichage" />
                <TextInput v-model.number="form.position" type="number" class="mt-1 w-32" required />
                <InputError class="mt-2" :message="form.errors.position" />
            </div>

            <div class="flex items-center gap-3 pt-2">
                <PrimaryButton :disabled="form.processing">Créer</PrimaryButton>
                <Link :href="route('admin.engagements.index')" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 hover:bg-gray-50">
                    Annuler
                </Link>
            </div>
        </form>
    </AdminLayout>
</template>
