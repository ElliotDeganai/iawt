<?php

namespace Database\Seeders;

use App\Models\CountrySheet;
use Illuminate\Database\Seeder;

class CountrySheetSeeder extends Seeder
{
    public function run(): void
    {
        CountrySheet::updateOrCreate(['country_name' => 'RCA'], [
            'flag_emoji'       => '🇨🇫',
            'title'            => 'Créer son entreprise en République centrafricaine',
            'intro'            => 'Le point d\'entrée est unique : le GUFE (Guichet Unique de Formalités des Entreprises) centralise le Greffe, les Impôts, le Travail et la CNSS en un seul dossier et un seul paiement.',
            'warning'          => 'Les informations de cette fiche peuvent évoluer : elle est un point de départ, pas une source immuable.',
            'actors'           => [
                ['name' => 'GUFE', 'role' => 'Point d\'entrée et centralisation des formalités'],
                ['name' => 'Greffe / RCCM', 'role' => 'Immatriculation au Registre du Commerce et du Crédit Mobilier'],
                ['name' => 'Administration fiscale', 'role' => 'NIF, fiscalité et obligations fiscales'],
                ['name' => 'CNSS', 'role' => 'Affiliation et obligations sociales (si salariés)'],
                ['name' => 'CCIMA', 'role' => 'Chambre de Commerce, d\'Industrie, des Mines et de l\'Artisanat'],
                ['name' => 'Ministère du Commerce', 'role' => 'Agrément / carte de commerçant selon le cas'],
                ['name' => 'Notaire / professionnel du droit', 'role' => 'Statuts, actes et accompagnement juridique'],
                ['name' => 'Ministère sectoriel', 'role' => 'Agrément pour activités réglementées'],
                ['name' => 'Banque', 'role' => 'Compte professionnel et dépôt du capital'],
            ],
            'organism_name'    => 'GUFE',
            'organism_full'    => 'Guichet Unique de Formalités des Entreprises',
            'organism_desc'    => 'Interface exclusive entre le créateur d\'entreprise et l\'État. Coordonne le Greffe (RCCM), l\'administration fiscale (NIF) et la CNSS.',
            'organism_address' => 'Bangui, 1er arrondissement, rue du Docteur Ballay, en face de la Radio Centrafrique',
            'organism_hours'   => 'Lundi–vendredi, 08h00–15h30',
            'organism_website' => 'gufe-rca.org',
            'documents'        => [
                'Copie(s) de pièce(s) d\'identité (CNI ou passeport)',
                '2 photos d\'identité récentes',
                'Extrait de casier judiciaire de moins de 3 mois',
                'Pour les étrangers : carte consulaire, titre de séjour ou visa',
                'Justificatif du siège social (contrat de bail ou titre foncier)',
                'Pour une société : copie des statuts (notariés ou sous seing privé)',
                'Pour une société : preuve de dépôt du capital social',
                'Pour une société : bordereau de versement ou tableau récapitulatif des apports',
            ],
            'individual_steps' => [
                'Préparer les pièces (Kit Création)', 'Dépôt au GUFE', 'RCCM', 'NIF / fiscalité',
                'CCIMA / formalités commerciales selon le cas', 'CNSS si salariés',
                'Autorisations sectorielles si applicable', 'Démarrage de l\'activité',
            ],
            'company_steps'    => [
                'Choisir la forme juridique', 'Choisir le nom (jusqu\'à 3 variantes, vérification RCCM)',
                'Préparer les statuts', 'Déterminer le capital et les apports',
                'Constituer le capital si nécessaire', 'Signer les actes', 'Dépôt au GUFE',
                'RCCM', 'NIF / fiscalité', 'CNSS si salariés', 'CCIMA / formalités commerciales',
                'Agréments éventuels', 'Démarrage de l\'activité',
            ],
            'fees'             => 'Le GUFE fonctionne sur le principe d\'un paiement unique couvrant l\'ensemble des formalités inter-administrations. Le montant précis varie selon la forme juridique et doit être demandé directement au guichet.',
            'sector_auth'      => 'Au-delà de l\'immatriculation, certaines activités restent soumises à des cadres réglementaires spécifiques : permis, autorisations ou licences à obtenir (mines, énergie, transport, télécoms, tourisme, agro-industrie…). Pour l\'agriculture et le tourisme, vérifier auprès du ministère sectoriel concerné.',
            'is_published'     => true,
        ]);
    }
}
