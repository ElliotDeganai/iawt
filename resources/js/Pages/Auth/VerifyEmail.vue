<script>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

export default {
    components: { GuestLayout, Head, Link, PrimaryButton },
    layout: GuestLayout,
    props: {
        status: String,
    },
    data() {
        return {
            form: useForm({}),
        };
    },
    computed: {
        verificationLinkSent() {
            return this.status === 'verification-link-sent';
        },
    },
    methods: {
        submit() {
            this.form.post(route('verification.send'));
        },
    },
};
</script>

<template>
    <Head title="Vérification de l'e-mail" />

    <div class="mb-4 text-sm text-gray-600">
        Merci de votre inscription ! Avant de commencer, merci de vérifier votre adresse e-mail en cliquant sur le lien
        que nous venons de vous envoyer. Si vous ne l'avez pas reçu, nous pouvons vous en envoyer un autre.
    </div>

    <div v-if="verificationLinkSent" class="mb-4 text-sm font-medium text-green-600">
        Un nouveau lien de vérification a été envoyé à l'adresse e-mail fournie lors de l'inscription.
    </div>

    <form @submit.prevent="submit">
        <div class="mt-4 flex items-center justify-between">
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Renvoyer l'e-mail de vérification
            </PrimaryButton>

            <Link :href="route('logout')" method="post" as="button" class="text-sm text-gray-600 underline hover:text-gray-900">
                Se déconnecter
            </Link>
        </div>
    </form>
</template>
