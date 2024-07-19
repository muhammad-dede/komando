{{--  <div class="row">
    <div class="col-md-12">
        <div align="left">
            <img src="{{asset('assets/images/logo_pln_terbaik.png')}}" width="100"
                class="img-responsive" align="left" style="margin: 10px;margin: 30px;">
        </div>
    </div>
</div>  --}}

<div class="m-b-30">
    <h4 class="card-title">Visi Misi</h4>
    <div class="col-md-12 form-horizontal">
        <div class="form-group m-t-20 {{($errors->has('visi'))?'has-danger':''}}">
            <label for="visi" class="form-control-label">Visi</label>

            <span>
                <input type="checkbox" name="visi" checked data-plugin="switchery" data-color="#039cfd" readonly value="1"/>
            </span>
            <label for="misi" class="form-control-label" style="margin-left: 20px;">Misi</label>

            <span>
                <input type="checkbox" name="misi" data-plugin="switchery" data-color="#039cfd" value="1"/>
            </span>
        </div>
    </div>
    
</div>

<div class="m-b-30 form-group {{($errors->has('tata_nilai'))?'has-danger':''}} ">
    <h4 class="card-title">Values/Nilai <span class="text-danger">*</span></h4>
    
    <div id='label_sipp' class="text-danger text-error m-b-10" style="font-weight: bold;"></div>
    
    <div class="row">
        <div class="col-sm-4 col-xs-12">
            <div class="card card-block text-xs-center">
                <h4 class="card-title">Integritas</h4>
                <p class="card-text">Integrity</p>
                <div>
                    <input type="checkbox" name="sipp[]" {{($random_value=='4')?'checked':''}}
                            data-plugin="switchery" data-color="#039cfd" value="4"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-12">
            <div class="card card-block text-xs-center">
                <h4 class="card-title">Profesionalisme</h4>
                <p class="card-text">Professionalism</p>
                <div>
                    <input type="checkbox" name="sipp[]" {{($random_value=='2')?'checked':''}}
                            data-plugin="switchery" data-color="#039cfd" value="2"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-12">
            <div class="card card-block text-xs-center">
                <h4 class="card-title">Berkomitmen Pada Pelanggan</h4>
                <p class="card-text">Commited to Customer</p>
                <div>
                    <input type="checkbox" name="sipp[]" {{($random_value=='3')?'checked':''}}
                            data-plugin="switchery" data-color="#039cfd" value="3"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-12">
            <div class="card card-block text-xs-center">
                <h4 class="card-title">Sinergi</h4>
                <p class="card-text">Synergy</p>
                <div>
                    <input type="checkbox" name="sipp[]" {{($random_value=='1')?'checked':''}}
                            data-plugin="switchery" data-color="#039cfd" value="1"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-12">
            <div class="card card-block text-xs-center">
                <h4 class="card-title">Keunggulan</h4>
                <p class="card-text">Excellence</p>
                <div>
                    <input type="checkbox" name="sipp[]" {{($random_value=='5')?'checked':''}}
                            data-plugin="switchery" data-color="#039cfd" value="5"/>
                </div>
            </div>
        </div>

    </div>

</div>