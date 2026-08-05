<script>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

export default {
    components: { GuestLayout, Head, Link, InputError, InputLabel, PrimaryButton, TextInput, Checkbox },
    layout: GuestLayout,
    props: {
        canResetPassword: Boolean,
        status: String,
    },
    data() {
        return {
            form: useForm({
                email: '',
                password: '',
                remember: false,
            }),
        };
    },
    methods: {
        submit() {
            this.form.post(route('login'), {
                onFinish: () => this.form.reset('password'),
            });
        },
    },
};
</script>

<template>
    <Head title="Connexion" />

    <div v-if="status" class="mb-4 text-sm font-medium text-green-600">{{ status }}</div>

    <form @submit.prevent="submit">
        <div>
            <InputLabel for="email" value="Adresse e-mail" />
            <TextInput id="email" v-model="form.email" type="email" class="mt-1" required autofocus autocomplete="username" />
            <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div class="mt-4">
            <InputLabel for="password" value="Mot de passe" />
            <TextInput id="password" v-model="form.password" type="password" class="mt-1" required autocomplete="current-password" />
            <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="mt-4 block">
            <label class="flex items-center">
                <Checkbox v-model:checked="form.remember" name="remember" />
                <span class="ms-2 text-sm text-gray-600">Se souvenir de moi</span>
            </label>
        </div>

        <div class="mt-4 flex items-center justify-between">
            <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-gray-600 underline hover:text-gray-900">
                Mot de passe oublié ?
            </Link>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Se connecter
            </PrimaryButton>
        </div>

        <p class="mt-6 text-sm text-gray-600">
            Pas encore de compte ?
            <Link :href="route('register')" class="text-primary-600 underline">Créer un compte</Link>
        </p>
    </form>
</template>
