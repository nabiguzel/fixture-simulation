<template>
    <div>
        <h1>Tournament Teams</h1>
        <table class="tournament-table">
            <thead>
                <tr>
                    <th scope="col">Team Name</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="team in teams" :key="team.id">
                    <td>{{ team.name }}</td>
                </tr>
            </tbody>
        </table>
        <TournamentButton
            title="Generate Fixtures"
            @handle-click="generateFixtures"
            :disabled="isBtnGenerateDisabled"
        />
    </div>
</template>

<script>
import axios from "axios";
import TournamentButton from "../comopnents/TournamentButton.vue";

export default {
    name: "Teams",
    components: {
        TournamentButton,
    },
    data() {
        return {
            teams: [],
            isBtnGenerateDisabled: true,
        };
    },
    mounted() {
        this.getTeams();
    },
    methods: {
        getTeams() {
            axios
                .get("api/teams")
                .then((response) => {
                    this.teams = response?.data?.data;
                    this.isBtnGenerateDisabled = false;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        generateFixtures() {
            this.isBtnGenerateDisabled = true;
            axios
                .post("api/team-matches")
                .then((response) => {
                    this.$router.push("/generated-fixtures");
                })
                .catch((error) => {
                    this.$router.push("/generated-fixtures");
                    this.isBtnGenerateDisabled = false;
                    console.log(error);
                });
        },
    },
};
</script>
