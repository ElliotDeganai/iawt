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
        heroImage: String,
        programImage: String,
    },
    data() {
        return {
            heroPreview: this.heroImage,
            programPreview: this.programImage,
            form: useForm({
                hero_title: this.settings.hero_title,
                hero_subtitle: this.settings.hero_subtitle,
                program_title: this.settings.program_title,
                program_text: this.settings.program_text,
                hero_image: null,
                program_image: null,
            }),
        };
    },
    methods: {
        onHeroImageChange(event) {
            const file = event.target.files[0];
            this.form.hero_image = file;
            if (file) this.heroPreview = URL.createObjectURL(file);
        },
        onProgramImageChange(event) {
            const file = event.target.files[0];
            this.form.program_image = file;
            if (file) this.programPreview = URL.createObjectURL(file);
        },
        submit() {
            this.form
                .transform((data) => ({ ...data, _method: 'put' }))
                .post(route('admin.home-content.update'), {
                    onSuccess: () => {
                        this.form.hero_image = null;
                        this.form.program_image = null;
                    },
                });
        },
    },
};
</script>

<template>
    <Head title="Administration — Contenu de la page d'accueil" />

    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Contenu de la page d'accueil</h1>
        </template>

        <form class="max-w-2xl space-y-6" enctype="multipart/form-data" @submit.prevent="submit">
            <div class="bg-white rounded-lg shadow p-6 space-y-4">
                <h2 class="font-medium text-gray-800">Section principale (hero)</h2>

                <div>
                    <InputLabel value="Titre" />
                    <TextInput v-model="form.hero_title" class="mt-1" required />
                    <InputError class="mt-2" :message="form.errors.hero_title" />
                </div>

                <div>
                    <InputLabel value="Sous-titre" />
                    <textarea v-model="form.hero_subtitle" rows="2" maxlength="500" class="mt-1 w-full rounded-md border-gray-300 text-sm" />
                    <InputError class="mt-2" :message="form.errors.hero_subtitle" />
                </div>

                <div>
                    <InputLabel value="Image de fond" />
                    <img v-if="heroPreview" :src="heroPreview" class="mt-2 h-32 w-full rounded-md object-cover" />
                    <input type="file" accept="image/*" class="mt-1 text-sm" @change="onHeroImageChange" />
                    <InputError class="mt-2" :message="form.errors.hero_image" />
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 space-y-4">
                <h2 class="font-medium text-gray-800">Section « Le programme »</h2>

                <div>
                    <InputLabel value="Titre" />
                    <TextInput v-model="form.program_title" class="mt-1" />
                    <InputError class="mt-2" :message="form.errors.program_title" />
                </div>

                <div>
                    <InputLabel value="Texte" />
                    <textarea v-model="form.program_text" rows="4" maxlength="1000" class="mt-1 w-full rounded-md border-gray-300 text-sm" />
                    <InputError class="mt-2" :message="form.errors.program_text" />
                </div>

                <div>
                    <InputLabel value="Image" />
                    <img v-if="programPreview" :src="programPreview" class="mt-2 h-32 w-full rounded-md object-cover" />
                    <input type="file" accept="image/*" class="mt-1 text-sm" @change="onProgramImageChange" />
                    <InputError class="mt-2" :message="form.errors.program_image" />
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Enregistrer</PrimaryButton>
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Enregistré.</p>
            </div>
        </form>
    </AdminLayout>
</template>
