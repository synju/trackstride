<script setup>
	import {onMounted, ref} from 'vue';

	let racecards = ref([]);

	onMounted(async () => {
		getRaceCards();
	});

	const getRaceCards = async () => {
		let response = await axios.get("/api/get_all_racecards");
		console.log('response', response.data.racecards);
		racecards.value = response.data.racecards.map(item => item.racecard);
	};
</script>

<template>
	<div class="container">
		<div id="racecards">
			<!-- List RaceCards if found -->
			<div v-for="racecard in racecards" :key="racecard.itw" v-if="racecards.length > 0">
				<a href="#">#{{racecard.track}}</a>
			</div>

			<!-- Else state no racecards found -->
			<div v-else>
				<p>No Racecards Found!</p>
			</div>
		</div>
	</div>
</template>
