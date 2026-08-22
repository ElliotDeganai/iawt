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
        errClass(k) { return this.hasErr(k) ? 'border-red-400 bg-red-50/30' : 'border-gray-200'; }, update(k, v) { this.$emit('update:modelValue', { ...this.d, [k]: v }); } },
};
</script>
<template>
    <div class="space-y-5">
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">1</span><p class="text-sm font-medium text-gray-800">Votre profil</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Situation personnelle</p><p class="mt-0.5 text-[10px] text-gray-400">Décrivez votre situation actuelle pour orienter le choix juridique.</p></div>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div><label class="block text-xs text-gray-600 mb-1">Pays d'implantation</label><input :value="d.country||''" @input="update('country',$event.target.value)" class="w-full rounded-md text-sm" :class="errClass('country')" /><p v-if="hasErr('country')" class="mt-1 text-xs text-red-600">{{ errMsg('country') }}</p></div>
                <div><label class="block text-xs text-gray-600 mb-1">Ville / zone</label><input :value="d.city||''" @input="update('city',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
            </div>
            <div class="mt-3"><label class="block text-xs text-gray-600 mb-1">Statut actuel</label>
                <div class="flex flex-wrap gap-2" :class="hasErr('status') ? 'rounded-lg border border-red-300 bg-red-50/50 p-2' : ''">
                    <button v-for="s in ['Sans activité','Informel','Entreprise existante','Association / coopérative','Autre']" :key="s" type="button" class="rounded-full border px-3 py-1.5 text-xs" :class="d.status===s?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-500'" @click="update('status',s)">{{ s }}</button>
                </div>
                <p v-if="hasErr('status')" class="mt-1 text-xs text-red-600">{{ errMsg('status') }}</p>
            </div>
            <div class="mt-3"><label class="block text-xs text-gray-600 mb-1">Expérience dans cette activité</label>
                <div class="flex flex-wrap gap-2">
                    <button v-for="e in ['Débutant','Moins de 2 ans','2 à 5 ans','Plus de 5 ans']" :key="e" type="button" class="rounded-full border px-3 py-1.5 text-xs" :class="d.experience===e?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-500'" @click="update('experience',e)">{{ e }}</button>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">2</span><p class="text-sm font-medium text-gray-800">Votre projet</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Caractéristiques du projet</p><p class="mt-0.5 text-[10px] text-gray-400">Ces informations déterminent la forme juridique la mieux adaptée.</p></div>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div><label class="block text-xs text-gray-600 mb-1">Nom du projet</label><input :value="d.project_name||''" @input="update('project_name',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-600 mb-1">Secteur principal</label>
                    <select :value="d.sector||''" @input="update('sector',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm">
                        <option value="">Choisir</option>
                        <option v-for="s in ['Agriculture','Tourisme','Agro-transformation','Services','Commerce','Autre']" :key="s" :value="s">{{ s }}</option>
                    </select>
                </div>
                <div><label class="block text-xs text-gray-600 mb-1">Projet individuel ou collectif</label>
                    <select :value="d.project_type||''" @input="update('project_type',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm">
                        <option value="">Choisir</option>
                        <option v-for="t in ['Individuel','Familial','Entre associés','Coopératif','Communautaire']" :key="t" :value="t">{{ t }}</option>
                    </select>
                </div>
                <div><label class="block text-xs text-gray-600 mb-1">Nombre de fondateurs</label><input type="number" :value="d.founders||''" @input="update('founders',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-600 mb-1">Investissement initial estimé (FCFA)</label><input :value="d.investment||''" @input="update('investment',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-600 mb-1">Besoin de financement bancaire</label>
                    <select :value="d.bank_need||''" @input="update('bank_need',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm">
                        <option v-for="b in ['Aucun','Faible','Moyen','Important']" :key="b" :value="b">{{ b }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">3</span><p class="text-sm font-medium text-gray-800">Choix de la forme juridique</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Forme juridique OHADA</p><p class="mt-0.5 text-[10px] text-gray-400">Sélectionnez la forme qui correspond le mieux à votre profil et votre projet.</p></div>
            <div class="space-y-2" :class="hasErr('legal_form') ? 'rounded-lg border border-red-300 bg-red-50/50 p-3' : ''">
                <label v-for="f in ['Commerçant personne physique','Entreprenant','SARL unipersonnelle','SARL','SASU','SAS','SA','SNC','GIE','Coopérative (SCOOPS)']" :key="f" class="flex items-center gap-2.5 rounded-lg px-3 py-2.5 cursor-pointer hover:bg-gray-50 transition">
                    <input type="radio" :checked="d.legal_form===f" @change="update('legal_form',f)" class="text-primary-600" />
                    <span class="text-sm text-gray-700">{{ f }}</span>
                </label>
            </div>
            <p v-if="hasErr('legal_form')" class="mt-2 text-xs text-red-600">{{ errMsg('legal_form') }}</p>
        </div>
    </div>
</template>
