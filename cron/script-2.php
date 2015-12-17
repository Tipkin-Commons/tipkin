<?php
/**
 * Ce script sert à créer une requete d'ajout de demande feedback si elle n'existe pas
 */

require_once (dirname(__FILE__).'/../lib/autoload.php');

lauchScript2();

function lauchScript2()
{
	$managers = new Managers('pdo', PDOFactory::getMysqlConnexion());
	
	$reservationManager 		= $managers->getManagerOf('announcementreservations');
	$feedbacksManager 			= $managers->getManagerOf('feedbacks');
	$feedbackRequestsManager	= $managers->getManagerOf('feedbackrequests');
	$contactsManager 			= $managers->getManagerOf('contacts');
	$contactRequestsManager		= $managers->getManagerOf('contactrequests');
	$usersManager				= $managers->getManagerOf('users');
	
	$listOfPassedReservation = $reservationManager->getListOfPassedValidated();
	
	$messageMail = new Mail();
	
	foreach ($listOfPassedReservation as $reservation) 
	{
		$listOfFeddbackRequest = $feedbackRequestsManager->getByReservationId($reservation->id());
		$listOfFeddback = $feedbacksManager->getByReservationId($reservation->id());
		
		if(count($listOfFeddback) + count($listOfFeddbackRequest) == 0)
		{
			$feedbackRequest = new FeedbackRequest();
			
			$feedbackRequest->setAnnounceId($reservation->getAnnouncementId());
			$feedbackRequest->setReservationId($reservation->id());
			$feedbackRequest->setUserOwnerId($reservation->getUserOwnerId());
			$feedbackRequest->setUserSubscriberId($reservation->getUserSubscriberId());
			
			//ENVOI POUR LE PRETEUR
			$feedbackRequest->setId(null);
			$feedbackRequest->setUserAuthorId($feedbackRequest->getUserOwnerId());
			$feedbackRequestsManager->save($feedbackRequest);
			
			//Envoyer un mail pour le preteur ICI
			$messageMail->sendFeedbackRequest($usersManager->get($feedbackRequest->getUserOwnerId()), $usersManager->get($feedbackRequest->getUserSubscriberId()));
			
			//ENVOI POUR L'EMPRUNTEUR
			$feedbackRequest->setId(null);
			$feedbackRequest->setUserAuthorId($feedbackRequest->getUserSubscriberId());
			$feedbackRequestsManager->save($feedbackRequest);
			
			//Envoyer un mail pour l'emprunteur ICI
			$messageMail->sendFeedbackRequest($usersManager->get($feedbackRequest->getUserSubscriberId()), $usersManager->get($feedbackRequest->getUserOwnerId()));
			
			//On effectue la création d'un ajout de contact s'ils ne le sont pas déjà ou qu'aucune demande n'est en attente
			$contactRequest = new ContactRequest();
			$contactRequest->setContactGroupId(ContactGroups::TIPPEURS);
			$contactRequest->setUserIdFrom($reservation->getUserSubscriberId());
			$contactRequest->setUserIdTo($reservation->getUserOwnerId());
			
			if(!$contactRequestsManager->isContactRequestExist($contactRequest) 
				&& !$contactsManager->isContactExistById($contactRequest->getUserIdFrom(), $contactRequest->getUserIdTo()))
			{
				$contactRequestsManager->save($contactRequest);
				
				$userFrom 	= $usersManager->get($contactRequest->getUserIdFrom());
    			$userTo		= $usersManager->get($contactRequest->getUserIdTo());
    		
    			$messageMail->sendContactRequest($userFrom, $userTo);
			}
		}
	}
}

?>