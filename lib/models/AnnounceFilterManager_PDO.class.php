<?php

class AnnounceFilterManager_PDO extends AnnounceFilterManager
{
	/* (non-PHPdoc)
	 * @see AnnounceFilterManager::getFilteredAnnouncement()
	 */
	public function getAnnouncement(AnnounceFilter $announceFilter) {

		global $tipkin_prefix;

		$where = " WHERE 1";

		if($announceFilter->getCategoryId() != "" && $announceFilter->getCategoryId() != "all")
			$where .= " AND a.CATEGORY_ID =" . (int) $announceFilter->getCategoryId();

		if($announceFilter->getSubCategoryId() != "" && $announceFilter->getSubCategoryId() != "all")
			$where .= " AND a.SUB_CATEGORY_ID =" . (int) $announceFilter->getSubCategoryId();

		if($announceFilter->getRegionId() != "all" && $announceFilter->getRegionId() != "")
			$where .= " AND a.REGION_ID =" . (int) $announceFilter->getRegionId();

		if($announceFilter->getDepartmentId() != "all" && $announceFilter->getDepartmentId() != "")
			$where .= " AND a.DEPARTMENT_ID ='" . $announceFilter->getDepartmentId() . "'";

		if($announceFilter->getZipCode() != "")
			$where .= " AND a.ZIP_CODE ='" . $announceFilter->getZipCode() . "'";

		//Filtre texte
		$where .= " AND (a.TITLE LIKE '%". $announceFilter->getFilterText() . "%' OR a.DESCRIPTION LIKE '%". $announceFilter->getFilterText() . "%')";

		//Contrainte globale pour la recherche
		$currentDate = new DateTime();
		$where .= " AND a.END_PUBLICATION_DATE > '" . $currentDate->format('Y-m-d') . "'";

		$where .= " AND a.STATE_ID =" . AnnouncementStates::STATE_VALIDATED ;
		//Fin de contrainte globale

		$query = 'SELECT a . * , IFNULL( AVG( f.MARK ) , 0 ) AS Mark '.
				 'FROM  `'.$tipkin_prefix.'announcements` AS a '.
				 'LEFT JOIN '.$tipkin_prefix.'feedbacks AS f ON a.ID = f.ANNOUNCE_ID AND f.USER_AUTHOR_ID = f.USER_SUBSCRIBER_ID '.
		         $where . ' '.
				 'GROUP BY a.ID '.
				 'ORDER BY Mark DESC, a.PUBLICATION_DATE DESC';
		//$query = 'SELECT * FROM announcements' . $where . ' ORDER BY PUBLICATION_DATE DESC';
		//echo $query; die;

		$q = $this->dao->prepare($query);
		$q->execute();

		$announces = array();

		while ($data = $q->fetch(PDO::FETCH_ASSOC))
		{
			$announces[] = new Announcement($data);
		}

		//Cette fonction permet de filtrer les utilisateurs étant membres de la communauté
		$user_id = $announceFilter->getInCommunity();
		if(!empty($user_id)){

			$query = "SELECT USER_ID_1, USER_ID_2 FROM ".$tipkin_prefix."contacts ".
					 "WHERE USER_ID_1 = '$user_id' OR USER_ID_2 = '$user_id'";

			$q = $this->dao->prepare($query);
			$q->execute();

			$users_id = array();

			while ($data = $q->fetch(PDO::FETCH_ASSOC))
			{
				if($data['USER_ID_1'] == $user_id)
					$users_id[] = $data['USER_ID_2'];
				else
					$users_id[] = $data['USER_ID_1'];
			}

			$indexToRemove = array();

			foreach ($announces as $index => $announce){
				if (!in_array($announce->getUserId(), $users_id)){
					$indexToRemove[] = $index;
				}
			}

			foreach ($indexToRemove as $index){
				unset($announces[$index]);
			}
		}

		return $announces;

	}

	/* (non-PHPdoc)
	 * @see AnnounceFilterManager::getFilteredAnnouncementPro()
	 */
	public function getAnnouncementPro(AnnounceFilter $announceFilter) {

		global $tipkin_prefix;

		$where = " WHERE 1";

		if($announceFilter->getCategoryId() != "" && $announceFilter->getCategoryId() != "all")
			$where .= " AND CATEGORY_ID =" . (int) $announceFilter->getCategoryId();

		if($announceFilter->getSubCategoryId() != "" && $announceFilter->getSubCategoryId() != "all")
			$where .= " AND SUB_CATEGORY_ID =" . (int) $announceFilter->getSubCategoryId();

		if($announceFilter->getRegionId() != "all" && $announceFilter->getRegionId() != "")
			$where .= " AND REGION_ID =" . (int) $announceFilter->getRegionId();

		if($announceFilter->getDepartmentId() != "all" && $announceFilter->getDepartmentId() != "")
			$where .= " AND DEPARTMENT_ID ='" . $announceFilter->getDepartmentId() . "'";

		if($announceFilter->getZipCode() != "")
			$where .= " AND ZIP_CODE ='" . $announceFilter->getZipCode() . "'";

		//Filtre texte
		$where .= " AND (TITLE LIKE '%". $announceFilter->getFilterText() . "%' OR DESCRIPTION LIKE '%". $announceFilter->getFilterText() . "%')";

		//Contrainte globale pour la recherche

		$where .= " AND STATE_ID =" . AnnouncementStates::STATE_VALIDATED ;
		$where .= " AND IS_PUBLISHED =" . true ;
		//Fin de contrainte globale

		$query = 'SELECT * FROM '.$tipkin_prefix.'announcements_pro' . $where . ' ORDER BY PUBLICATION_DATE DESC';
		//echo $query; die;

		$q = $this->dao->prepare($query);
		$q->execute();

		$announces = array();

		while ($data = $q->fetch(PDO::FETCH_ASSOC))
		{
			$announces[] = new AnnouncementPro($data);
		}

		return $announces;

	}

	private function sans_accent($chaine)
	{
	   $accent  ="ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ";
	   $noaccent="aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyyby";
	   return strtr(trim($chaine),$accent,$noaccent);
	}

}

?>