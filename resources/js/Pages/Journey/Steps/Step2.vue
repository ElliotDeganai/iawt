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
                    project_name: r.project_name || '',
                    project_zone: r.project_zone || '',
                    project_description: r.project_description || '',
                    problem: r.problem || '',
                    problem_frequency: r.problem_frequency || '',
                    problem_consequences: r.problem_consequences || '',
                    problem_source: r.problem_source || '',
                    people_consulted: r.people_consulted || '',
                    key_quotes: r.key_quotes || '',
                    target_main: r.target_main || '',
                    target_age: r.target_age || '',
                    target_constraints: r.target_constraints || '',
                    target_segments: r.target_segments || '',
                    target_priority: r.target_priority || '',
                    market_scope: r.market_scope || '',
                    market_trend: r.market_trend || '',
                    env_political: r.env_political || '',
                    env_economic: r.env_economic || '',
                    env_social: r.env_social || '',
                    env_tech: r.env_tech || '',
                    env_legal: r.env_legal || '',
                    competitors: r.competitors || [{ name: '', strengths: '', weaknesses: '', price: '', access: '' }],
                    alt_solutions: r.alt_solutions || '',
                    buy_current: r.buy_current || '',
                    buy_amount: r.buy_amount || '',
                    buy_payer: r.buy_payer || '',
                    buy_barriers: r.buy_barriers || '',
                    buy_conditions: r.buy_conditions || '',
                    market_size: r.market_size || '',
                    market_hypothesis: r.market_hypothesis || '',
                    market_confidence: r.market_confidence || '',
                    swot_strengths: r.swot_strengths || '',
                    swot_weaknesses: r.swot_weaknesses || '',
                    swot_opportunities: r.swot_opportunities || '',
                    swot_threats: r.swot_threats || '',
                    confirms: r.confirms || '',
                    questions: r.questions || '',
                    adjustments: r.adjustments || '',
                    next_hypotheses: r.next_hypotheses || '',
                    decision: r.decision || '',
                },
            }),
        };
    },
    methods: {
        addCompetitor() { this.form.responses.competitors.push({ name: '', strengths: '', weaknesses: '', price: '', access: '' }); },
        removeCompetitor(i) { this.form.responses.competitors.splice(i, 1); },
        save() { this.form.put(route('journey-response.save', this.stepNumber)); },
        complete() { this.form.put(route('journey-response.complete', this.stepNumber)); },
    },
};
</script>

<template>
    <StepLayout :step-number="stepNumber" :total-steps="totalSteps" title="Étude de marché" subtitle="Transformer votre idée grâce au terrain." :saving="form.processing" @save="save" @complete="complete">

        <!-- 1. Projet -->
        <div class="mb-6 rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">1. Présentation du projet</p>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Nom du projet</label><input v-model="form.responses.project_name" class="w-full rounded-lg border-gray-200 text-sm" /></div>
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Zone géographique</label><input v-model="form.responses.project_zone" class="w-full rounded-lg border-gray-200 text-sm" /></div>
            </div>
            <div class="mt-3"><label class="mb-1 block text-xs font-medium text-gray-700">Description rapide (5-7 lignes)</label><textarea v-model="form.responses.project_description" rows="4" class="w-full rounded-lg border-gray-200 text-sm"></textarea></div>
        </div>

        <!-- 2. Problème -->
        <div class="mb-6 rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">2. Problème identifié</p>
            <div class="space-y-3">
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Quel problème résolvez-vous ?</label><textarea v-model="form.responses.problem" rows="3" class="w-full rounded-lg border-gray-200 text-sm"></textarea></div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div><label class="mb-1 block text-xs font-medium text-gray-700">Fréquence</label><input v-model="form.responses.problem_frequency" class="w-full rounded-lg border-gray-200 text-sm" /></div>
                    <div><label class="mb-1 block text-xs font-medium text-gray-700">Conséquences</label><input v-model="form.responses.problem_consequences" class="w-full rounded-lg border-gray-200 text-sm" /></div>
                </div>
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Comment l'avez-vous identifié ?</label><input v-model="form.responses.problem_source" class="w-full rounded-lg border-gray-200 text-sm" /></div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div><label class="mb-1 block text-xs font-medium text-gray-700">Personnes consultées</label><input v-model="form.responses.people_consulted" type="number" class="w-full rounded-lg border-gray-200 text-sm" /></div>
                    <div><label class="mb-1 block text-xs font-medium text-gray-700">Citations significatives</label><textarea v-model="form.responses.key_quotes" rows="2" class="w-full rounded-lg border-gray-200 text-sm" placeholder="Les mots exacts des personnes rencontrées"></textarea></div>
                </div>
            </div>
        </div>

        <!-- 3. Cible -->
        <div class="mb-6 rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">3. Cible du projet</p>
            <div class="space-y-3">
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Client / utilisateur principal</label><input v-model="form.responses.target_main" class="w-full rounded-lg border-gray-200 text-sm" /></div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div><label class="mb-1 block text-xs font-medium text-gray-700">Âge / situation</label><input v-model="form.responses.target_age" class="w-full rounded-lg border-gray-200 text-sm" /></div>
                    <div><label class="mb-1 block text-xs font-medium text-gray-700">Contraintes</label><input v-model="form.responses.target_constraints" class="w-full rounded-lg border-gray-200 text-sm" /></div>
                </div>
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Segments (plusieurs types de clients ?)</label><textarea v-model="form.responses.target_segments" rows="2" class="w-full rounded-lg border-gray-200 text-sm"></textarea></div>
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Cible prioritaire au lancement et pourquoi</label><textarea v-model="form.responses.target_priority" rows="2" class="w-full rounded-lg border-gray-200 text-sm"></textarea></div>
            </div>
        </div>

        <!-- 5. Concurrence -->
        <div class="mb-6 rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">5. Concurrence</p>
            <div v-for="(c, i) in form.responses.competitors" :key="i" class="mb-3 rounded-lg border border-gray-100 bg-gray-50 p-3">
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-xs font-medium text-gray-700">Solution {{ i + 1 }}</span>
                    <button v-if="form.responses.competitors.length > 1" type="button" class="text-xs text-red-600" @click="removeCompetitor(i)">Retirer</button>
                </div>
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                    <input v-model="c.name" class="rounded border-gray-200 text-sm" placeholder="Nom" />
                    <input v-model="c.price" class="rounded border-gray-200 text-sm" placeholder="Prix / coût" />
                    <input v-model="c.strengths" class="rounded border-gray-200 text-sm" placeholder="Points forts" />
                    <input v-model="c.weaknesses" class="rounded border-gray-200 text-sm" placeholder="Points faibles" />
                </div>
            </div>
            <button type="button" class="text-sm text-primary-600 hover:underline" @click="addCompetitor">+ Ajouter une solution à comparer</button>
        </div>

        <!-- SWOT -->
        <div class="mb-6 rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">8. SWOT</p>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div class="rounded-lg bg-green-50 p-3"><p class="mb-1 text-xs font-semibold text-green-700">Forces</p><textarea v-model="form.responses.swot_strengths" rows="3" class="w-full rounded border-gray-200 text-sm bg-white"></textarea></div>
                <div class="rounded-lg bg-red-50 p-3"><p class="mb-1 text-xs font-semibold text-red-700">Faiblesses</p><textarea v-model="form.responses.swot_weaknesses" rows="3" class="w-full rounded border-gray-200 text-sm bg-white"></textarea></div>
                <div class="rounded-lg bg-blue-50 p-3"><p class="mb-1 text-xs font-semibold text-blue-700">Opportunités</p><textarea v-model="form.responses.swot_opportunities" rows="3" class="w-full rounded border-gray-200 text-sm bg-white"></textarea></div>
                <div class="rounded-lg bg-amber-50 p-3"><p class="mb-1 text-xs font-semibold text-amber-700">Menaces</p><textarea v-model="form.responses.swot_threats" rows="3" class="w-full rounded border-gray-200 text-sm bg-white"></textarea></div>
            </div>
        </div>

        <!-- Décision -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">10. Décision</p>
            <div class="space-y-2">
                <label v-for="d in ['pursue_unchanged','pursue_adjusted','pause','abandon']" :key="d" class="flex items-center gap-3 rounded-lg border px-4 py-3 cursor-pointer transition" :class="form.responses.decision === d ? 'border-primary-600 bg-primary-50' : 'border-gray-200'">
                    <input type="radio" v-model="form.responses.decision" :value="d" class="text-primary-600" />
                    <span class="text-sm text-gray-700" v-text="{ pursue_unchanged: 'Je poursuis sans modification majeure', pursue_adjusted: 'Je poursuis avec ajustements', pause: 'Je mets le projet en pause', abandon: 'J\'abandonne le projet' }[d]"></span>
                </label>
            </div>
            <div class="mt-4 rounded-lg border-l-4 border-gold-500 bg-gold-50 px-4 py-3">
                <p class="text-sm italic text-gray-600">Une étude de marché réussie n'est pas celle qui rassure, mais celle qui permet de décider avec lucidité.</p>
            </div>
        </div>
    </StepLayout>
</template>
