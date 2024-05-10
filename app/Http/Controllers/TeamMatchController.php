<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamMatch;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TeamMatchController extends Controller
{
    public function index(Request $request)
    {
        $teamMatches = TeamMatch::with(['homeTeam', 'awayTeam'])->get();
        return $this->responseSuccess($teamMatches);
    }

    public function store(Request $request)
    {
        $teamIds = Team::all()->pluck('id')->shuffle()->toArray();

        if (TeamMatch::count() > 0) {
            return $this->responseSuccess(TeamMatch::all());
        }

        $firstHalf = $this->getHalfOfFixture($teamIds);
        shuffle($teamIds);

        $secondHalf = $this->getHalfOfFixture($teamIds, true);
        foreach ($secondHalf as &$secondHalfMatch) {
            foreach ($firstHalf as $firstHalfMatch) {
                if ($secondHalfMatch['home_team_id'] === $firstHalfMatch['home_team_id'] && $secondHalfMatch['away_team_id'] === $firstHalfMatch['away_team_id']) {
                    $secondHalfMatch['home_team_id'] = $firstHalfMatch['away_team_id'];
                    $secondHalfMatch['away_team_id'] = $firstHalfMatch['home_team_id'];
                }
            }
        }

        $fixtureData = array_merge($firstHalf, $secondHalf);

        TeamMatch::insert($fixtureData);

        $this->responseSuccess($fixtureData, Response::HTTP_CREATED);
    }

    public function getNextWeek(Request $request)
    {
        $weekNo = $this->getUnPlayedWeek();
        $nextWeekMatches = $weekNo === null ? null : $this->getWeekMatches($weekNo);

        return $this->responseSuccess(
            $nextWeekMatches,
            Response::HTTP_OK
        );
    }

    public function playNextWeek(Request $request)
    {
        $weekNo = $this->getUnPlayedWeek();
        if ($weekNo === null) {
            return $this->responseError("No next week info");
        }

        $this->playWeek($weekNo);

        return $this->responseSuccess(
            $this->getWeekMatches($weekNo),
            Response::HTTP_OK
        );
    }

    public function playAllWeeks(Request $request)
    {
        $weekMatches = TeamMatch::whereNull('result')->get();

        foreach ($weekMatches as $weekMatch) {
            $weekMatch->update(
                $this->getRandomResult(),
            );
        }

        $teamMatches = TeamMatch::with(['homeTeam', 'awayTeam'])->get();
        return $this->responseSuccess($teamMatches);
    }


    function getHalfOfFixture($teamIds, $isSecondHalf = false)
    {
        $halfOfTeamCount = count($teamIds) / 2;
        $weeksMatches = [];
        $weekPlus = $isSecondHalf ?  $halfOfTeamCount + 2 : 1; //to continue from last week of first half

        for ($week = 0; $week < count($teamIds) - 1; $week++) {
            for ($i = 0; $i < $halfOfTeamCount; $i++) {
                $homeTeamId = null;
                if ($week === 0 || $i === 0) {
                    $homeTeamId = $teamIds[$i];
                } elseif ($week > 0 && $i === 1) {
                    $homeTeamId = $weeksMatches[$week - 1][0]['away_team_id'];
                } else {
                    $homeTeamId = $weeksMatches[$week - 1][$i - 1]['home_team_id'];
                }

                $awayTeamId = null;
                if ($week === 0) {
                    $awayTeamId = $teamIds[$i + $halfOfTeamCount];
                } elseif ($week > 0 && $i === $halfOfTeamCount - 1) {
                    $awayTeamId = $weeksMatches[$week - 1][$halfOfTeamCount - 1]['home_team_id'];
                } else {
                    $awayTeamId = $weeksMatches[$week - 1][$i + 1]['away_team_id'];
                }

                $weeksMatches[$week][] = ['home_team_id' => $homeTeamId, 'away_team_id' => $awayTeamId];
            }
        }

        $fixtureDataList = [];
        foreach ($weeksMatches as $week => $matches) {
            foreach ($matches as $match) {
                array_push($fixtureDataList, ['week' => $week + $weekPlus, 'home_team_id' => $match['home_team_id'], 'away_team_id' => $match['away_team_id']]);
            }
        }

        return $fixtureDataList;
    }

    function getWeekMatches($weekNo)
    {
        $weekMatches = TeamMatch::where('week', $weekNo)->with(['homeTeam', 'awayTeam'])->get();
        return [
            'week' => $weekNo,
            'weekMatches' => $weekMatches
        ];
    }

    function getUnPlayedWeek()
    {
        $weekNo =
            TeamMatch::whereNull('result')
                ->orderBy('week', 'asc')
                ->selectRaw('week, count(*) as total')
                ->groupBy('week')
                ->first()['week'] ?? null;

        return $weekNo;
    }

    function playWeek($weekNo)
    {
        $weekMatches = TeamMatch::where('week', $weekNo)->whereNull('result')->get();

        foreach ($weekMatches as $weekMatch) {
            $weekMatch->update(
                $this->getRandomResult(),
            );
        }
    }

    function getRandomResult()
    {
        $maxTotalGoals = 20;
        $homeTeamGoals = rand(0, $maxTotalGoals);
        $awayTeamGoals = rand(0, $maxTotalGoals - $homeTeamGoals);
        $result = $homeTeamGoals - $awayTeamGoals;

        if ($result !== 0) {
            $result /= $result; // greater 1 is 1, less -1 is -1
        }

        return [
            'result' => $result,
            'home_team_goals' => $homeTeamGoals,
            'away_team_goals' => $awayTeamGoals,
        ];
    }
}
