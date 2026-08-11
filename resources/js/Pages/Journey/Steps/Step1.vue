<script>
import StepLayout from '@/Components/Journey/StepLayout.vue';
import { useForm } from '@inertiajs/vue3';

export default {
    components: { StepLayout },
    props: { stepNumber: Number, journeyResponse: Object, totalSteps: Number },
    data() {
        const r = this.journeyResponse.responses || {};
        return {
            form: useForm({
                responses: {
                    idea_origin: r.idea_origin || [],
                    idea_origin_other: r.idea_origin_other || '',
                    who: r.who || '',
                    what: r.what || '',
                    why: r.why || '',
                    how: r.how || '',
                    where: r.where || '',
                    alignment_energy: r.alignment_energy ?? 3,
                    alignment_skills: r.alignment_skills ?? 3,
                    alignment_lifestyle: r.alignment_lifestyle ?? 3,
                },
            }),
        };
    },
    computed: {
        origins() {
            return [
                { key: 'experience', label: "L'expérience personnelle ou professionnelle" },
                { key: 'observation', label: "L'observation d'un besoin mal servi" },
                { key: 'innovation', label: "L'innovation" },
                { key: 'passion', label: 'La passion / passe-temps' },
                { key: 'other', label: 'Autre' },
            ];
        },
        ideaFields() {
            return [
                { key: 'who', label: 'Qui', placeholder: 'Qui portera ce projet ? Qui sont les bénéficiaires ?' },
                { key: 'what', label: 'Quoi', placeholder: 'En quoi consiste concrètement l\'idée ?' },
                { key: 'why', label: 'Pourquoi', placeholder: 'Quel problème résout-elle, quelle motivation ?' },
                { key: 'how', label: 'Comment', placeholder: 'Ressources, moyens et démarche' },
                { key: 'where', label: 'Où', placeholder: 'Zone géographique, lieu d\'implantation envisagé' },
            ];
        },
        sliders() {
            return [
                { key: 'alignment_energy', label: 'Alignement énergétique', help: 'Ce projet a-t-il du sens pour vous ?' },
                { key: 'alignment_skills', label: 'Alignement des compétences', help: 'Avez-vous les compétences nécessaires ?' },
                { key: 'alignment_lifestyle', label: 'Alignement mode de vie', help: 'Compatible avec votre vie actuelle ?' },
            ];
        },
    },
    methods: {
        toggleOrigin(key) {
            const i = this.form.responses.idea_origin.indexOf(key);
            if (i >= 0) this.form.responses.idea_origin.splice(i, 1);
            else this.form.responses.idea_origin.push(key);
        },
        save() { this.form.put(route('journey-response.save', this.stepNumber)); },
        complete() { this.form.put(route('journey-response.complete', this.stepNumber)); },
    },
};
</script>

<template>
    <StepLayout :step-number="stepNumber" :total-steps="totalSteps" title="De l'idée au projet" subtitle="Toute entreprise commence par une idée." :saving="form.processing" @save="save" @complete="complete">

        <!-- Origine -->
        <div class="mb-6 rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-sm font-medium text-gray-800">D'où vient votre idée ?</p>
            <div class="flex flex-wrap gap-2">
                <button
                    v-for="o in origins" :key="o.key"
                    type="button"
                    class="rounded-full border px-4 py-2 text-sm transition"
                    :class="form.responses.idea_origin.includes(o.key) ? 'border-primary-600 bg-primary-50 text-primary-700' : 'border-gray-200 text-gray-600 hover:border-gray-300'"
                    @click="toggleOrigin(o.key)"
                >{{ o.label }}</button>
            </div>
            <div v-if="form.responses.idea_origin.includes('other')" class="mt-3">
                <input v-model="form.responses.idea_origin_other" class="w-full rounded-lg border-gray-200 text-sm" placeholder="Précisez…" />
            </div>
        </div>

        <!-- Présentation -->
        <div class="mb-6 rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-sm font-medium text-gray-800">Présentez votre idée</p>
            <p class="mb-4 text-xs text-gray-500">2-3 lignes maximum par champ.</p>
            <div class="space-y-3">
                <div v-for="f in ideaFields" :key="f.key" class="flex gap-3">
                    <span class="flex h-9 w-14 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-xs font-semibold text-primary-700">{{ f.label }}</span>
                    <textarea v-model="form.responses[f.key]" rows="2" maxlength="300" class="flex-1 rounded-lg border-gray-200 text-sm" :placeholder="f.placeholder"></textarea>
                </div>
            </div>
        </div>

        <!-- Auto-évaluation -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-1 text-sm font-medium text-gray-800">Adéquation homme/projet</p>
            <p class="mb-4 text-xs text-gray-500">Évaluez-vous honnêtement de 1 à 5.</p>
            <div class="space-y-5">
                <div v-for="s in sliders" :key="s.key">
                    <div class="mb-1 flex items-center justify-between">
                        <span class="text-sm text-gray-700">{{ s.label }}</span>
                        <span class="text-sm font-semibold text-primary-700">{{ form.responses[s.key] }} / 5</span>
                    </div>
                    <p class="mb-3 text-xs text-gray-400">{{ s.help }}</p>
                    <div class="flex gap-2">
                        <button
                            v-for="n in 5" :key="n"
                            type="button"
                            class="flex h-10 w-10 items-center justify-center rounded-full border-2 text-sm font-semibold transition sm:h-11 sm:w-11"
                            :class="form.responses[s.key] >= n ? 'border-primary-600 bg-primary-600 text-white' : 'border-gray-200 text-gray-400 hover:border-primary-300'"
                            @click="form.responses[s.key] = n"
                        >{{ n }}</button>
                    </div>
                </div>
            </div>
        </div>
    </StepLayout>
</template>