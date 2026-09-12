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
    computed: {
        showUrl()  { return ['link', 'video'].includes(this.form.type); },
        showFile() { return ['pdf', 'template', 'video'].includes(this.form.type); },
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
                <div>
                    <InputLabel value="Catégorie" />
                    <select v-model="form.category_id" class="mt-1 w-full rounded-md border-gray-300 text-sm" required>
                        <option value="">Choisir</option>
                        <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                    </select>
                    <InputError class="mt-1" :message="form.errors.category_id" />
                </div>
                <div>
                    <InputLabel value="Titre" />
                    <TextInput v-model="form.title" class="mt-1 w-full" required />
                    <InputError class="mt-1" :message="form.errors.title" />
                </div>
                <div>
                    <InputLabel value="Description" />
                    <textarea v-model="form.description" rows="3" class="mt-1 w-full rounded-md border-gray-300 text-sm" />
                </div>
                <div>
                    <InputLabel value="Type de ressource" />
                    <div class="mt-2 grid grid-cols-2 gap-2 sm:grid-cols-4">
                        <label v-for="t in [{v:'pdf',l:'PDF / Document'},{v:'video',l:'Vidéo'},{v:'link',l:'Lien externe'},{v:'template',l:'Modèle'}]" :key="t.v"
                            class="flex items-center gap-2 rounded-lg border-2 px-3 py-2.5 cursor-pointer transition"
                            :class="form.type === t.v ? 'border-primary-600 bg-primary-50' : 'border-gray-200 hover:border-gray-300'">
                            <input v-model="form.type" type="radio" :value="t.v" class="text-primary-600" />
                            <span class="text-sm" :class="form.type === t.v ? 'font-medium text-primary-700' : 'text-gray-600'">{{ t.l }}</span>
                        </label>
                    </div>
                </div>

                <!-- URL (lien, vidéo) -->
                <div v-if="showUrl">
                    <InputLabel :value="form.type === 'video' ? 'URL de la vidéo (YouTube, Vimeo…)' : 'URL du lien'" />
                    <TextInput v-model="form.url" type="url" class="mt-1 w-full" placeholder="https://..." />
                    <p v-if="form.type === 'video'" class="mt-1 text-xs text-gray-400">Collez un lien YouTube, Vimeo ou autre. Vous pouvez aussi uploader un fichier vidéo ci-dessous.</p>
                    <InputError class="mt-1" :message="form.errors.url" />
                </div>

                <!-- Fichier (pdf, template, vidéo) -->
                <div v-if="showFile">
                    <InputLabel :value="form.type === 'video' ? 'Ou uploader un fichier vidéo' : 'Fichier'" />
                    <input type="file" class="mt-1 text-sm"
                        :accept="form.type === 'video' ? '.mp4,.mov,.avi,.wmv,.webm,.mkv' : '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.jpg,.jpeg,.png,.gif,.svg'"
                        @change="form.file = $event.target.files[0]" />
                    <p class="mt-1 text-xs text-gray-400">
                        <template v-if="form.type === 'video'">MP4, MOV, AVI, WebM, MKV — max 200 Mo</template>
                        <template v-else>PDF, Word, Excel, PowerPoint, images, ZIP — max 200 Mo</template>
                    </p>
                    <p v-if="isEdit && resource?.file_path" class="mt-1 text-xs text-gray-500">Fichier actuel : <span class="font-mono">{{ resource.file_path.split('/').pop() }}</span></p>
                    <InputError class="mt-1" :message="form.errors.file" />
                </div>

                <label class="flex items-center gap-2">
                    <Checkbox v-model:checked="form.is_published" />
                    <span class="text-sm text-gray-700">Publié</span>
                </label>
            </div>
            <div class="flex items-center gap-3">
                <PrimaryButton :disabled="form.processing">{{ isEdit ? 'Enregistrer' : 'Créer' }}</PrimaryButton>
                <Link :href="route('admin.resources.index')" class="text-sm text-gray-600 hover:underline">Annuler</Link>
            </div>
        </form>
    </AdminLayout>
</template>