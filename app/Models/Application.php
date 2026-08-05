<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    protected $fillable = [
        'user_id', 'current_step', 'status', 'journey_current_step', 'rejection_reason',
        // Étape 1
        'gender', 'country_of_residence', 'city_of_residence', 'postal_address',
        'personal_email', 'whatsapp_phone', 'has_internet_access', 'current_status',
        'university_name', 'university_doc', 'weekly_hours',
        // Étape 2
        'project_name', 'project_problem', 'project_solution', 'project_sector',
        'project_competition', 'project_pitch', 'project_stage', 'team_project',
        // Étape 3
        'cv_file', 'linkedin_url',
        // Étape 4
        'previous_program', 'support_needs', 'heard_about_us',
        'consent_data', 'consent_image', 'submitted_at',
    ];

    protected $casts = [
        'has_internet_access' => 'boolean',
        'team_project'        => 'boolean',
        'previous_program'    => 'boolean',
        'consent_data'        => 'boolean',
        'consent_image'       => 'boolean',
        'support_needs'       => 'array',
        'submitted_at'        => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isSubmitted(): bool
    {
        return $this->status !== 'draft';
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\AdminComment::class)->latest();
    }
}
