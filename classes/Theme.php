<?php
	class Theme
	{
		private $id;
		private $name;
		private $numOfQuestions;
		private $numOfPublished;
		private $numOfUnanswered;

		public function getThemeById($id, $pdo)
		{
			$sql = "SELECT * FROM theme WHERE id=".$id;
			$data = $pdo->query($sql);
			if ($data) {
				foreach ($data as $value) {
					$this->id = $value['id'];
					$this->name = $value['theme'];
					$this->numOfQuestions = $this->getNumOfQustionsInDB($pdo);
					$this->numOfPublished = $this->getNumOfPublishedQuestionsInDB($pdo);
					$this->numOfUnanswered = $this->getNumOfUnansweredQuestionsInDB($pdo);
					return true;
				}
			}
			return false;
		}

		public function getThemeByName($name, $pdo)
		{
			$sql = "SELECT * FROM theme WHERE theme='".$name."'";
			$data = $pdo->query($sql);
			if ($data) {
				if ($data->rowCount()==0)
					return false;
				foreach ($data as $value) {
					$this->id = $value['id'];
					$this->name = $value['theme'];
				}
				return true;
			}
			return false;
		}

		public function addTheme($name, $pdo)
		{
			$this->name = $name;
			$sql = "INSERT INTO theme (theme) VALUES ('".$this->name."')";
			return $pdo->exec($sql);
		}

		public function getId()
		{
			return $this->id;
		}

		public function getName()
		{
			return $this->name;
		}

		public function getNumOfQuestions()
		{
			return $this->numOfQuestions;
		}

		public function getNumOfPublished()
		{
			return $this->numOfPublished;
		}

		public function getNumOfUnanswered()
		{
			return $this->numOfUnanswered;
		}

		public function deleteMe($pdo)
		{
			$sql = "DELETE FROM theme WHERE id=".$this->id;
			if ($pdo->exec($sql)==1) {
				return true;
			}
			else {
				return false;
			}
		}

		public function getAllThemes($pdo)
		{
			$sql = 'SELECT id FROM theme';
			$data = $pdo->query($sql);
			if ($data) {
				$allThemes = array();
				foreach ($data as $value) {
					$theme = new Theme();
					$theme->getThemeById($value['id'], $pdo);
					$allThemes[] = $theme;
				}
				return $allThemes;
			}
			else {
				return false;
			}
		}

		public function getNumOfQustionsInDB($pdo)
		{
			$sql = "SELECT * FROM question WHERE themeId=".$this->id;
			$data = $pdo->query($sql);
			if ($data) 
				return $data->rowCount();
			else
				return 0;
		}

		public function getNumOfPublishedQuestionsInDB($pdo)
		{
			$sql = "SELECT * FROM question WHERE themeId=".$this->id." AND answer IS NOT NULL AND blocked IS NULL";
			$data = $pdo->query($sql);
			if ($data)
				return $data->rowCount();
			else
				return 0;
		}

		public function getNumOfUnansweredQuestionsInDB($pdo)
		{
			$sql = "SELECT * FROM question WHERE themeId=".$this->id." AND answer IS NULL";
				$data = $pdo->query($sql);
				if ($data)
					return  $data->rowCount();
				else
					return  0;
		}

		public function setName($name, $pdo)
		{
			$this->name = $name;
			$sql = "UPDATE theme SET theme='".$name."' WHERE id=".$this->id;
			if ($pdo->exec($sql)==1) {
				return true;
			}
			else {
				return false;
			}
		}

	}
?>
