<?php

namespace App\Http\Controllers;

use App\DTO\TeamPointsDTO;
use App\Models\Team;
use App\Models\TeamMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Team::count() !== 4) {
            Team::query()->delete();
            $newTeamList = [
                ['name' => 'Arsenal'],
                ['name' => 'Chelsea'],
                ['name' => 'Liverpool'],
                ['name' => 'Manchester City'],
            ];
            Team::insert($newTeamList);
        }

        $teams = Team::all();

        return $this->responseSuccess($teams);
    }

    public function getTeamsWithPoints(Request $request)
    {
        $teams = Team::all();
        $teamMatches = TeamMatch::whereNotNull('result')->get();
        $teamPoints = [];

        foreach ($teams as $team) {
            $teamId = $team->id;
            $teamPoints[$teamId] = new TeamPointsDTO($teamId, $team->name);
        }

        foreach ($teamMatches as $match) {

            $homeTeamId = $match->home_team_id;
            $awayTeamId = $match->away_team_id;
            $homeTeamGoals = $match->home_team_goals;
            $awayTeamGoals = $match->away_team_goals;

            $homeTeam = $teamPoints[$homeTeamId];
            $homeTeam->AddGoalDiffrence($homeTeamGoals - $awayTeamGoals);

            $awayTeam = $teamPoints[$awayTeamId];
            $awayTeam->AddGoalDiffrence($awayTeamGoals - $homeTeamGoals);

            if ($homeTeamGoals > $awayTeamGoals) {
                $homeTeam->increaseWins();
                $awayTeam->increaseLosses();
            } elseif ($homeTeamGoals < $awayTeamGoals) {
                $homeTeam->increaseLosses();
                $awayTeam->increaseWins();
            } else {
                $homeTeam->increaseDraws();
                $awayTeam->increaseDraws();
            }

            $homeTeam->setPoint();
            $awayTeam->setPoint();
        }

        $pointList = array_values($teamPoints);

        usort($pointList, function ($a, $b) {
            return $b->point - $a->point;
        });

        return $this->responseSuccess($pointList);
    }

    public function resetData()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        TeamMatch::truncate();
        Team::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return $this->responseSuccess([]);
    }
}
