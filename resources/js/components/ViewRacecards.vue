<script setup>
	import {onMounted, ref} from 'vue';
	import {useRouter} from "vue-router";

	const router = useRouter()

	let racecards = ref([]);
	let currentRacecardIndex = ref(0);
	let silks = ref([]);

	const props = defineProps({
		itw:{
			type:String,
			default:''
		}
	})

	onMounted(async() => {
		await getRacecards();
	});

	const getRacecards = async() => {
		let response = await axios.get("/api/get_all_racecards");
		racecards.value = response.data.racecards.map((item) => item.racecard);

		// SilkFileNames
		const racecardsData = response.data.racecards.map((item) => item.racecard);
		let silkFileNamesSet = new Set();
		racecardsData.forEach((racecard) => {
			if(racecard.itw === "XGD") {
				let races = racecard.races;
				races.forEach((race) => {
					let horses = race.horses;
					horses.forEach((horse) => {
						silkFileNamesSet.add(horse.silksFilename);
					});
				});
			}
		});

		// Convert the Set back to an array
		const silkFileNames = Array.from(silkFileNamesSet);
		silks.value = silkFileNames.map((fileName) => `/storage/silks/${fileName}`);
	};

	const viewRaces = (itw) => {
		router.push(`/viewraces/${itw}`);
	};

	const nextRacecard = () => {
		currentRacecardIndex.value =
			(currentRacecardIndex.value + 1) % racecards.value.length;
	};

	const prevRacecard = () => {
		currentRacecardIndex.value = (currentRacecardIndex.value - 1 + racecards.value.length) % racecards.value.length;
	};

	const handleImageError = (index) => {
		// Handle the image load error by removing the problematic image URL from silks
		silks.value.splice(index, 1);
	};
</script>

<template>
	<!-- Navigation Input for Racecards -->
	<div class="row">
		<h3>Race Cards</h3>
		<div class="col-md-12 mb-3 px-0 d-flex align-items-center">
			<input type="hidden" class="form-control" v-model="currentRacecardIndex" :min="1" :max="racecards.length"/>
			<button @click="prevRacecard" class="btn btn-primary">Previous</button>
			<span v-if="racecards.length > 0" class="px-2 fs-3">{{ racecards[currentRacecardIndex].track }}</span>
			<button @click="nextRacecard" class="btn btn-primary">Next</button>
		</div>
	</div>

	<!-- List Racecards -->
	<div id="racecards" class="row">
		<!-- Racecards Available -->
		<div class="card col-md-12 mb-3" v-if="racecards.length > 0">
			<div class="card-body">
				<!-- Racecard info -->
				<ul>
					<li class="card-text">Date: {{ racecards[currentRacecardIndex].dayOfWeek }} {{ racecards[currentRacecardIndex].day }} {{ racecards[currentRacecardIndex].month }} {{ racecards[currentRacecardIndex].year }}</li>
					<li class="card-text">ITW: {{ racecards[currentRacecardIndex].itw }}</li>
					<li class="card-text">Status: {{ racecards[currentRacecardIndex].status }}</li>
				</ul>

				<!-- View Races Button -->
				<button class="btn btn-primary" @click="viewRaces(racecards[currentRacecardIndex].itw)">View Races</button>

				<!-- Racecard Silk Images -->
				<div id="silkImages" class="mt-3" v-if="racecards[currentRacecardIndex].itw === 'XGD'">
					<h3>Silks</h3>
					<div class="racecard-silk-images mb-3">
						<img
							v-for="(silk, index) in silks"
							:src="silk"
							:key="index"
							alt="Silk Image"
							class="silk-image"
							@error="handleImageError(index)"
						/>
					</div>
				</div>
			</div>
		</div>

		<!-- No Racecards Available -->
		<div v-else>
			<p>No Racecards Found!</p>
		</div>
	</div>
</template>
