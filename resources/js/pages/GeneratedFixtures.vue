<template>
    <div>
        <h1>Generated Fixtures</h1>
        <div class="box">
            <div v-for="weekItem in groupedWeekMatches" :key="weekItem.week">
                <WeekTable :weekItem="weekItem" />
            </div>
        </div>

        <TournamentButton
            title="Start Simulation"
            @handle-click="startSimulation"
            :disabled="isBtnSimulateDisabled"
        />
    </div>
</template>

<script>
import axios from "axios";
import TournamentButton from "../comopnents/TournamentButton.vue";
import WeekTable from "../comopnents/WeekTable.vue";

export default {
    name: "Teams",
    components: {
        TournamentButton,
        WeekTable,
    },
    data() {
        return {
            generatedFixtures: [],
            groupedWeekMatches: [],
            isBtnSimulateDisabled: true,
        };
    },
    mounted() {
        this.getGeneratedFixtures();
    },
    methods: {
        getGeneratedFixtures() {
            axios
                .get("api/team-matches")
                .then((response) => {
                    const teamMatches = response?.data?.data;
                    this.generatedFixtures = teamMatches;

                    const weeks = [...new Set(teamMatches.map((i) => i.week))];
                    this.groupedWeekMatches = weeks.map((week) => ({
                        week: week,
                        weekMatches: teamMatches.filter((i) => i.week === week),
                    }));

                    this.isBtnSimulateDisabled = false;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        startSimulation() {
            this.isBtnGenerateDisabled = true;
            this.$router.push("/simulation");
        },
    },
};
</script>
