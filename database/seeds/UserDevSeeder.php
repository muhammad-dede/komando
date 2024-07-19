<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserDevSeeder extends Seeder
{

	protected $existId;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->setDevUser('root');
        $this->setDevUser('guest');
        $this->setDevUser('admin_pusat');
        $this->setDevUser('admin_unit');
        $this->setDevUser('pegawai');
        $this->setDevUser('admin_ki');
        $this->setDevUser('admin_evp');
    }

	public function getUser($role)
	{
		$user = User::whereHas('roles', function ($q) use ($role) {
			$q->whereName($role);
		})
			->where('ad_company', 'KANTOR PUSAT')
			->orWhere('ad_company', '!=', 'KANTOR PUSAT')
			->where('status', 'ACTV')
			->where('id', '!=', 1);

		if ($this->existId) {
			$user->where('id', '!=', $this->existId);
		}
		$user = $user->first();
		$this->existId = $user->id;
		return $user;
	}

	public function setDevUser($role)
	{
		$user = $this->getUser($role);
		$user->update(
			[
				'username2'	=> "pusat\\$role",
				'password'	=> Hash::make('asdf1234')
			]
		);
	}

}
