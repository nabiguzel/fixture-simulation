<?php

namespace App\DTO;

class TeamPointsDTO
{
    public int $point = 0;
    public function __construct(
        public int $team_id,
        public string $team_name,
        public int $wins = 0,
        public int $draws = 0,
        public int $losses = 0,
        public int $goal_difference = 0
    ) {
    }

    public function increaseWins()
    {
        $this->wins += 1;
    }

    public function increaseDraws()
    {
        $this->draws += 1;
    }

    public function increaseLosses()
    {
        $this->losses += 1;
    }

    public function AddGoalDiffrence($difference)
    {
        $this->goal_difference += $difference;
    }

    public function setPoint()
    {
        $this->point = $this->wins * 3 + $this->draws;
    }

    public function toArray(): array
    {
        $this->setPoint();
        return array(
            "team_id" => $this->team_id,
            "team_name" => $this->team_name,
            "wins" => $this->wins,
            "draws" => $this->draws,
            "losses" => $this->losses,
            "goal_difference" => $this->goal_difference,
            "point" => $this->point
        );
    }
}
