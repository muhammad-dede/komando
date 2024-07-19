<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Materi extends Model 
{

    protected $table = 'materi';
    public $timestamps = true;
    protected $fillable = [
        'tema_id',
        'pernr_penulis',
        'judul',
        'deskripsi',
        'jenis_materi_id',
        'company_code',
        'business_area',
        'orgeh'
    ];

    protected $dates = ['tanggal'];

    public function setTemaIdAttribute($attrValue){
        $this->attributes['tema_id'] = (string) $attrValue;
    }

    public function setJenisMateriIdAttribute($attrValue){
        $this->attributes['jenis_materi_id'] = (string) $attrValue;
    }

    public function setEventIdAttribute($attrValue){
        $this->attributes['event_id'] = (string) $attrValue;
    }

    public function setOrgehAttribute($attrValue){
        $this->attributes['orgeh'] = (string) $attrValue;
    }

    public function penulis(){
        return $this->belongsTo('App\StrukturJabatan', 'pernr_penulis','pernr');
    }

    public function attachments()
    {
        return $this->hasMany('App\AttachmentMateri', 'materi_id', 'id');
    }

    public function tema()
    {
        return $this->belongsTo('App\Tema', 'tema_id', 'id');
    }

    public function coc()
    {
        return $this->hasMany('App\Coc', 'materi_id', 'id');
    }

    public function jenisMateri(){
        return $this->belongsTo('App\JenisMateri','jenis_materi_id','id');
    }

    public function businessArea(){
        return $this->belongsTo('App\BusinessArea','business_area','business_area');
    }

    public function companyCode(){
        return $this->belongsTo('App\CompanyCode','company_code','company_code');
    }

    public function strukturOrganisasi(){
        return $this->belongsTo('App\StrukturOrganisasi','orgeh','objid');
    }

    public function event(){
        return $this->belongsTo('App\Event','event_id','id');
    }

    public function reader()
    {
        return $this->hasMany('App\ReadMateri','materi_id', 'id');
    }

    public function checkReader($user_id, $coc_id){
        return $this->reader()->where('user_id',$user_id)->where('coc_id',$coc_id)->first();
    }
    
    public static function getJmlMateri(){
        if( Cache::has('jml_materi') ) {
            return Cache::get('jml_materi');
        }
        $jml = Materi::whereYear('tanggal','=',date('Y'))->get()->count();;
        Cache::put( 'jml_materi', $jml, 1440 );

//        dd($jml);
        return $jml;
    }

    public function getReaderFromAdmin($admin_id)
    {
        return $this->hasMany('App\ReadMateri','materi_id', 'id')->where('admin_id', $admin_id)->get();
    }

    public function getReaderFromCoC($coc_id)
    {
        return $this->hasMany('App\ReadMateri','materi_id', 'id')->where('coc_id', $coc_id)->get();
    }

    public function getRating(){
        // dd($this->hasMany('App\ReadMateri','materi_id', 'id')->pluck('rate_star')->avg());
        return number_format($this->hasMany('App\ReadMateri','materi_id', 'id')->pluck('rate_star')->avg(),1,'.',',');
    }

    public function getTotalRate(){
        // dd($this->numberFormatShort($this->hasMany('App\ReadMateri','materi_id', 'id')->pluck('rate_star')->count()));
        return $this->numberFormatShort($this->hasMany('App\ReadMateri','materi_id', 'id')->pluck('rate_star')->count());
    }

    public function getJmlRate($star){
        return $this->hasMany('App\ReadMateri','materi_id', 'id')->where('rate_star', $star)->count();
    }

    public function getStrJmlRate($star){
        return $this->numberFormatShort($this->hasMany('App\ReadMateri','materi_id', 'id')->where('rate_star', $star)->count());
    }

    public function getArrJmlRate(){
        
        if(Cache::has('rate_materi_'.$this->id)){
            $rate = Cache::get('rate_materi_'.$this->id);
        }
        else{
            $read_materi = $this->hasMany('App\ReadMateri','materi_id', 'id')->where('rate_star','!=',null)->get(['rate_star']); 

            $average = number_format($read_materi->pluck('rate_star')->avg(),1,'.',',');
            
            $total = $read_materi->count();
            $str_total = $this->numberFormatShort($total);
            
            $arr_rate = [];
            $arr_rate['1'] =  $read_materi->where('rate_star','1')->count();
            $arr_rate['2'] =  $read_materi->where('rate_star','2')->count();
            $arr_rate['3'] =  $read_materi->where('rate_star','3')->count();
            $arr_rate['4'] =  $read_materi->where('rate_star','4')->count();
            $arr_rate['5'] =  $read_materi->where('rate_star','5')->count();

            // $arr_rate['1'] =  0;
            // $arr_rate['2'] =  0;
            // $arr_rate['3'] =  0;
            // $arr_rate['4'] =  0;
            // $arr_rate['5'] =  0;

            $arr_str_rate = [];
            $arr_str_rate['1'] =  $this->numberFormatShort($arr_rate['1']);
            $arr_str_rate['2'] =  $this->numberFormatShort($arr_rate['2']);
            $arr_str_rate['3'] =  $this->numberFormatShort($arr_rate['3']);
            $arr_str_rate['4'] =  $this->numberFormatShort($arr_rate['4']);
            $arr_str_rate['5'] =  $this->numberFormatShort($arr_rate['5']);

            $rate = ['avg'=>$average, 'total'=>$total, 'str_total'=>$str_total, 'arr_rate'=>$arr_rate, 'arr_str_rate'=>$arr_str_rate];
            
            // jika materi lebih lama dari 1 minggu, cache disimpan selamanya
            if(Carbon::parse($this->tanggal) < Carbon::now()->subDays(7)){
                Cache::forever('rate_materi_'.$this->id, $rate);
            }
            else{
                Cache::put('rate_materi_'.$this->id, $rate, 60);
            }
            // Cache::forever('rate_materi_'.$this->id, $rate);
        }

        return $rate;
    }

    public function calcArrJmlRate(){
            $read_materi = $this->hasMany('App\ReadMateri','materi_id', 'id')->where('rate_star','!=',null)->get(['rate_star']); 

            $average = number_format($read_materi->pluck('rate_star')->avg(),1,'.',',');
            
            $total = $read_materi->count();
            $str_total = $this->numberFormatShort($total);
            
            $arr_rate = [];
            $arr_rate['1'] =  $read_materi->where('rate_star','1')->count();
            $arr_rate['2'] =  $read_materi->where('rate_star','2')->count();
            $arr_rate['3'] =  $read_materi->where('rate_star','3')->count();
            $arr_rate['4'] =  $read_materi->where('rate_star','4')->count();
            $arr_rate['5'] =  $read_materi->where('rate_star','5')->count();

            $arr_str_rate = [];
            $arr_str_rate['1'] =  $this->numberFormatShort($arr_rate['1']);
            $arr_str_rate['2'] =  $this->numberFormatShort($arr_rate['2']);
            $arr_str_rate['3'] =  $this->numberFormatShort($arr_rate['3']);
            $arr_str_rate['4'] =  $this->numberFormatShort($arr_rate['4']);
            $arr_str_rate['5'] =  $this->numberFormatShort($arr_rate['5']);

            $rate = ['avg'=>$average, 'total'=>$total, 'str_total'=>$str_total, 'arr_rate'=>$arr_rate, 'arr_str_rate'=>$arr_str_rate];
            Cache::put('rate_materi_'.$this->id, $rate, 5);
            // Cache::forever('rate_materi_'.$this->id, $rate);
            
    }

    public function getArrStrJmlRate(){
        // $read_materi = $this->hasMany('App\ReadMateri','materi_id', 'id');
        $arr_rate = [];
        $arr_rate['1'] =  $this->numberFormatShort($this->hasMany('App\ReadMateri','materi_id', 'id')->where('rate_star','1')->count());
        $arr_rate['2'] =  $this->numberFormatShort($this->hasMany('App\ReadMateri','materi_id', 'id')->where('rate_star','2')->count());
        $arr_rate['3'] =  $this->numberFormatShort($this->hasMany('App\ReadMateri','materi_id', 'id')->where('rate_star','3')->count());
        $arr_rate['4'] =  $this->numberFormatShort($this->hasMany('App\ReadMateri','materi_id', 'id')->where('rate_star','4')->count());
        $arr_rate['5'] =  $this->numberFormatShort($this->hasMany('App\ReadMateri','materi_id', 'id')->where('rate_star','5')->count());

        return $arr_rate;
    }

    public function getJumlahLike($format='round'){
        if($format == 'round')
            return $this->numberFormatShort($this->hasMany('App\ReadMateri','materi_id', 'id')->where('rate', '1')->count());
        elseif($format=='number')
            return number_format($this->hasMany('App\ReadMateri','materi_id', 'id')->where('rate', '1')->count(),0,',','.');
        else
            return $this->hasMany('App\ReadMateri','materi_id', 'id')->where('rate', '1')->count();
    }

    public function getJumlahDislike($format='round'){
        if($format == 'round')
            return $this->numberFormatShort($this->hasMany('App\ReadMateri','materi_id', 'id')->where('rate', '0')->count());
        elseif($format=='number')
            return number_format($this->hasMany('App\ReadMateri','materi_id', 'id')->where('rate', '0')->count(),0,',','.');
        else
            return $this->hasMany('App\ReadMateri','materi_id', 'id')->where('rate', '0')->count();
    }

    public function numberFormatShort( $n, $precision = 1 ) {
        if ($n < 900) {
            // 0 - 900
            $n_format = number_format($n, $precision);
            $suffix = '';
        } else if ($n < 900000) {
            // 0.9k-850k
            $n_format = number_format($n / 1000, $precision);
            $suffix = 'K';
        } else if ($n < 900000000) {
            // 0.9m-850m
            $n_format = number_format($n / 1000000, $precision);
            $suffix = 'M';
        } else if ($n < 900000000000) {
            // 0.9b-850b
            $n_format = number_format($n / 1000000000, $precision);
            $suffix = 'B';
        } else {
            // 0.9t+
            $n_format = number_format($n / 1000000000000, $precision);
            $suffix = 'T';
        }
        // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
        // Intentionally does not affect partials, eg "1.50" -> "1.50"
        if ( $precision > 0 ) {
            $dotzero = '.' . str_repeat( '0', $precision );
            $n_format = str_replace( $dotzero, '', $n_format );
        }
        return $n_format . $suffix;
    }

    public function getJumlahPegawaiReadMateri($arr_nip){

        // $struktur = StrukturOrganisasi::where('objid',$orgeh)->first();
        // $arr_nip = $struktur->getArrNIPPegawai();

        // Split the array into chunks of 1000 items
        $chunks = array_chunk($arr_nip, 1000);
        $nip_exist = array();

        $jml = 0;
        foreach($chunks as $chunk){
            // $jml += $this->reader()->whereIn('nip', $chunk)->count();
            $peg = $this->reader()->whereIn('nip', $chunk)->get();
            foreach ($peg as $vals) {
                if(!in_array($vals->nip, $nip_exist)){
                    $nip_exist[] = $vals->nip;
                    $jml+=1;
                }
            }
        }

        return $jml;

    }

}
