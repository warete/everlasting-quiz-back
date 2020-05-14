<?php


use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
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
				'login'		=> 'admin',
				'password'	=> password_hash('1234578', PASSWORD_BCRYPT),
			],
		];

		$users = $this->table('users');
//		$users->truncate();
		$users->insert($data)
			->saveData();
    }
}
