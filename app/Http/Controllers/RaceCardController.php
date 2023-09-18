<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class RaceCardController extends Controller {
	public function loadJSONRacecards() {
		// Get racecard JSON files from "racecards" directory
		$files = Storage::disk('local')->files('racecards');

		// Read each file JSON content.
		$racecards = [];
		foreach($files as $file) {
			$contents = Storage::disk('local')->get($file);

			// Decode JSON content
			$racecardData = json_decode($contents, true);

			// Add data to the $racecards array
			$racecards[] = $racecardData;
		}

		return $racecards;
	}

	public function get_all_racecards() {
		$racecards = $this->loadJSONRacecards();

		return response()->json([
			'racecards' => $racecards
		], 200);
	}

	public function get_racecard($itw) {
		$racecards = $this->loadJSONRacecards();

		$racecard = null;
		foreach($racecards as $rc) {
			if($rc['racecard']['itw'] === $itw) {
				$racecard = $rc['racecard'];
				break;
			}
		}

		return response()->json([
			'racecard' => $racecard
		], 200);
	}
}
