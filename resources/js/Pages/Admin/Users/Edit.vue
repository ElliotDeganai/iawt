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
        user: Object,
        roles: Array,
    },
    data() {
        return {
            form: useForm({
                first_name: this.user.first_name,
                last_name: this.user.last_name,
                email: this.user.email,
                password: '',
                role_id: this.user.role_id ?? '',
                phone: this.user.phone,
                country: this.user.country,
                city: this.user.city,
                is_active: this.user.is_active,
            }),
        };
    },
    methods: {
        submit() {
            this.form.put(route('admin.users.update', this.user.id));
        },
    },
};
</script>

<template>
    <Head title="Administration — Modifier l'utilisateur" />

    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Modifier {{ user.first_name }} {{ user.last_name }}</h1>
        </template>

        <form class="max-w-2xl bg-white rounded-lg shadow p-6 space-y-4" @submit.prevent="submit">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <InputLabel value="Prénom" />
                    <TextInput v-model="form.first_name" class="mt-1" required />
                    <InputError class="mt-2" :message="form.errors.first_name" />
                </div>
                <div>
                    <InputLabel value="Nom" />
                    <TextInput v-model="form.last_name" class="mt-1" required />
                    <InputError class="mt-2" :message="form.errors.last_name" />
                </div>
            </div>

            <div>
                <InputLabel value="Adresse e-mail" />
                <TextInput v-model="form.email" type="email" class="mt-1" required />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel value="Nouveau mot de passe" />
                <TextInput v-model="form.password" type="password" class="mt-1" placeholder="Laisser vide pour ne pas changer" />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <InputLabel value="Téléphone" />
                    <TextInput v-model="form.phone" class="mt-1" />
                </div>
                <div>
                    <InputLabel value="Pays" />
                    <TextInput v-model="form.country" class="mt-1" />
                </div>
                <div>
                    <InputLabel value="Ville" />
                    <TextInput v-model="form.city" class="mt-1" />
                </div>
            </div>

            <div>
                <InputLabel value="Rôle back-office" />
                <select v-model="form.role_id" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                    <option value="">Aucun (candidat / porteur de projet)</option>
                    <option v-for="r in roles" :key="r.id" :value="r.id">{{ r.name }}</option>
                </select>
                <InputError class="mt-2" :message="form.errors.role_id" />
            </div>

            <label class="flex items-center gap-2">
                <Checkbox v-model:checked="form.is_active" />
                <span class="text-sm text-gray-700">Compte actif</span>
            </label>

            <div class="flex items-center gap-3 pt-2">
                <PrimaryButton :disabled="form.processing">Enregistrer</PrimaryButton>
                <Link :href="route('admin.users.index')" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 hover:bg-gray-50">
                    Annuler
                </Link>
            </div>
        </form>
    </AdminLayout>
</template>
