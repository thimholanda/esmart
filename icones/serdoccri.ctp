<h1 class="titulo_pag"><?php
    if (isset($_GET['bc']) && $_GET['bc'] == 1)
        echo $rot_serbcrtit;
    else
        echo $rot_serdcrtit;
    ?>
</h1>
<form method="POST" name="serdoccri" id="serdoccri" action="serdoccri<?php if (isset($_GET['bc']) && $_GET['bc'] == 1) echo '?bc=1' ?>" class="form-horizontal">
    <div class="form-group">
        <label class="control-label col-md-1 col-sm-3" ><?= $rot_gerempcod . ":" ?></label>
        <div class="col-md-2 col-sm-3">
            <div class="col-md-9 row">
                <select class="form-control" name="gerempcod" id="gerempcod" onchange="gerempsel(this.value);"> 
                    <?php
                    foreach ($gerempcod_list as $item) {
                        if ($gerempcod = $item['valor']) {
                            ?>
                            <option value="<?= $item["valor"] ?>" selected="selected"><?= $item["rotulo"] ?> </option> 
                        <?php } else { ?>
                            <option value="<?= $item["valor"] ?>"><?= $item["rotulo"] ?> </option> 
                            <?php
                        }
                    }
                    ?> 
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-1 col-sm-3" ><?= $rot_resquacod . ":" ?></label>
        <div class="col-md-2 col-sm-3">
            <div class="col-md-9 row">
                <select class="form-control" name="serquacod" id="serquacod"> 

                    <?php foreach ($gerquacod_list as $item) { ?>
                        <option value="<?= $item["valor"] ?>" ><?= $item["valor"] ?> </option> 
                    <?php } ?> 
                </select>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-1 col-sm-3" ><?= $rot_gertiptit . ":" ?></label>
        <div class="col-md-2 col-sm-3">
            <select required="required" class="form-control" name="serdoctip" id="serdoctip" 
                    onchange="gerdomsta(this.value, '1', 'select');
                                if (this.value == 'mb' || this.value == 'bc') {
                                    $('#delimitador_data, #div_serfindat, #serdocmot_lbl, #serdocmot_cam').css('display', 'block');
                                } else if (this.value == 'ms') {
                                    $('#serdocmot_lbl, #serdocmot_cam').css('display', 'block');
                                    $('#delimitador_data, #div_serfindat').css('display', 'none');
                                } else {
                                    $('#delimitador_data, #div_serfindat, #serdocmot_lbl, #serdocmot_cam').css('display', 'none');
                                }
                                gerdommot(this.value)"> 
                <option value="" ></option> 
                <?php
                foreach ($gerdoctip_list as $item) {
                    if ($item['valor'] != 'bc') {
                        ?>
                        <option value="<?= $item["valor"] ?>" ><?= $item["rotulo"] ?> </option> 
                        <?php
                    }
                }
                ?> 
            </select>
        </div>
        <label class="control-label col-md-1 col-sm-3" ><?= $rot_resdocsta . ":" ?></label>
        <div class="col-md-2 col-sm-3">
            <select class="form-control" name="serdocsta" id="serdocsta"> 
                <option value=""></option>
                <?php
                foreach ($gerdomsta_list as $item) {
                    if ($serdocsta == $item["valor"]) {
                        $selected = 'selected = \"selected\"';
                    } else {
                        $selected = "";
                    }
                    ?>
                    <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                    <?php
                }
                ?> 
            </select>
        </div>

        <label id="serdocmot_lbl" class="control-label col-md-1 col-sm-3" style="display: none" ><?= $rot_germottit . ":" ?></label>
        <div id="serdocmot_cam" class="col-md-2 col-sm-3" style="display: none">
            <select class="form-control" name="serdocmot" id="serdocmot"> 
                <option value=""></option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-1 col-sm-3" for="serinidat"><?= $rot_gerdattit ?>:</label>
        <div class='col-md-2 col-sm-3'  style="padding-left:0; padding-right: 0;"> 
            <input  required="required" class='form-control datepicker' maxlength="10" type="text" name="serinidat" id="serinidat" value="<?= $serinidat ?? "" ?>" placeholder="<?= $for_gerdattit ?>" <?= $pro_gerdattit ?> <?= $val_gerdattit ?> />
        </div>
        <div class='col-md-1 col-sm-3' id="delimitador_data" style="padding:0; margin-left: -70px; margin-top: 4px;margin-right: -40px; display: none"> 
            <span>-</span>
        </div>
        <div class='col-md-2 col-sm-3' id="div_serfindat" style="padding-left:0; margin-left: -58px;display: none;"> 
            <input class='form-control datepicker' maxlength="10" type="text" name="serfindat" id="serfindat" value="<?= $serfindat ?? "" ?>" placeholder="<?= $for_gerdattit ?>"  <?= $pro_gerdattit ?> <?= $val_gerdattit ?> />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-1 col-sm-3" for="serdoctxt"><?= $rot_gerobstit ?>:</label>
        <div class='col-md-2 col-sm-3'  style="padding-left:0; padding-right: 0;"> 
            <input class='form-control' type="text" name="serdoctxt" id="serdoctxt" />
        </div>

        <label class="control-label col-md-1 col-sm-3" style="padding-top:0" for="serdocref"><?= $rot_serdocref ?>:</label>
        <div class='col-md-1 col-sm-3'  style="padding-left:0; padding-right: 0;"> 
            <input class='form-control' type="text" name="serdocref" id="serdocref" />
        </div>
    </div>
    <div class="col-md-7">
        <div class='col-md-2 col-sm-3'>
            <input class="form-control btn-primary" type="submit" name="sercadbtn" value="<?= $rot_gersalbot ?>">
        </div>
    </div>
</form>