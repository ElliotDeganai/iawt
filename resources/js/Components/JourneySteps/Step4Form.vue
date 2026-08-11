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
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">4.1 — Plan de financement initial</p>
            <p class="mb-3 text-xs text-gray-400">Les deux colonnes doivent s'équilibrer. Montants en FCFA.</p>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <p class="text-xs font-medium text-primary-700 mb-2">Besoins durables</p>
                    <div class="space-y-2">
                        <div v-for="b in ['Frais d\'immatriculation','Frais de conseil','Matériel et équipement','Aménagement du local','Mobilier','Véhicule','Logiciels et outils','Formation','Dépôt de marque','BFR de démarrage','Trésorerie de sécurité']" :key="b">
                            <label class="block text-[10px] text-gray-500 mb-0.5">{{ b }}</label>
                            <input :value="d['need_'+b]||''" @input="update('need_'+b,$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" placeholder="0" />
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-medium text-green-700 mb-2">Ressources durables</p>
                    <div class="space-y-2">
                        <div v-for="r in ['Apport personnel','Apport en compte courant','Subvention 1','Subvention 2','Prêt d\'honneur','Emprunt bancaire','Crowdfunding / dons','Autres financements']" :key="r">
                            <label class="block text-[10px] text-gray-500 mb-0.5">{{ r }}</label>
                            <input :value="d['res_'+r]||''" @input="update('res_'+r,$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" placeholder="0" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">4.2 — Compte de résultat prévisionnel</p>
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[500px]">
                    <thead><tr class="border-b border-gray-100">
                        <th class="py-2 text-left text-xs text-gray-500 font-medium">Rubrique</th>
                        <th class="py-2 text-left text-xs text-gray-500 font-medium">Année 1</th>
                        <th class="py-2 text-left text-xs text-gray-500 font-medium">Année 2</th>
                        <th class="py-2 text-left text-xs text-gray-500 font-medium">Année 3</th>
                    </tr></thead>
                    <tbody>
                        <tr v-for="row in ['CA ventes produits','CA services','Subventions exploitation','Achats matières premières','Charges externes','Salaires et charges','Impôts et taxes','Dotations amortissements']" :key="row" class="border-b border-gray-50">
                            <td class="py-1.5 text-xs text-gray-600">{{ row }}</td>
                            <td class="py-1.5"><input :value="d['cr_'+row+'_y1']||''" @input="update('cr_'+row+'_y1',$event.target.value)" class="w-full rounded border-gray-200 text-xs" placeholder="0" /></td>
                            <td class="py-1.5"><input :value="d['cr_'+row+'_y2']||''" @input="update('cr_'+row+'_y2',$event.target.value)" class="w-full rounded border-gray-200 text-xs" placeholder="0" /></td>
                            <td class="py-1.5"><input :value="d['cr_'+row+'_y3']||''" @input="update('cr_'+row+'_y3',$event.target.value)" class="w-full rounded border-gray-200 text-xs" placeholder="0" /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
