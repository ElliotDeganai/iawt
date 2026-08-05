<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import RichTextEditor from '@/Components/RichTextEditor.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

export default {
    components: { AdminLayout, InputLabel, InputError, TextInput, Checkbox, RichTextEditor, PrimaryButton, Head, Link },
    data() {
        return {
            imagePreview: null,
            form: useForm({
                title: '',
                description: '',
                image: null,
                url: '',
                date: '',
                time: '',
                is_featured: false,
                is_published: true,
            }),
        };
    },
    methods: {
        onImageChange(e) {
            const file = e.target.files[0];
            this.form.image = file;
            if (file) this.imagePreview = URL.createObjectURL(file);
        },
        submit() {
            this.form.post(route('admin.events.store'));
        },
    },
};
</script>

<template>
    <Head title="Administration — Nouvel événement" />
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Ajouter un événement</h1>
        </template>

        <form class="max-w-2xl space-y-6" enctype="multipart/form-data" @submit.prevent="submit">
            <div class="bg-white rounded-lg shadow p-6 space-y-5">

                <div>
                    <InputLabel value="Titre de l'événement" />
                    <TextInput v-model="form.title" class="mt-1 w-full" required autofocus />
                    <InputError class="mt-2" :message="form.errors.title" />
                </div>

                <div>
                    <InputLabel value="Description" />
                    <div class="mt-1"><RichTextEditor v-model="form.description" /></div>
                    <InputError class="mt-2" :message="form.errors.description" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <InputLabel value="Date" />
                        <TextInput v-model="form.date" type="date" class="mt-1 w-full" required />
                        <InputError class="mt-2" :message="form.errors.date" />
                    </div>
                    <div>
                        <InputLabel value="Heure" />
                        <TextInput v-model="form.time" type="time" class="mt-1 w-full" />
                        <InputError class="mt-2" :message="form.errors.time" />
                    </div>
                </div>

                <div>
                    <InputLabel value="Lien URL (inscription, programme…)" />
                    <TextInput v-model="form.url" type="url" class="mt-1 w-full" placeholder="https://" />
                    <InputError class="mt-2" :message="form.errors.url" />
                </div>

                <div>
                    <InputLabel value="Image d'illustration" />
                    <img v-if="imagePreview" :src="imagePreview" class="mt-2 h-40 w-full rounded-lg object-cover" />
                    <input type="file" accept="image/*" class="mt-1 text-sm" @change="onImageChange" />
                    <InputError class="mt-2" :message="form.errors.image" />
                </div>

                <div class="flex flex-col gap-3">
                    <label class="flex items-center gap-2">
                        <Checkbox v-model:checked="form.is_featured" />
                        <span class="text-sm text-gray-700">Événement vedette <span class="text-xs text-gold-600">(affiché en doré dans le calendrier)</span></span>
                    </label>
                    <label class="flex items-center gap-2">
                        <Checkbox v-model:checked="form.is_published" />
                        <span class="text-sm text-gray-700">Publié (visible sur le site)</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <PrimaryButton :disabled="form.processing">Créer</PrimaryButton>
                <Link :href="route('admin.events.index')" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 hover:bg-gray-50">
                    Annuler
                </Link>
            </div>
        </form>
    </AdminLayout>
</template>
