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
    props: { resource: Object, categories: Array },
    data() {
        const r = this.resource || {};
        return {
            isEdit: !!this.resource,
            form: useForm({
                category_id: r.category_id ?? '',
                title: r.title ?? '',
                description: r.description ?? '',
                type: r.type ?? 'pdf',
                url: r.url ?? '',
                file: null,
                is_published: r.is_published ?? true,
            }),
        };
    },
    methods: {
        submit() {
            const opts = { forceFormData: true };
            if (this.isEdit) this.form.transform(d => ({ ...d, _method: 'put' })).post(route('admin.resources.update', this.resource.id), opts);
            else this.form.post(route('admin.resources.store'), opts);
        },
    },
};
</script>
<template>
    <Head :title="isEdit ? 'Modifier ressource' : 'Ajouter une ressource'" />
    <AdminLayout>
        <template #header><h1 class="text-lg font-semibold text-gray-800">{{ isEdit ? 'Modifier' : 'Ajouter' }} une ressource</h1></template>
        <form class="max-w-2xl space-y-5" @submit.prevent="submit">
            <div class="rounded-lg bg-white p-6 shadow space-y-4">
                <div><InputLabel value="Catégorie" /><select v-model="form.category_id" class="mt-1 w-full rounded-md border-gray-300 text-sm" required><option value="">Choisir</option><option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option></select><InputError class="mt-1" :message="form.errors.category_id" /></div>
                <div><InputLabel value="Titre" /><TextInput v-model="form.title" class="mt-1 w-full" required /><InputError class="mt-1" :message="form.errors.title" /></div>
                <div><InputLabel value="Description" /><textarea v-model="form.description" rows="2" class="mt-1 w-full rounded-md border-gray-300 text-sm" /></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><InputLabel value="Type" /><select v-model="form.type" class="mt-1 w-full rounded-md border-gray-300 text-sm"><option value="pdf">PDF</option><option value="link">Lien</option><option value="video">Vidéo</option><option value="template">Modèle/Template</option></select></div>
                    <div v-if="form.type === 'link' || form.type === 'video'"><InputLabel value="URL" /><TextInput v-model="form.url" type="url" class="mt-1 w-full" /></div>
                    <div v-else><InputLabel value="Fichier" /><input type="file" class="mt-1 text-sm" @change="form.file = $event.target.files[0]" /><InputError class="mt-1" :message="form.errors.file" /></div>
                </div>
                <label class="flex items-center gap-2"><Checkbox v-model:checked="form.is_published" /><span class="text-sm text-gray-700">Publié</span></label>
            </div>
            <div class="flex items-center gap-3">
                <PrimaryButton :disabled="form.processing">{{ isEdit ? 'Enregistrer' : 'Créer' }}</PrimaryButton>
                <Link :href="route('admin.resources.index')" class="text-sm text-gray-600 hover:underline">Annuler</Link>
            </div>
        </form>
    </AdminLayout>
</template>
