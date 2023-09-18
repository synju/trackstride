<script setup>
	import {onMounted, ref} from "vue";
	import {useRouter} from "vue-router";

	const router = useRouter()

	let horses = ref([]);
	let pastRuns = ref([]);

	onMounted(async() => {
		getPastRuns();
	});

	const props = defineProps({
		itw:{
			type:String,
			default:''
		},
		raceIndex:{
			type:Number,
			default:1
		},
		horseId:{
			type:Number,
			default:1
		},
	});

	const getPastRuns = async() => {
		let response = await axios.get(`/api/get_racecard/${props.itw}`);
		//console.log('response', response.data.racecard);
		const races = response.data.racecard.races;
		const raceIndex = parseInt(props.raceIndex);
		const horseId = parseInt(props.horseId);

		let horsePastRuns = null
		races.forEach((race) => {
			if(race.race === raceIndex) {
				let raceHorses = race.horses;
				raceHorses.forEach((horse) => {
					if(horse.horse === horseId) {
						horsePastRuns = horse.pastRuns;
					}
				})
			}
		});

		pastRuns.value = horsePastRuns;
	}

	const formatDate = (dateString) => {
		const day = dateString.substring(0, 2);
		const month = dateString.substring(2, 5);
		const year = dateString.substring(5, 7);

		const monthMap = {
			JAN:'January',
			FEB:'February',
			MAR:'March',
			APR:'April',
			MAY:'May',
			JUN:'June',
			JUL:'July',
			AUG:'August',
			SEP:'September',
			OCT:'October',
			NOV:'November',
			DEC:'December',
		};

		const fullMonth = monthMap[month.toUpperCase()] || month;

		return `${day} ${fullMonth} ${year}`;
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

	<!-- Past Runs -->
	<div id="races" class="row">
		<!-- List Past Runs if found -->
		<h3>Past Runs</h3>
		<div class="card col-md-12 mb-3 pb-0" v-for="pastRun in pastRuns" :key="pastRun.date" v-if="pastRuns.length > 0">
			<!-- Past Runs Available -->
			<div class="card-body pb-0">
				<h4 class="card-text">Date: {{ formatDate(pastRun.dateString) }}</h4>
				<ul>
					<li class="card-text">CFCode: {{ pastRun.cfcode }}</li>
					<li class="card-text">Race Code: {{ pastRun.raceCode }}</li>
					<li class="card-text">Distance: {{ pastRun.distance }}</li>
					<li class="card-text">Jockey: {{ pastRun.jockeyName }}</li>
					<li class="card-text">Weight: {{ pastRun.weight }}</li>
					<li class="card-text">Draw: {{ pastRun.draw }}</li>
					<li class="card-text">Finished: {{ pastRun.finished }}</li>
					<li class="card-text">Time Finished: {{ pastRun.timeFinished }}</li>
					<li class="card-text">Close Odds: {{ pastRun.closeOdds }}</li>
				</ul>
			</div>
		</div>

		<!-- No Past Runs Available -->
		<div v-else>
			<p>No Past Runs Found!</p>
		</div>
	</div>
</template>
