<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

export default {
    components: { AdminLayout, InputLabel, InputError, TextInput, Checkbox, PrimaryButton, Head, Link },
    props: {
        permissions: Array,
    },
    data() {
        return {
            slugTouched: false,
            form: useForm({
                name: '',
                slug: '',
                permissions: [],
            }),
        };
    },
    watch: {
        'form.name'(value) {
            if (!this.slugTouched) {
                this.form.slug = value
                    .toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/(^-|-$)/g, '');
            }
        },
    },
    methods: {
        submit() {
            this.form.post(route('admin.roles.store'));
        },
    },
};
</script>

<template>
    <Head title="Administration — Nouveau rôle" />

    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Ajouter un rôle</h1>
        </template>

        <form class="max-w-2xl bg-white rounded-lg shadow p-6 space-y-4" @submit.prevent="submit">
            <div>
                <InputLabel value="Nom du rôle" />
                <TextInput v-model="form.name" class="mt-1" required autofocus />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel value="Identifiant (slug)" />
                <TextInput v-model="form.slug" class="mt-1" required @input="slugTouched = true" />
                <InputError class="mt-2" :message="form.errors.slug" />
            </div>

            <div>
                <InputLabel value="Permissions" />
                <div class="mt-2 grid grid-cols-1 gap-2 sm:grid-cols-2">
                    <label v-for="p in permissions" :key="p.id" class="flex items-center gap-2 text-sm text-gray-700">
                        <Checkbox :value="p.id" :checked="form.permissions.includes(p.id)" @update:checked="() => {
                            form.permissions.includes(p.id)
                                ? form.permissions.splice(form.permissions.indexOf(p.id), 1)
                                : form.permissions.push(p.id);
                        }" />
                        {{ p.name }}
                    </label>
                </div>
                <InputError class="mt-2" :message="form.errors.permissions" />
            </div>

            <div class="flex items-center gap-3 pt-2">
                <PrimaryButton :disabled="form.processing">Créer</PrimaryButton>
                <Link :href="route('admin.roles.index')" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 hover:bg-gray-50">
                    Annuler
                </Link>
            </div>
        </form>
    </AdminLayout>
</template>
