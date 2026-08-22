<script>
export default {
    props: {
        modelValue: { type: Object, default: () => ({}) },
        errors: { type: Object, default: () => ({}) },
    },
    computed: { d() { return this.modelValue; } },
    methods: {
        hasErr(k) { return !!this.errors[k]; },
        errMsg(k) { return this.errors[k] || ''; },
        errClass(k) { return this.hasErr(k) ? 'border-red-400 bg-red-50/30' : 'border-gray-200'; },
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
            this.update('competitors', (this.d.competitors || []).filter((_, idx) => idx !== i));
        },
    },
};
</script>
<template>
    <div class="space-y-6">

        <div class="rounded-lg border-l-[3px] border-gold-500 bg-gold-50 px-4 py-3 text-xs text-gray-600 italic">
            Une étude de marché ne sert pas à valider une idée, elle sert à la transformer. Ce document est un outil de travail pour structurer votre réflexion et prendre des décisions.
        </div>

        <!-- ═══ 1. Présentation synthétique ═══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">1</span>
                <p class="text-sm font-medium text-gray-800">Présentation synthétique du projet</p>
            </div>
            <div class="space-y-3">
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div><label class="block text-xs text-gray-600 mb-1">Nom du projet (provisoire)</label><input :value="d.project_name||''" @input="update('project_name',$event.target.value)" class="w-full rounded-md text-sm" :class="errClass('project_name')" /><p v-if="hasErr('project_name')" class="mt-1 text-xs text-red-600">{{ errMsg('project_name') }}</p></div>
                    <div><label class="block text-xs text-gray-600 mb-1">Porteur(s) du projet</label><input :value="d.project_holders||''" @input="update('project_holders',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                </div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <div><label class="block text-xs text-gray-600 mb-1">Pays</label><input :value="d.zone_country||''" @input="update('zone_country',$event.target.value)" class="w-full rounded-md text-sm" :class="errClass('zone_country')" /><p v-if="hasErr('zone_country')" class="mt-1 text-xs text-red-600">{{ errMsg('zone_country') }}</p></div>
                    <div><label class="block text-xs text-gray-600 mb-1">Région</label><input :value="d.zone_region||''" @input="update('zone_region',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                    <div><label class="block text-xs text-gray-600 mb-1">Ville</label><input :value="d.zone_city||''" @input="update('zone_city',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                </div>
                <div><label class="block text-xs text-gray-600 mb-1">Description rapide de l'idée (5-7 lignes max)</label><textarea :value="d.description||''" @input="update('description',$event.target.value)" rows="4" class="w-full rounded-md text-sm" :class="errClass('description')"></textarea><p v-if="hasErr('description')" class="mt-1 text-xs text-red-600">{{ errMsg('description') }}</p></div>
            </div>
        </div>

        <!-- ═══ 2. Problème identifié ═══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">2</span>
                <p class="text-sm font-medium text-gray-800">Problème identifié et besoin réel</p>
            </div>
            <div class="mb-4 mt-1 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Description du problème</p><p class="mt-0.5 text-[10px] text-gray-400">Identifiez et décrivez le problème que vous cherchez à résoudre.</p></div>
            <div class="space-y-3">
                <div><label class="block text-xs text-gray-600 mb-1">Quel est le problème précis que vous cherchez à résoudre ?</label><textarea :value="d.problem||''" @input="update('problem',$event.target.value)" rows="3" class="w-full rounded-md text-sm" :class="errClass('problem')"></textarea><p v-if="hasErr('problem')" class="mt-1 text-xs text-red-600">{{ errMsg('problem') }}</p></div>
                <div><label class="block text-xs text-gray-600 mb-1">Dans quelles situations ce problème apparaît-il ?</label><textarea :value="d.problem_situations||''" @input="update('problem_situations',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-600 mb-1">À quelle fréquence ce problème survient-il ?</label><input :value="d.problem_frequency||''" @input="update('problem_frequency',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-600 mb-1">Quelles sont les conséquences pour la personne concernée ?</label><textarea :value="d.problem_consequences||''" @input="update('problem_consequences',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
            </div>

            <div class="mb-4 mt-5 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Validation terrain</p><p class="mt-0.5 text-[10px] text-gray-400">Vérifiez votre compréhension du problème auprès des personnes concernées.</p></div>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Comment avez-vous identifié ce problème ?</label>
                    <p class="text-[10px] text-gray-400 mb-1">Expérience personnelle, observation, échanges…</p>
                    <textarea :value="d.problem_source||''" @input="update('problem_source',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea>
                </div>
                <div><label class="block text-xs text-gray-600 mb-1">Avez-vous échangé avec des personnes concernées ?</label>
                    <div class="flex gap-3 mt-1">
                        <button type="button" class="rounded-full border px-4 py-1.5 text-xs" :class="d.exchanged_with_people==='oui'?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-500'" @click="update('exchanged_with_people','oui')">Oui</button>
                        <button type="button" class="rounded-full border px-4 py-1.5 text-xs" :class="d.exchanged_with_people==='non'?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-500'" @click="update('exchanged_with_people','non')">Non</button>
                    </div>
                </div>
                <div><label class="block text-xs text-gray-600 mb-1">Nombre de personnes consultées</label><input type="number" :value="d.people_consulted||''" @input="update('people_consulted',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Citations ou retours significatifs</label>
                    <p class="text-[10px] text-gray-400 mb-1">Reprenez ici, si possible, les mots exacts des personnes rencontrées.</p>
                    <textarea :value="d.feedback_quotes||''" @input="update('feedback_quotes',$event.target.value)" rows="3" class="w-full rounded-md border-gray-200 text-sm"></textarea>
                </div>
            </div>
        </div>

        <!-- ═══ 3. Cibles ═══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">3</span>
                <p class="text-sm font-medium text-gray-800">Cible(s) du projet</p>
            </div>
            <div class="mb-4 mt-1 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Client / utilisateur principal</p><p class="mt-0.5 text-[10px] text-gray-400">Décrivez la personne principalement concernée par le problème.</p></div>
            <div class="space-y-3">
                <div><label class="block text-xs text-gray-600 mb-1">Qui est la personne principalement concernée par le problème ?</label><textarea :value="d.main_client||''" @input="update('main_client',$event.target.value)" rows="2" class="w-full rounded-md text-sm" :class="errClass('main_client')"></textarea><p v-if="hasErr('main_client')" class="mt-1 text-xs text-red-600">{{ errMsg('main_client') }}</p></div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <div><label class="block text-xs text-gray-600 mb-1">Âge</label><input :value="d.client_age||''" @input="update('client_age',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                    <div><label class="block text-xs text-gray-600 mb-1">Situation</label><input :value="d.client_situation||''" @input="update('client_situation',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                    <div><label class="block text-xs text-gray-600 mb-1">Activité</label><input :value="d.client_activity||''" @input="update('client_activity',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                </div>
                <div><label class="block text-xs text-gray-600 mb-1">Contexte de vie ou de travail</label><textarea :value="d.client_context||''" @input="update('client_context',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-600 mb-1">Contraintes principales (temps, argent, accès, compétences…)</label><textarea :value="d.client_constraints||''" @input="update('client_constraints',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
            </div>

            <div class="mb-4 mt-5 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Segmentation</p><p class="mt-0.5 text-[10px] text-gray-400">Identifiez les différents types de clients et votre cible prioritaire.</p></div>
            <div class="space-y-3">
                <div><label class="block text-xs text-gray-600 mb-1">Existe-t-il plusieurs types de clients / utilisateurs ?</label><textarea :value="d.segments||''" @input="update('segments',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-600 mb-1">Quelle est la cible prioritaire au lancement ? Pourquoi ?</label><textarea :value="d.priority_target||''" @input="update('priority_target',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
            </div>
        </div>

        <!-- ═══ 4. Environnement du marché ═══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">4</span>
                <p class="text-sm font-medium text-gray-800">Analyse de l'environnement du marché</p>
            </div>
            <div class="mb-4 mt-1 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Contexte général</p><p class="mt-0.5 text-[10px] text-gray-400">Évaluez la taille et la dynamique de votre marché.</p></div>
            <div class="space-y-3">
                <div><label class="block text-xs text-gray-600 mb-1">Le marché est-il local, national ou régional ?</label>
                    <div class="flex flex-wrap gap-2 mt-1">
                        <button v-for="m in ['Local','National','Régional']" :key="m" type="button" class="rounded-full border px-3 py-1.5 text-xs" :class="d.market_scope===m?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-500'" @click="update('market_scope',m)">{{ m }}</button>
                    </div>
                </div>
                <div><label class="block text-xs text-gray-600 mb-1">Le marché est-il en croissance, stable ou en déclin ?</label>
                    <div class="flex flex-wrap gap-2 mt-1">
                        <button v-for="t in ['En croissance','Stable','En déclin']" :key="t" type="button" class="rounded-full border px-3 py-1.5 text-xs" :class="d.market_trend===t?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-500'" @click="update('market_trend',t)">{{ t }}</button>
                    </div>
                </div>
            </div>

            <div class="mb-4 mt-5 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Facteurs externes</p><p class="mt-0.5 text-[10px] text-gray-400">Analysez les facteurs politiques, économiques, sociaux, technologiques et légaux.</p></div>
            <div class="space-y-3">
                <div><label class="block text-xs text-gray-600 mb-1">Politique / institutionnel</label><textarea :value="d.env_political||''" @input="update('env_political',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-600 mb-1">Économique (pouvoir d'achat, prix, financement)</label><textarea :value="d.env_economic||''" @input="update('env_economic',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-600 mb-1">Social / culturel (habitudes, freins, perceptions)</label><textarea :value="d.env_social||''" @input="update('env_social',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-600 mb-1">Technologique (accès au numérique, outils, usages)</label><textarea :value="d.env_tech||''" @input="update('env_tech',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-600 mb-1">Légal / réglementaire</label><textarea :value="d.env_legal||''" @input="update('env_legal',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
            </div>
        </div>

        <!-- ═══ 5. Concurrence ═══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">5</span>
                <p class="text-sm font-medium text-gray-800">Analyse de la concurrence et des alternatives</p>
            </div>
            <div class="mb-4 mt-1 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Solutions existantes</p><p class="mt-0.5 text-[10px] text-gray-400">Recensez les solutions qui répondent déjà au même besoin.</p></div>
            <div class="space-y-3">
                <div><label class="block text-xs text-gray-600 mb-1">Qui répond aujourd'hui au même besoin ?</label><textarea :value="d.existing_solutions||''" @input="update('existing_solutions',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-600 mb-1">Solutions concurrentes directes</label><textarea :value="d.direct_competitors||''" @input="update('direct_competitors',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-600 mb-1">Solutions alternatives indirectes</label><textarea :value="d.indirect_competitors||''" @input="update('indirect_competitors',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-600 mb-1">Comment les clients font-ils aujourd'hui s'il n'y a pas de solution dédiée ?</label><textarea :value="d.current_workaround||''" @input="update('current_workaround',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
            </div>

            <div class="mb-4 mt-5 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Analyse comparative</p><p class="mt-0.5 text-[10px] text-gray-400">Comparez les forces, faiblesses, prix et accessibilité de chaque solution.</p></div>
            <div v-for="(c, i) in (d.competitors || [])" :key="i" class="mb-3 rounded-lg border border-gray-100 p-4">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-medium text-gray-700">Solution {{ i + 1 }}</span>
                    <button type="button" class="text-xs text-red-500 hover:underline" @click="removeCompetitor(i)">Retirer</button>
                </div>
                <div class="space-y-2">
                    <div><label class="block text-xs text-gray-500 mb-0.5">Nom de la solution</label><input :value="c.name" @input="updateCompetitor(i,'name',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                        <div><label class="block text-xs text-gray-500 mb-0.5">Points forts</label><textarea :value="c.strengths" @input="updateCompetitor(i,'strengths',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                        <div><label class="block text-xs text-gray-500 mb-0.5">Points faibles</label><textarea :value="c.weaknesses" @input="updateCompetitor(i,'weaknesses',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                        <div><label class="block text-xs text-gray-500 mb-0.5">Prix / coût</label><input :value="c.price" @input="updateCompetitor(i,'price',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                        <div><label class="block text-xs text-gray-500 mb-0.5">Accessibilité (géo, numérique, financière)</label><input :value="c.access" @input="updateCompetitor(i,'access',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                    </div>
                </div>
            </div>
            <button type="button" class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs text-gray-600 hover:bg-gray-50" @click="addCompetitor">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14m-7-7h14"/></svg>
                Ajouter une solution à comparer
            </button>
        </div>

        <!-- ═══ 6. Comportement d'achat ═══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">6</span>
                <p class="text-sm font-medium text-gray-800">Comportement d'achat et disposition à payer</p>
            </div>
            <div class="mb-4 mt-1 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Pratiques actuelles</p><p class="mt-0.5 text-[10px] text-gray-400">Comprenez comment et combien les clients paient aujourd'hui.</p></div>
            <div class="space-y-3">
                <div><label class="block text-xs text-gray-600 mb-1">Les clients paient-ils déjà pour ce type de solution ?</label>
                    <div class="flex gap-3 mt-1">
                        <button v-for="o in ['Oui','Non','Partiellement']" :key="o" type="button" class="rounded-full border px-3 py-1.5 text-xs" :class="d.clients_pay===o?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-500'" @click="update('clients_pay',o)">{{ o }}</button>
                    </div>
                </div>
                <div><label class="block text-xs text-gray-600 mb-1">Si oui, combien et sous quelle forme ?</label><textarea :value="d.pay_how_much||''" @input="update('pay_how_much',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-600 mb-1">Qui paie réellement (client final, institution, entreprise, tiers) ?</label><input :value="d.who_pays||''" @input="update('who_pays',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
            </div>

            <div class="mb-4 mt-5 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Perception de la valeur</p><p class="mt-0.5 text-[10px] text-gray-400">Identifiez les freins et les conditions pour que les clients acceptent de payer.</p></div>
            <div class="space-y-3">
                <div><label class="block text-xs text-gray-600 mb-1">Quels sont les freins au paiement ?</label><textarea :value="d.payment_barriers||''" @input="update('payment_barriers',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-600 mb-1">À quelles conditions les clients seraient-ils prêts à payer ?</label><textarea :value="d.payment_conditions||''" @input="update('payment_conditions',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
            </div>
        </div>

        <!-- ═══ 7. Taille du marché ═══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">7</span>
                <p class="text-sm font-medium text-gray-800">Taille du marché (estimation simple)</p>
            </div>
            <div class="space-y-3">
                <div><label class="block text-xs text-gray-600 mb-1">Nombre de clients potentiels</label><input :value="d.market_size||''" @input="update('market_size',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-600 mb-1">Hypothèse utilisée pour l'estimation</label><textarea :value="d.market_hypothesis||''" @input="update('market_hypothesis',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-600 mb-1">Niveau de confiance dans cette estimation</label>
                    <div class="flex flex-wrap gap-2 mt-1">
                        <button v-for="n in ['Faible','Moyen','Élevé']" :key="n" type="button" class="rounded-full border px-3 py-1.5 text-xs" :class="d.market_confidence===n?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-500'" @click="update('market_confidence',n)">{{ n }}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══ 8. SWOT ═══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">8</span>
                <p class="text-sm font-medium text-gray-800">Synthèse stratégique (SWOT)</p>
            </div>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div class="rounded-lg border border-green-100 bg-green-50/50 p-3">
                    <label class="block text-xs font-medium text-green-700 mb-1">Forces</label>
                    <textarea :value="d.swot_strengths||''" @input="update('swot_strengths',$event.target.value)" rows="3" class="w-full rounded-md border-green-200 bg-white text-sm"></textarea>
                </div>
                <div class="rounded-lg border border-red-100 bg-red-50/50 p-3">
                    <label class="block text-xs font-medium text-red-700 mb-1">Faiblesses</label>
                    <textarea :value="d.swot_weaknesses||''" @input="update('swot_weaknesses',$event.target.value)" rows="3" class="w-full rounded-md border-red-200 bg-white text-sm"></textarea>
                </div>
                <div class="rounded-lg border border-blue-100 bg-blue-50/50 p-3">
                    <label class="block text-xs font-medium text-blue-700 mb-1">Opportunités</label>
                    <textarea :value="d.swot_opportunities||''" @input="update('swot_opportunities',$event.target.value)" rows="3" class="w-full rounded-md border-blue-200 bg-white text-sm"></textarea>
                </div>
                <div class="rounded-lg border border-amber-100 bg-amber-50/50 p-3">
                    <label class="block text-xs font-medium text-amber-700 mb-1">Menaces</label>
                    <textarea :value="d.swot_threats||''" @input="update('swot_threats',$event.target.value)" rows="3" class="w-full rounded-md border-amber-200 bg-white text-sm"></textarea>
                </div>
            </div>
        </div>

        <!-- ═══ 9. Enseignements clés ═══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">9</span>
                <p class="text-sm font-medium text-gray-800">Enseignements clés et décisions</p>
            </div>
            <div class="space-y-3">
                <div><label class="block text-xs text-gray-600 mb-1">Ce que l'étude confirme</label><textarea :value="d.study_confirms||''" @input="update('study_confirms',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-600 mb-1">Ce que l'étude remet en question</label><textarea :value="d.study_questions||''" @input="update('study_questions',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-600 mb-1">Ajustements envisagés du projet</label><textarea :value="d.adjustments||''" @input="update('adjustments',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-600 mb-1">Hypothèses à tester lors de la phase suivante</label><textarea :value="d.next_hypotheses||''" @input="update('next_hypotheses',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
            </div>
        </div>

        <!-- ═══ 10. Décision ═══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">10</span>
                <p class="text-sm font-medium text-gray-800">Décision à l'issue de l'étude de marché</p>
            </div>
            <div :class="hasErr('decision') ? 'rounded-lg border border-red-300 bg-red-50/50 p-3' : ''" class="space-y-2">
                <label v-for="opt in ['Je poursuis le projet sans modification majeure','Je poursuis le projet avec ajustements','Je mets le projet en pause','J\'abandonne le projet']" :key="opt" class="flex items-center gap-2.5 rounded-lg px-3 py-2.5 cursor-pointer hover:bg-gray-50 transition">
                    <input type="radio" :checked="d.decision===opt" @change="update('decision',opt)" class="text-primary-600" />
                    <span class="text-sm text-gray-700">{{ opt }}</span>
                </label>
            </div>
            <p v-if="hasErr('decision')" class="mt-2 text-xs text-red-600">{{ errMsg('decision') }}</p>
            <div class="mt-4 rounded-lg border-l-[3px] border-gold-500 bg-gold-50 px-4 py-3 text-xs text-gray-600 italic">
                Une étude de marché réussie n'est pas celle qui rassure, mais celle qui permet de décider avec lucidité.
            </div>
        </div>

    </div>
</template>
