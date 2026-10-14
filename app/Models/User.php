<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

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
        'bio',
        'university',
        'avatar',
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

    /**
     * All skills linked to the user (both teaching and learning).
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'user_skills')
            ->withPivot('type', 'proficiency')
            ->withTimestamps();
    }

    /**
     * Skills the user is willing to teach.
     */
    public function teachSkills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'user_skills')
            ->wherePivot('type', 'teach')
            ->withPivot('type', 'proficiency')
            ->withTimestamps();
    }

    /**
     * Skills the user wants to learn.
     */
    public function learnSkills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'user_skills')
            ->wherePivot('type', 'learn')
            ->withPivot('type', 'proficiency')
            ->withTimestamps();
    }

    /**
     * Pivot records linking this user to skills.
     */
    public function userSkills(): HasMany
    {
        return $this->hasMany(UserSkill::class);
    }

    /**
     * Swap requests this user has sent to others.
     */
    public function sentSwapRequests(): HasMany
    {
        return $this->hasMany(SwapRequest::class, 'sender_id');
    }

    /**
     * Swap requests this user has received from others.
     */
    public function receivedSwapRequests(): HasMany
    {
        return $this->hasMany(SwapRequest::class, 'receiver_id');
    }

    /**
     * Two-letter initials used for the avatar placeholder.
     */
    public function initials(): string
    {
        $parts = preg_split('/\s+/', trim($this->name));

        $initials = '';
        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= mb_strtoupper(mb_substr($part, 0, 1));
        }

        return $initials !== '' ? $initials : '?';
    }

    /**
     * Deterministic Tailwind background class for the avatar placeholder.
     */
    public function avatarColor(): string
    {
        $palette = [
            'bg-sage-500',
            'bg-sage-600',
            'bg-sage-700',
            'bg-sage-800',
            'bg-beige-500',
            'bg-beige-600',
            'bg-beige-700',
            'bg-amber-600',
        ];

        $hash = crc32((string) ($this->email ?? $this->name)) & 0x7FFFFFFF;

        return $palette[$hash % count($palette)];
    }

    /**
     * Public URL of the uploaded avatar, or null when none is set.
     */
    public function avatarUrl(): ?string
    {
        return $this->avatar ? asset('storage/'.$this->avatar) : null;
    }

    /**
     * Remove the stored avatar file (if any) from the public disk.
     */
    public function deleteAvatarFile(): void
    {
        if ($this->avatar) {
            Storage::disk('public')->delete($this->avatar);
        }
    }
}
