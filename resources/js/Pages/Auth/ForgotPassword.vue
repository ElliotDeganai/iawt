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
        status: String,
    },
    data() {
        return {
            form: useForm({ email: '' }),
        };
    },
    methods: {
        submit() {
            this.form.post(route('password.email'));
        },
    },
};
</script>

<template>
    <Head title="Mot de passe oublié" />

    <div class="mb-4 text-sm text-gray-600">
        Mot de passe oublié ? Indiquez votre adresse e-mail et nous vous enverrons un lien de réinitialisation.
    </div>

    <div v-if="status" class="mb-4 text-sm font-medium text-green-600">{{ status }}</div>

    <form @submit.prevent="submit">
        <InputLabel for="email" value="Adresse e-mail" />
        <TextInput id="email" v-model="form.email" type="email" class="mt-1" required autofocus />
        <InputError class="mt-2" :message="form.errors.email" />

        <div class="mt-4 flex justify-end">
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Envoyer le lien
            </PrimaryButton>
        </div>
    </form>
</template>
