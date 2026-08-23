<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

export default {
    components: { AdminLayout, InputLabel, InputError, TextInput, Checkbox, PrimaryButton, Head, Link },
    props: { categories: Array },
    data() {
        return {
            imagePreview: null,
            form: useForm({
                title: '', description: '', image: null, url: '',
                date: '', time: '', is_featured: false, is_published: true,
                event_category_id: '',
            }),
        };
    },
    computed: {
        selectedCatColor() {
            const cat = this.categories.find(c => c.id === this.form.event_category_id);
            return cat?.color || null;
        },
    },
    methods: {
        onImageChange(e) { const f = e.target.files[0]; this.form.image = f; if (f) this.imagePreview = URL.createObjectURL(f); },
        submit() { this.form.post(route('admin.events.store')); },
    },
};
</script>
<template>
    <Head title="Administration — Nouvel événement" />
    <AdminLayout>
        <template #header><h1 class="text-lg font-semibold text-gray-800">Ajouter un événement</h1></template>
        <form class="max-w-2xl space-y-4" @submit.prevent="submit" enctype="multipart/form-data">
            <div class="rounded-lg bg-white p-6 shadow space-y-4">
                <div><InputLabel value="Titre" /><TextInput v-model="form.title" class="mt-1 w-full" required /><InputError class="mt-1" :message="form.errors.title" /></div>
                <div><InputLabel value="Catégorie" />
                    <div class="mt-1 flex items-center gap-2">
                        <span v-if="selectedCatColor" class="h-4 w-4 shrink-0 rounded-full" :style="{ background: selectedCatColor }"></span>
                        <select v-model="form.event_category_id" class="w-full rounded-md border-gray-300 text-sm">
                            <option value="">— Sans catégorie —</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                        </select>
                    </div>
                    <InputError class="mt-1" :message="form.errors.event_category_id" />
                </div>
                <div><InputLabel value="Description" /><textarea v-model="form.description" rows="4" class="mt-1 w-full rounded-md border-gray-300 text-sm" /><InputError class="mt-1" :message="form.errors.description" /></div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div><InputLabel value="Date" /><input type="date" v-model="form.date" class="mt-1 w-full rounded-md border-gray-300 text-sm" required /><InputError class="mt-1" :message="form.errors.date" /></div>
                    <div><InputLabel value="Heure (optionnel)" /><input type="time" v-model="form.time" class="mt-1 w-full rounded-md border-gray-300 text-sm" /><InputError class="mt-1" :message="form.errors.time" /></div>
                </div>
                <div><InputLabel value="Lien externe (optionnel)" /><TextInput v-model="form.url" class="mt-1 w-full" placeholder="https://..." /><InputError class="mt-1" :message="form.errors.url" /></div>
                <div>
                    <InputLabel value="Image" />
                    <img v-if="imagePreview" :src="imagePreview" class="mt-2 h-32 w-full rounded-md object-cover" />
                    <input type="file" accept="image/*" class="mt-1 text-sm" @change="onImageChange" />
                    <InputError class="mt-1" :message="form.errors.image" />
                </div>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2"><Checkbox v-model:checked="form.is_published" /><span class="text-sm text-gray-700">Publié</span></label>
                    <label class="flex items-center gap-2"><Checkbox v-model:checked="form.is_featured" /><span class="text-sm text-gray-700">En vedette</span></label>
                </div>
            </div>
            <div class="flex items-center gap-3"><PrimaryButton :disabled="form.processing">Créer</PrimaryButton><Link :href="route('admin.events.index')" class="text-sm text-gray-600 hover:underline">Annuler</Link></div>
        </form>
    </AdminLayout>
</template>