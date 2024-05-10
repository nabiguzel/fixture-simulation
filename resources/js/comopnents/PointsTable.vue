<template>
    <table class="tournament-table">
        <thead>
            <tr>
                <th scope="col">Team Name</th>
                <th scope="col">Point</th>
                <th scope="col">Win</th>
                <th scope="col">Draw</th>
                <th scope="col">Lose</th>
                <th scope="col">Goal Difference</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="teamPoint in teamsPoints" :key="teamPoint.team_id">
                <td>{{ teamPoint.team_name }}</td>
                <td>{{ teamPoint.point }}</td>
                <td>{{ teamPoint.wins }}</td>
                <td>{{ teamPoint.draws }}</td>
                <td>{{ teamPoint.losses }}</td>
                <td>{{ teamPoint.goal_difference }}</td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    name: "teams-points-table",
    props: ["teamsPoints"],
};
</script>
