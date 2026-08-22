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
        update(k, v) { this.$emit('update:modelValue', { ...this.d, [k]: v }); },
        toggleChecklist(val) {
            const arr = this.d.checklist || [];
            const idx = arr.indexOf(val);
            this.update('checklist', idx >= 0 ? arr.filter((_, i) => i !== idx) : [...arr, val]);
        },
    },
};
</script>
<template>
    <div class="space-y-5">
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">1</span><p class="text-sm font-medium text-gray-800">Pré-requis avant dépôt</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Vérification des conditions</p><p class="mt-0.5 text-[10px] text-gray-400">Cochez chaque élément une fois qu'il est prêt.</p></div>
            <div class="space-y-1">
                <label v-for="item in ['Projet validé après le Business Lab','Forme juridique choisie','Nom de l\'entreprise choisi','Associés et répartition des parts définis','Siège social identifié','Capital et apports déterminés','Dirigeant(s) identifié(s)','Activités définies','Autorisations identifiées','Documents personnels réunis']" :key="item"
                    class="flex items-center gap-2.5 rounded-lg px-3 py-2.5 cursor-pointer hover:bg-gray-50 transition">
                    <input type="checkbox" :checked="(d.checklist||[]).includes(item)" @change="toggleChecklist(item)" class="rounded text-primary-600" />
                    <span class="text-sm text-gray-700">{{ item }}</span>
                </label>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">2</span><p class="text-sm font-medium text-gray-800">Pays de création</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Choisir le pays</p><p class="mt-0.5 text-[10px] text-gray-400">Sélectionnez le pays où vous souhaitez immatriculer votre entreprise.</p></div>
            <div class="flex flex-wrap gap-2" :class="hasErr('creation_country') ? 'rounded-lg border border-red-300 bg-red-50/50 p-2' : ''">
                <button v-for="c in [{code:'🇨🇫',name:'RCA'},{code:'🇨🇲',name:'Cameroun'},{code:'🇨🇮',name:'Côte d\'Ivoire'},{code:'🇸🇳',name:'Sénégal'}]" :key="c.name" type="button"
                    class="flex items-center gap-2 rounded-lg border px-4 py-2.5 text-sm transition"
                    :class="d.creation_country===c.name?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-600 hover:border-primary-300'"
                    @click="update('creation_country',c.name)">
                    <span class="text-lg">{{ c.code }}</span>{{ c.name }}
                </button>
            </div>
            <p v-if="hasErr('creation_country')" class="mt-2 text-xs text-red-600">{{ errMsg('creation_country') }}</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">3</span><p class="text-sm font-medium text-gray-800">Type de parcours</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Parcours de formalisation</p><p class="mt-0.5 text-[10px] text-gray-400">Choisissez selon que vous créez seul ou en société.</p></div>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2" :class="hasErr('track') ? 'rounded-lg border border-red-300 bg-red-50/50 p-2' : ''">
                <button type="button" class="rounded-xl border-2 p-5 text-center transition" :class="d.track==='individual'?'border-primary-600 bg-primary-50':'border-gray-200 hover:border-primary-300'" @click="update('track','individual')">
                    <svg class="mx-auto h-8 w-8 mb-2" :class="d.track==='individual'?'text-primary-700':'text-gray-400'" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
                    <p class="text-sm font-medium" :class="d.track==='individual'?'text-primary-700':'text-gray-700'">Entrepreneur individuel</p>
                </button>
                <button type="button" class="rounded-xl border-2 p-5 text-center transition" :class="d.track==='company'?'border-primary-600 bg-primary-50':'border-gray-200 hover:border-primary-300'" @click="update('track','company')">
                    <svg class="mx-auto h-8 w-8 mb-2" :class="d.track==='company'?'text-primary-700':'text-gray-400'" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M4 21V7l8-4 8 4v14M4 21h16M9 21v-4h6v4M9 11h.01M9 15h.01M15 11h.01M15 15h.01"/></svg>
                    <p class="text-sm font-medium" :class="d.track==='company'?'text-primary-700':'text-gray-700'">Société</p>
                </button>
            </div>
            <p v-if="hasErr('track')" class="mt-2 text-xs text-red-600">{{ errMsg('track') }}</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">4</span><p class="text-sm font-medium text-gray-800">Notes personnelles</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Observations</p><p class="mt-0.5 text-[10px] text-gray-400">Notez vos remarques sur les démarches en cours ou à venir.</p></div>
            <textarea :value="d.notes||''" @input="update('notes',$event.target.value)" rows="4" class="w-full rounded-md border-gray-200 text-sm" placeholder="Vos observations…"></textarea>
        </div>
    </div>
</template>
