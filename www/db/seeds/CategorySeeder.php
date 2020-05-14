<?php


use Phinx\Seed\AbstractSeed;

class CategorySeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
		$data = [
			[
				'name'			=> 'Легкие вопросы',
				'external_id' => 1,
			],
			[
				'name'			=> 'Вопросы средней сложности',
				'external_id' => 2,
			],
			[
				'name'			=> 'Сложные вопросы',
				'external_id' => 3,
			],
			[
				'name'			=> 'Детские вопросы',
				'external_id' => 4,
			],
		];

		$categories = $this->table('categories');
//		$categories->truncate();
		$categories->insert($data)
			->saveData();
    }
}
