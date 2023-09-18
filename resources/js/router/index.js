import {createRouter, createWebHistory} from "vue-router";

import ViewRacecards from '../components/ViewRacecards.vue'
import ViewRacecard from '../components/ViewRacecard.vue'
import ViewHorses from '../components/ViewHorses.vue'
import ViewPastRuns from '../components/ViewPastRuns.vue'
import ViewRace from '../components/ViewRace.vue'
import NotFound from '../components/NotFound.vue'

const routes = [
	{
		path:'/',
		component:ViewRacecards,
		props:true
	},
	{
		path:'/viewraces/:itw',
		component:ViewRacecard,
		props:true
	},
	{
		path:'/viewhorses/:itw/:raceIndex',
		component:ViewHorses,
		props:true
	},
	{
		path:'/viewpastpuns/:itw/:raceIndex/:horseId',
		component:ViewPastRuns,
		props:true
	},
	{
		path:'/viewrace/:itw/:raceIndex',
		component:ViewRace,
		props:true
	},
	{
		path:'/:pathMatch(.*)*',
		component:NotFound
	},
]

const router = createRouter({
	history:createWebHistory(),
	routes
})

export default router
