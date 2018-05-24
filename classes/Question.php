<?php
class Question
{
	private $id;
	private $themeId;
	private $email;
	private $text;
	private $answer;
	private $blocked;
	private $date;

	public function getQuestionById($id, $pdo)
	{
		$sql = "SELECT * FROM question WHERE id=".$id;
		$data = $pdo->query($sql);
		if ($data) {
			foreach ($data as $question) {
				$this->id = $question['id'];
				$this->themeId = $question['themeId'];
				$this->email = $question['email'];
				$this->text = $question['text'];
				$this->answer = $question['answer'];
				$this->blocked = $question['blocked'];
				$this->date = $question['date'];
				return true;
			}
		}
		return false;
	}

	public function getPublishedQuestions($themeId, $pdo)
	{
		$sql = "SELECT id FROM question WHERE themeId=".$themeId." AND answer IS NOT NULL AND blocked IS NULL";
		$data = $pdo->query($sql);
		if ($data){
			$questions = array();
			foreach ($data as $value) {
				$question = new Question();
				$question->getQuestionById($value['id'], $pdo);
				$questions[] = $question;
			}
			return $questions;
		}
		else
			return false;
	}

	public function getAllQuestions($themeId, $pdo)
	{
		$sql = "SELECT id FROM question WHERE themeId=".$themeId;
		$data = $pdo->query($sql);
		if ($data){
			$questions = array();
			foreach ($data as $value) {
				$question = new Question();
				$question->getQuestionById($value['id'], $pdo);
				$questions[] = $question;
			}
			return $questions;
		}
		else
			return false;
	}

	public function getAllUnansweredQuestions($pdo)
	{
		$sql = "SELECT id FROM question WHERE answer IS NULL ORDER BY `date`";
		$data = $pdo->query($sql);
		if ($data){
			$questions = array();
			foreach ($data as $value) {
				$question = new Question();
				$question->getQuestionById($value['id'], $pdo);
				$questions[] = $question;
			}
			return $questions;
		}
		else
			return false;
	}

	public function getUnansweredQuestions($themeId, $pdo)
	{
		$sql = "SELECT id FROM question WHERE themeId=".$themeId." AND answer IS NULL";
		$data = $pdo->query($sql);
		if ($data){
			$questions = array();
			foreach ($data as $value) {
				$question = new Question();
				$question->getQuestionById($value['id'], $pdo);
				$questions[] = $question;
			}
			return $questions;
		}
		else
			return false;
	}

	public function addQuestion($themeId, $email, $text, $pdo)
	{
		$this->themeId = $themeId;
		$this->email = $email;
		$this->text = $text;
		$sql = "INSERT INTO question (`themeId`, `email`, `text`) VALUES ('".$this->themeId."', '".$this->email."', '".$this->text."')";
		return $pdo->exec($sql);
	}

	public function getId()
	{
		return $this->id;
	}

	public function getThemeId()
	{
		return $this->themeId;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function getText()
	{
		return $this->text;
	}

	public function getAnswer()
	{
		return $this->answer;
	}

	public function getBlocked()
	{
		return $this->blocked;
	}

	public function getDate()
	{
		return $this->date;
	}

	public function deleteme($pdo)
	{
		$sql = "DELETE FROM question WHERE id=".$this->id;
		if ($pdo->exec($sql)==1) {
			return true;
		}
		else {
			return false;
		}
	}

	public function block($pdo)
	{
		$this->blocked = 1;
		$sql = "UPDATE question SET blocked=1 WHERE id=".$this->id;
		if ($pdo->exec($sql)==1) {
			return true;
		}
		else {
			return false;
		}
	}

	public function unblock($pdo)
	{
		$this->blocked = null;
		$sql = "UPDATE question SET blocked=null WHERE id=".$this->id;
		if ($pdo->exec($sql)==1) {
			return true;
		}
		else {
			return false;
		}
	}

	public function setThemeId($themeId, $pdo)
	{
		$this->themeId = $themeId;
		$sql = "UPDATE question SET themeId='".$themeId."' WHERE id=".$this->id;
		if ($pdo->exec($sql)==1) {
			return true;
		}
		else {
			return false;
		}
	}

	public function setEmail($email, $pdo)
	{
		$this->email = $email;
		$sql = "UPDATE question SET email='".$email."' WHERE id=".$this->id;
		if ($pdo->exec($sql)==1) {
			return true;
		}
		else {
			return false;
		}
	}

	public function setText($text, $pdo)
	{
		$this->text = $text;
		$sql = "UPDATE question SET `text`='".$text."' WHERE id=".$this->id;
		if ($pdo->exec($sql)==1) {
			return true;
		}
		else {
			return false;
		}
	}

	public function setAnswer($answer, $pdo)
	{
		$this->answer = $answer;
		$sql = "UPDATE question SET answer='".$answer."' WHERE id=".$this->id;
		if ($pdo->exec($sql)==1) {
			return true;
		}
		else {
			return false;
		}
	}

}

?>
