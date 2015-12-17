<?php

class InviteController extends BackController
{
	protected $_usersManager;
	protected $_contactsManager;
	protected $_contactRequestsManager;
	protected $_profilesManager;
	protected $_addressesManager;
	
	protected $_user;
	
	public function __construct(Application $app,$module, $action)
    {
		parent::__construct($app, $module, $action);
		
		$this->authenticationRedirection();
		
		$this->init();
		
		$this->displayInfoMessage();
    }
    
	public function executeIndex(HTTPRequest $request)
	{
		if($request->getExists('contactGroup'))
		{
			$contactGroup = $request->getData('contactGroup');
			$this->page->smarty()->assign('contactGroup', $contactGroup);
		}
		
		$contacts = $this->_contactsManager->getListOf($this->_user->id());
		$this->page->smarty()->assign('contacts', $contacts);
		
		$contactRequests = $this->_contactRequestsManager->getListOfTo($this->_user->id());
		$this->page->smarty()->assign('contactRequests', $contactRequests);
		
		$this->page->smarty()->assign('profilesManager', $this->_profilesManager);
		$this->page->smarty()->assign('usersManager', $this->_usersManager);
		$this->page->smarty()->assign('addressesManager', $this->_addressesManager);
		if(@$_POST['commentaire']){
			$this->page->smarty()->assign('commentaire',$_POST['commentaire']);
		} else {
			$this->page->smarty()->assign('commentaire','');
		}
		if(@$_POST['emails_liste']){
			$this->page->smarty()->assign('emails_liste',$_POST['commentaire']);
		} else {
			$this->page->smarty()->assign('emails_liste','');
		}
	}
	
	public function executeSend(HTTPRequest $request)
	{
		//var_dump($_POST);
		if(!$request->postExists('submit-form')){
			$this->app->httpResponse()->redirect('/invite');
		} else 	{
	
		$this->_userManager 	= $this->managers->getManagerOf('users'); //$_POST['useremail']
		$this->_profileManager 		= $this->managers->getManagerOf('profiles');
        
        $userId = $this->app->user()->getAttribute('id');
		$this->_user = $this->_userManager->get($userId);
		$this->_profile = $this->_profileManager->getByUserId($userId);
		//$this->user=$this->_userManager->get($_POST['id']);
		$mail = new Mail();
		$invitation = new Invite();
		
		$invitation->emails_liste=$_POST['emails_liste'];
		if(isset($_POST['sendback'])){
			$invitation->emails_liste.=",".$this->_user->getMail();
		}
		$invitation->commentaire=$_POST['commentaire'];
		
		$user = $this->_userManager->get($request->app()->httpRequest()->getData('userId'));
		$invitation->email_error=$invitation->email_sent=FALSE;
		$mail->sendInvitation($invitation, $this->_user, $this->_profile);
/*		$mail->to=$_POST['emails_liste'];
		//$mail->subject="Sujet : vous êtes invité";
		//$mail->CharSet="UTF-8";
		
        $mail->from ='no-reply@tipkin.fr'; // adresse mail du compte qui envoi
      //  $mail->FromName = "L'équipe TIPKIN"; // remplace le nom du destinateur lors de la lecture d'un email
      //  $mail->Sender='no-reply@tipkin.fr';
		$mail->subject="Vous êtes invité sur TIPKIN";
	//	$mail->content=htmlentities(htmlspecialchars($_POST['commentaire']));
 			$mail->content="Yoo".$_POST['commentaire'];
		$liste=preg_split("/[\s,]+/", $_POST['emails_liste']);
		$error_flag=FALSE;
		foreach($liste as $email){
			$mail->to=$email;
			//$mail->send();
			if($_REQUEST['error']) {
				$error_flag=TRUE;
				$email_error[]=$email;
			}
		}
		
*/
       $message='';
       if($invitation->email_error) {
       		$result=implode(', ', $invitation->email_error);
			$message = MessageBox::Error("Erreur d'envoi à $result");	
	   } 
	   if ($invitation->email_sent) {
			$result=implode(', ', $invitation->email_sent);
			$message .= MessageBox::Success("Message envoyé à $result");	 
	   }
	   $this->page->smarty()->assign('message', $message);
		$this->page->smarty()->assign('emails_liste', $_POST['emails_liste']);
		$this->page->smarty()->assign('commentaire', $_POST['commentaire']);
		
/*		$messageMail = new PHPmailer();
		$messageMail->AddAddress("fr.laugier@gmail.com");
		$messageMail->From="no-reply@tipkin.fr";
		$messageMail->Subject="Vous êtes invité sur TIPKIN";
		$messageMail->Body="COntenu de l'invitation";
		echo $messageMail->Subject;
        if(!$messageMail->Send()) {
            $_REQUEST['error'] = $messageMail->ErrorInfo; // affiche une erreur => pas toujours explicite
        }
        $messageMail->SmtpClose();
        unset($messageMail); // ferme la connexion smtp et désalloue la mémoire...
        
        		$this->to = "fr.laugier@gmail.com";
		$this->subject = 		'Nouveau mot de passe';
        $this->content = 		'Bonjour ,
								<br /><br />
								Vous avez demandé la réinitialisation de votre mot de passe sur Tipkin.
								<br /><br />
								Voici vos nouveaux identifiants de connexion :  <br />
								---------------------------- <br />
								---------------------------- 
								<br /><br />
								A tout de suite sur TIPKIN !
								<br /><br />Je possède. Tu empruntes. Nous partageons !
								<br /><br />
								L\'équipe TIPKIN.';
        $this->send();
 */       
 		}
	}
	public function executeAccept(HTTPRequest $request)
	{
		$contactRequestId = htmlspecialchars($request->getData('contactRequestId'));
		$contactRequest = $this->_contactRequestsManager->get($contactRequestId);
		
		$profile = $this->_profilesManager->getByUserId($contactRequest->getUserIdFrom());
		
		$this->page->smarty()->assign('contactRequest', $contactRequest);
		$this->page->smarty()->assign('profile', $profile);
		$this->page->smarty()->assign('usersManager', $this->_usersManager);
		
		if($request->postExists('confirm'))
		{
			$contact = new Contact();
			$contact->setUserId1($contactRequest->getUserIdFrom());
			$contact->setUserId2($contactRequest->getUserIdTo());
			$contact->setContactGroupId($contactRequest->getContactGroupId());

			$this->_contactsManager->save($contact);
			$this->_contactRequestsManager->delete($contactRequest->id());
			
			$this->app->user()->setFlash('contact-added');
			
			$this->app->httpResponse()->redirect('/contacts');
			exit();
		}
	}
	
	public function executeRefuse(HTTPRequest $request)
	{
		$contactRequestId = htmlspecialchars($request->getData('contactRequestId'));
		$contactRequest = $this->_contactRequestsManager->get($contactRequestId);
		
		$profile = $this->_profilesManager->getByUserId($contactRequest->getUserIdFrom());
		
		$this->page->smarty()->assign('contactRequest', $contactRequest);
		$this->page->smarty()->assign('usersManager', $this->_usersManager);
		$this->page->smarty()->assign('profile', $profile);
		
		if($request->postExists('confirm'))
		{
			$this->_contactRequestsManager->delete($contactRequest->id());
			
			$this->app->user()->setFlash('contact-request-refused');
			
			$this->app->httpResponse()->redirect('/contacts');
			exit();
		}
	}
	
	public function executeDelete(HTTPRequest $request)
	{
		$contactId = htmlspecialchars($request->getData('contactId'));
		$contact = $this->_contactsManager->get($contactId);
		
		$userId =  null;
		if($contact->getUserId1() == $this->_user->id())
			$userId = $contact->getUserId2();
		else
			$userId = $contact->getUserId1();
			
		$profile = $this->_profilesManager->getByUserId($userId);
		
		$this->page->smarty()->assign('contact', $contact);
		$this->page->smarty()->assign('profile', $profile);
		
		if($request->postExists('confirm'))
		{
			$this->_contactsManager->delete($contact->id());
			
			$this->app->user()->setFlash('contact-deleted');
			
			$this->app->httpResponse()->redirect('/contacts');
			exit();
		}
	}
	
	public function executeAdd(HTTPRequest $request)
	{
    	
		
		$userId 		= htmlspecialchars($request->getData('userId'));
    	$user 			= $this->_usersManager->get($userId);
    	
    	$this->page->smarty()->assign('user', $user);
    	
    	if($request->postExists('contact-group'))
    	{
    		$contactRequest = new ContactRequest();
    		
    		$contactRequest->setUserIdFrom($this->_user->id());
    		$contactRequest->setUserIdTo(htmlspecialchars($request->postData('user-id-to')));
    		$contactRequest->setContactGroupId(htmlspecialchars($request->postData('contact-group')));
    		
    		$this->_contactRequestsManager->save($contactRequest);

    		//TODO envoyer un mail
    		$userFrom 	= $this->_usersManager->get($contactRequest->getUserIdFrom());
    		$userTo		= $this->_usersManager->get($contactRequest->getUserIdTo());
    		
    		$messageMail = new Mail();
    		$messageMail->sendContactRequest($userFrom, $userTo);
    		
    		$this->app->user()->setFlash('contact-request-sent');
    		$this->app->httpResponse()->redirect('/contacts');
    		
    	}
    }
    
	private function authenticationRedirection()
	{
    	if(!$this->app->user()->isAuthenticated())
        	$this->app->httpResponse()->redirect('/login');
	}
    
    private function init()
    {
    	$this->_usersManager			= $this->managers->getManagerOf('users');
    	$this->_contactsManager			= $this->managers->getManagerOf('contacts');
    	$this->_contactRequestsManager	= $this->managers->getManagerOf('contactrequests');
    	$this->_profilesManager			= $this->managers->getManagerOf('profiles');
    	$this->_addressesManager		= $this->managers->getManagerOf('addresses');
    	
    	$userId = $this->app->user()->getAttribute('id');
		
		//Initialisation de variables
		$this->_user = $this->_usersManager->get($userId);
		
    	if($this->_user->getRoleId() == Role::ROLE_MEMBER_PRO)
		{
			$this->app->httpResponse()->redirect404();
			exit();
		}
		
		if(is_null($this->_profilesManager->getByUserId($userId)))
		{
			$this->app->httpResponse()->redirect('/invite');
			exit();
		}
    }
    
	private function displayInfoMessage()
	{
		$message = '';
		if($this->app->user()->hasFlash())
		{
			switch ($this->app->user()->getFlash()) 
			{
				case 'invite-request-sent':
				$message = 'Votre invitation a été envoyée !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'contact-added':
				$message = 'Le membre a été ajouté à votre liste de contacts !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'contact-request-refused':
				$message = 'La demande d\'ajout de contact a été supprimée !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'contact-deleted':
				$message = 'Le contact a été supprimé !';
	        	$message = MessageBox::Success($message);
				break;
			}
		}
		$this->page->smarty()->assign('message', $message);
	}
	
	
}

?>