<script setup>
	import {ref} from 'vue';
	import {useRouter} from "vue-router";

	const router = useRouter();

	let searchResults = ref([]);
	let debounceTimer = null;

	const search = async() => {
		const searchQuery = document.getElementById('searchBox').value;

		clearTimeout(debounceTimer);
		debounceTimer = setTimeout(async () => {
			let searchResult = await searchHorsesAndJockeys(searchQuery);

			if(searchQuery !== "" && searchResult.length > 0) {
				//console.log("Search Result",searchResult);
				searchResults.value = await searchHorsesAndJockeys(searchQuery);
			}
			else {
				document.getElementById("searchResults").innerHTML = "";
				searchResults.value = [];
			}
		}, 1000);
	};

	const searchHorsesAndJockeys = async(searchQuery) => {
		let response = await axios.get("/api/get_all_racecards");
		let racecards = response.data.racecards.map((item) => item.racecard);

		let searchCollection = [];
		racecards.forEach((racecard) => {
			let races = racecard.races;
			races.forEach((race) => {
				let horses = race.horses;
				horses.forEach((horse) => {
					let jockeyName = horse.jockeyName.trim();
					let horseName = horse.horseName;

					if(jockeyName.toLowerCase().includes(searchQuery.toLowerCase()) || horseName.toLowerCase().includes(searchQuery.toLowerCase())) {
						let index = racecard.itw + "_" + race.race + "_" + horse.horse;
						let entry = {
							"index":index,
							"itw":racecard.itw,
							"date":racecard.date,
							"raceIndex":race.race,
							"startTime":race.startTime,
							"horseId":horse.horse,
							"horseName":horse.horseName,
							"jockeyName":horse.jockeyName.trim(),
						};
						searchCollection.push(entry);
					}
				});
			});
		});

		return searchCollection;
	};

	const clearSearch = () => {
		document.getElementById('searchBox').value = '';
		searchResults.value = [];
	}

	const viewRace = (itw, raceIndex) => {
		document.getElementById('searchBox').value = '';
		search();
		router.push(`/viewrace/${itw}/${raceIndex}`);
	};

</script>

<template>
	<div class="container-fluid px-5 pt-3">
		<!-- List Racecards Button and Search Input -->
		<div class="row mb-3">
			<!-- List Racecards Button -->
			<div class="col-md-6 mt-2 p-0">
				<router-link to="/" class="btn btn-primary">List Racecards</router-link>
			</div>

			<!-- Search -->
			<div class="col-md-6 mt-2 p-0">
				<div class="input-group">
					<input id="searchBox" type="text" class="form-control" placeholder="Search Horses and Jockeys" @input="search($event)">
				</div>
			</div>
		</div>

		<!-- Content -->
		<div id="content" class="container-fluid m-0 p-0">
			<!-- Search Results Available -->
			<div id="searchResults" class="row" v-if="searchResults.length > 0">
				<!-- Clear Search Button -->
				<div class="row">
					<div class="col-md-12 mb-3 px-0 d-flex align-items-center">
						<button @click="clearSearch()" class="btn btn-primary">Clear Search</button>
					</div>
				</div>

				<!-- Actual Search Results -->
				<h3>Search Results</h3>
				<div class="card col-md-12 mb-3" v-for="result in searchResults" :key="result.index">
					<div class="card-body">
						<ul>
							<li class="card-text">ITW: {{result.itw}}</li>
							<li class="card-text">Date: {{result.date}}</li>
							<li class="card-text">Race Number: {{result.raceIndex}}</li>
							<li class="card-text">Start Time: {{result.startTime}}</li>
							<li class="card-text">Horse: {{result.horseName}}</li>
							<li class="card-text">Jockey: {{result.jockeyName}}</li>
						</ul>

						<!-- View Race Button -->
						<button class="btn btn-primary" @click="viewRace(result.itw,result.raceIndex)">View Race</button>
					</div>
				</div>
			</div>

			<!-- No Search Results -->
			<div v-else>
				<router-view/>
			</div>
		</div>
	</div>
</template>
