<script>
export default {
    props: { modelValue: { type: Object, default: () => ({}) } },
    computed: { d() { return this.modelValue; } },
    methods: {
        update(key, val) { this.$emit('update:modelValue', { ...this.d, [key]: val }); },
        addCompetitor() {
            const arr = this.d.competitors || [];
            this.update('competitors', [...arr, { name: '', strengths: '', weaknesses: '', price: '', access: '' }]);
        },
        updateCompetitor(i, key, val) {
            const arr = [...(this.d.competitors || [])];
            arr[i] = { ...arr[i], [key]: val };
            this.update('competitors', arr);
        },
        removeCompetitor(i) {
            const arr = (this.d.competitors || []).filter((_, idx) => idx !== i);
            this.update('competitors', arr);
        },
    },
};
</script>
<template>
    <div class="space-y-5">
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">1. Présentation du projet</p>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div><label class="block text-xs text-gray-500 mb-1">Nom du projet</label><input :value="d.project_name||''" @input="update('project_name',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-500 mb-1">Zone géographique</label><input :value="d.zone||''" @input="update('zone',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
            </div>
            <div class="mt-3"><label class="block text-xs text-gray-500 mb-1">Description rapide (5-7 lignes)</label><textarea :value="d.description||''" @input="update('description',$event.target.value)" rows="4" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">2. Problème identifié</p>
            <div class="space-y-3">
                <div><label class="block text-xs text-gray-500 mb-1">Quel est le problème précis ?</label><textarea :value="d.problem||''" @input="update('problem',$event.target.value)" rows="3" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-500 mb-1">Comment avez-vous identifié ce problème ?</label><textarea :value="d.problem_source||''" @input="update('problem_source',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div><label class="block text-xs text-gray-500 mb-1">Personnes consultées</label><input type="number" :value="d.people_consulted||''" @input="update('people_consulted',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                    <div><label class="block text-xs text-gray-500 mb-1">Fréquence du problème</label><input :value="d.problem_frequency||''" @input="update('problem_frequency',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                </div>
                <div><label class="block text-xs text-gray-500 mb-1">Citations ou retours significatifs</label><textarea :value="d.feedback_quotes||''" @input="update('feedback_quotes',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm" placeholder="Les mots exacts des personnes rencontrées…"></textarea></div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">3. Cible(s)</p>
            <div class="space-y-3">
                <div><label class="block text-xs text-gray-500 mb-1">Client principal</label><textarea :value="d.main_client||''" @input="update('main_client',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-500 mb-1">Cible prioritaire au lancement</label><textarea :value="d.priority_target||''" @input="update('priority_target',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">5. Concurrence</p>
            <div v-for="(c, i) in (d.competitors || [])" :key="i" class="mb-3 rounded-lg border border-gray-100 p-3">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-700">Solution {{ i + 1 }}</span>
                    <button type="button" class="text-xs text-red-600 hover:underline" @click="removeCompetitor(i)">Retirer</button>
                </div>
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                    <input :value="c.name" @input="updateCompetitor(i,'name',$event.target.value)" class="rounded-md border-gray-200 text-sm" placeholder="Nom" />
                    <input :value="c.price" @input="updateCompetitor(i,'price',$event.target.value)" class="rounded-md border-gray-200 text-sm" placeholder="Prix/coût" />
                    <input :value="c.strengths" @input="updateCompetitor(i,'strengths',$event.target.value)" class="rounded-md border-gray-200 text-sm" placeholder="Points forts" />
                    <input :value="c.weaknesses" @input="updateCompetitor(i,'weaknesses',$event.target.value)" class="rounded-md border-gray-200 text-sm" placeholder="Points faibles" />
                </div>
            </div>
            <button type="button" class="inline-flex items-center gap-1 rounded-md border border-gray-200 px-3 py-1.5 text-xs text-gray-600 hover:bg-gray-50" @click="addCompetitor">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14m-7-7h14"/></svg>
                Ajouter une solution
            </button>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">8. SWOT</p>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div><label class="block text-xs font-medium text-green-700 mb-1">Forces</label><textarea :value="d.swot_strengths||''" @input="update('swot_strengths',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs font-medium text-red-700 mb-1">Faiblesses</label><textarea :value="d.swot_weaknesses||''" @input="update('swot_weaknesses',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs font-medium text-blue-700 mb-1">Opportunités</label><textarea :value="d.swot_opportunities||''" @input="update('swot_opportunities',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs font-medium text-amber-700 mb-1">Menaces</label><textarea :value="d.swot_threats||''" @input="update('swot_threats',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">10. Décision</p>
            <div class="space-y-2">
                <label v-for="opt in ['Poursuivre sans modification','Poursuivre avec ajustements','Mettre en pause','Abandonner']" :key="opt" class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" :checked="d.decision===opt" @change="update('decision',opt)" class="text-primary-600" />
                    <span class="text-sm text-gray-700">{{ opt }}</span>
                </label>
            </div>
            <div class="mt-4 rounded-lg border-l-3 border-gold-500 bg-gold-50 px-4 py-3 text-xs text-gray-600 italic">
                Une étude de marché réussie n'est pas celle qui rassure, mais celle qui permet de décider avec lucidité.
            </div>
        </div>
    </div>
</template>
