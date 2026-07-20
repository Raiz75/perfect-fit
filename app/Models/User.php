<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'church_code',
        'church_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function demographicRestrictions()
    {
        $this->hasMany(DemographicRestriction::class);
    }

    public function skillRestrictions()
    {
        $this->hasMany(SkillRestriction::class);
    }

    public function skillQuestions()
    {
        $this->hasMany(SkillQuestion::class);
    }

    public function interestAndPassionQuestions()
    {
        $this->hasMany(InterestAndPassionQuestion::class);
    }

    public function behavioralQuestions()
    {
        $this->hasMany(BehavioralQuestion::class);
    }
}
