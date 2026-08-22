<script>
import * as XLSX from 'xlsx-js-style';

const NEEDS = ["Frais d'immatriculation et de création","Frais de conseil juridique ou comptable","Achat de matériel et équipement","Aménagement et travaux du local","Mobilier","Achat ou location initiale d'un véhicule","Logiciels et outils numériques","Formation(s) du porteur de projet","Dépôt de marque / propriété intellectuelle","Besoin en fonds de roulement de démarrage","Trésorerie de départ (matelas de sécurité)"];
const RESOURCES = ["Apport personnel","Apport en compte courant d'associé(e)","Subvention — organisme 1","Subvention — organisme 2","Prêt d'honneur","Emprunt bancaire","Crowdfunding / dons","Autres financements"];
const PRODUCTS = ["CA — ventes de produits","CA — prestations de services","Production stockée / immobilisée","Subventions d'exploitation"];
const CHARGES = ["Achats de marchandises / matières premières","Variation de stock","Loyer et charges locatives","Assurances","Entretien et réparations","Honoraires (comptable, juridique, conseil)","Transport et déplacements","Communication et marketing","Sous-traitance","Frais postaux et téléphone","Fournitures de bureau et consommables","Frais bancaires","Impôts et taxes","Salaires et charges sociales","Dotations aux amortissements"];
const MONTHS = ['Janv.','Févr.','Mars','Avr.','Mai','Juin','Juil.','Août','Sept.','Oct.','Nov.','Déc.'];
const INFLOWS = ["Apport personnel / associé(e)s","Emprunts bancaires reçus","Subventions perçues","Ventes de produits encaissées","Ventes de prestations encaissées","Autres recettes"];
const OUTFLOWS = ["Achats marchandises / matières premières","Fournitures et consommables","Loyer et charges locatives","Assurances","Entretien et réparations","Honoraires","Salaires et charges sociales","Transport et déplacements","Communication / marketing","Sous-traitance","Frais postaux et téléphone","Frais bancaires","Remboursement d'emprunt","Impôts et taxes","Achat de matériel / investissements","Autres charges"];

export default {
    props: {
        modelValue: { type: Object, default: () => ({}) },
        errors: { type: Object, default: () => ({}) },
    },
    computed: {
        d() { return this.modelValue; },
        totalNeeds() { return NEEDS.reduce((s, k) => s + this.num('n_' + k), 0); },
        totalResources() { return RESOURCES.reduce((s, k) => s + this.num('r_' + k), 0); },
        gap() { return this.totalResources - this.totalNeeds; },
        totalProducts() { return [1,2,3].map(y => PRODUCTS.reduce((s, k) => s + this.num('p_' + k + '_y' + y), 0)); },
        totalCharges() { return [1,2,3].map(y => CHARGES.reduce((s, k) => s + this.num('c_' + k + '_y' + y), 0)); },
        resultExploit() { return [0,1,2].map(i => this.totalProducts[i] - this.totalCharges[i]); },
        finCharges() { return [1,2,3].map(y => this.num('fin_charges_y' + y)); },
        finProducts() { return [1,2,3].map(y => this.num('fin_products_y' + y)); },
        resultCourant() { return [0,1,2].map(i => this.resultExploit[i] - this.finCharges[i] + this.finProducts[i]); },
        taxe() { return [1,2,3].map(y => this.num('tax_y' + y)); },
        resultNet() { return [0,1,2].map(i => this.resultCourant[i] - this.taxe[i]); },
    },
    methods: {
        hasErr(k) { return !!this.errors[k]; },
        errMsg(k) { return this.errors[k] || ''; },
        errClass(k) { return this.hasErr(k) ? 'border-red-400 bg-red-50/30' : 'border-gray-200'; },
        update(k, v) { this.$emit('update:modelValue', { ...this.d, [k]: v }); },
        num(k) { return parseInt(String(this.d[k]).replace(/[^\d-]/g, '')) || 0; },
        fmtApos(n) { return String(Math.abs(n)).replace(/\B(?=(\d{3})+(?!\d))/g, "'"); },
        fmt(n) { return (n < 0 ? '-' : '') + this.fmtApos(n); },
        fmtVal(k) {
            const raw = this.d[k];
            if (!raw && raw !== 0) return '';
            const n = parseInt(String(raw).replace(/[^\d]/g, ''));
            return isNaN(n) || n === 0 ? '' : this.fmtApos(n);
        },
        onNumInput(k, e) {
            const digits = e.target.value.replace(/[^\d]/g, '');
            const n = digits ? parseInt(digits) : '';
            this.update(k, n);
            this.$nextTick(() => { e.target.value = this.fmtVal(k); });
        },
exportExcel() {
            const data = { ...this.d };
            const v = (k) => parseInt(String(data[k]).replace(/[^\d-]/g, '')) || 0;
            const fmtX = (n) => { const s = String(Math.abs(n)).replace(/\B(?=(\d{3})+(?!\d))/g, "'"); return n < 0 ? '-' + s : s; };
            const wb = XLSX.utils.book_new();

            const headerStyle = { font: { bold: true, color: { rgb: 'FFFFFF' }, sz: 11 }, fill: { fgColor: { rgb: '7A1F2B' } }, alignment: { horizontal: 'center' } };
            const subHeaderGreen = { font: { bold: true, sz: 10 }, fill: { fgColor: { rgb: 'E8F5E9' } } };
            const subHeaderRed = { font: { bold: true, sz: 10 }, fill: { fgColor: { rgb: 'FFEBEE' } } };
            const totalStyle = { font: { bold: true, sz: 10 }, fill: { fgColor: { rgb: 'FFF3E0' } }, border: { top: { style: 'thin' } } };
            const gapOk = { font: { bold: true, color: { rgb: '1B5E20' }, sz: 11 }, fill: { fgColor: { rgb: 'C8E6C9' } } };
            const gapBad = { font: { bold: true, color: { rgb: 'B71C1C' }, sz: 11 }, fill: { fgColor: { rgb: 'FFCDD2' } } };
            const labelStyle = { font: { sz: 9, color: { rgb: '555555' } } };
            const numStyle = { font: { sz: 9 }, alignment: { horizontal: 'right' } };
            const numCell = (val, style) => ({ v: fmtX(val), t: 's', s: style || numStyle });
            const resultNet = { font: { bold: true, sz: 12 }, fill: { fgColor: { rgb: 'FBF0DC' } }, border: { top: { style: 'medium' } } };

            // === Onglet 1 — Plan de financement ===
            const NEEDS = this.NEEDS;
            const RESOURCES = this.RESOURCES;
            const s1 = [];
            s1.push([{ v: 'PLAN DE FINANCEMENT INITIAL', s: headerStyle }, { v: '', s: headerStyle }, { v: '', s: headerStyle }, { v: '', s: headerStyle }]);
            s1.push([]);
            s1.push([{ v: 'Besoins durables', s: subHeaderRed }, { v: 'Montant (FCFA)', s: subHeaderRed }, { v: 'Ressources durables', s: subHeaderGreen }, { v: 'Montant (FCFA)', s: subHeaderGreen }]);
            const mx = Math.max(NEEDS.length, RESOURCES.length);
            for (let i = 0; i < mx; i++) {
                s1.push([
                    { v: NEEDS[i] || '', s: labelStyle },
                    NEEDS[i] ? { v: v('n_' + NEEDS[i]), s: numStyle } : '',
                    { v: RESOURCES[i] || '', s: labelStyle },
                    RESOURCES[i] ? { v: v('r_' + RESOURCES[i]), s: numStyle } : '',
                ]);
            }
            s1.push([{ v: 'Total des besoins', s: totalStyle }, { v: this.totalNeeds, s: { ...totalStyle } }, { v: 'Total des ressources', s: totalStyle }, { v: this.totalResources, s: { ...totalStyle } }]);
            s1.push([]);
            const gapStyle = this.gap >= 0 ? gapOk : gapBad;
            s1.push([{ v: 'Écart (Ressources − Besoins)', s: gapStyle }, { v: this.gap, s: { ...gapStyle } }]);
            const ws1 = XLSX.utils.aoa_to_sheet(s1);
            ws1['!cols'] = [{ wch: 40 }, { wch: 16 }, { wch: 40 }, { wch: 16 }];
            XLSX.utils.book_append_sheet(wb, ws1, 'Plan de financement');

            // === Onglet 2 — Compte de résultat ===
            const PRODUCTS = this.PRODUCTS;
            const CHARGES = this.CHARGES;
            const s2 = [];
            s2.push([{ v: 'COMPTE DE RÉSULTAT PRÉVISIONNEL', s: headerStyle }, { v: '', s: headerStyle }, { v: '', s: headerStyle }, { v: '', s: headerStyle }]);
            s2.push([]);
            s2.push([{ v: 'Rubrique', s: { font: { bold: true } } }, { v: 'Année 1', s: { font: { bold: true }, alignment: { horizontal: 'center' } } }, { v: 'Année 2', s: { font: { bold: true }, alignment: { horizontal: 'center' } } }, { v: 'Année 3', s: { font: { bold: true }, alignment: { horizontal: 'center' } } }]);
            s2.push([]);
            s2.push([{ v: "PRODUITS D'EXPLOITATION", s: subHeaderGreen }]);
            PRODUCTS.forEach(p => s2.push([{ v: p, s: labelStyle }, numCell(v('p_'+p+'_y1')), numCell(v('p_'+p+'_y2')), numCell(v('p_'+p+'_y3'))]));
            s2.push([{ v: 'Total des produits (A)', s: totalStyle }, numCell(this.totalProducts[0], totalStyle), numCell(this.totalProducts[1], totalStyle), numCell(this.totalProducts[2], totalStyle)]);
            s2.push([]);
            s2.push([{ v: "CHARGES D'EXPLOITATION", s: subHeaderRed }]);
            CHARGES.forEach(c => s2.push([{ v: c, s: labelStyle }, numCell(v('c_'+c+'_y1')), numCell(v('c_'+c+'_y2')), numCell(v('c_'+c+'_y3'))]));
            s2.push([{ v: 'Total des charges (B)', s: totalStyle }, numCell(this.totalCharges[0], totalStyle), numCell(this.totalCharges[1], totalStyle), numCell(this.totalCharges[2], totalStyle)]);
            s2.push([]);
            const reStyle = (val) => ({ font: { bold: true }, fill: { fgColor: { rgb: val >= 0 ? 'C8E6C9' : 'FFCDD2' } } });
            s2.push([{ v: "Résultat d'exploitation (A − B)", s: { font: { bold: true } } }, numCell(this.resultExploit[0], reStyle(this.resultExploit[0])), numCell(this.resultExploit[1], reStyle(this.resultExploit[1])), numCell(this.resultExploit[2], reStyle(this.resultExploit[2]))]);
            s2.push([{ v: 'Charges financières', s: labelStyle }, numCell(v('fin_charges_y1')), numCell(v('fin_charges_y2')), numCell(v('fin_charges_y3'))]);
            s2.push([{ v: 'Produits financiers', s: labelStyle }, numCell(v('fin_products_y1')), numCell(v('fin_products_y2')), numCell(v('fin_products_y3'))]);
            s2.push([{ v: 'Résultat courant avant impôts', s: { font: { bold: true } } }, numCell(this.resultCourant[0], reStyle(this.resultCourant[0])), numCell(this.resultCourant[1], reStyle(this.resultCourant[1])), numCell(this.resultCourant[2], reStyle(this.resultCourant[2]))]);
            s2.push([{ v: 'Impôts sur les bénéfices', s: labelStyle }, numCell(v('tax_y1')), numCell(v('tax_y2')), numCell(v('tax_y3'))]);
            s2.push([{ v: 'RÉSULTAT NET', s: resultNet }, numCell(this.resultNet[0], resultNet), numCell(this.resultNet[1], resultNet), numCell(this.resultNet[2], resultNet)]);
            const ws2 = XLSX.utils.aoa_to_sheet(s2);
            ws2['!cols'] = [{ wch: 45 }, { wch: 15 }, { wch: 15 }, { wch: 15 }];
            XLSX.utils.book_append_sheet(wb, ws2, 'Compte de résultat');

            // === Onglet 3 — Plan de trésorerie ===
            const MONTHS = this.MONTHS;
            const INFLOWS = this.INFLOWS;
            const OUTFLOWS = this.OUTFLOWS;
            const s3 = [];
            const mHeaders = MONTHS.map(m => ({ v: m, s: { font: { bold: true, sz: 8 }, alignment: { horizontal: 'center' } } }));
            s3.push([{ v: 'PLAN DE TRÉSORERIE', s: headerStyle }, ...MONTHS.map(() => ({ v: '', s: headerStyle })), { v: '', s: headerStyle }]);
            s3.push([]);
            s3.push([{ v: 'Rubrique', s: { font: { bold: true } } }, ...mHeaders, { v: 'Total', s: { font: { bold: true }, alignment: { horizontal: 'center' } } }]);
            s3.push([]);
            s3.push([{ v: 'ENCAISSEMENTS', s: subHeaderGreen }]);
            INFLOWS.forEach(inf => {
                const row = [{ v: inf, s: labelStyle }];
                let total = 0;
                MONTHS.forEach((_, i) => { const val = v('ti_' + inf + '_m' + (i + 1)); row.push(numCell(val)); total += val; });
                row.push(numCell(total, totalStyle));
                s3.push(row);
            });
            const tInRow = [{ v: 'Total encaissements (A)', s: totalStyle }];
            let gIn = 0;
            MONTHS.forEach((_, i) => { const m = INFLOWS.reduce((s, inf) => s + v('ti_' + inf + '_m' + (i + 1)), 0); tInRow.push(numCell(m, totalStyle)); gIn += m; });
            tInRow.push(numCell(gIn, totalStyle));
            s3.push(tInRow);
            s3.push([]);
            s3.push([{ v: 'DÉCAISSEMENTS', s: subHeaderRed }]);
            OUTFLOWS.forEach(out => {
                const row = [{ v: out, s: labelStyle }];
                let total = 0;
                MONTHS.forEach((_, i) => { const val = v('to_' + out + '_m' + (i + 1)); row.push(numCell(val)); total += val; });
                row.push(numCell(total, totalStyle));
                s3.push(row);
            });
            const tOutRow = [{ v: 'Total décaissements (B)', s: totalStyle }];
            let gOut = 0;
            MONTHS.forEach((_, i) => { const m = OUTFLOWS.reduce((s, out) => s + v('to_' + out + '_m' + (i + 1)), 0); tOutRow.push(numCell(m, totalStyle)); gOut += m; });
            tOutRow.push(numCell(gOut, totalStyle));
            s3.push(tOutRow);
            s3.push([]);
            const soldeRow = [{ v: 'Solde du mois (A − B)', s: resultNet }];
            MONTHS.forEach((_, i) => {
                const inM = INFLOWS.reduce((s, inf) => s + v('ti_' + inf + '_m' + (i + 1)), 0);
                const outM = OUTFLOWS.reduce((s, out) => s + v('to_' + out + '_m' + (i + 1)), 0);
                const solde = inM - outM;
                soldeRow.push(numCell(solde, { ...resultNet, font: { ...resultNet.font, color: { rgb: solde >= 0 ? '1B5E20' : 'B71C1C' } } }));
            });
            soldeRow.push(numCell(gIn - gOut, resultNet));
            s3.push(soldeRow);
            const ws3 = XLSX.utils.aoa_to_sheet(s3);
            ws3['!cols'] = [{ wch: 38 }, ...MONTHS.map(() => ({ wch: 10 })), { wch: 12 }];
            XLSX.utils.book_append_sheet(wb, ws3, 'Plan de trésorerie');

            XLSX.writeFile(wb, 'Structuration-Financiere-IAWT.xlsx');
        },
    },
    data() { return { NEEDS, RESOURCES, PRODUCTS, CHARGES, MONTHS, INFLOWS, OUTFLOWS }; },
};
</script>
<template>
    <div class="space-y-6">
        <div class="rounded-lg border-l-[3px] border-gold-500 bg-gold-50 px-4 py-3 text-xs text-gray-600 leading-relaxed">
            <p class="font-medium text-gray-700 mb-1">Chiffrer un projet, ce n'est pas le rendre plus vrai sur le papier : c'est vérifier qu'il tient debout dans la réalité, mois après mois.</p>
            <p class="italic">À savoir — Ces trois tableaux sont des outils de travail. Ils s'adaptent aussi bien à un projet de vente de produits qu'à un projet de service — laissez simplement à zéro les lignes qui ne vous concernent pas. Les montants sont exprimés en FCFA.</p>
        </div>
        <div class="flex justify-end"><button type="button" class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 transition" @click="exportExcel"><svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M6 20h12a2 2 0 002-2V8l-6-6H6a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>Télécharger en Excel</button></div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">1</span><p class="text-sm font-medium text-gray-800">Plan de financement initial</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Besoins et ressources de démarrage</p><p class="mt-0.5 text-[10px] text-gray-400">Les ressources doivent couvrir au moins les besoins. L'écart doit être ≥ 0.</p></div>
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <div>
                    <div class="mb-3 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Besoins durables</p></div>
                    <div class="space-y-1.5">
                        <div v-for="b in NEEDS" :key="b" class="flex items-center gap-2"><label class="flex-1 text-[11px] text-gray-600 truncate" :title="b">{{ b }}</label><input :value="fmtVal('n_'+b)" @input="onNumInput('n_'+b,$event)" type="text" inputmode="numeric" class="w-28 rounded border-gray-200 text-xs text-right" placeholder="0" /></div>
                        <div class="flex items-center gap-2 pt-2 border-t border-gray-200"><span class="flex-1 text-xs font-medium text-gray-800">Total des besoins</span><span class="w-28 text-right text-xs font-bold text-gray-900">{{ fmt(totalNeeds) }}</span></div>
                    </div>
                </div>
                <div>
                    <div class="mb-3 border-l-[3px] border-green-600 pl-3"><p class="text-xs font-medium text-green-700">Ressources durables</p></div>
                    <div class="space-y-1.5">
                        <div v-for="r in RESOURCES" :key="r" class="flex items-center gap-2"><label class="flex-1 text-[11px] text-gray-600 truncate" :title="r">{{ r }}</label><input :value="fmtVal('r_'+r)" @input="onNumInput('r_'+r,$event)" type="text" inputmode="numeric" class="w-28 rounded border-gray-200 text-xs text-right" placeholder="0" /></div>
                        <div class="flex items-center gap-2 pt-2 border-t border-gray-200"><span class="flex-1 text-xs font-medium text-gray-800">Total des ressources</span><span class="w-28 text-right text-xs font-bold text-gray-900">{{ fmt(totalResources) }}</span></div>
                    </div>
                </div>
            </div>
            <div class="mt-4 rounded-lg px-4 py-3" :class="gap>=0?'bg-green-50 border border-green-200':'bg-red-50 border border-red-200'">
                <div class="flex items-center justify-between"><span class="text-xs font-medium" :class="gap>=0?'text-green-800':'text-red-800'">Écart (Ressources − Besoins)</span><span class="text-sm font-bold" :class="gap>=0?'text-green-700':'text-red-700'">{{ fmt(gap) }} FCFA</span></div>
                <p v-if="gap<0" class="mt-1 text-[10px] text-red-600">Les ressources ne couvrent pas les besoins. Il manque {{ fmt(Math.abs(gap)) }} FCFA.</p>
                <p v-else-if="gap===0&&totalNeeds>0" class="mt-1 text-[10px] text-green-600">Équilibre parfait.</p>
                <p v-else-if="gap>0" class="mt-1 text-[10px] text-green-600">Marge de {{ fmt(gap) }} FCFA.</p>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">2</span><p class="text-sm font-medium text-gray-800">Compte de résultat prévisionnel</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Performance économique sur 3 ans</p><p class="mt-0.5 text-[10px] text-gray-400">Mesure si l'activité est rentable : ce qu'elle rapporte moins ce qu'elle coûte.</p></div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs min-w-[550px]">
                    <thead><tr class="border-b border-gray-200"><th class="py-2 text-left font-medium text-gray-500">Rubrique</th><th class="py-2 text-right font-medium text-gray-500 w-24">Année 1</th><th class="py-2 text-right font-medium text-gray-500 w-24">Année 2</th><th class="py-2 text-right font-medium text-gray-500 w-24">Année 3</th></tr></thead>
                    <tbody>
                        <tr><td colspan="4" class="pt-3 pb-1 text-[10px] font-bold uppercase tracking-widest text-green-700">Produits d'exploitation</td></tr>
                        <tr v-for="p in PRODUCTS" :key="p" class="border-b border-gray-50"><td class="py-1 text-gray-600 pr-2">{{ p }}</td><td class="py-1" v-for="y in [1,2,3]" :key="y"><input :value="fmtVal('p_'+p+'_y'+y)" @input="onNumInput('p_'+p+'_y'+y,$event)" type="text" inputmode="numeric" class="w-full rounded border-gray-200 text-xs text-right" placeholder="0" /></td></tr>
                        <tr class="border-b border-gray-200 bg-green-50/50"><td class="py-1.5 font-medium text-gray-800">Total produits (A)</td><td v-for="(t,i) in totalProducts" :key="i" class="py-1.5 text-right font-bold text-gray-900">{{ fmt(t) }}</td></tr>
                        <tr><td colspan="4" class="pt-4 pb-1 text-[10px] font-bold uppercase tracking-widest text-red-700">Charges d'exploitation</td></tr>
                        <tr v-for="c in CHARGES" :key="c" class="border-b border-gray-50"><td class="py-1 text-gray-600 pr-2">{{ c }}</td><td class="py-1" v-for="y in [1,2,3]" :key="y"><input :value="fmtVal('c_'+c+'_y'+y)" @input="onNumInput('c_'+c+'_y'+y,$event)" type="text" inputmode="numeric" class="w-full rounded border-gray-200 text-xs text-right" placeholder="0" /></td></tr>
                        <tr class="border-b border-gray-200 bg-red-50/50"><td class="py-1.5 font-medium text-gray-800">Total charges (B)</td><td v-for="(t,i) in totalCharges" :key="i" class="py-1.5 text-right font-bold text-gray-900">{{ fmt(t) }}</td></tr>
                        <tr class="border-b border-gray-300" :class="resultExploit[0]>=0?'bg-green-50':'bg-red-50'"><td class="py-2 font-medium text-gray-900">Résultat d'exploitation (A − B)</td><td v-for="(r,i) in resultExploit" :key="i" class="py-2 text-right font-bold" :class="r>=0?'text-green-700':'text-red-700'">{{ fmt(r) }}</td></tr>
                        <tr class="border-b border-gray-50"><td class="py-1 text-gray-600">Charges financières (intérêts)</td><td class="py-1" v-for="y in [1,2,3]" :key="y"><input :value="fmtVal('fin_charges_y'+y)" @input="onNumInput('fin_charges_y'+y,$event)" type="text" inputmode="numeric" class="w-full rounded border-gray-200 text-xs text-right" placeholder="0" /></td></tr>
                        <tr class="border-b border-gray-50"><td class="py-1 text-gray-600">Produits financiers</td><td class="py-1" v-for="y in [1,2,3]" :key="y"><input :value="fmtVal('fin_products_y'+y)" @input="onNumInput('fin_products_y'+y,$event)" type="text" inputmode="numeric" class="w-full rounded border-gray-200 text-xs text-right" placeholder="0" /></td></tr>
                        <tr class="border-b border-gray-200"><td class="py-1.5 font-medium text-gray-800">Résultat courant avant impôts</td><td v-for="(r,i) in resultCourant" :key="i" class="py-1.5 text-right font-bold" :class="r>=0?'text-green-700':'text-red-700'">{{ fmt(r) }}</td></tr>
                        <tr class="border-b border-gray-50"><td class="py-1 text-gray-600">Impôts sur les bénéfices</td><td class="py-1" v-for="y in [1,2,3]" :key="y"><input :value="fmtVal('tax_y'+y)" @input="onNumInput('tax_y'+y,$event)" type="text" inputmode="numeric" class="w-full rounded border-gray-200 text-xs text-right" placeholder="0" /></td></tr>
                        <tr class="bg-primary-50 border-t-2 border-primary-600"><td class="py-2 font-bold text-primary-800">RÉSULTAT NET</td><td v-for="(r,i) in resultNet" :key="i" class="py-2 text-right font-bold text-lg" :class="r>=0?'text-green-700':'text-red-700'">{{ fmt(r) }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">3</span><p class="text-sm font-medium text-gray-800">Plan de trésorerie</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Suivi mois par mois</p><p class="mt-0.5 text-[10px] text-gray-400">L'argent qui entre et qui sort, pour anticiper tout manque de liquidités.</p></div>
            <div class="overflow-x-auto">
                <table class="text-[10px] min-w-[900px]">
                    <thead><tr class="border-b border-gray-200"><th class="py-1.5 text-left font-medium text-gray-500 sticky left-0 bg-white w-40">Rubrique</th><th v-for="m in MONTHS" :key="m" class="py-1.5 text-right font-medium text-gray-500 w-16">{{ m }}</th><th class="py-1.5 text-right font-medium text-gray-500 w-20">Total</th></tr></thead>
                    <tbody>
                        <tr><td colspan="14" class="pt-3 pb-1 text-[9px] font-bold uppercase tracking-widest text-green-700 sticky left-0 bg-white">Encaissements</td></tr>
                        <tr v-for="inf in INFLOWS" :key="inf" class="border-b border-gray-50"><td class="py-0.5 text-gray-600 sticky left-0 bg-white pr-1">{{ inf }}</td><td v-for="(m,mi) in MONTHS" :key="m" class="py-0.5"><input :value="fmtVal('ti_'+inf+'_m'+(mi+1))" @input="onNumInput('ti_'+inf+'_m'+(mi+1),$event)" type="text" inputmode="numeric" class="w-full rounded border-gray-200 text-[10px] text-right px-1" placeholder="0" /></td><td class="py-0.5 text-right font-medium text-gray-800">{{ fmt(MONTHS.reduce((s,_,i)=>s+(parseInt(d['ti_'+inf+'_m'+(i+1)])||0),0)) }}</td></tr>
                        <tr><td colspan="14" class="pt-3 pb-1 text-[9px] font-bold uppercase tracking-widest text-red-700 sticky left-0 bg-white">Décaissements</td></tr>
                        <tr v-for="out in OUTFLOWS" :key="out" class="border-b border-gray-50"><td class="py-0.5 text-gray-600 sticky left-0 bg-white pr-1">{{ out }}</td><td v-for="(m,mi) in MONTHS" :key="m" class="py-0.5"><input :value="fmtVal('to_'+out+'_m'+(mi+1))" @input="onNumInput('to_'+out+'_m'+(mi+1),$event)" type="text" inputmode="numeric" class="w-full rounded border-gray-200 text-[10px] text-right px-1" placeholder="0" /></td><td class="py-0.5 text-right font-medium text-gray-800">{{ fmt(MONTHS.reduce((s,_,i)=>s+(parseInt(d['to_'+out+'_m'+(i+1)])||0),0)) }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-lg border-l-[3px] border-gold-500 bg-gold-50 px-4 py-3 text-xs text-gray-600 italic leading-relaxed">
            Ces trois tableaux racontent la même histoire sous trois angles différents : ce que le projet rapporte, ce qu'il coûte pour démarrer, et sa capacité à tenir sa trésorerie au quotidien. C'est leur cohérence d'ensemble, plus que chaque chiffre pris isolément, qui donne la mesure de la solidité du projet.
        </div>
    </div>
</template>
