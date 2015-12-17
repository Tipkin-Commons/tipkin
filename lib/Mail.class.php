<?php
require_once "phpmailer/class.phpmailer.php";

class Mail
{
	public $to;
	public $subject;
	public $from;
	public $adminMail;
	public $content;
	public $userPassword;
	public $attachement;
	public $attachementName;
	public $attachementContent;
	public $serverName;
  public $senderMail;
  public $replyToMail;
  public $senderMailName;

	public function __construct()
	{
		$this->adminMail   = Tipkin\Config::get('admin-email');
    $this->senderMail  = Tipkin\Config::get('sender-email');
    $this->replyToMail = Tipkin\Config::get('replyto-email');
    $this->senderMailName = Tipkin\Config::get('sender-email-name');

    $this->senderMail = $this->senderMail ? $this->senderMail : $this->adminMail;
    $this->replyToMail = $this->replyToMail ? $this->replyToMail : $this->senderMail;
		$this->serverName  = Tipkin\Config::get('app-url');
	}

	public function send()
	{
		$this->replaceContentKeywords();

		$mail = new PHPmailer();
    // $mail->IsSMTP();
    // $mail->SMTPDebug=true;    //permet de voir les erreurs si ça ne fonctionne pas
    $mail->Host=ini_get('SMTP'); // Connexion au serveur SMTP
    $mail->Port = 25;
    $mail->isSendMail();

    // $mail->SMTPAuth = true; // Cette partie est optionnelle si le serveur SMTP n'a pas besoin d'authentification
    // $mail->Username = 'postmaster[at]monsite.e4y.fr'; // mettre l'adresse email que founit l'hébergeur
    // $mail->Password = 'monMotDePasse'; // le mot de passe pour se connecter à votre boite mail sur l'hébergeur



    $mail->IsHTML(true); // Permet d'écrire un mail en HTML (=> conversion des balises
    $mail->CharSet = 'UTF-8'; // évite d'avoir des caractères chinois :)

		$from = $this->from;

		if(empty($from)){
			$mail->From =$this->senderMail; // adresse mail du compte qui envoi
			$mail->FromName = $this->senderMailName; // remplace le nom du destinateur lors de la lecture d'un email
		}
		else {
			$mail->From = $from;
		}
    	$mail->AddReplyTo($this->replyToMail);

    	$forceDelivery = Tipkin\Config::get('delivery-email');
    	if (empty($forceDelivery))
			$adresses = explode(",", $this->to);
    	else
    		$adresses = array($forceDelivery);
		foreach($adresses as $adress){
			$mail->AddAddress($adress); // adresse du destinataire, plusieurs adresses possibles en même temps !
		}
		
        //$mail->AddReplyTo('postmaster[at]monsite.e4y.fr'); // renvoi une copie de l'email au destinateur, fonctionnalité pas toujours opérationnelle
        $mail->Subject=$this->subject; // l'entête = nom du sujet
        $mail->Body=$this->content; // le corps = le message en lui-même, codé en HTML si vous voulez
        //$mail->AltBody="This is text only alternative body."; // corps du message à afficher si le HTML n'est pas accepter par celui qui lit le message
        if(!$mail->Send()) {
            $_REQUEST['error'] = $mail->ErrorInfo; // affiche une erreur => pas toujours explicite
        }
        $mail->SmtpClose();
        unset($mail); // ferme la connexion smtp et désalloue la mémoire...
	}
	
	public function showTest()
	{
		$this->replaceContentKeywords();
		
		echo 'Sujet : ' . $this->subject . '"<br /><br />';
		echo 'A : ' . $this->to . '<br /><br />';
		echo $this->content;
	}
	
	private function getAdditionnalHeaders()
	{
		if(empty($this->from))
		{
			$contactMail = "no-reply@tipkin.fr";
			$contactNom = "L'équipe TIPKIN";
			$headers  = 'From: "=?UTF-8?B?' . base64_encode($contactNom) . '?=" <' . $contactMail . '>'."\r\n";
		}
		else
		{
			$contactMail = $this->from;
			$headers  = 'From: ' . $this->from ."\r\n";
		}
		$headers .= 'Return-Path: <' . $contactMail . '>'."\r\n";
		
		if(!is_null($this->attachement))
		{
			$boundary = "Message-Boundary";

  			$attached_file = file_get_contents($this->attachement); //file name ie: ./image.jpg
  			$attached_file = chunk_split(base64_encode($attached_file));

  			$attached 	= "\n\n" . '--' .$boundary . "\n";
  			$attached  .= 'Content-Type: application/pdf; name="'.$this->attachementName.'"' . "\r\n";
  			$attached  .= 'Content-Transfer-Encoding: BASE64' . "\r\n";
  			$attached  .= 'Content-Disposition: attachment; filename="'.$this->attachementName.'"' . "\r\n\n";
  			$attached  .= $attached_file . "--" . $boundary . "--";

  			$this->attachementContent = $attached;
  			
  			$headers .= 'Content-Type: multipart/mixed; boundary="' . $boundary . '"' ."\r\n\n";
  			$headers .= 'MIME-Version: 1.0' . "\n";
  			 
  			$headers .= "--". $boundary . "\n";
  			$headers .= 'Content-Type: text/html; charset="utf-8"' . "\n";

		}
		else
		{
			$headers .= 'Content-Type: text/html; charset="UTF-8"' . "\n";
			$headers .= 'MIME-Version: 1.0' . "\n";
			$headers .= 'Content-Transfer-Encoding: 8BIT' . "\n";
			
		}
		
		return $headers;
	}
	
	private function replaceContentKeywords()
	{
		$patterns = $replacements = array();
		//$patterns[0]  = '/##expe-email##/';					$replacements[0] = $this->Expediteur->Email;
		
		$this->content = preg_replace($patterns, $replacements, $this->content);
	}
	/**
	 * Send the new password
	 *
	 * @param Users $user
	 * @param string $newPassword The plain text new password
	 */
	public function sendNewPassword(Users $user, $newPassword)
	{
		$this->to = $user->getMail();
		$this->subject = 		'Nouveau mot de passe';
        $this->content = 		'Bonjour ' . $user->getUsername() . ',
								<br /><br />
								Vous avez demandé la réinitialisation de votre mot de passe sur Tipkin.
								<br /><br />
								Voici vos nouveaux identifiants de connexion :  <br />
								---------------------------- <br />
								Mail : ' . $user->getMail() . ' <br />
								Pseudo : ' . $user->getUsername() . ' <br />
								Mot de passe : ' . $newPassword . ' <br />
								----------------------------
								<br /><br />
								A tout de suite sur TIPKIN !
								<br /><br />Je possède. Tu empruntes. Nous partageons !
								<br /><br />
								L\'équipe TIPKIN.';
        $this->send();
	}
	
	public function sendRegistrationInfo(Users $user, $newPassword = null)
	{
		$this->to = $user->getMail();
		$this->subject = 		'Vos identifiants de connexion';
        $this->content = 		'Bonjour,
        						 <br /><br />
        						 Bienvenue sur la plateforme TIPKIN.
        						 <br /><br />
        						 Voici vos identifiants de connexion :
        						 <br /><br />
        						 <table>
        						 	<tr>
        						 		<td>
        						 			- Email :
        						 		</td>
        						 		<td>
        						 			<b>' . $user->getMail() . '</b>
        						 		</td>
        						 	</tr>
        						 	<tr>
        						 		<td>
        						 			- Nom d\'utilisateur :
        						 		</td>
        						 		<td>
        						 			<b>' . $user->getUsername() . '</b>
        						 		</td>
        						 	</tr>
        						 	<tr>
        						 		<td>
        						 			- Mot de passe :
        						 		</td>
        						 		<td>
        						 			<b>' . ((is_null($newPassword))?'(inchangé)':$newPassword) . '</b>
        						 		</td>
        						 	</tr>
        						 </table>
        						 <br />
        						 Cordialement,
        						 <br />
        						 L\'équipe TIPKIN.
        						 ';
        $this->send();
	}
	
	public function sendRegistrationProInfo(Users $user, $newPassword)
	{
		$this->to = $user->getMail();
		$this->subject = 		'Bienvenue sur TIPKIN.FR !';
        $this->content = 		'
								Bonjour ' . $user->getUsername() . ',
        						<br /><br />
        						Bienvenue sur la plateforme TIPKIN.
        						<br />
								Votre inscription a été prise en compte. <br />
								L’équipe procédera à l’activation de votre compte dès la validation de votre abonnement.
								<br /><br />
								Nous vous recommandons de conserver cet e-mail qui contient vos informations de connexion.
								<br /><br />
								---------------------------- <br />
								Pseudo : ' . $user->getUsername() . ' <br />
								Mot de passe : ' . $newPassword . ' <br />
								----------------------------
								<br /><br />
								Ces informations sont nécessaires à votre connexion sur Tipkin.fr <br />
								N\'hésitez pas à nous envoyer un email pour toute question sur l\'utilisation du site.
								<br /><br />
								A tout de suite sur TIPKIN !
								<br /><br />
								Je possède. Tu empruntes. Nous partageons !
								<br /><br />
								L\'équipe TIPKIN.
        						';
	}
	
	public function sendAccountDeletedByAdmin(Users $user)
	{
		$this->to = $user->getMail();
		$this->subject = 		'Votre compte a été supprimé';
        $this->content = 		'Bonjour,
        						 <br /><br />
        						 Suite à une décision de l\'équipe d\'administration TIPKIN, votre compte a été supprimé de notre base de données.
        						 <br /><br />
        						 Cordialement,
        						 <br />
        						 L\'équipe TIPKIN.
        						 ';
        $this->send();
	}
	
	public function sendAccountDisabled(Users $user)
	{
		$this->to = $user->getMail();
		$this->subject = 		'Votre compte a été désactivé';
        $this->content = 		'Bonjour,
        						 <br /><br />
        						 Suite à une décision de l\'équipe d\'administration TIPKIN, votre compte a été désactivé.
        						 <br /><br />
        						 Cordialement,
        						 <br />
        						 L\'équipe TIPKIN.
        						 ';
        $this->send();
	}
	
	public function sendAccountEnabled(Users $user)
	{
		$this->to = $user->getMail();
		$this->subject = 		'Votre compte vient d\'être activé';
        $this->content = 		'Bonjour,
        						 <br /><br />
        						 Suite à une décision de l\'équipe d\'administration TIPKIN, votre compte vient d\'être activé.
        						 <br /><br />
        						 Cordialement,
        						 <br />
        						 L\'équipe TIPKIN.
        						 ';
        $this->send();
	}
	
	public function sendAccountProEnabled(Users $user)
	{
		$this->to = $user->getMail();
		$this->subject = 		'Création de votre compte sur TIPKIN.FR';
        $this->content = 		'
								Bonjour ' . $user->getUsername() . ',
								<br /><br />
								Félicitations votre compte est maintenant activé !
								<br /><br />
								Vous pouvez profiter pleinement de la mise en annonce de tous vos produits, sans aucune limitation !
								<br /><br /><br /><br />
								N\'hésitez pas à nous envoyer un email pour toute question sur l\'utilisation du site.
								<br /><br />
								A tout de suite sur TIPKIN !
								<br /><br />
								Je possède. Tu empruntes. Nous partageons !
								<br /><br />
								L\'équipe TIPKIN
        						';
        $this->send();
	}
	
	public function sendAnnouncePendingValidation(Announcement $announce, Users $user)
	{
		$adminInterfaceUrl = $this->serverName."admin/";
		
		$this->to = $this->adminMail;
		$this->subject = 		'Demande de validation d\'annonce';
        $this->content = 		'
								Bonjour,
								<b>' . $user->getUsername() . '</b> demande à valider l\'annonce de <b>' . $announce->getTitle() . '</b>
								<br /><br />
								<a href="' . $adminInterfaceUrl . '">Cliquez ici<a> pour vous connecter à l\'interface d\'administration.
        						';
        $this->send();
	}
	
	public function sendPromoteAdmin(Users $user)
	{
		$adminInterfaceUrl = $this->serverName."admin/";
		
		$this->to = $user->getMail();
		$this->subject = 		'Votre compte a été élevé au rang Administrateur';
        $this->content = 		'Bonjour,
        						 <br /><br />
        						 Suite à une décision de l\'équipe d\'administration TIPKIN, votre compte a été élevé au rang d\'Administrateur.
        						 <br /><br />
        						 <a href="' . $adminInterfaceUrl . '">Cliquez ici<a> pour vous connecter à l\'interface d\'administration.
        						 <br /><br />
        						 Cordialement,
        						 <br />
        						 L\'équipe TIPKIN.
        						 ';
        $this->send();
	}
	
	public function sendRevoqueAdmin(Users $user)
	{
		$this->to = $user->getMail();
		$this->subject = 		'Votre compte a été révoqué du rang Administrateur';
        $this->content = 		'Bonjour,
        						 <br /><br />
        						 Suite à une décision de l\'équipe d\'administration TIPKIN, votre compte a été révoqué du rang d\'Administrateur.
        						 <br />
        						 Vous êtes désormais un simple membre de la plateforme TIPKIN.
        						 <br /><br />
        						 Cordialement,
        						 <br />
        						 L\'équipe TIPKIN.
        						 ';
        $this->send();
	}
	
	public function sendVerifyEmail(Users $user, $password = null)
	{
		$this->to = $user->getMail();
		$this->subject = 		'Activation de votre compte';
        $this->content = 		'Bonjour ' . $user->getUsername() . ',
        						 <br /><br />
        						 Félicitations, vous faites maintenant partie de la communauté des Tippeurs !
        						 <br/>
        						 Pour valider définitivement votre inscription, cliquez sur le lien suivant :
        						 <br /><br />
        						 <a href="' . $this->serverName . 'valid-email/' .  $user->id() . '/' . $user->getActivationKey() . '">
        						 	' . $this->serverName . 'valid-email/' . $user->id() . '/' . $user->getActivationKey() . '
        						 </a>
        						 <br /><br />
        						 Nous vous recommandons de conserver cet e-mail qui contient vos informations de connexion.
								<br /><br />
								---------------------------- <br />
								Pseudo : ' . $user->getUsername() . ' <br />
								Mot de passe : ' . ((is_null($password))?'(inchangé)':$password) . ' <br />
								----------------------------
								<br /><br />
								Ces informations sont nécessaires à votre connexion sur Tipkin.fr <br />
								N\'hésitez pas à nous envoyer un email pour toute question sur l\'utilisation du site.
								<br /><br />
								A tout de suite sur TIPKIN !
								<br /><br />
								Je possède. Tu empruntes. Nous partageons !
								<br /><br />
        						 Cordialement,
        						 <br />
        						 L\'équipe TIPKIN.
        						 ';
        $this->send();
	}
	
	public function sendDisableAccount(Users $user, $adminMail)
	{
		$admin = $adminMail;
		
		$role = $user->getRoleId();
		if($role == Role::ROLE_MEMBER)
			$role = 'membre simple';
		elseif($role == Role::ROLE_MEMBER_PRO)
			$role = 'membre pro';
		else
			$role = 'administrateur';
		
		$this->to = $admin;
		$this->subject = 		'Demande de désinscription d\'un utilisateur';
        $this->content = 		'Bonjour,
        						 <br /><br />
        						 Un utilisateur souhaite se désinscrire de votre plateforme.
        						 <br/>
        						 Il s\'agit de <i>' . $user->getUsername() . '</i> dont le statut est <b>' . $role . '</b>.
        						 <br /><br />
        						 Vous pouvez le contacter à l\'adresse suivante : ' . $user->getMail() . '
        						 <br /><br />
        						 Cordialement,
        						 <br />
        						 La plateforme TIPKIN.
        						 ';
        $this->send();
	}
	
	public function sendAccountProCreated(Users $user)
	{
		$this->to 			= $this->adminMail;
		$this->subject = 		'Création d\'un nouveau compte PRO';
        $this->content = 		'
								Bonjour,
								<br /><br />
								L\'utilisateur <b>' . $user->getUsername() . '</b> vient de s\'inscrire en tant que membre de Tipkin.
								Son compte n\'est pas encore validé pour la mise en annonce.
								
								<a href="' . $this->serverName . 'admin/">Cliquez ici pour accéder à l\'interface d\'administration.</a>
        						';
        $this->send();
	}
	
	public function sendAccountProDisabled(Users $user)
	{
		$this->to = $user->getMail();
		$this->subject = 		'Désactivation de votre compte TIPKIN';
        $this->content = 		'
								Bonjour ' . $user->getUsername() . ',
								<br /><br />
								Nous vous informons que vous êtes arrivés au terme de votre abonnement, votre compte se trouve
								désormais désactivé.
								<br /><br />
								Vous pouvez le réactiver dès que vous le souhaiterez en contactant notre service commercial.
								<br /><br />
								A très bientôt sur TIPKIN !
								<br /><br />
								L\'équipe TIPKIN.
        						';
        $this->send();
	}
	
	public function sendReservationOwnerValidation(Users $userOwner, Users $userSubscriber, Announcement $announce, AnnouncementReservation $reservation)
	{
		$label = 'pour';
		if($reservation->getDateOption() == 'period') { $label = 'au'; }
		$date = new DateTime($reservation->getDate());
		$this->to = $userOwner->getMail();
		$this->subject = 		'Demande de réservation ';
        $this->content = 		'
								Bonjour ' . ucfirst($userOwner->getUsername()) . ',
								<br /><br />
								Votre annonce de <b>' . $announce->getTitle() . '</b> intéresse <b>' . ucfirst($userSubscriber->getUsername()) . '</b> pour la période du
								<b>' . $date->format('d-m-Y') . '</b> ' . $label . ' <b>' . $reservation->getDateOptionLabel() . '</b>.
								<br /><br />
								<a href="' . $this->serverName . 'users/member/' . $userSubscriber->id() . '">Voir son profil</a>.
								<br /><br />
								Pour vous mettre en relation et valider ce Tip  <a href="' . $this->serverName .'reservations/valid/' . $reservation->id() . '/' . $reservation->getKeyCheck() . '">cliquez ici</a>
								<br /><br />
								<b>' . ucfirst($userSubscriber->getUsername()) . '</b> pourra ainsi recevoir vos coordonnées et vous pourrez convenir
								de votre rencontre.
								<br /><br />
								Vous ne pouvez pas prêter votre <b>' . $announce->getTitle() . '</b> <a href="' . $this->serverName .'reservations/cancel/' . $reservation->id() . '/' . $reservation->getKeyCheck() . '">cliquez ici</a>
								<br /><br />
								<b>' .ucfirst($userOwner->getUsername()) . '</b>, n’oubliez pas que sans réponse de votre part dans les 5 jours la demande sera annulée.
								<br /><br />
								A très bientôt sur TIPKIN !
								<br /><br />
								Je possède. Tu empruntes. Nous partageons !
								<br /><br />
								L’équipe TIPKIN.
        						';
        $this->send();
	}
	
	public function sendReservationSubscriberValidated(Users $userOwner, Users $userSubscriber, Announcement $announce, Profile $profileOwner)
	{

		$this->to = $userSubscriber->getMail();
		$this->subject = 		'Confirmation de votre réservation';
        $this->content = 		'
								Bonjour ' . ucfirst($userSubscriber->getUsername()) . ',
								<br /><br />
								Félicitation ! Votre demande de tip  de <b>' . $announce->getTitle() . '</b> a été confirmée.
								<br />
								Voici les coordonnées de <b>' . ucfirst($userOwner->getUsername()) . '</b> :<br />
								- ' . $profileOwner->getLastname() . ' ' . $profileOwner->getFirstname() . '<br />
								- ' . $profileOwner->getPhone() . ' <br />
								- ' . $userOwner->getMail() . ' <br />
								Désormais, vous pouvez vous mettre en relation pour convenir des modalités de votre rencontre.
								<br /><br />
								Vous trouverez <a href="' . $this->serverName . 'files/contrat-etabli.pdf">en cliquant ici</a> un contrat type, nous vous invitons à en prendre connaissance et à le signer avec votre prêteur le jour de la location.
								<br /><br />
								Si le lien ci-dessus ne fonctionne pas, <a href="' . $this->serverName . 'files/contrat-etabli.rtf">cliquez ici</a>.
								<br /><br />
								N’oubliez pas de remplir l’évaluation de <b>' . ucfirst($userOwner->getUsername()) . '</b> à la fin de votre Tip.
								<br /><br />
								Nous vous souhaitons un très bel échange.
								<br /><br />
								A très bientôt sur TIPKIN !
								<br /><br />
								Je possède. Tu empruntes. Nous partageons !
        						';
        $this->send();
	}
	
	public function sendReservationOwnerValidated(Users $userOwner, Users $userSubscriber, Announcement $announce, AnnouncementReservation $reservation)
	{
		$label = 'pour';
		if($reservation->getDateOption() == 'period')
			$label = 'au';
		$platform_fee_ratio = Tipkin\Config::get('platform-fee-ratio');
    if($platform_fee_ratio > 0) {
      $reservation_price_info = 'le restant dû soit : '. ($reservation->getPrice() - round($reservation->getPrice() * $platform_fee_ratio, 2)) .'EUR';
    } else {
      $reservation_price_info = 'le montant de '. $reservation->getPrice() .'EUR';
    }
		$this->to = $userOwner->getMail();
		$this->subject = 		'Confirmation de votre réservation';
        $this->content = 		'
								Bonjour ' . ucfirst($userOwner->getUsername()) . ',
								<br /><br />
								Nous vous faisons parvenir le récapitulatif de l’emprunt que vous avez validé.
								<b>' . $announce->getTitle() . '</b> sera emprunté du <b>'. date_format(date_create($reservation->getDate()),'d/m/Y') .'</b> '. $label . ' <b>' . $reservation->getDateOptionLabel() . '</b> pour un montant de ' . $reservation->getPrice() . 'EUR.
								<br />
								' . ucfirst($userSubscriber->getUsername()) . ' devra vous régler ' . $reservation_price_info .
								'<br /><br />
								Vous avez également pu choisir de régler en sol violette, ce qui n\'est pas forcément indiqué par ce mail qui prends par défaut la valeur en € du produit.
								<br /><br />
								Vous trouverez <a href="http://beta.tipkin.fr/files/contrat-etabli.pdf">en cliquant ici</a> un contrat type, nous vous invitons à en prendre connaissance et à le signer avec votre prêteur le jour de la location.
								<br /><br />
								Si le lien ci-dessus ne fonctionne pas, <a href="http://beta.tipkin.fr/files/contrat-etabli.rtf">cliquez ici</a>.
								<br /><br />
								N’oubliez pas de remplir l’évaluation de <b>' . ucfirst($userSubscriber->getUsername()) . '</b> à la fin de votre Tip.
								<br /><br />
								Nous vous souhaitons un très bel échange.
								<br /><br />
								A très bientôt sur TIPKIN !
								<br /><br />
								Je possède. Tu empruntes. Nous partageons !
        						';
        $this->send();
	}
	
	public function sendReservationSubscriberCanceled(Users $userOwner, Users $userSubscriber, Announcement $announce)
	{
		$this->to = $userSubscriber->getMail();
		$this->subject = 		'Annulation de votre réservation';
        $this->content = 		'
								Bonjour ' . ucfirst($userSubscriber->getUsername()) . ',
								<br />
								Désolés ! Votre demande de Tip de <b>' . $announce->getTitle() . '</b> n’a pas été validée.<br />
								Elle est donc annulée et votre acompte ne sera pas débité.
								<br /><br />
								N’hésitez pas à retourner sur TIPKIN afin de trouver un nouveau <b>' . $announce->getTitle() . '</b>
								à emprunter.
								<br /><br />
								A très bientôt sur TIPKIN !
								<br /><br />
								Je possède. Tu empruntes. Nous partageons !
								<br /><br />
								L’équipe TIPKIN.
        						';
        $this->send();
	}
	
	public function sendAdminReservationSubscriberCanceled(Users $userOwner, Users $userSubscriber, Announcement $announce, AnnouncementReservation $reservation)
	{
    $platform_fee_ratio = Tipkin\Config::get('platform-fee-ratio');
    $platform_fee_extra = $platform_fee_ratio > 0 ?  'et l\'acompte ne sera pas débité' : '';

		$this->to = $this->adminMail;
		$this->subject = 		'Annulation de réservation';
    $this->content = 		'
								Bonjour,
								<br />
								Une demande de Tip de <b>' . $announce->getTitle() . '</b> n’a pas été validée.<br />
								Elle est donc annulée'. $platform_fee_extra . '.
								<br /><br />
								Référence de transation : <b>' . $reservation->getTransactionRef() . '</b>
								<br /><br />
								A très bientôt sur TIPKIN !
								<br /><br />
								Je possède. Tu empruntes. Nous partageons !
								<br /><br />
								Administration TIPKIN.
        						';
        $this->send();
	}
	
	public function sendReservationSubscriberRecap(Users $userOwner, Users $userSubscriber, Announcement $announce)
	{
		$this->to = $userSubscriber->getMail();
		$this->subject = 		'Votre demande de réservation est enregistrée';
        $this->content = 		'
								Bonjour ' . ucfirst($userSubscriber->getUsername()) . ',
								<br /><br />
								Nous avons traité votre demande de mise en relation pour le tip de <b>' . $announce->getTitle() . '</b>.
								<br />
								Dès que  <b>' . ucfirst($userOwner->getUsername()) . '</b> confirmera cette demande vous recevrez un mail
								avec ses coordonnées afin de vous mettre en relation.
								<br /><br />
								Sans nouvelle de sa part dans les 5 jours suivant ce mail, la demande sera annulée.
								<br /><br />
								A très bientôt sur TIPKIN !
								<br /><br />
								Je possède. Tu empruntes. Nous partageons !
								<br /><br />
								L’équipe TIPKIN.
        
        						';
        $this->send();
	}
	
	public function sendContactRequest(Users $userFrom, Users $userTo)
	{
		$this->to = $userTo->getMail();
		$this->subject = 		'Une demande d\'ajout de contact vient de vous être envoyé';
        $this->content = 		'
								Bonjour ' . ucfirst($userTo->getUsername()) . ',
								<br /><br />
								Vous venez de recevoir une demande pour entrer dans la tipkin-ship de <b>' . ucfirst($userFrom->getUsername()) . '</b>
								<br /><br />
								Connectez-vous ici pour la consulter :
								<br /><br />
								<a href="' . $this->serverName . 'contacts/wait">' . $this->serverName . 'contacts/wait</a>
								<br /><br />
								Cliquez ici pour voir son profil :
								<br /><br />
								<a href="' . $this->serverName. 'users/member/' . $userFrom->id() . '">' . $this->serverName . 'users/member/' . $userFrom->id() . '</a>
								<br /><br />
								A très bientôt sur TIPKIN !
								<br /><br />
								Je possède. Tu empruntes. Nous partageons !
								<br /><br />
								L’équipe TIPKIN.
        
        						';
        $this->send();
	}
	
	public function sendFeedbackRequest(Users $userFrom, Users $userTo)
	{
		$this->to = $userTo->getMail();
		$this->subject = 		'Une demande de feedback de contact vient de vous être envoyée';
        $this->content = 		'
								Bonjour ' . ucfirst($userTo->getUsername()) . ',
								<br /><br />
								Vous venez d\'effectuer un Tip avec <b>' . ucfirst($userFrom->getUsername()) . '</b>,
								afin d\'évaluer cet évènement, merci de prendre un instant et répondre à ce questionnaire.
								<br /><br />
								Connectez-vous ici pour le consulter :
								<br /><br />
								<a href="' . $this->serverName . 'feedback">' . $this->serverName . 'feedback</a>
								<br /><br />
								Cette évaluation est importante et permet d\'établir une vraie confiance entre chaque tippeur,
								n\'hésitez pas à répondre en toute objectivité et à commenter votre emprunt.
								<br /><br />
								Merci pour votre participation !
								<br /><br />
								A très bientôt sur TIPKIN !
								<br /><br />
								Je possède. Tu empruntes. Nous partageons !
								<br /><br />
								L\'équipe TIPKIN.
        
        						';
        $this->send();
	}
	
	public function sendModerationRequest()
	{
		$serveurUrl 		= $this->serverName;
		$this->to 			= $this->adminMail;
		$this->subject = 		'Nouvelle demande de modération';
        $this->content = 		'
								Bonjour,
								<br /><br />
								Une demande de modération vient d\'être effectuée.
								<br /><br />
								<a href="' . $this->serverName . 'admin/">Cliquez ici pour accéder à l\'interface d\'administration.</a>
        						';
        $this->send();
	}
	
	public function sendInvitation(Invite $invitation, Users $user, Profile $profile)
	{
 		$this->from=$user->getMail();
		$this->subject="Vous êtes invité sur TIPKIN";
 		$this->content =
								'
<p>Bonjour, <br/><br/>
								'.$profile->getFirstname()." ".$profile->getLastname()." vous invite à rejoindre sa communauté sur Tipkin :
								<br/><br/>
								<q>Si tu ne connais pas encore Tipkin, connecte-toi, et rejoints ma Tipkin-ship !<br/>
Ensemble nous pourrons partager tous nos objets.								<br />
  N'hésites plus et viens consulter mes annonces sur mon profil <a href='".$this->serverName."users/member/".$profile->getUserId()."'>".$user->getUsername()."</a><br/>
  ".$invitation->commentaire."</q><br/><br/>
								Rejoignez le mouvement de la consommation collaborative!<br />
								<br />
								A tout de suite sur <a href=".$this->serverName.">TIPKIN</a> ! <br /><br/>Je possède. Tu empruntes. Nous partageons !
								<br />
								L'équipe TIPKIN.</p>
								";
 		$email_error=FALSE;
		$invitation->explodeEmailsListe();
		
		
		
				$this->replaceContentKeywords();
		
		$mail = new PHPmailer();
//        $mail->IsSMTP();
$mail->IsSendMail();
        //$mail->SMTPDebug=true;    //permet de voir les erreurs si ça ne fonctionne pas
        
        $mail->Host=ini_get('SMTP'); // Connexion au serveur SMTP
        $mail->Port = 25;
        

        //$mail->SMTPAuth = true; // Cette partie est optionnelle si le serveur SMTP n'a pas besoin d'authentification
        //$mail->Username = 'postmaster[at]monsite.e4y.fr'; // mettre l'adresse email que founit l'hébergeur
        //$mail->Password = 'monMotDePasse'; // le mot de passe pour se connecter à votre boite mail sur l'hébergeur



        $mail->IsHTML(true); // Permet d'écrire un mail en HTML (=> conversion des balises
        $mail->CharSet = 'UTF-8'; // évite d'avoir des caractères chinois :)
        $mail->From ='no-reply@tipkin.fr'; // adresse mail du compte qui envoi
        $mail->AddReplyTo($this->from);
        $mail->FromName = "L'équipe TIPKIN"; // remplace le nom du destinateur lors de la lecture d'un email
        $mail->Subject=$this->subject; // l'entête = nom du sujet
        $mail->Body=$this->content; // le corps = le message en lui-même, codé en HTML si vous voulez
		
		//$adresses = explode(",", $this->to);
		$invitation->email_error=FALSE;
		$invitation->email_sent=FALSE;
		foreach($invitation->getEmailsliste() as $email){
			$mail->AddAddress($email); // adresse du destinataire, plusieurs adresses possibles en même temps !
			   $Syntaxe='#^[\w.-.+]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
   				if(preg_match($Syntaxe,$email))
      				$checkemail= true;
   				else
     				$checkemail= false;
			/*$checkemail=FALSE;
			if(filter_var($email, FILTER_VALIDATE_EMAIL)){
    			$checkemail=TRUE;
			}
			*/
        	if(!$mail->Send() || !$checkemail) {
            	$_REQUEST['error'] = $mail->ErrorInfo; // affiche une erreur => pas toujours explicite
				$error_flag=TRUE;
				$invitation->email_error[]=$email;
            } else {
            	$invitation->email_sent[]=$email;
            }
						
			$mail->ClearAddresses();
		}
    $mail->SmtpClose();
    unset($mail); // ferme la connexion smtp et désalloue la mémoire...
		return $email_error;
	}
}
