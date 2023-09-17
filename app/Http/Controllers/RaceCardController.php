<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RaceCardController extends Controller {
	public function get_all_racecards() {
		// Get racecard JSON files from "racecards" directory
		$files = Storage::disk('local')->files('racecards');

		$racecards = [];

		// Read each file JSON content.
		foreach($files as $file) {
			$contents = Storage::disk('local')->get($file);

			// Decode JSON content
			$racecardData = json_decode($contents, true);

			// Add data to the $racecards array
			$racecards[] = $racecardData;
		}

		return response()->json([
			'racecards' => $racecards
		], 200);
	}
}
