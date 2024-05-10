<template>
    <div>
        <h1>Simultion</h1>
        <div>
            <div>
                <TournamentButton
                    title="Play All Weeks"
                    @handle-click="playAllWeeks"
                    :disabled="isBtnPlayAllWeeksDisabled"
                />
                <TournamentButton
                    title="Play Next Week"
                    @handle-click="playNextWeek"
                    :disabled="isBtnPlayNextWeekDisabled"
                />
                <TournamentButton
                    title="Reset Data"
                    class="danger"
                    @handle-click="resetData"
                    :disabled="isBtnResetDataDisabled"
                />
            </div>
            <div>
                <PointsTable :teamsPoints="teamsPoints" />
            </div>
            <div>
                <div v-if="nextWeekMatches">
                    <WeekTable :weekItem="nextWeekMatches" />
                </div>
                <div v-else>No Next Week Info</div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import TournamentButton from "../comopnents/TournamentButton.vue";
import PointsTable from "../comopnents/PointsTable.vue";
import WeekTable from "../comopnents/WeekTable.vue";

export default {
    name: "Teams",
    components: {
        TournamentButton,
        WeekTable,
        PointsTable,
    },
    data() {
        return {
            teamsPoints: [],
            nextWeekMatches: null,
            groupedWeekMatches: [],
            isBtnPlayNextWeekDisabled: true,
            isBtnPlayAllWeeksDisabled: true,
            isBtnResetDataDisabled: false,
        };
    },
    mounted() {
        this.getPoints();
        this.getNextWeek();
    },
    methods: {
        getPoints() {
            axios
                .get("api/teams/points")
                .then((response) => {
                    this.teamsPoints = response?.data?.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getNextWeek() {
            axios
                .get("api/team-matches/next-week")
                .then((response) => {
                    this.nextWeekMatches = response?.data?.data;
                    if (response?.data?.data) {
                        this.isBtnPlayNextWeekDisabled = false;
                        this.isBtnPlayAllWeeksDisabled = false;
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        playNextWeek() {
            this.isBtnPlayNextWeekDisabled = true;
            axios
                .put("api/team-matches/next-week")
                .then((response) => {
                    this.getPoints();
                    this.getNextWeek();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        playAllWeeks() {
            this.isBtnPlayAllWeeksDisabled = true;
            axios
                .put("api/team-matches/play-all-weeks")
                .then((response) => {
                    this.getPoints();
                    this.getNextWeek();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        resetData() {
            this.isBtnResetDataDisabled = true;
            axios
                .delete("api/teams/reset-data")
                .then((response) => {
                    this.$router.push("/");
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
};
</script>

<style lang="scss" scoped>
table {
    margin-bottom: 20px;
}
</style>
