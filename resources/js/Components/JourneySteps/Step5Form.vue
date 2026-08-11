<script>
export default {
    props: { modelValue: { type: Object, default: () => ({}) } },
    computed: { d() { return this.modelValue; } },
    methods: { update(k, v) { this.$emit('update:modelValue', { ...this.d, [k]: v }); } },
};
</script>
<template>
    <div class="space-y-5">
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">5.1 — Votre profil</p>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div><label class="block text-xs text-gray-500 mb-1">Pays d'implantation</label><input :value="d.country||''" @input="update('country',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-500 mb-1">Ville / zone</label><input :value="d.city||''" @input="update('city',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
            </div>
            <div class="mt-3"><label class="block text-xs text-gray-500 mb-1">Statut actuel</label>
                <div class="flex flex-wrap gap-2">
                    <button v-for="s in ['Sans activité','Informel','Entreprise existante','Association / coopérative','Autre']" :key="s" type="button"
                        class="rounded-full border px-3 py-1.5 text-xs" :class="d.status===s?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-500'"
                        @click="update('status',s)">{{ s }}</button>
                </div>
            </div>
            <div class="mt-3"><label class="block text-xs text-gray-500 mb-1">Expérience dans cette activité</label>
                <div class="flex flex-wrap gap-2">
                    <button v-for="e in ['Débutant(e)','Moins de 2 ans','2 à 5 ans','Plus de 5 ans']" :key="e" type="button"
                        class="rounded-full border px-3 py-1.5 text-xs" :class="d.experience===e?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-500'"
                        @click="update('experience',e)">{{ e }}</button>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">5.2 — Votre projet</p>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div><label class="block text-xs text-gray-500 mb-1">Nom du projet</label><input :value="d.project_name||''" @input="update('project_name',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-500 mb-1">Secteur principal</label>
                    <select :value="d.sector||''" @input="update('sector',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm">
                        <option value="">Choisir</option>
                        <option v-for="s in ['Agriculture','Tourisme','Agro-transformation','Services','Commerce','Autre']" :key="s" :value="s">{{ s }}</option>
                    </select>
                </div>
                <div><label class="block text-xs text-gray-500 mb-1">Projet individuel ou collectif</label>
                    <select :value="d.project_type||''" @input="update('project_type',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm">
                        <option value="">Choisir</option>
                        <option v-for="t in ['Individuel','Familial','Entre associés','Coopératif','Communautaire']" :key="t" :value="t">{{ t }}</option>
                    </select>
                </div>
                <div><label class="block text-xs text-gray-500 mb-1">Nombre de fondateurs</label><input type="number" :value="d.founders||''" @input="update('founders',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-500 mb-1">Investissement initial estimé (FCFA)</label><input :value="d.investment||''" @input="update('investment',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-500 mb-1">Besoin de financement bancaire</label>
                    <select :value="d.bank_need||''" @input="update('bank_need',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm">
                        <option v-for="b in ['Aucun','Faible','Moyen','Important']" :key="b" :value="b">{{ b }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Forme juridique recommandée</p>
            <p class="text-xs text-gray-400 mb-3">Sélectionnez la forme qui correspond à votre projet.</p>
            <div class="space-y-2">
                <label v-for="f in ['Commerçant personne physique','Entreprenant','SARL unipersonnelle','SARL','SASU','SAS','SA','SNC','GIE','Coopérative (SCOOPS)']" :key="f" class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" :checked="d.legal_form===f" @change="update('legal_form',f)" class="text-primary-600" />
                    <span class="text-sm text-gray-700">{{ f }}</span>
                </label>
            </div>
        </div>
    </div>
</template>
