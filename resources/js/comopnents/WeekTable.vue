<template>
    <table class="tournament-table">
        <thead>
            <tr>
                <th scope="col">Week {{ weekItem.week }}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="weekMatch in weekItem.weekMatches" :key="weekMatch.id">
                <td>
                    {{ weekMatch.home_team.name }} -
                    {{ weekMatch.away_team.name }}
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    name: "week-table",
    props: ["weekItem"],
};
</script>
