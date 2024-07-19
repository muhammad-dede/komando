<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KomisarisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(['username' => 'amien.sunaryadi', 'name'=>'Amien Sunaryadi', 'email'=>'amien.sunaryadi@pln.co.id', 'password'=>Hash::make('123'), 'active_directory'=>'1', 'status'=>'ACTV', 'domain'=>'pusat', 'username2'=>'pusat\\amien.sunaryadi']);
        DB::table('users')->insert(['username' => 'suahasil.nazara', 'name'=>'Suahasil Nazara', 'email'=>'suahasil.nazara@pln.co.id', 'password'=>Hash::make('123'), 'active_directory'=>'1', 'status'=>'ACTV', 'domain'=>'pusat', 'username2'=>'pusat\\suahasil.nazara']);
        DB::table('users')->insert(['username' => 'rida.mulyana', 'name'=>'Rida Mulyana', 'email'=>'rida.mulyana@pln.co.id', 'password'=>Hash::make('123'), 'active_directory'=>'1', 'status'=>'ACTV', 'domain'=>'pusat', 'username2'=>'pusat\\rida.mulyana']);
        DB::table('users')->insert(['username' => 'moh.ikhsan', 'name'=>'Mohamad Ikhsan', 'email'=>'moh.ikhsan@pln.co.id', 'username2'=>'pusat\\moh.ikhsan', 'password'=>Hash::make('123'), 'active_directory'=>'1', 'status'=>'ACTV', 'domain'=>'pusat']);
        DB::table('users')->insert(['username' => 'ilya.avianti', 'name'=>'Ilya Avianti', 'email'=>'ilya.avianti@pln.co.id', 'username2'=>'pusat\\ilya.avianti', 'password'=>Hash::make('123'), 'active_directory'=>'1', 'status'=>'ACTV', 'domain'=>'pusat']);
        DB::table('users')->insert(['username' => 'murtaqi', 'name'=>'Murtaqi Syamsudin', 'email'=>'murtaqi@pln.co.id', 'username2'=>'pusat\\murtaqi', 'password'=>Hash::make('123'), 'active_directory'=>'1', 'status'=>'ACTV', 'domain'=>'pusat']);
        DB::table('users')->insert(['username' => 'deden.juhara', 'name'=>'Deden Juhara', 'email'=>'deden.juhara@pln.co.id', 'username2'=>'pusat\\deden.juhara', 'password'=>Hash::make('123'), 'active_directory'=>'1', 'status'=>'ACTV', 'domain'=>'pusat']);
        DB::table('users')->insert(['username' => 'dudy.purwagandhi', 'name'=>'Dudy Purwagandhi', 'email'=>'dudy.purwagandhi@pln.co.id', 'username2'=>'pusat\\dudy.purwagandhi', 'password'=>Hash::make('123'), 'active_directory'=>'1', 'status'=>'ACTV', 'domain'=>'pusat']);
        DB::table('users')->insert(['username' => 'eko.sulistyo', 'name'=>'Eko Sulistyo', 'email'=>'eko.sulistyo@pln.co.id', 'username2'=>'pusat\\eko.sulistyo', 'password'=>Hash::make('123'), 'active_directory'=>'1', 'status'=>'ACTV', 'domain'=>'pusat']);
        DB::table('users')->insert(['username' => 'rudy.salahuddin', 'name'=>'Mohammad Rudy Salahuddin', 'email'=>'rudy.salahuddin@pln.co.id', 'username2'=>'pusat\\rudy.salahuddin', 'password'=>Hash::make('123'), 'active_directory'=>'1', 'status'=>'ACTV', 'domain'=>'pusat']);
        DB::table('users')->insert(['username' => 'yusuf.ateh', 'name'=>'Muhammad Yusuf Ateh', 'email'=>'yusuf.ateh@pln.co.id', 'username2'=>'pusat\\yusuf.ateh', 'password'=>Hash::make('123'), 'active_directory'=>'1', 'status'=>'ACTV', 'domain'=>'pusat']);
        
    }
}
