<script>
import StepLayout from '@/Components/Journey/StepLayout.vue';
import { useForm } from '@inertiajs/vue3';

const BLOCKS = [
    { key: 'partners', title: 'Partenaires clés', help: 'Qui vous aide à faire tourner le projet ?' },
    { key: 'activities', title: 'Activités clés', help: 'Que devez-vous faire concrètement ?' },
    { key: 'value', title: 'Proposition de valeur', help: 'Quel problème résolvez-vous, pour qui ?' },
    { key: 'relationship', title: 'Relation client', help: 'Quel lien créez-vous avec vos clients ?' },
    { key: 'segments', title: 'Segments de clientèle', help: 'Qui sont vos clients ou bénéficiaires ?' },
    { key: 'resources', title: 'Ressources clés', help: 'De quoi avez-vous besoin pour démarrer ?' },
    { key: 'channels', title: 'Canaux', help: 'Comment touchez-vous vos clients ?' },
    { key: 'costs', title: 'Structure de coûts', help: 'Que dépensez-vous pour faire fonctionner le projet ?' },
    { key: 'revenue', title: 'Sources de revenus', help: 'Comment et par qui êtes-vous payé ?' },
];

export default {
    components: { StepLayout },
    props: { stepNumber: Number, journeyResponse: Object, totalSteps: Number },
    data() {
        const r = this.journeyResponse.responses || {};
        const responses = {};
        BLOCKS.forEach(b => { responses[b.key] = r[b.key] || ''; });
        return { blocks: BLOCKS, form: useForm({ responses }) };
    },
    methods: {
        save() { this.form.put(route('journey-response.save', this.stepNumber)); },
        complete() { this.form.put(route('journey-response.complete', this.stepNumber)); },
    },
};
</script>

<template>
    <StepLayout :step-number="stepNumber" :total-steps="totalSteps" title="Business model canvas" subtitle="Votre modèle économique en 9 blocs." :saving="form.processing" @save="save" @complete="complete">
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-4 text-xs text-gray-500">Remplissez chaque case avec vos propres mots — mots-clés ou courtes notes.</p>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="b in blocks" :key="b.key" class="rounded-lg border border-gray-100 p-3">
                    <p class="mb-1 text-xs font-semibold text-primary-700">{{ b.title }}</p>
                    <p class="mb-2 text-[10px] text-gray-400">{{ b.help }}</p>
                    <textarea v-model="form.responses[b.key]" rows="3" class="w-full rounded border-gray-200 text-sm"></textarea>
                </div>
            </div>
            <div class="mt-4 rounded-lg border-l-4 border-gold-500 bg-gold-50 px-4 py-3">
                <p class="text-xs text-gray-600">Commencez par la case centrale (proposition de valeur), puis avancez vers la droite (client), puis vers la gauche (infrastructure).</p>
            </div>
        </div>
    </StepLayout>
</template>
