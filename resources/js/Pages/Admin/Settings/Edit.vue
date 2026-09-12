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
    },
    data() {
        return {
            logoPreview: this.settings.logo || null,
            form: useForm({
                site_name: this.settings.site_name,
                tagline: this.settings.tagline,
                contact_email: this.settings.contact_email,
                description: this.settings.description,
                forum_moderation_mode: this.settings.forum_moderation_mode || 'strict',
                logo: null,
            }),
        };
    },
    methods: {
        onLogoChange(e) {
            const file = e.target.files[0];
            this.form.logo = file;
            if (file) this.logoPreview = URL.createObjectURL(file);
        },
        submit() {
            this.form
                .transform((data) => ({ ...data, _method: 'put' }))
                .post(route('admin.settings.update'), { forceFormData: true });
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

        <form class="max-w-2xl w-full bg-white rounded-lg shadow p-6 space-y-4" enctype="multipart/form-data" @submit.prevent="submit">
            <!-- Logo -->
            <div>
                <InputLabel value="Logo du site" />
                <div class="mt-2 flex items-center gap-4">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 overflow-hidden">
                        <img v-if="logoPreview" :src="logoPreview" class="h-full w-full object-contain p-1" alt="Logo" />
                        <span v-else class="text-xs text-gray-400">Aucun</span>
                    </div>
                    <div>
                        <label class="inline-flex cursor-pointer items-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 hover:bg-gray-50">
                            Changer le logo
                            <input type="file" accept=".svg,.png,.jpg,.jpeg,.webp" class="hidden" @change="onLogoChange" />
                        </label>
                        <p class="mt-1 text-xs text-gray-400">SVG, PNG, JPG ou WebP</p>
                    </div>
                </div>
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

            <!-- Modération forum -->
            <div class="border-t border-gray-200 pt-4">
                <InputLabel value="Modération du forum" />
                <p class="mt-1 text-xs text-gray-500 mb-3">Détermine si les publications du forum nécessitent une validation avant d'être visibles.</p>
                <div class="flex gap-3">
                    <label class="flex-1 cursor-pointer rounded-xl border-2 p-4 transition" :class="form.forum_moderation_mode === 'strict' ? 'border-primary-600 bg-primary-50' : 'border-gray-200 hover:border-gray-300'" @click="form.forum_moderation_mode = 'strict'">
                        <p class="text-sm font-semibold" :class="form.forum_moderation_mode === 'strict' ? 'text-primary-700' : 'text-gray-700'">Modération stricte</p>
                        <p class="mt-1 text-xs text-gray-500">Chaque publication doit être approuvée par un administrateur avant d'être visible.</p>
                    </label>
                    <label class="flex-1 cursor-pointer rounded-xl border-2 p-4 transition" :class="form.forum_moderation_mode === 'soft' ? 'border-primary-600 bg-primary-50' : 'border-gray-200 hover:border-gray-300'" @click="form.forum_moderation_mode = 'soft'">
                        <p class="text-sm font-semibold" :class="form.forum_moderation_mode === 'soft' ? 'text-primary-700' : 'text-gray-700'">Modération souple</p>
                        <p class="mt-1 text-xs text-gray-500">Les publications sont visibles immédiatement. L'admin peut masquer ou supprimer a posteriori.</p>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-2">
                <PrimaryButton :disabled="form.processing">Enregistrer</PrimaryButton>
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Enregistré.</p>
            </div>
        </form>
    </AdminLayout>
</template>
