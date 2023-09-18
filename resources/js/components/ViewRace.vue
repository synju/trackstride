<script setup>
	import {onMounted, ref} from "vue";
	import {useRouter} from "vue-router";

	const router = useRouter();

	let race = ref([]);

	onMounted(async() => {
		getRace();
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

	const getRace = async() => {
		let response = await axios.get(`/api/get_racecard/${props.itw}`);
		//console.log('response', response.data.racecard);
		const races = response.data.racecard.races;
		const raceIndex = parseInt(props.raceIndex);

		let raceData = null;
		races.forEach((race) => {
			if(race.race === raceIndex) {
				raceData = race;
			}
		});

		race.value = raceData;
	}

	const back = () => {
		router.go(-1);
	};

	const viewHorses = (itw, raceIndex) => {
		router.push(`/viewhorses/${itw}/${raceIndex}`);
	};
</script>

<template>
	<!-- Back Button -->
	<div class="row">
		<div class="col-md-12 mb-3 px-0 d-flex align-items-center">
			<button @click="back" class="btn btn-primary">Back</button>
		</div>
	</div>

	<!-- Race -->
	<div id="race" class="row">
		<h3>Race: {{ race.race }}</h3>
		<div class="card col-md-12 mb-3 pb-3">
			<!-- Race Available -->
			<div class="card-body pb-0">
				<ul>
					<li class="card-text">Race Status: {{ race.raceStatus }}</li>
					<li class="card-text">Start Time: {{ race.startTime }}</li>
					<li class="card-text">Distance: {{ race.distance }}</li>
					<li class="card-text">Surface: {{ race.surface }}</li>
					<li class="card-text">Turn: {{ race.turn }}</li>
					<li class="card-text">Course: {{ race.course }}</li>
					<li class="card-text">Club: {{ race.club }}</li>
					<li class="card-text">Title: {{ race.title }}</li>
					<li class="card-text">Description: {{ race.description }}</li>
					<li class="card-text">Stake: {{ race.stake }}</li>
					<li class="card-text">Average MR: {{ race.averageMR }}</li>
				</ul>

				<!-- View Horses Button -->
				<button class="btn btn-primary" @click="viewHorses(props.itw, props.raceIndex)">View Horses</button>
			</div>
		</div>
	</div>
</template>
