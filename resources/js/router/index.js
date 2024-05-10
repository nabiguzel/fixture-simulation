import { createRouter, createWebHistory } from "vue-router";

import tournamentTeams from "../pages/TournamentTeams.vue";
import generatedFixtures from "../pages/GeneratedFixtures.vue";
import simulation from "../pages/Simulation.vue";
import about from "../pages/AboutPage.vue";
import notFound from "../pages/NotFoundPage.vue";

const routes = [
    {
        path: "/",
        component: tournamentTeams,
    },
    {
        path: "/generated-fixtures",
        component: generatedFixtures,
    },
    {
        path: "/simulation",
        component: simulation,
    },
    {
        path: "/about",
        component: about,
    },
    {
        path: "/:pathMatch(.*)*",
        component: notFound,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
