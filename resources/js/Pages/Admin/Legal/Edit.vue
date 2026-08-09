<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import RichTextEditor from '@/Components/RichTextEditor.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/vue3';

export default {
    components: { AdminLayout, RichTextEditor, PrimaryButton, Head },
    props: {
        legalNotice: String,
        privacyPolicy: String,
    },
    data() {
        return {
            activeTab: 'legal',
            form: useForm({
                legal_notice: this.legalNotice || '',
                privacy_policy: this.privacyPolicy || '',
            }),
        };
    },
    methods: {
        submit() {
            this.form.put(route('admin.legal.update'));
        },
    },
};
</script>

<template>
    <Head title="Administration — Pages légales" />

    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Pages légales</h1>
        </template>

        <form class="max-w-4xl w-full space-y-6" @submit.prevent="submit">
            <!-- Tabs -->
            <div class="flex gap-0 border-b border-gray-200">
                <button
                    type="button"
                    class="border-b-2 px-5 pb-3 text-sm font-medium transition"
                    :class="activeTab === 'legal' ? 'border-primary-600 text-primary-700' : 'border-transparent text-gray-400 hover:text-gray-600'"
                    @click="activeTab = 'legal'"
                >Mentions légales</button>
                <button
                    type="button"
                    class="border-b-2 px-5 pb-3 text-sm font-medium transition"
                    :class="activeTab === 'privacy' ? 'border-primary-600 text-primary-700' : 'border-transparent text-gray-400 hover:text-gray-600'"
                    @click="activeTab = 'privacy'"
                >Politique de confidentialité</button>
            </div>

            <!-- Mentions légales -->
            <div v-show="activeTab === 'legal'" class="bg-white rounded-lg shadow p-6">
                <div class="mb-4">
                    <h2 class="font-medium text-gray-800">Mentions légales</h2>
                    <p class="mt-1 text-xs text-gray-500">Ce contenu sera affiché sur la page /mentions-legales.</p>
                </div>
                <RichTextEditor v-model="form.legal_notice" min-height="400px" />
            </div>

            <!-- Politique de confidentialité -->
            <div v-show="activeTab === 'privacy'" class="bg-white rounded-lg shadow p-6">
                <div class="mb-4">
                    <h2 class="font-medium text-gray-800">Politique de confidentialité</h2>
                    <p class="mt-1 text-xs text-gray-500">Ce contenu sera affiché sur la page /politique-de-confidentialite.</p>
                </div>
                <RichTextEditor v-model="form.privacy_policy" min-height="400px" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Enregistrer</PrimaryButton>
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Enregistré.</p>
            </div>
        </form>
    </AdminLayout>
</template>
