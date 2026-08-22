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
        toggleItem(val) {
            const arr = this.d.checklist || [];
            const idx = arr.indexOf(val);
            this.$emit('update:modelValue', { ...this.d, checklist: idx >= 0 ? arr.filter((_, i) => i !== idx) : [...arr, val] });
        },
        allDone() { return (this.d.checklist || []).length === 7; },
    },
};
</script>
<template>
    <div class="space-y-5">
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">8</span>
                <p class="text-sm font-medium text-gray-800">Mon entreprise est officiellement créée</p>
            </div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3">
                <p class="text-xs font-medium text-primary-700">Checklist finale</p>
                <p class="mt-0.5 text-[10px] text-gray-400">Cochez chaque élément au fur et à mesure de sa réalisation.</p>
            </div>
            <div class="space-y-1" :class="hasErr('checklist') ? 'rounded-lg border border-red-300 bg-red-50/50 p-3' : ''">
                <label v-for="item in ['RCCM obtenu','NIF obtenu','Documents constitutifs récupérés','Formalités sociales effectuées','Autorisations sectorielles obtenues','Compte bancaire professionnel ouvert','Première facture / première vente prête']" :key="item"
                    class="flex items-center gap-3 rounded-lg px-3 py-3 cursor-pointer hover:bg-gray-50 transition">
                    <input type="checkbox" :checked="(d.checklist||[]).includes(item)" @change="toggleItem(item)" class="h-5 w-5 rounded text-primary-600" />
                    <span class="text-sm" :class="(d.checklist||[]).includes(item) ? 'text-gray-900 font-medium' : 'text-gray-600'">{{ item }}</span>
                    <svg v-if="(d.checklist||[]).includes(item)" class="h-4 w-4 ml-auto text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </label>
            </div>
            <p v-if="hasErr('checklist')" class="mt-2 text-xs text-red-600">{{ errMsg('checklist') }}</p>
        </div>

        <div v-if="allDone()" class="rounded-xl border border-green-100 bg-green-50 px-5 py-4 text-center">
            <svg class="mx-auto h-10 w-10 text-green-600 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm font-medium text-green-700">Félicitations ! Vous êtes prêt(e) à démarrer votre activité.</p>
        </div>
    </div>
</template>
