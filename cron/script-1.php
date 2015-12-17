<?php
/**
 * Ce script sert à informer les utilisateurs que leurs annonces n'a pas été validé sous 5 jours
 */

require_once (dirname(__FILE__).'/../lib/autoload.php');

lauchScript1();

function lauchScript1()
{
	$managers = new Managers('pdo', PDOFactory::getMysqlConnexion());
	
	$reservationManager 	= $managers->getManagerOf('announcementreservations');
	$announcementManager 	= $managers->getManagerOf('announcements');
	$userManager			= $managers->getManagerOf('users');
	
	//On supprime les réservations non-payée
	$reservationManager->deletePassedWaitingPaiement();
	
	$listOfReservationPassed = $reservationManager->getListOfPassed();
	
	$messageMail = new Mail();
	$countRervation = 0;
	foreach($listOfReservationPassed as $reservation)
	{
		$reservation->setStateId(PaiementStates::CANCELED);
		$reservation->setUpdatedTime(time());
		$reservation->setKeyCheck(null);
		$reservationManager->save($reservation);
		
		$announce 			= $announcementManager->get($reservation->getAnnouncementId());
		$userOwner 			= $userManager->get($reservation->getUserOwnerId());
		$userSubscriber 	= $userManager->get($reservation->getUserSubscriberId());
		
		$messageMail->sendReservationSubscriberCanceled($userOwner, $userSubscriber, $announce);
		$messageMail->sendAdminReservationSubscriberCanceled($userOwner, $userSubscriber, $announce, $reservation);
		
		$countRervation++;
	}
	
	if($countRervation > 0)
		echo $countRervation . ' mail(s) d\'annulation de réservation envoyé(s) !';
}

?>