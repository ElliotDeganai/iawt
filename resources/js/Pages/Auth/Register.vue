<script>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

export default {
    components: { GuestLayout, Head, Link, InputError, InputLabel, PrimaryButton, TextInput },
    layout: GuestLayout,
    data() {
        return {
            form: useForm({
                first_name: '',
                last_name: '',
                email: '',
                password: '',
                password_confirmation: '',
            }),
        };
    },
    methods: {
        submit() {
            this.form.post(route('register'), {
                onFinish: () => this.form.reset('password', 'password_confirmation'),
            });
        },
    },
};
</script>

<template>
    <Head title="Créer un compte" />

    <form @submit.prevent="submit">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <InputLabel for="first_name" value="Prénom" />
                <TextInput id="first_name" v-model="form.first_name" class="mt-1" required autofocus autocomplete="given-name" />
                <InputError class="mt-2" :message="form.errors.first_name" />
            </div>

            <div>
                <InputLabel for="last_name" value="Nom" />
                <TextInput id="last_name" v-model="form.last_name" class="mt-1" required autocomplete="family-name" />
                <InputError class="mt-2" :message="form.errors.last_name" />
            </div>
        </div>

        <div class="mt-4">
            <InputLabel for="email" value="Adresse e-mail" />
            <TextInput id="email" v-model="form.email" type="email" class="mt-1" required autocomplete="username" />
            <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div class="mt-4">
            <InputLabel for="password" value="Mot de passe" />
            <TextInput id="password" v-model="form.password" type="password" class="mt-1" required autocomplete="new-password" />
            <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="mt-4">
            <InputLabel for="password_confirmation" value="Confirmer le mot de passe" />
            <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1" required autocomplete="new-password" />
            <InputError class="mt-2" :message="form.errors.password_confirmation" />
        </div>

        <div class="mt-6 flex items-center justify-between">
            <Link :href="route('login')" class="text-sm text-gray-600 underline hover:text-gray-900">
                Déjà un compte ?
            </Link>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Créer mon compte
            </PrimaryButton>
        </div>
    </form>
</template>
