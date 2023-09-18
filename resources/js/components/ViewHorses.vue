<script setup>
	import {onMounted, ref} from "vue";
	import {useRouter} from "vue-router";

	const router = useRouter();

	let horses = ref([]);

	onMounted(async() => {
		getHorses();
	});

	const props = defineProps({
		itw:{
			type:String,
			default:''
		},
		raceIndex:{
			type:Number,
			default:1
		}
	});

	const getHorses = async() => {
		let response = await axios.get(`/api/get_racecard/${props.itw}`);
		//console.log('response', response.data.racecard);
		const races = response.data.racecard.races;
		const raceIndex = parseInt(props.raceIndex);

		let raceHorses = null
		races.forEach((race) => {
			if(race.race === raceIndex) {
				raceHorses = race.horses;
			}
		});

		horses.value = raceHorses;
	}

	const viewPastRuns = (itw, raceIndex, horseId) => {
		router.push(`/viewPastRuns/${itw}/${raceIndex}/${horseId}`);
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

	<!-- Horses -->
	<div id="races" class="row">
		<!-- List Horses if found -->
		<h3>Horses</h3>
		<div class="card col-md-12 mb-3 pb-3" v-for="horse in horses" :key="horse.horse" v-if="horses.length > 0">
			<!-- Horses Available -->
			<div class="card-body pb-0">
				<h4 class="card-text">No {{ horse.horseNo }}: {{ horse.horseName }}</h4>
				<ul>
					<li class="card-text">Draw: {{ horse.draw }}</li>
					<li class="card-text">Jockey: {{ horse.jockeyName }}</li>
					<li class="card-text">Trainer: {{ horse.trainerName }}</li>
					<li class="card-text">Weight: {{ horse.weight }}</li>
				</ul>

				<!-- View Races Button -->
				<button class="btn btn-primary" @click="viewPastRuns(props.itw, props.raceIndex, horse.horse)">View Past Runs</button>
			</div>
		</div>

		<!-- No Horses Available -->
		<div v-else>
			<p>No Horses Found!</p>
		</div>
	</div>
</template>
