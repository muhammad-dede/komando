<?php

namespace App;

use Yajra\Oci8\Eloquent\OracleEloquent as Model;
use Illuminate\Support\Facades\Cache;

class Coc extends Model 
{

    protected $table = 'coc';
    protected $fillable = ['event_id', 'tema_id', 'pemateri_id', 'admin_id', 'tanggal_jam', 'judul', 'deskripsi',
                            'status', 'pusat'];
    protected $dates = ['tanggal_jam'];
    public $timestamps = true;
    public $arrOrgeh = [];

    public function attendants()
    {
        return $this->hasMany('App\Attendant', 'coc_id', 'id');
    }

    public function event()
    {
        return $this->belongsTo('App\Event', 'event_id', 'id');
    }

    public function tema()
    {
        return $this->belongsTo('App\Tema', 'tema_id', 'id');
    }

    public function temaUnit()
    {
        return $this->belongsTo('App\Tema', 'tema_id_unit', 'id');
    }

    public function attachment()
    {
        return $this->hasMany('App\Attachment', 'coc_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment', 'coc_id', 'id');
    }

    public function pemateri()
    {
//        return $this->belongsTo('App\User', 'pemateri_id', 'id');
        // return $this->belongsTo('App\StrukturJabatan', 'pernr_pemateri', 'pernr');
        return $this->belongsTo('App\User', 'nip_pemateri', 'nip');
    }

    public function admin()
    {
        return $this->belongsTo('App\User', 'admin_id', 'id');
    }

    public function setEventIdAttribute($attrValue){
        $this->attributes['event_id'] = (string) $attrValue;
    }

    public function setTemaIdAttribute($attrValue){
        $this->attributes['tema_id'] = (string) $attrValue;
    }

    public function setTemaIdUnitAttribute($attrValue){
        $this->attributes['tema_id_unit'] = (string) $attrValue;
    }

    public function setPemateriIdAttribute($attrValue){
        $this->attributes['pemateri_id'] = (string) $attrValue;
    }

    public function setAdminIdAttribute($attrValue){
        $this->attributes['admin_id'] = (string) $attrValue;
    }

    public function setMateriIdAttribute($attrValue){
        $this->attributes['materi_id'] = (string) $attrValue;
    }

    public function setPedomanPerilakuIdAttribute($attrValue){
        $this->attributes['pedoman_perilaku_id'] = (string) $attrValue;
    }

    public function companyCode(){
        return $this->belongsTo('App\CompanyCode', 'company_code', 'company_code');
    }

    public function businessArea(){
        return $this->belongsTo('App\BusinessArea', 'business_area', 'business_area');
    }

    public function organisasi(){
        return $this->belongsTo('App\StrukturOrganisasi', 'orgeh', 'objid');
    }

    public function checkAtendant($id){
        return $this->attendants()->where('user_id',$id)->first();
    }

    public function gallery(){
        return $this->hasMany('App\GalleryCoc', 'coc_id', 'id');
    }

    public function getPegawaiUnit(){
        $arr_orgeh = StrukturOrganisasi::where('status','ACTV')->where('sobid', $this->orgeh)->whereNull('level')->get();

        foreach($arr_orgeh as $org){
//            echo $org->objid."<br>";
            array_push($this->arrOrgeh, $org->objid);
            $this->findChild($org->objid);
        }

        $pegawai = StrukturJabatan::whereIn('orgeh', $this->arrOrgeh)->get();

        return $pegawai;
    }

    public function getJmlPegawaiUnit(){
        return $this->getPegawaiUnit()->count();
    }

    public function findChild($id_org)
    {
        $arr_orgeh = StrukturOrganisasi::where('status','ACTV')->where('sobid', $id_org)->whereNull('level')->get();

        foreach ($arr_orgeh as $org) {
//            echo $org->objid."<br>";
            array_push($this->arrOrgeh, $org->objid);
            $this->findChild($org->objid);
        }
    }

    public function pedomanPerilaku(){
//        return $this->belongsTo('App\PedomanPerilaku', 'pedoman_perilaku_id','id');
        return $this->belongsTo('App\DoDont', 'pedoman_perilaku_id','id');
    }

    public function jenis(){
        return $this->belongsTo('App\JenisCoc','jenis_coc_id','id');
    }

    public function leader(){
        // return $this->belongsTo('App\StrukturJabatan','pernr_leader','pernr');
        return $this->belongsTo('App\User','nip_leader','nip');
    }

    public function materi()
    {
        return $this->belongsTo('App\Materi','materi_id','id');
    }

    public static function getJumlahCheckin($admin_id, $tgl_awal, $tgl_akhir, $jenis_coc_id){
        $total = 0;
        $user = User::findOrFail($admin_id);
        $coc_list = $user->coc()
                    ->where('status', 'COMP')
                    ->whereDate('tanggal_jam','>=',$tgl_awal->format('Y-m-d'))
                    ->whereDate('tanggal_jam','<=',$tgl_akhir->format('Y-m-d'))
                    ->where('jenis_coc_id', $jenis_coc_id)
                    ->get();
        foreach ($coc_list as $coc){
            $jml = $coc->attendants->count();
            $total = $total + $jml;
        }

        return $total;
    }

    public static function getJumlahBacaMateri($admin_id, $tgl_awal, $tgl_akhir, $jenis_coc_id){
        $total = 0;
        $user = User::findOrFail($admin_id);
        $coc_list = $user->coc()
            ->where('status', 'COMP')
            ->whereDate('tanggal_jam','>=',$tgl_awal->format('Y-m-d'))
            ->whereDate('tanggal_jam','<=',$tgl_akhir->format('Y-m-d'))
            ->where('jenis_coc_id', $jenis_coc_id)
            ->get();
        if($coc_list->count() !=0) {
            foreach ($coc_list as $coc) {
//            $jml = $coc->materi->reader->count();
                if($coc->materi!=null) {
                    $jml = $coc->materi->getReaderFromAdmin($admin_id)->unique('nip')->count();
                    $total = $total + $jml;
                }
            }
        }
        else $total = 0;

        return $total;
    }

    public static function getJmlCoc(){
        if( Cache::has('jml_coc') ) {
            return Cache::get('jml_coc');
        }
        $jml = Coc::whereYear('created_at','=',date('Y'))->count();
        Cache::put( 'jml_coc', $jml, 60 );

        return $jml;
    }

    public function ritualCoc(){
        return $this->hasMany('App\RitualCoc','coc_id','id');
    }

    public function realisasi(){
        return $this->hasOne('App\RealisasiCoc', 'coc_id', 'id');
    }

    public static function updateNIPLeader(){
        $coc = Coc::whereNull('nip_leader')->orderBy('id','desc')->take(100000)->get();
        // dd($coc);
        foreach ($coc as $c){
            $pernr_leader = $c->pernr_leader;
            $leader = StrukturJabatan::where('pernr',$pernr_leader)->first();
            if($leader!=null){
                $c->nip_leader = $leader->nip;
                $c->nip_pemateri = $leader->nip;
                $c->save();

                echo $c->id." - ".$c->nip_leader." - ".$c->tanggal_jam."\n";
            }
        }
    }
}
