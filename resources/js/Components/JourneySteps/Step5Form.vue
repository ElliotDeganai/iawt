<script>
import { AFRICAN_COUNTRIES, getCities } from '@/Data/africanCountries';

const FORMS = [
    { key: 'entreprenant', label: 'Entreprenant', desc: 'Statut individuel à régime simplifié, réservé aux petites activités sous certains seuils. Responsabilité illimitée, mais formalités et fiscalité allégées.' },
    { key: 'sarlu', label: 'SARLU', desc: 'Société à associé unique, dotée d\'une personnalité morale distincte de vous. Responsabilité limitée au montant de vos apports.' },
    { key: 'sarl', label: 'SARL', desc: 'Société pluripersonnelle (2 associés minimum), la forme la plus courante pour les PME. Responsabilité de chaque associé limitée à ses apports.' },
    { key: 'sasu', label: 'SASU', desc: 'Société à associé unique, à la gouvernance très libre, souvent choisie en vue d\'investisseurs. Responsabilité limitée à vos apports.' },
    { key: 'sas', label: 'SAS', desc: 'Société pluripersonnelle offrant une grande liberté d\'organisation entre associés. Responsabilité de chaque associé limitée à ses apports.' },
    { key: 'sa', label: 'SA', desc: 'Société pour projets de grande taille, fonctionnement plus formel (capital minimum, conseil d\'administration). Responsabilité limitée.' },
    { key: 'gie', label: 'GIE', desc: 'Groupement entre plusieurs entreprises pour mettre des moyens en commun. Responsabilité illimitée et solidaire des membres.' },
    { key: 'scoops', label: 'SCOOPS', desc: 'Coopérative à gouvernance simplifiée, pour un petit collectif de membres ou producteurs. Responsabilité limitée aux parts sociales.' },
    { key: 'coopca', label: 'COOP-CA', desc: 'Coopérative plus structurée, avec conseil d\'administration, pour un collectif important. Responsabilité limitée aux parts sociales.' },
];

const VERIF_ITEMS = [
    'La forme permet le nombre de fondateurs prévu',
    'La responsabilité correspond au niveau de risque du projet',
    'Le capital et les apports sont compatibles avec les ressources disponibles',
    'La forme permet les financements recherchés',
    'La gouvernance correspond aux relations entre associés ou membres',
    'La forme est compatible avec l\'entrée future d\'investisseurs',
    'Les obligations comptables et administratives sont supportables',
    'Les règles fiscales et sociales du pays ont été examinées',
    'Les licences, agréments ou autorisations nécessaires sont identifiés',
    'Les conséquences concrètes de ce choix sont comprises',
];

const CHECKLIST_ITEMS = [
    'Fiche profil complétée',
    'Questionnaire général complété',
    'Au moins 2 formes juridiques comparées',
    'Capital et apports analysés',
    'Responsabilité analysée',
    'Fiscalité et charges sociales identifiées',
    'Besoin de financement analysé',
    'Gouvernance et associés analysés',
    'Autorisations et agréments identifiés',
    'Forme recommandée bien comprise',
    'Réserves et points à vérifier notés',
    'Validation professionnelle prévue avant l\'immatriculation',
];

export default {
    props: {
        modelValue: { type: Object, default: () => ({}) },
        errors: { type: Object, default: () => ({}) },
    },
    data() {
        return {
            africanCountries: AFRICAN_COUNTRIES,
            availableCities: this.modelValue?.country ? getCities(this.modelValue.country) : [],
            customCity: !!(this.modelValue?.city && this.modelValue?.country && !getCities(this.modelValue.country || '').includes(this.modelValue.city)),
        };
    },
    watch: {
        'modelValue.country'(val) {
            this.availableCities = val ? getCities(val) : [];
            if (val && this.d.city && !this.availableCities.includes(this.d.city)) this.customCity = true;
        },
        recommendation: {
            handler(recs) {
                if (recs.length >= 1) {
                    this.$emit('update:modelValue', {
                        ...this.d,
                        legal_form: recs[0].label,
                        legal_form_2: recs.length >= 2 ? recs[1].label : '',
                    });
                }
            },
            deep: true,
            immediate: true,
        },
    },
    computed: {
        d() { return this.modelValue; },
        recommendation() {
            const scores = {};
            FORMS.forEach(f => { scores[f.key] = 0; });

            const fondateurs = this.d.Q1 || '';
            const investissement = this.d.Q4 || this.d.investment || '';
            const objectif = this.d.objective || '';
            const Q2 = this.d.Q2 || '';
            const Q3 = this.d.Q3 || '';
            const Q5 = this.d.Q5 || '';
            const Q8 = this.d.Q8 || '';
            const Q9 = this.d.Q9 || '';
            const Q10 = this.d.Q10 || '';
            const Q11 = this.d.Q11 || '';
            const Q12 = this.d.Q12 || '';
            const Q13 = this.d.Q13 || '';
            const Q18 = this.d.Q18 || '';
            const Q19 = this.d.Q19 || '';

            // fondateurs = 1 ET Q2 = Non → Entreprenant +5
            if (fondateurs === '1' && Q2 === 'Non') scores.entreprenant += 5;
            // fondateurs = 1 ET Q2 = Oui → SARLU +4, SASU +4
            if (fondateurs === '1' && Q2 === 'Oui') { scores.sarlu += 4; scores.sasu += 4; }
            // fondateurs ≠ 1 → SARL +4, SAS +4
            if (fondateurs && fondateurs !== '1') { scores.sarl += 4; scores.sas += 4; }
            // Q5 Oui/Peut-être ET Q8 Oui → SAS +3, SASU +3
            if (['Oui', 'Peut-être'].includes(Q5) && Q8 === 'Oui') { scores.sas += 3; scores.sasu += 3; }
            // Q5 Oui/Peut-être → SA +1
            if (['Oui', 'Peut-être'].includes(Q5)) scores.sa += 1;
            // investissement Important/Très important → SA +3, SAS +2, SARL +1
            if (['Important', 'Très important'].includes(investissement)) { scores.sa += 3; scores.sas += 2; scores.sarl += 1; }
            // Q3 Priorité forte → Entreprenant -2, SARLU +1, SASU +1, SARL +1, SAS +1
            if (Q3 === 'Priorité forte') { scores.entreprenant -= 2; scores.sarlu += 1; scores.sasu += 1; scores.sarl += 1; scores.sas += 1; }
            // Q18 Priorité forte → Entreprenant +2, SARLU +1
            if (Q18 === 'Priorité forte') { scores.entreprenant += 2; scores.sarlu += 1; }
            // Q19 Oui OU objectif Croissance forte/Groupe/holding → SAS +2, SA +1
            if (Q19 === 'Oui' || ['Croissance forte', 'Groupe / holding'].includes(objectif)) { scores.sas += 2; scores.sa += 1; }
            // Q9 Oui → SAS +1, SA +1, SARL +1
            if (Q9 === 'Oui') { scores.sas += 1; scores.sa += 1; scores.sarl += 1; }
            // Q11 Oui OU Q12 Oui → SCOOPS +5, COOP-CA +3
            if (Q11 === 'Oui' || Q12 === 'Oui') { scores.scoops += 5; scores.coopca += 3; }
            // Q13 Oui → GIE +5
            if (Q13 === 'Oui') scores.gie += 5;
            // Q10 Oui → SARL +1, SAS +1, SA +1
            if (Q10 === 'Oui') { scores.sarl += 1; scores.sas += 1; scores.sa += 1; }

            // Floor at 0
            Object.keys(scores).forEach(k => { if (scores[k] < 0) scores[k] = 0; });

            // Sort by score desc, then by FORMS order
            const ranked = FORMS.map(f => ({ ...f, score: scores[f.key] }))
                .filter(f => f.score > 0)
                .sort((a, b) => b.score - a.score);

            return ranked.slice(0, 2);
        },
        hasRecommendation() { return this.recommendation.length >= 2; },
        showRegulatedAlert() {
            const Q14 = this.d.Q14 || '';
            return Q14 === 'Oui' || Q14 === 'À vérifier';
        },
    },
    methods: {
        hasErr(k) { return !!this.errors[k]; },
        errMsg(k) { return this.errors[k] || ''; },
        errClass(k) { return this.hasErr(k) ? 'border-red-400 bg-red-50/30' : 'border-gray-200'; },
        update(k, v) { this.$emit('update:modelValue', { ...this.d, [k]: v }); },
        onCountryChange(val) {
            this.$emit('update:modelValue', { ...this.d, country: val, city: '' });
            this.customCity = false;
        },
        toggleVerif(item) {
            const arr = this.d.auto_verif || [];
            const idx = arr.indexOf(item);
            this.update('auto_verif', idx >= 0 ? arr.filter((_, i) => i !== idx) : [...arr, item]);
        },
        toggleChecklist(item) {
            const arr = this.d.checklist || [];
            const idx = arr.indexOf(item);
            this.update('checklist', idx >= 0 ? arr.filter((_, i) => i !== idx) : [...arr, item]);
        },
        verifItems() { return VERIF_ITEMS; },
        checklistItems() { return CHECKLIST_ITEMS; },
    },
};
</script>

<template>
    <div class="space-y-5">
        <div class="rounded-lg border-l-[3px] border-gold-500 bg-gold-50 px-4 py-3 text-xs text-gray-600 leading-relaxed">
            <p class="font-medium text-gray-700 mb-1">Étape 5 — Choisir sa forme juridique dans l'espace OHADA</p>
            <p class="italic">La forme juridique est un outil au service de votre projet, pas l'inverse : elle doit correspondre à votre réalité.</p>
        </div>

        <!-- ══ 5.1 Votre profil ══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">5.1</span><p class="text-sm font-medium text-gray-800">Votre profil</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Situation personnelle</p><p class="mt-0.5 text-[10px] text-gray-400">Informations de base pour le dossier.</p></div>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div><label class="block text-xs text-gray-600 mb-1">Pays d'implantation</label>
                    <select :value="d.country||''" @change="onCountryChange($event.target.value)" class="w-full rounded-md text-sm" :class="errClass('country')">
                        <option value="">Sélectionnez un pays</option>
                        <option v-for="co in africanCountries" :key="co.name" :value="co.name">{{ co.name }}</option>
                    </select>
                    <p v-if="hasErr('country')" class="mt-1 text-xs text-red-600">{{ errMsg('country') }}</p></div>
                <div><label class="block text-xs text-gray-600 mb-1">Ville / zone</label>
                    <select v-if="availableCities.length && !customCity" :value="d.city||''" @change="if($event.target.value==='__other__'){customCity=true;update('city','')}else{update('city',$event.target.value)}" class="w-full rounded-md border-gray-200 text-sm">
                        <option value="">Sélectionnez une ville</option>
                        <option v-for="city in availableCities" :key="city" :value="city">{{ city }}</option>
                        <option value="__other__">Autre ville…</option>
                    </select>
                    <div v-if="customCity || !availableCities.length">
                        <input :value="d.city||''" @input="update('city',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" placeholder="Saisissez votre ville" />
                        <button v-if="customCity && availableCities.length" type="button" class="mt-1 text-xs text-primary-600 hover:underline" @click="customCity=false;update('city','')">Revenir à la liste</button>
                    </div></div>
            </div>
            <div class="mt-3"><label class="block text-xs text-gray-600 mb-1">Statut actuel</label>
                <div class="flex flex-wrap gap-2" :class="hasErr('status') ? 'rounded-lg border border-red-300 bg-red-50/50 p-2' : ''">
                    <button v-for="s in ['Sans activité','Informel','Entreprise existante','Association / coopérative','Autre']" :key="s" type="button" class="rounded-full border px-3 py-1.5 text-xs" :class="d.status===s?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-500'" @click="update('status',s)">{{ s }}</button>
                </div>
                <p v-if="hasErr('status')" class="mt-1 text-xs text-red-600">{{ errMsg('status') }}</p>
            </div>
            <div class="mt-3"><label class="block text-xs text-gray-600 mb-1">Expérience dans cette activité</label>
                <div class="flex flex-wrap gap-2">
                    <button v-for="e in ['Débutant(e)','Moins de 2 ans','2 à 5 ans','Plus de 5 ans']" :key="e" type="button" class="rounded-full border px-3 py-1.5 text-xs" :class="d.experience===e?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-500'" @click="update('experience',e)">{{ e }}</button>
                </div>
            </div>
        </div>

        <!-- ══ 5.2 Votre projet ══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">5.2</span><p class="text-sm font-medium text-gray-800">Votre projet</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Caractéristiques du projet</p><p class="mt-0.5 text-[10px] text-gray-400">Ces informations déterminent la forme juridique la mieux adaptée.</p></div>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div><label class="block text-xs text-gray-600 mb-1">Nom du projet</label><input :value="d.project_name||''" @input="update('project_name',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-600 mb-1">Secteur principal</label>
                    <select :value="d.sector||''" @input="update('sector',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm"><option value="">Choisir</option><option v-for="s in ['Agriculture','Tourisme','Agro-transformation','Services','Commerce','Autre']" :key="s" :value="s">{{ s }}</option></select>
                </div>
                <div><label class="block text-xs text-gray-600 mb-1">Sous-secteur</label><input :value="d.sub_sector||''" @input="update('sub_sector',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-600 mb-1">Localisation de l'activité</label><input :value="d.activity_location||''" @input="update('activity_location',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-600 mb-1">Projet individuel ou collectif</label>
                    <select :value="d.project_type||''" @input="update('project_type',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm"><option value="">Choisir</option><option v-for="t in ['Individuel','Familial','Entre associés','Coopératif','Communautaire']" :key="t" :value="t">{{ t }}</option></select>
                </div>
                <div><label class="block text-xs text-gray-600 mb-1">Nombre de fondateurs</label><input :value="d.founders||''" @input="update('founders',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-600 mb-1">Emplois prévus — année 1</label><input :value="d.jobs_y1||''" @input="update('jobs_y1',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-600 mb-1">Emplois prévus — année 3</label><input :value="d.jobs_y3||''" @input="update('jobs_y3',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-600 mb-1">Investissement initial estimé (FCFA)</label><input :value="d.investment||''" @input="update('investment',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-600 mb-1">Apport personnel</label><input :value="d.personal_contribution||''" @input="update('personal_contribution',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-600 mb-1">Financement recherché</label><input :value="d.funding_needed||''" @input="update('funding_needed',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-600 mb-1">CA prévisionnel — année 1</label><input :value="d.revenue_y1||''" @input="update('revenue_y1',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-600 mb-1">CA prévisionnel — année 3</label><input :value="d.revenue_y3||''" @input="update('revenue_y3',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-600 mb-1">Besoin de financement bancaire</label>
                    <select :value="d.bank_need||''" @input="update('bank_need',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm"><option value="">Choisir</option><option v-for="b in ['Aucun','Faible','Moyen','Important']" :key="b" :value="b">{{ b }}</option></select>
                </div>
                <div><label class="block text-xs text-gray-600 mb-1">Investisseurs externes envisagés</label>
                    <select :value="d.ext_investors||''" @input="update('ext_investors',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm"><option value="">Choisir</option><option v-for="i in ['Non','Oui, locaux','Oui, étrangers','À étudier']" :key="i" :value="i">{{ i }}</option></select>
                </div>
                <div class="sm:col-span-2"><label class="block text-xs text-gray-600 mb-1">Objectif à 3–5 ans</label>
                    <div class="flex flex-wrap gap-2">
                        <button v-for="o in ['Rester petite structure','Devenir PME','Croissance forte','Groupe / holding','Transmission / cession']" :key="o" type="button" class="rounded-full border px-3 py-1.5 text-xs" :class="d.objective===o?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-500'" @click="update('objective',o)">{{ o }}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ 5.3 Questionnaire général ══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">5.3</span><p class="text-sm font-medium text-gray-800">Questionnaire général</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Structure, gouvernance et ambitions</p><p class="mt-0.5 text-[10px] text-gray-400">Cochez la réponse qui correspond le mieux. Votre recommandation sera calculée automatiquement.</p></div>

            <div class="space-y-4">
                <div v-for="q in [
                    { k:'Q1', l:'Q1 — Combien de personnes portent juridiquement le projet ?', opts:['1','2 à 5','6 à 15','Plus de 15'] },
                    { k:'Q2', l:'Q2 — Souhaitez-vous créer une personne morale ?', opts:['Oui','Non','Je ne sais pas encore'] },
                    { k:'Q3', l:'Q3 — Protéger votre patrimoine personnel est-il important ?', opts:['Priorité forte','Important','Peu important'] },
                    { k:'Q4', l:'Q4 — Le projet nécessite-t-il un investissement important ?', opts:['Faible','Moyen','Important','Très important'] },
                    { k:'Q5', l:'Q5 — Prévoyez-vous des investisseurs externes ?', opts:['Non','Peut-être','Oui'] },
                    { k:'Q6', l:'Q6 — Des investisseurs étrangers pourraient-ils entrer au capital ?', opts:['Non','Peut-être','Oui'] },
                    { k:'Q7', l:'Q7 — Prévoyez-vous de faire entrer de nouveaux associés (3-5 ans) ?', opts:['Non','Oui'] },
                    { k:'Q8', l:'Q8 — Grande liberté pour organiser les relations entre associés ?', opts:['Non','Oui'] },
                    { k:'Q9', l:'Q9 — Le projet doit-il pouvoir être facilement transmis ou cédé ?', opts:['Non','Oui'] },
                    { k:'Q10', l:'Q10 — Besoin de financements bancaires importants ?', opts:['Non','Oui'] },
                    { k:'Q11', l:'Q11 — Le projet repose-t-il sur plusieurs producteurs ou membres ?', opts:['Non','Oui'] },
                    { k:'Q12', l:'Q12 — Production, achat ou vente collective ?', opts:['Non','Oui'] },
                    { k:'Q13', l:'Q13 — Mutualisation de moyens entre entreprises ?', opts:['Non','Oui'] },
                    { k:'Q14', l:'Q14 — Activité réglementée ou soumise à agrément ?', opts:['Non','Oui','À vérifier'] },
                    { k:'Q15', l:'Q15 — Activité liée au foncier, environnement ou ressources naturelles ?', opts:['Non','Oui','À vérifier'] },
                    { k:'Q16', l:'Q16 — Le projet prévoit-il des salarié(e)s ?', opts:['Non','1 à 5','6 à 20','Plus de 20'] },
                    { k:'Q17', l:'Q17 — Le projet est-il principalement familial ?', opts:['Non','Oui'] },
                    { k:'Q18', l:'Q18 — Structure très simple à administrer au quotidien ?', opts:['Priorité forte','Important','Peu important'] },
                    { k:'Q19', l:'Q19 — Croissance rapide visée ?', opts:['Non','Oui'] },
                    { k:'Q20', l:'Q20 — Plusieurs activités ou filiales envisagées à terme ?', opts:['Non','Oui','Peut-être'] },
                ]" :key="q.k" class="rounded-lg border border-gray-100 px-4 py-3">
                    <p class="text-xs font-medium text-gray-700 mb-2">{{ q.l }}</p>
                    <div class="flex flex-wrap gap-2">
                        <button v-for="opt in q.opts" :key="opt" type="button" class="rounded-full border px-3 py-1.5 text-xs transition" :class="d[q.k]===opt ? 'border-primary-600 bg-primary-50 text-primary-700 font-medium' : 'border-gray-200 text-gray-500 hover:border-primary-200'" @click="update(q.k, opt)">{{ opt }}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ 5.6 Recommandation automatique ══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">5.6</span><p class="text-sm font-medium text-gray-800">Votre recommandation</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Formes juridiques suggérées</p><p class="mt-0.5 text-[10px] text-gray-400">Calculé automatiquement à partir de vos réponses. Ce classement vous aide à comparer, il ne remplace pas une décision juridique.</p></div>

            <div v-if="hasRecommendation" class="space-y-3" :class="hasErr('legal_form') ? 'rounded-lg border border-red-300 bg-red-50/50 p-3' : ''">
                <div v-for="(rec, i) in recommendation" :key="rec.key" class="rounded-xl border-2 px-5 py-4" :class="i === 0 ? 'border-primary-600 bg-primary-50' : 'border-gray-200 bg-gray-50'">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full text-[10px] font-bold text-white" :class="i === 0 ? 'bg-primary-600' : 'bg-gray-400'">{{ i + 1 }}</span>
                        <p class="text-sm font-bold" :class="i === 0 ? 'text-primary-800' : 'text-gray-700'">{{ rec.label }}</p>
                        <span class="ml-auto rounded-full px-2 py-0.5 text-[10px] font-medium" :class="i === 0 ? 'bg-primary-100 text-primary-700' : 'bg-gray-200 text-gray-600'">Score : {{ rec.score }}</span>
                    </div>
                    <p class="text-xs leading-relaxed text-gray-600">{{ rec.desc }}</p>
                </div>
            </div>
            <p v-if="hasErr('legal_form')" class="mt-2 text-xs text-red-600">{{ errMsg('legal_form') }}</p>
            <div v-if="!hasRecommendation" class="rounded-lg bg-amber-50 border border-amber-200 px-4 py-3 text-xs text-amber-700">
                <p class="font-medium">Pas assez de données pour une recommandation</p>
                <p class="mt-1">Complétez le questionnaire ci-dessus (sections 5.2 et 5.3) pour obtenir une recommandation automatique.</p>
            </div>

            <div v-if="showRegulatedAlert" class="mt-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3">
                <div class="flex items-start gap-2">
                    <svg class="h-4 w-4 shrink-0 mt-0.5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86l-8.58 14.88A1 1 0 002.58 20h18.84a1 1 0 00.86-1.26L13.71 3.86a1 1 0 00-1.72 0z"/></svg>
                    <p class="text-xs text-red-700">Votre activité est réglementée ou soumise à agrément. Vérifiez les autorisations et licences nécessaires avant l'immatriculation.</p>
                </div>
            </div>
        </div>

        <!-- ══ 5.7 Auto-vérification ══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">5.7</span><p class="text-sm font-medium text-gray-800">Auto-vérification</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Points de contrôle</p><p class="mt-0.5 text-[10px] text-gray-400">Cochez ce qui est déjà clair pour vous avant de passer à l'étape suivante.</p></div>
            <div class="space-y-1.5">
                <label v-for="item in verifItems()" :key="item" class="flex items-center gap-3 rounded-lg px-3 py-2 cursor-pointer hover:bg-gray-50 transition">
                    <input type="checkbox" :checked="(d.auto_verif||[]).includes(item)" @change="toggleVerif(item)" class="h-4 w-4 rounded text-primary-600" />
                    <span class="text-xs" :class="(d.auto_verif||[]).includes(item) ? 'text-gray-900 font-medium' : 'text-gray-500'">{{ item }}</span>
                </label>
            </div>
        </div>

        <!-- ══ 5.8 Checklist de sortie ══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">5.8</span><p class="text-sm font-medium text-gray-800">Checklist de sortie</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Récapitulatif final</p><p class="mt-0.5 text-[10px] text-gray-400">Vérifiez que tous les points sont couverts avant de passer à l'étape suivante.</p></div>
            <div class="space-y-1.5" :class="hasErr('checklist') ? 'rounded-lg border border-red-300 bg-red-50/50 p-3' : ''">
                <label v-for="item in checklistItems()" :key="item" class="flex items-center gap-3 rounded-lg px-3 py-2 cursor-pointer hover:bg-gray-50 transition">
                    <input type="checkbox" :checked="(d.checklist||[]).includes(item)" @change="toggleChecklist(item)" class="h-4 w-4 rounded text-primary-600" />
                    <span class="text-xs" :class="(d.checklist||[]).includes(item) ? 'text-gray-900 font-medium' : 'text-gray-500'">{{ item }}</span>
                </label>
            </div>
            <p v-if="hasErr('checklist')" class="mt-2 text-xs text-red-600">{{ errMsg('checklist') }}</p>
        </div>

        <div class="rounded-lg border-l-[3px] border-gold-500 bg-gold-50 px-4 py-3 text-xs text-gray-600 leading-relaxed italic">
            La forme juridique est un outil au service du projet, pas l'inverse.
        </div>
    </div>
</template>