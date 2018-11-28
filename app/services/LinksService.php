<?php
namespace app\services;

class LinksService {
	
	/**
	 * Gets the link for determing if a film time slot is available or not in the distributors account.
	 * 
	 * @param id $screening_venue_submission_id The id from the ScreeningVenueSubmissions model
	 * 
	 * @return string
	 */
	public static function getScreeningAvailabilityDistributor($submission_id) {
		return \PVConfiguration::getConfiguration('adbience_sites') -> distribution . '/venues/submission/' . $submission_id;
	}
	
	/**
	 * Gets the link for determing if a films time slot is available or not in the coordinator account.
	 * 
	 * @param id $screening_venue_submission_id The id from the ScreeningVenueSubmissions model
	 * 
	 * @return string
	 */
	public static function getScreeningAvailabilityCoordinator($submission_id) {
		return \PVConfiguration::getConfiguration('adbience_sites') -> coordinators . '/screenings/confirm/' . $submission_id;
	}
	
	/**
	 * Gets the url the page that gives all the information about a screening
	 * 
	 * @param id $screening_id
	 * 
	 * @return string
	 */
	public static function getScreeningInfoPage($screening_id) {
		return \PVConfiguration::getConfiguration('adbience_sites') -> main . '/screenings/view/' .$screening_id;
	}
	
	/**
	 * Gets the url the page of for RSVPing to a screening
	 * 
	 * @param id $screening_id
	 * 
	 * @return string
	 */
	public static function getScreeningTicketPage($screening_id) {
		return \PVConfiguration::getConfiguration('adbience_sites') -> main . '/screenings/purchase/' .$screening_id;
	}
}
