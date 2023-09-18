<script setup>
	import {onMounted, ref} from "vue";
	import {useRouter} from "vue-router";

	const router = useRouter()

	let racecard = ref([]);
	let races = ref([]);
	let currentRaceIndex = ref(0);

	onMounted(async() => {
		await getRacecard();
	});

	const props = defineProps({
		itw:{
			type:String,
			default:''
		}
	});

	const getRacecard = async() => {
		let response = await axios.get(`/api/get_racecard/${props.itw}`);
		console.log('response', response.data.racecard.races);

		// Set Racecard...
		racecard.value = response.data.racecard;

		// Get Races ordered by startTime...
		races.value = racecard.value.races.sort((a, b) => {
			const timeA = a.startTime.split(":").map(Number);
			const timeB = b.startTime.split(":").map(Number);

			// Compare hours and minutes
			if(timeA[0] === timeB[0]) {
				return timeA[1] - timeB[1];
			}

			return timeA[0] - timeB[0];
		});
	}

	const nextRace = () => {
		currentRaceIndex.value = (currentRaceIndex.value + 1) % races.value.length;
	};

	const prevRace = () => {
		currentRaceIndex.value = (currentRaceIndex.value - 1 + races.value.length) % races.value.length;
	};

	const viewHorses = (itw, raceIndex) => {
		router.push(`/viewhorses/${itw}/${raceIndex}`);
	};

	const back = () => {
		router.go(-1);
	};
</script>

<template>
	<!-- Back Button -->
	<div class="row">
		<div class="col-md-12 mb-3 px-0 d-flex align-items-center">
			<button @click="back" class="btn btn-primary">Back</button>
		</div>
	</div>

	<!-- Navigation Input -->
	<div class="row">
		<h3>Races</h3>
		<div class="col-md-12 mb-3 px-0 d-flex align-items-center">
			<input type="hidden" class="form-control" v-model="currentRaceIndex" :min="1" :max="races.length"/>
			<button @click="prevRace" class="btn btn-primary">Previous</button>
			<span class="px-2 fs-3">Race {{ currentRaceIndex + 1 }}</span>
			<button @click="nextRace" class="btn btn-primary">Next</button>
		</div>
	</div>

	<!-- Racecard -->
	<div id="racecard" class="row"></div>

	<!-- Races -->
	<div id="races" class="row">
		<!-- List Races if found -->
		<div class="card col-md-12 mb-3">
			<!-- Races Available -->
			<div class="card-body pb-3" v-if="races.length > 0">
				<ul>
					<li class="card-text">Race Status: {{ races[currentRaceIndex].raceStatus }}</li>
					<li class="card-text">Start Time: {{ races[currentRaceIndex].startTime }}</li>
					<li class="card-text">Distance: {{ races[currentRaceIndex].distance }}</li>
					<li class="card-text">Surface: {{ races[currentRaceIndex].surface }}</li>
					<li class="card-text">Turn: {{ races[currentRaceIndex].turn }}</li>
					<li class="card-text">Course: {{ races[currentRaceIndex].course }}</li>
					<li class="card-text">Club: {{ races[currentRaceIndex].club }}</li>
					<li class="card-text">Title: {{ races[currentRaceIndex].title }}</li>
					<li class="card-text">Description: {{ races[currentRaceIndex].description }}</li>
					<li class="card-text">Stake: {{ races[currentRaceIndex].stake }}</li>
					<li class="card-text">Average MR: {{ races[currentRaceIndex].averageMR }}</li>
				</ul>

				<!-- View Races Button -->
				<button class="btn btn-primary" @click="viewHorses(racecard.itw, races[currentRaceIndex].race)">View Horses</button>
			</div>

			<!-- Races Available -->
			<div v-else>
				<p>No Races Found!</p>
			</div>
		</div>
	</div>
</template>
