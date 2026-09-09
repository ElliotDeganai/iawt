<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\JourneyResponse;
use App\Models\User;
use Illuminate\Console\Command;

class JourneyFastForward extends Command
{
    protected $signature = 'journey:fast-forward
                            {email : E-mail de l\'utilisateur}
                            {step : Numéro de l\'étape cible (1-8)}
                            {--reset : Remet à zéro les étapes après la cible}';

    protected $description = 'Valide automatiquement toutes les étapes du parcours jusqu\'à l\'étape indiquée (dev/test)';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error("Utilisateur introuvable : {$this->argument('email')}");
            return 1;
        }

        $application = Application::where('user_id', $user->id)->first();

        if (! $application) {
            $this->error("Aucune candidature pour cet utilisateur.");
            return 1;
        }

        if ($application->status !== 'accepted') {
            $this->info("Candidature pas encore acceptée — passage en 'accepted'...");
            $application->update(['status' => 'accepted']);
        }

        $target = (int) $this->argument('step');

        if ($target < 1 || $target > 8) {
            $this->error("L'étape doit être entre 1 et 8.");
            return 1;
        }

        $now = now();

        // Validate all steps up to target - 1 (completed + validated)
        for ($s = 1; $s < $target; $s++) {
            JourneyResponse::updateOrCreate(
                ['user_id' => $user->id, 'step_number' => $s],
                [
                    'data'         => $this->getDummyData($s),
                    'completed_at' => $now,
                    'validated_at' => $now,
                    'rework_reason'=> null,
                ]
            );
            $this->line("  ✓ Étape {$s} — complétée et validée");
        }

        // Unlock the target step (current step = target)
        $application->update(['journey_current_step' => $target]);
        $this->info("→ Étape courante débloquée : {$target}");

        // Optionally reset steps after target
        if ($this->option('reset')) {
            JourneyResponse::where('user_id', $user->id)
                ->where('step_number', '>=', $target)
                ->delete();
            $this->line("  ↺ Étapes {$target}–8 remises à zéro");
        }

        $this->newLine();
        $this->info("Parcours avancé jusqu'à l'étape {$target} pour {$user->first_name} {$user->last_name} ({$user->email}).");

        return 0;
    }

    private function getDummyData(int $step): array
    {
        return match ($step) {
            1 => ['origins' => ['Observation d\'un besoin'], 'who' => 'Porteur test', 'what' => 'Produits agricoles', 'why' => 'Besoin local', 'how' => 'Production directe', 'where' => 'Côte d\'Ivoire', 'adequation' => 4],
            2 => ['project_name' => 'Projet test', 'zone_country' => 'Côte d\'Ivoire', 'zone_region' => 'Lagunes', 'zone_city' => 'Abidjan', 'description' => 'Transformation agricole', 'problem' => 'Manque de produits transformés', 'main_client' => 'Consommateurs urbains', 'decision' => 'Je poursuis le projet sans modification majeure', 'client_age' => '25-45 ans'],
            3 => ['value_prop' => 'Produits locaux transformés', 'segments' => 'Urbains, restaurants', 'partners' => 'Producteurs locaux', 'activities' => 'Transformation', 'channels' => 'Vente directe', 'revenue' => 'Ventes produits'],
            4 => ['n_Frais d\'immatriculation et de création' => 75000, 'n_Achat de matériel et équipement' => 500000, 'r_Apport personnel' => 400000, 'p_CA — ventes de produits_y1' => 1200000],
            5 => ['country' => 'Côte d\'Ivoire', 'city' => 'Abidjan', 'status' => 'Sans activité', 'experience' => 'Moins de 2 ans', 'sector' => 'Agro-transformation', 'project_type' => 'Individuel', 'founders' => '1', 'legal_form' => 'SARLU', 'Q1' => '1', 'Q2' => 'Oui'],
            6 => ['holder_name' => 'Porteur test', 'host_company' => 'Ferme Modèle', 'tested_activity' => 'Transformation manioc', 'sector' => 'Agriculture', 'activities_done' => ['Production', 'Vente'], 'revenue' => 150000, 'found_clients' => 'Oui', 'assessment' => 'Prêt à être lancé', 'next_30_days' => 'Finaliser le plan', 'support_needs' => ['Financement']],
            7 => ['creation_country' => 'Côte d\'Ivoire', 'track' => 'individual', 'notes' => 'RAS'],
            8 => ['checklist' => ['Fiche profil complétée', 'Questionnaire complété']],
            default => [],
        };
    }
}