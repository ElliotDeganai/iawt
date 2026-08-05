<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/vue3';

export default {
    components: { AdminLayout, InputLabel, InputError, TextInput, PrimaryButton, Head },
    props: {
        settings: Object,
        logo: String,
    },
    data() {
        return {
            logoPreview: this.logo,
            form: useForm({
                site_name: this.settings.site_name,
                tagline: this.settings.tagline,
                contact_email: this.settings.contact_email,
                description: this.settings.description,
                logo: null,
            }),
        };
    },
    methods: {
        onLogoChange(event) {
            const file = event.target.files[0];
            this.form.logo = file;
            if (file) this.logoPreview = URL.createObjectURL(file);
            console.log(this.form);
        },
        submit() {
                    this.form
                        .transform((data) => ({ ...data, _method: 'put' }))
                        .post(route('admin.settings.update'), {
                            onSuccess: () => (this.form.logo = null),
                        });
        },
    },
};
</script>

<template>
    <Head title="Administration — Réglages du site" />

    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Réglages du site</h1>
        </template>

        <form class="max-w-2xl bg-white rounded-lg shadow p-6 space-y-4" enctype="multipart/form-data" @submit.prevent="submit">
            <input type="hidden" name="_method" value="PUT" />

            <div>
                <InputLabel value="Logo du site" />
                <div class="mt-2 flex items-center gap-4">
                    <img :src="logoPreview" class="h-16 w-16 rounded-full border border-gray-200 object-cover" />
                    <input type="file" accept=".svg,.png,.jpg,.jpeg,.webp" class="text-sm" @change="onLogoChange" />
                </div>
                <p class="mt-1 text-xs text-gray-500">SVG recommandé (utilisé aussi comme icône d'onglet du navigateur). 1 Mo max.</p>
                <InputError class="mt-2" :message="form.errors.logo" />
            </div>

            <div>
                <InputLabel value="Nom du site" />
                <TextInput v-model="form.site_name" class="mt-1" required />
                <InputError class="mt-2" :message="form.errors.site_name" />
            </div>

            <div>
                <InputLabel value="Accroche" />
                <TextInput v-model="form.tagline" class="mt-1" />
                <InputError class="mt-2" :message="form.errors.tagline" />
            </div>

            <div>
                <InputLabel value="E-mail de contact" />
                <TextInput v-model="form.contact_email" type="email" class="mt-1" required />
                <InputError class="mt-2" :message="form.errors.contact_email" />
            </div>

            <div>
                <InputLabel value="Description" />
                <textarea v-model="form.description" rows="4" class="mt-1 w-full rounded-md border-gray-300 text-sm" />
                <InputError class="mt-2" :message="form.errors.description" />
            </div>

            <div class="flex items-center gap-4 pt-2">
                <PrimaryButton :disabled="form.processing">Enregistrer</PrimaryButton>
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Enregistré.</p>
            </div>
        </form>
    </AdminLayout>
</template>