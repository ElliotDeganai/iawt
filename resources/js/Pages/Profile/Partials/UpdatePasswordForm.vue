<script>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

export default {
    components: { InputError, InputLabel, PrimaryButton, TextInput },
    data() {
        return {
            form: useForm({
                current_password: '',
                password: '',
                password_confirmation: '',
            }),
        };
    },
    methods: {
        submit() {
            this.form.put(route('password.update'), {
                preserveScroll: true,
                onSuccess: () => this.form.reset(),
                onError: () => {
                    if (this.form.errors.password) {
                        this.form.reset('password', 'password_confirmation');
                        this.$refs.password?.focus();
                    }
                    if (this.form.errors.current_password) {
                        this.form.reset('current_password');
                        this.$refs.currentPassword?.focus();
                    }
                },
            });
        },
    },
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Modifier le mot de passe</h2>
            <p class="mt-1 text-sm text-gray-600">
                Utilisez un mot de passe long et unique pour sécuriser votre compte.
            </p>
        </header>

        <form class="mt-6 space-y-6" @submit.prevent="submit">
            <div>
                <InputLabel for="current_password" value="Mot de passe actuel" />
                <TextInput
                    id="current_password" ref="currentPassword" v-model="form.current_password"
                    type="password" class="mt-1" autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.current_password" />
            </div>

            <div>
                <InputLabel for="password" value="Nouveau mot de passe" />
                <TextInput
                    id="password" ref="password" v-model="form.password"
                    type="password" class="mt-1" autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Confirmer le mot de passe" />
                <TextInput
                    id="password_confirmation" v-model="form.password_confirmation"
                    type="password" class="mt-1" autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Enregistrer
                </PrimaryButton>

                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Enregistré.</p>
            </div>
        </form>
    </section>
</template>
