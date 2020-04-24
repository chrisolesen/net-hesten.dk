<?php

class competitions
{

	public static function signup_horse($attr = [])
	{
		global $link_new;
		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
			$attr[$key] = (int) $value;
		}
		$sql = "INSERT INTO game_data_competition_participants (participant_id, competition_id, signup_date) VALUES ({$attr['horse_id']},{$attr['competition_id']},NOW())";
		$result = $link_new->query($sql);
		return var_export($result, true);
	}
}
