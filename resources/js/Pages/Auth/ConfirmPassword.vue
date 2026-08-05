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
    data() {
        return {
            form: useForm({ password: '' }),
        };
    },
    methods: {
        submit() {
            this.form.post(route('password.confirm'), {
                onFinish: () => this.form.reset(),
            });
        },
    },
};
</script>

<template>
    <Head title="Confirmer le mot de passe" />

    <div class="mb-4 text-sm text-gray-600">
        Ceci est une zone sécurisée. Merci de confirmer votre mot de passe avant de continuer.
    </div>

    <form @submit.prevent="submit">
        <InputLabel for="password" value="Mot de passe" />
        <TextInput id="password" v-model="form.password" type="password" class="mt-1" required autofocus autocomplete="current-password" />
        <InputError class="mt-2" :message="form.errors.password" />

        <div class="mt-4 flex justify-end">
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Confirmer
            </PrimaryButton>
        </div>
    </form>
</template>
