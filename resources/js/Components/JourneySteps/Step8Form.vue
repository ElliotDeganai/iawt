<script>
export default {
    props: { modelValue: { type: Object, default: () => ({}) } },
    computed: { d() { return this.modelValue; } },
    methods: {
        toggleItem(val) {
            const arr = this.d.checklist || [];
            const idx = arr.indexOf(val);
            this.$emit('update:modelValue', { ...this.d, checklist: idx >= 0 ? arr.filter((_, i) => i !== idx) : [...arr, val] });
        },
        allDone() {
            return (this.d.checklist || []).length === 7;
        },
    },
};
</script>
<template>
    <div class="space-y-5">
        <div class="text-center py-4">
            <p class="text-3xl mb-2">🎉</p>
            <h2 class="font-serif text-xl text-gray-900">Mon entreprise est officiellement créée</h2>
            <p class="text-sm text-gray-500 mt-1">Cochez chaque élément au fur et à mesure de sa réalisation.</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="space-y-1">
                <label v-for="item in ['RCCM obtenu','NIF obtenu','Documents constitutifs récupérés','Formalités sociales effectuées','Autorisations sectorielles obtenues','Compte bancaire professionnel ouvert','Première facture / première vente prête']" :key="item"
                    class="flex items-center gap-3 rounded-lg px-3 py-3 cursor-pointer hover:bg-gray-50 transition">
                    <input type="checkbox" :checked="(d.checklist||[]).includes(item)" @change="toggleItem(item)" class="h-5 w-5 rounded text-primary-600" />
                    <span class="text-sm" :class="(d.checklist||[]).includes(item) ? 'text-gray-900 font-medium' : 'text-gray-600'">{{ item }}</span>
                    <svg v-if="(d.checklist||[]).includes(item)" class="h-4 w-4 ml-auto text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </label>
            </div>
        </div>

        <div v-if="allDone()" class="rounded-xl border border-green-100 bg-green-50 px-5 py-4 text-center">
            <p class="text-sm font-medium text-green-700">Félicitations ! Vous êtes prêt(e) à démarrer votre activité. 🚀</p>
        </div>
    </div>
</template>
