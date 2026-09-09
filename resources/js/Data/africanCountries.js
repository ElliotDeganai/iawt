/**
 * Pays africains avec villes principales
 * Utilisé pour les dropdowns candidature + profil
 */
export const AFRICAN_COUNTRIES = [
    { name: 'Afrique du Sud', cities: ['Johannesburg','Le Cap','Pretoria','Durban','Port Elizabeth'] },
    { name: 'Algérie', cities: ['Alger','Oran','Constantine','Annaba','Blida'] },
    { name: 'Angola', cities: ['Luanda','Huambo','Lobito','Benguela','Lubango'] },
    { name: 'Bénin', cities: ['Cotonou','Porto-Novo','Parakou','Abomey-Calavi','Djougou'] },
    { name: 'Botswana', cities: ['Gaborone','Francistown','Maun','Molepolole'] },
    { name: 'Burkina Faso', cities: ['Ouagadougou','Bobo-Dioulasso','Koudougou','Banfora','Ouahigouya'] },
    { name: 'Burundi', cities: ['Bujumbura','Gitega','Muyinga','Ngozi','Ruyigi'] },
    { name: 'Cameroun', cities: ['Douala','Yaoundé','Bamenda','Bafoussam','Garoua','Maroua','Kribi'] },
    { name: 'Cap-Vert', cities: ['Praia','Mindelo','Santa Maria','Espargos'] },
    { name: 'Centrafrique', cities: ['Bangui','Bimbo','Berbérati','Carnot','Bambari'] },
    { name: 'Comores', cities: ['Moroni','Mutsamudu','Fomboni','Domoni'] },
    { name: 'Congo', cities: ['Brazzaville','Pointe-Noire','Dolisie','Nkayi','Ouesso'] },
    { name: 'Congo (RDC)', cities: ['Kinshasa','Lubumbashi','Mbuji-Mayi','Kisangani','Goma','Bukavu'] },
    { name: "Côte d'Ivoire", cities: ['Abidjan','Bouaké','Yamoussoukro','Korhogo','San-Pédro','Daloa','Man'] },
    { name: 'Djibouti', cities: ['Djibouti','Ali Sabieh','Tadjoura','Obock'] },
    { name: 'Égypte', cities: ['Le Caire','Alexandrie','Gizeh','Louxor','Assouan'] },
    { name: 'Érythrée', cities: ['Asmara','Keren','Massawa','Assab'] },
    { name: 'Eswatini', cities: ['Mbabane','Manzini','Big Bend','Malkerns'] },
    { name: 'Éthiopie', cities: ['Addis-Abeba','Dire Dawa','Mekele','Gondar','Bahir Dar'] },
    { name: 'Gabon', cities: ['Libreville','Port-Gentil','Franceville','Oyem','Moanda'] },
    { name: 'Gambie', cities: ['Banjul','Serekunda','Brikama','Bakau'] },
    { name: 'Ghana', cities: ['Accra','Kumasi','Tamale','Sekondi-Takoradi','Cape Coast'] },
    { name: 'Guinée', cities: ['Conakry','Nzérékoré','Kankan','Kindia','Labé'] },
    { name: 'Guinée équatoriale', cities: ['Malabo','Bata','Ebebiyín','Mongomo'] },
    { name: 'Guinée-Bissau', cities: ['Bissau','Bafatá','Gabú','Bissorã'] },
    { name: 'Kenya', cities: ['Nairobi','Mombasa','Kisumu','Nakuru','Eldoret'] },
    { name: 'Lesotho', cities: ['Maseru','Teyateyaneng','Mafeteng','Hlotse'] },
    { name: 'Libéria', cities: ['Monrovia','Gbarnga','Kakata','Buchanan'] },
    { name: 'Libye', cities: ['Tripoli','Benghazi','Misrata','Sebha'] },
    { name: 'Madagascar', cities: ['Antananarivo','Toamasina','Antsirabe','Fianarantsoa','Mahajanga'] },
    { name: 'Malawi', cities: ['Lilongwe','Blantyre','Mzuzu','Zomba'] },
    { name: 'Mali', cities: ['Bamako','Sikasso','Ségou','Mopti','Koutiala','Kayes'] },
    { name: 'Maroc', cities: ['Casablanca','Rabat','Marrakech','Fès','Tanger','Agadir'] },
    { name: 'Maurice', cities: ['Port-Louis','Beau Bassin','Vacoas','Curepipe'] },
    { name: 'Mauritanie', cities: ['Nouakchott','Nouadhibou','Kaédi','Zouérat'] },
    { name: 'Mozambique', cities: ['Maputo','Beira','Nampula','Quelimane','Chimoio'] },
    { name: 'Namibie', cities: ['Windhoek','Walvis Bay','Swakopmund','Oshakati'] },
    { name: 'Niger', cities: ['Niamey','Zinder','Maradi','Tahoua','Agadez'] },
    { name: 'Nigeria', cities: ['Lagos','Abuja','Kano','Ibadan','Port Harcourt'] },
    { name: 'Ouganda', cities: ['Kampala','Gulu','Lira','Mbarara','Jinja'] },
    { name: 'Rwanda', cities: ['Kigali','Butare','Gisenyi','Ruhengeri','Gitarama'] },
    { name: 'Sao Tomé-et-Principe', cities: ['São Tomé','Santo Amaro','Neves'] },
    { name: 'Sénégal', cities: ['Dakar','Thiès','Saint-Louis','Kaolack','Ziguinchor','Touba'] },
    { name: 'Seychelles', cities: ['Victoria','Anse Boileau','Beau Vallon'] },
    { name: 'Sierra Leone', cities: ['Freetown','Bo','Kenema','Makeni'] },
    { name: 'Somalie', cities: ['Mogadiscio','Hargeisa','Kismayo','Berbera'] },
    { name: 'Soudan', cities: ['Khartoum','Omdurman','Port-Soudan','Kassala'] },
    { name: 'Soudan du Sud', cities: ['Juba','Malakal','Wau','Bor'] },
    { name: 'Tanzanie', cities: ['Dar es Salaam','Dodoma','Mwanza','Arusha','Zanzibar'] },
    { name: 'Tchad', cities: ['N\'Djamena','Moundou','Abéché','Sarh'] },
    { name: 'Togo', cities: ['Lomé','Sokodé','Kara','Kpalimé','Atakpamé'] },
    { name: 'Tunisie', cities: ['Tunis','Sfax','Sousse','Kairouan','Bizerte'] },
    { name: 'Zambie', cities: ['Lusaka','Kitwe','Ndola','Livingstone','Kabwe'] },
    { name: 'Zimbabwe', cities: ['Harare','Bulawayo','Chitungwiza','Mutare','Gweru'] },
];

export const COUNTRY_NAMES = AFRICAN_COUNTRIES.map(c => c.name);

export function getCities(countryName) {
    const country = AFRICAN_COUNTRIES.find(c => c.name === countryName);
    return country ? country.cities : [];
}
