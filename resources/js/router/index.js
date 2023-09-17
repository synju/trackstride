import {createRouter, createWebHistory} from "vue-router";

import raceIndex from '../components/racecards/index.vue'
import notFound from '../components/notFound.vue'

const routes = [
	{
		path:'/',
		component:raceIndex
	},
	{
		path:'/:pathMatch(.*)*',
		component:notFound
	},
]

const router = createRouter({
	history:createWebHistory(),
	routes
})

export default router
