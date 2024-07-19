<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use App\Enum\KelebihanKekuranganStatus;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'username' => $faker->userName,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'active_directory' => 1
    ];
});

$factory->define(\App\Models\Liquid\KelebihanKekurangan::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence,
        'deskripsi' => $faker->sentence,
        'status' => $faker->randomElement(
			[
				KelebihanKekuranganStatus::AKTIF,
				KelebihanKekuranganStatus::TIDAK_AKTIF
			]
		),
    ];
});

$factory->define(\App\Models\Liquid\KelebihanKekuranganDetail::class, function (Faker\Generator $faker) {
    return [
        'deskripsi_kelebihan' 	=> $faker->sentence,
        'deskripsi_kekurangan'	=> $faker->sentence,
		'parent_id'				=> null,
    ];
});

$factory->define(\App\Models\Liquid\Liquid::class, function (Faker\Generator $faker) {
    $feedbackDate = $faker->dateTimeBetween('now', '+1 days');
    $penyelarasanDate = $faker->dateTimeBetween('now', '+1 days');
    $pengukuranPertamaDate = $faker->dateTimeBetween('now', '+1 days');
    $pengukuranKeduaDate = $faker->dateTimeBetween('now', '+1 days');
    $gatheringDate = $faker->dateTimeBetween('now', '+1 days');
    $interval = new DateInterval('P1D');
    return [
        'feedback_start_date' => $feedbackDate->format('Y-m-d'),
        'feedback_end_date' => $feedbackDate->add($interval)->format('Y-m-d'),
        'penyelarasan_start_date' => $penyelarasanDate->format('Y-m-d'),
        'penyelarasan_end_date' => $penyelarasanDate->add($interval)->format('Y-m-d'),
        'pengukuran_pertama_start_date' => $pengukuranPertamaDate->format('Y-m-d'),
        'pengukuran_pertama_end_date' => $pengukuranPertamaDate->add($interval)->format('Y-m-d'),
        'pengukuran_kedua_start_date' => $pengukuranKeduaDate->format('Y-m-d'),
        'pengukuran_kedua_end_date' => $pengukuranKeduaDate->add($interval)->format('Y-m-d'),
        'gathering_start_date' => $gatheringDate->format('Y-m-d'),
        'gathering_end_date' => $gatheringDate->add($interval)->format('Y-m-d'),
        'reminder_aksi_resolusi' => \App\Enum\ReminderAksiResolusi::MINGGUAN,
        'kelebihan_kekurangan_id' => 1, //TODO: use seeder
    ];
});
