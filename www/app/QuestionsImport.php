<?php
namespace App;

use Zttp\Zttp;
use RedBeanPHP\R as R;

/**
 * Class QuestionsImport
 * @package App
 */
class QuestionsImport
{
	/** @var string  */
	const API_URL = 'https://lip2.xyz/api/millionaire.php';
	/** @var int Максимальное количество вопросов в одном запросе */
	const MAX_QUESTIONS_PER_REQUEST = 5;
	/** @var int Тип вопросов (средняя сложность) */
	const DEFAULT_QUESTIONS_TYPE = 2;

	public static function run()
	{
		$categories = R::findAll('categories');
		foreach ($categories as $category)
		{
			$questions = static::getQuestionsFromApi(intval($category['external_id']));

			static::addVersionHashesToResult($questions);

			static::addQuestionsForCategory($questions, intval($category['id']));
		}
	}

	/**
	 * @param int $typeId
	 * @param int $count
	 * @return array
	 */
	protected function getQuestionsFromApi(int $typeId, int $count = 5)
	{
		if (!$count)
		{
			return [];
		}

		$queriesCount = $count / static::MAX_QUESTIONS_PER_REQUEST;

		$questions = [];
		for ($i = 0; $i < $queriesCount; $i++)
		{
			try
			{
				$response = Zttp::get(static::API_URL, [
					'qType' => $typeId,
					'count' => $count <= static::MAX_QUESTIONS_PER_REQUEST ? $count : static::MAX_QUESTIONS_PER_REQUEST,
				]);
				if ($response->isOk())
				{
					try
					{
						$responseBody = $response->json();
					}
					catch (\Throwable $e)
					{
						$responseBody = [];
					}

					if (count($responseBody['data']))
					{
						$questions = array_merge_recursive($questions, $responseBody['data']);
					}
				}
			}
			catch (\Throwable $e)
			{
				echo '<pre>' . __FILE__ . ':' . __LINE__ . ':<br>' . print_r($e->getMessage(), true) . '</pre>';
				echo '<pre>' . __FILE__ . ':' . __LINE__ . ':<br>' . print_r($e->getTrace(), true) . '</pre>';
			}
		}

		return $questions;
	}

	/**
	 * @param $questions
	 */
	protected function addVersionHashesToResult(&$questions)
	{
		foreach ($questions as $key => $question)
		{
			$questions[$key]['version_hash'] = md5(serialize($question));
		}
	}

	/**
	 * @param array $questions
	 * @param int $categoryId
	 * @throws \RedBeanPHP\RedException\SQL
	 */
	protected function addQuestionsForCategory(array $questions, int $categoryId)
	{
		$existedQuestions = static::getExistingQuestions($questions);

		$addedCnt = 0;
		foreach ($questions as $question)
		{
			if (!array_key_exists($question['version_hash'], $existedQuestions))
			{
				$question['category_id'] = $categoryId;
				$questionId = static::addQuestion($question);
				if (intval($questionId))
				{
					$addedCnt++;
					echo '<pre>' . __FILE__ . ':' . __LINE__ . ':<br>' . print_r('Добавлен вопрос с id=' . $questionId, true) . '</pre>';
				}
				else
				{
					echo '<pre>' . __FILE__ . ':' . __LINE__ . ':<br>' . print_r('Ошибка добавления вопроса', true) . '</pre>';
				}
			}
		}
	}

	/**
	 * @param array $questionData
	 * @return int|string
	 * @throws \RedBeanPHP\RedException\SQL
	 */
	protected function addQuestion(array $questionData)
	{
		$questionDb = R::dispense(QUESTIONS_TABLE);
		$questionDb->category_id = $questionData['category_id'];
		$questionDb->text = $questionData['question'];
		$questionDb->version_hash = $questionData['version_hash'];
		$questionDb->created = date('Y-m-d H:i:s');

		foreach (array_reverse($questionData['answers']) as $i => $answer)
		{
			$questionDb['answer_' . ($i + 1)] = $answer;
		}

		return R::store($questionDb);
	}

	/**
	 * @param array $questions
	 * @return array
	 */
	protected static function getExistingQuestions(array $questions)
	{
		//Соберем хеши для запроса
		$hashes = array_map(function ($item) {
			return $item['version_hash'];
		}, $questions);

		$preparedInValues = array_combine(
			array_map(function($key) {
				return ':var_'.$key;
			}, array_keys($hashes)),
			array_values($hashes)
		);
		$sqlParts = array_map(function($key){return 'version_hash = '.$key;}, array_keys($preparedInValues));
		try
		{
			$existedQuestions = R::find(QUESTIONS_TABLE,
				implode(' or ', $sqlParts),
				$preparedInValues);
		}
		catch (\Throwable $e)
		{
			$existedQuestions = [];
			echo '<pre>' . __FILE__ . ':' . __LINE__ . ':<br>' . print_r($e->getMessage(), true) . '</pre>';
			echo '<pre>' . __FILE__ . ':' . __LINE__ . ':<br>' . print_r($e->getTrace(), true) . '</pre>';
		}

		$result = [];
		foreach ($existedQuestions as $question)
		{
			$result[$question['version_hash']] = $question;
		}

		return $result;
	}
}
