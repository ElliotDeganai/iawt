<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import Icon from '@/Components/Icon.vue';
import RichTextEditor from '@/Components/RichTextEditor.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const ICONS = ['leaf', 'palm', 'rocket', 'lightbulb', 'search', 'chart', 'currency', 'scale', 'target', 'building'];

export default {
    components: { AdminLayout, InputLabel, InputError, TextInput, Icon, RichTextEditor, PrimaryButton, Head, Link },
    data() {
        return {
            icons: ICONS,
            imagePreview: null,
            form: useForm({
                icon: 'lightbulb',
                label: '',
                description: '',
                image: null,
                position: 1,
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
            this.form.post(route('admin.journey-steps.store'));
        },
    },
};
</script>

<template>
    <Head title="Administration — Nouvelle étape du parcours" />

    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Ajouter une étape du parcours</h1>
        </template>

        <form class="max-w-2xl space-y-6" enctype="multipart/form-data" @submit.prevent="submit">
            <div class="bg-white rounded-lg shadow p-6 space-y-5">

                <!-- Icône -->
                <div>
                    <InputLabel value="Icône" />
                    <div class="mt-2 flex flex-wrap gap-2">
                        <button
                            v-for="i in icons" :key="i" type="button"
                            class="flex h-10 w-10 items-center justify-center rounded-full border-2 transition"
                            :class="form.icon === i ? 'border-primary-600 bg-primary-600 text-white' : 'border-gray-200 text-gray-500 hover:border-primary-300'"
                            @click="form.icon = i"
                        >
                            <Icon :name="i" class="h-5 w-5" />
                        </button>
                    </div>
                    <InputError class="mt-2" :message="form.errors.icon" />
                </div>

                <!-- Libellé -->
                <div>
                    <InputLabel value="Libellé de l'étape" />
                    <TextInput v-model="form.label" class="mt-1 w-full" required autofocus />
                    <InputError class="mt-2" :message="form.errors.label" />
                </div>

                <!-- Description richtext -->
                <div>
                    <InputLabel value="Description" />
                    <div class="mt-1">
                        <RichTextEditor v-model="form.description" />
                    </div>
                    <InputError class="mt-2" :message="form.errors.description" />
                </div>

                <!-- Image d'illustration -->
                <div>
                    <InputLabel value="Image d'illustration" />
                    <img v-if="imagePreview" :src="imagePreview" class="mt-2 h-40 w-full rounded-lg object-cover" />
                    <input type="file" accept="image/*" class="mt-1 text-sm" @change="onImageChange" />
                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, WebP · max 4 Mo</p>
                    <InputError class="mt-2" :message="form.errors.image" />
                </div>

                <!-- Position -->
                <div>
                    <InputLabel value="Ordre d'affichage" />
                    <TextInput v-model.number="form.position" type="number" min="1" class="mt-1 w-24" required />
                    <InputError class="mt-2" :message="form.errors.position" />
                </div>
            </div>

            <div class="flex items-center gap-3">
                <PrimaryButton :disabled="form.processing">Créer</PrimaryButton>
                <Link :href="route('admin.journey-steps.index')" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 hover:bg-gray-50">
                    Annuler
                </Link>
            </div>
        </form>
    </AdminLayout>
</template>
