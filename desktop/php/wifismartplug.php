<?php
    if (!isConnect('admin')) {
        throw new Exception('{{401 - Accès non autorisé}}');
    }
    $plugin = plugin::byId('wifismartplug');
    sendVarToJS('eqType', $plugin->getId());
    $eqLogics = eqLogic::byType($plugin->getId());
    ?><div class="row row-overflow">
<div class="col-lg-2">
<div class="bs-sidebar">
<ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
<a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle "></i> {{Ajouter un dispositif}}</a>
<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
<?php
    foreach ($eqLogics as $eqLogic) {
        echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
    }
    ?>
</ul>
</div>
</div>
<div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
<legend><i class="fa fa-cog"></i>  {{Gestion}}</legend>
<div class="eqLogicThumbnailContainer">
<div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
<center>
<i class="fa fa-plus-circle" style="font-size : 5em;color:#94ca02;"></i>
</center>
<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;;color:#94ca02"><center>{{Ajouter}}</center></span>
</div>
<div class="cursor" id="bt_healthsmartplug" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
<center>
<i class="fa fa-medkit" style="font-size : 5em;color:#767676;"></i>
</center>
<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Santé}}</center></span>
</div>
</div>
<legend><i class="icon techno-cable1"></i>  {{Mes SmartPlugs}}
</legend>
<div class="eqLogicThumbnailContainer">
<?php
    foreach ($eqLogics as $eqLogic) {
        $opacity = '';
        if ($eqLogic->getIsEnable() != 1) {
            $opacity = 'opacity:0.3;';
        }
        echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
        echo "<center>";
        if ($eqLogic->getConfiguration('model', '') != '') {
            echo '<img src="plugins/wifismartplug/doc/images/' . $eqLogic->getConfiguration('model', '') . '.jpg" height="105" width="105" />';
        } else {
            echo '<img src="plugins/wifismartplug/doc/images/HS100.jpg" height="105" width="105" />';
        }
        echo "</center>";
        echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
        echo '</div>';
    }
    ?>
</div>
</div>

<div class="col-lg-10 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
<a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
<a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>

<ul class="nav nav-tabs" role="tablist">
<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Equipement}}</a></li>
<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>

</ul>

<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
<div role="tabpanel" class="tab-pane active" id="eqlogictab">
<div class="row">
<div class="col-sm-6">
<form class="form-horizontal">
<fieldset>
<legend><i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i> {{Général}}<i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i></legend>
<div class="form-group">
<label class="col-lg-3 control-label">{{Nom de l'équipement}}</label>
    <div class="col-lg-4">
    <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
    <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}"/>
    </div>
    
    </div>
    <div class="form-group">
    <label class="col-lg-3 control-label" >{{Objet parent}}</label>
    <div class="col-lg-4">
    <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
    <option value="">{{Aucun}}</option>
    <?php
    foreach (object::all() as $object) {
        echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
    }
    ?>
    </select>
    </div>
    </div>
    <div class="form-group">
    <label class="col-lg-3 control-label">{{Catégorie}}</label>
    <div class="col-lg-9">
    <?php
    foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
        echo '<label class="checkbox-inline">';
        echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
        echo '</label>';
    }
    ?>
    
    </div>
    </div>
    <div class="form-group">
    <label class="col-sm-3 control-label"></label>
    <div class="col-sm-9">
    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
    </div>
    </div>
    <legend><i class="fa fa-wrench"></i>  {{Configuration}}</legend>
    <div class="form-group">
    <label class="col-lg-3 control-label">{{Modèle}}</label>
    <div class="col-lg-4">
    <select id="sel_object" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="model">
    <option value="HS100">{{prise HS100}}</option>
    <option value="HS110">{{prise HS110}}</option>
    <option value="MAGINONSP1E">{{prise MAGINON SP-1E}}</option>
    </select>
    </div>
    </div>
    <div class="form-group">
    <label class="col-lg-3 control-label">{{Adresse IP}}</label>
    <div class="col-lg-4">
    <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="addr" placeholder="{{Adresse IP}}"/>
    </div>
    </div>
    </fieldset>
    
    </form>
    </div>
    <div class="col-sm-6">
    <center>
   <img src="plugins/wifismartplug/doc/images/HS100.jpg" id="img_mpowerModel" style="width : 500px;">
    </center>
    </div>
    </div>
    
    </div>
    <div role="tabpanel" class="tab-pane" id="commandtab">
    <table id="table_cmd" class="table table-bordered table-condensed">
    <thead>
    <tr>
    <th>{{Nom}}</th><th>{{Options}}</th><th>{{Action}}</th>
    </tr>
    </thead>
    <tbody>
    
    </tbody>
    </table>
    </div>
    </div>
    </div>
    </div>
    
    <?php include_file('desktop', 'wifismartplug', 'js', 'wifismartplug');?>
    <?php include_file('core', 'plugin.template', 'js');?>
