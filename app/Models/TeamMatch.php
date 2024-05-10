<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\App;

class TeamMatch extends Model
{
    use HasFactory;

    protected $table = 'team_matches';

    protected $fillable = [
        'home_team_id',
        'away_team_id',
        'week',
        'result',
        'home_team_goals',
        'away_team_goals',
    ];

    public function homeTeam(): BelongsTo 
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function getResultAttribute($value)
    {
        switch ($value) {
            case null:
                return 'Not Yet Played';
            case -1:
                return 'Away Win';
            case 0:
                return 'Draw';
            case 1:
                return 'Home Win';
            default:
                return 'Unknown';
        }
    }
}
