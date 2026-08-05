<script>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

export default {
    components: { GuestLayout, Head, InputError, InputLabel, PrimaryButton, TextInput },
    layout: GuestLayout,
    props: {
        email: String,
        token: String,
    },
    data() {
        return {
            form: useForm({
                token: this.token,
                email: this.email,
                password: '',
                password_confirmation: '',
            }),
        };
    },
    methods: {
        submit() {
            this.form.post(route('password.store'), {
                onFinish: () => this.form.reset('password', 'password_confirmation'),
            });
        },
    },
};
</script>

<template>
    <Head title="Réinitialiser le mot de passe" />

    <form @submit.prevent="submit">
        <div>
            <InputLabel for="email" value="Adresse e-mail" />
            <TextInput id="email" v-model="form.email" type="email" class="mt-1" required autofocus autocomplete="username" />
            <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div class="mt-4">
            <InputLabel for="password" value="Nouveau mot de passe" />
            <TextInput id="password" v-model="form.password" type="password" class="mt-1" required autocomplete="new-password" />
            <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="mt-4">
            <InputLabel for="password_confirmation" value="Confirmer le mot de passe" />
            <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1" required autocomplete="new-password" />
            <InputError class="mt-2" :message="form.errors.password_confirmation" />
        </div>

        <div class="mt-4 flex justify-end">
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Réinitialiser le mot de passe
            </PrimaryButton>
        </div>
    </form>
</template>
