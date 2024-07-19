- Clone repo
- Copy `.env.example` to `.env`
- Copy `docker-compose.yml.example` to `docker-compose.yml`
- Copy `nginx/sites-available/default.local` to `nginx/sites-available/default`
- Copy `docker-compose up -d`
- Masuk ke terminal aplikasi dengan `docker exec -it komando-locl bash -l` + `cd /var/www/komando`
- Jalankan `php artisan db:seed --class=DevelopmentSeeder` (**langkah ini perlu dilakukan setiap kali image DB ter-reset atau diperbarui**, karena data yang disimpan di docker image DB tidak persistent)
- Aplikasi bisa diakses di http://localhost:8800/

@@ -19,7 +20,7 @@ pusat\m.fahmi.rizal

### Change User Password
```bash
$ docker exec -it komando-locl php artisan tinker
$ docker exec -it komando-locl php /var/www/komando/artisan tinker
>> $user = App\User::find(1);
>> $user->password = Hash::make('123');
>> $user->save();
```

## FAQ

### Setup data pertama kali

Setiap kali docker image DB direfresh (down + up atau ganti image), maka data yang tersimpan di DB akan ter-reset. Untuk itu perlu melakukan migration + inisiasi data pertama kali dengan perintah:

```bash
php artisan db:seed --class=DevelopmentSeeder
```

### Masuk ke terminal aplikasi (semacam SSH)

```bash
docker exec -it komando-locl bash -l
cd /var/www/komando
```

### Cara menjalankan automated tests

Dari folder aplikasi, jalankan:
```bash
vendor/phpunit/phpunit/phpunit --configuration=phpunit.xml
```

### Cara mengecek role

```php
// Admin pusat
// "admin_pusat" dilihat dari tabel roles
auth()->user()->hasRole('admin_pusat');
```

### Cara mengecek permission

```php
  // Cara 1: entrust, ini akan mengecek sesuai role dan permission yang diset di database
  auth()->user()->can('permission')


  // Cara 2: Laravel built in policy (authorization)
  // ini akan mengecek ke Policy yang didefine di AuthServiceProvider

  // di view
  @can('ability')

  // di Controller
  $this->authorize('ability')
```

### Mendapatkan resolusi di liquid aktif untuk atasan tertentu

```php
$atasan = User::where('nip', '123445')->firstOrFail();

app(LiquidService::class)->resolusi(User $atasan);
```



### Mendapatkan unit kerja (business area) yang available untuk user tertentu

Mendapatkan unit kerja sesuai permission yang diassign, apakah bisa:

- view all
- view unit dalam company  yang sama
- hanya view unitnya saja

```php
app(LiquidService::class)->listUnitKerja(User $user);
```





### Data Dictionary

#### Istilah Umum

| Column     | Description                                                  |
| ---------- | ------------------------------------------------------------ |
| BUKRS      | Company code                                                 |
| BUKRS=1000 | Kantor pusat                                                 |
| GESBR      | Business area                                                |
| PERNR      | Personal Number                                              |
| PLANS      | Position                                                     |
| ORGEH      | Organizational Unit                                          |
| MGRP       | Job Title Main group                                         |
| SGRP       | Job Title Sub group                                          |
| WERKS      | Personnel area                                               |
| BTRTL      | Personnel sub area                                           |
| SOBID      | Related object information (semacam Foreign Key)             |
| RELAT      | Relationship object information (Jenis relationship, related to SOBID) |

#### Jabatan Terkait Liquid

| Jabatan                                      | Condition                                                    |
| -------------------------------------------- | ------------------------------------------------------------ |
| EVP (Executive Vice President)               | BUKRS = 1000 && MGRP = '04' & SGRP = '01'                    |
| GM (General Manager)                         | BUKRS <> 1000 && MGRP = '04' & SGRP = '01'                   |
| VP (Vice President)                          | BUKRS = 1000 && MGRP = '04' & SGRP = '02'                    |
| SRM (Senior Manager)                         | BUKRS <> 1000 && MGRP = '04' & SGRP = '02'                   |
| MD (Manajer Dasar) Unit Pelaksana            | PLANS IN (MGRP = '04' & SGRP = '03')<br /> WERKS in (Select WERKS from M_LEVEL_UNIT Where level =’2’)<br /> BTRTL in (Select BTRTL from M_LEVEL_UNIT Where level =’2’) |
| MD (Manajer Dasar) Kantor Pusat & Unit Induk | PLANS IN (MGRP = '04' & SGRP = '03')<br /> WERKS in (Select WERKS from M_LEVEL_UNIT Where level =’1’)<br /> BTRTL in (Select BTRTL from M_LEVEL_UNIT Where level =’1’) |
| SPV Atas SUP (Sub Unit Pelaksana)            | PLANS IN (MGRP = '04' & SGRP = '03')<br /> WERKS in (Select WERKS from M_LEVEL_UNIT Where level =’3’)<br /> BTRTL in (Select BTRTL from M_LEVEL_UNIT Where level =’3’) |
|                                              |                                                              |
