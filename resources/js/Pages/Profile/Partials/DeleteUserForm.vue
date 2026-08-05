<script>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

export default {
    components: { DangerButton, InputError, InputLabel, Modal, SecondaryButton, TextInput },
    data() {
        return {
            confirmingUserDeletion: false,
            form: useForm({ password: '' }),
        };
    },
    methods: {
        confirmUserDeletion() {
            this.confirmingUserDeletion = true;
            this.$nextTick(() => this.$refs.password?.focus());
        },
        deleteUser() {
            this.form.delete(route('profile.destroy'), {
                preserveScroll: true,
                onSuccess: () => this.closeModal(),
                onError: () => this.$refs.password?.focus(),
                onFinish: () => this.form.reset(),
            });
        },
        closeModal() {
            this.confirmingUserDeletion = false;
            this.form.reset();
        },
    },
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Supprimer le compte</h2>
            <p class="mt-1 text-sm text-gray-600">
                Une fois votre compte supprimé, toutes ses données seront définitivement effacées, y compris votre
                parcours entrepreneur.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion">Supprimer mon compte</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Êtes-vous sûr de vouloir supprimer votre compte ?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Cette action est irréversible. Merci de saisir votre mot de passe pour confirmer.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Mot de passe" class="sr-only" />
                    <TextInput
                        id="password" ref="password" v-model="form.password" type="password"
                        class="mt-1 block w-3/4" placeholder="Mot de passe"
                        @keyup.enter="deleteUser"
                    />
                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="closeModal">Annuler</SecondaryButton>
                    <DangerButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="deleteUser">
                        Supprimer mon compte
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
