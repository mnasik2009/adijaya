<!DOCTYPE html>
<html>
<head>
<script type="text/javascript">
var url;
function create(){
	$('#dialog-form').dialog('open').dialog('setTitle','Tambah Data');
	$('#form').form('clear');
}
function save(){
	var partnumber = $("#partnumber").val();
	var string = $("#form").serialize();
	if(descript.length==0){
		$.messager.show({
			title:'Info',
			msg:'Maaf, partnumber tidak boleh kosong',
			timeout:2000,
			showType:'slide'
		});
		$("#partnumber").focus();
		return false();
	}

	$.ajax({
		type	: "POST",
		url		: "master/barang/simpan.php",
		data	: string,
		success	: function(data){
			$.messager.show({
				title:'Info',
				msg:data, //'Password Tidak Boleh Kosong.',
				timeout:2000,
				showType:'slide'
			});
			$('#datagrid-crud').datagrid('reload');
		}
	});
}
function hapus(){
	var row = $('#datagrid-crud').datagrid('getSelected');
	if (row){
		$.messager.confirm('Confirm','Apakah Anda akan menghapus data ini ?',function(r){
			if (r){
				$.ajax({
					type	: "POST",
					url		: "master/barang/hapus.php",
					data	: 'id='+row.partnumber,
					success	: function(data){
						$.messager.show({
							title:'Info',
							msg:data, //'Password Tidak Boleh Kosong.',
							timeout:2000,
							showType:'slide'
						});
						$('#datagrid-crud').datagrid('reload');
					}
				});
			}
		});
	}
}

function hapus1(){
	var row = $('#dgl').datagrid('getSelected');
	if (row){
		$.messager.confirm('Confirm','Apakah Anda akan menghapus data ini ?',function(r){
			if (r){
				$.ajax({
					type	: "POST",
					url		: "master/barang/hapus.php",
					data	: 'id='+row.partnumber,
					success	: function(data){
						$.messager.show({
							title:'Info',
							msg:data, //'Password Tidak Boleh Kosong.',
							timeout:2000,
							showType:'slide'
						});
						$('#dgl').datagrid('reload');
					}
				});
			}
		});
	}
}

function update(){
	var row = $('#datagrid-crud').datagrid('getSelected');
	if(row){
		$('#dialog-form').dialog('open').dialog('setTitle','Edit Data');
		$('#form').form('load',row);
	}
}

function cetakqr(){
	var row = $('#datagrid-crud').datagrid('getSelected');
	if(row){
		$('#dialog-print').dialog('open').dialog('setTitle','Print QR Data');
		$('#form-print').form('load',row);
	}
}
function cetakqr1(){
	var row = $('#dgl').datagrid('getSelected');
	if(row){
		$('#dialog-print').dialog('open').dialog('setTitle','Print QR Data');
		$('#form-print').form('load',row);
	}
}
function update1(){
	var row = $('#dgl').datagrid('getSelected');
	if(row){
		$('#dialog-form').dialog('open').dialog('setTitle','Edit Data');
		$('#form').form('load',row);
	}
}

function listb(){
		$('#dialog-list').dialog('open').dialog('setTitle','List All Spare Part Stok = 0');
	}

function fresh(){
		$('#datagrid-crud').datagrid('reload');
}

function fresh1(){
		$('#dgl').datagrid('reload');
}

function excel(){
	//$('#datagrid-crud').datagrid('toExcel','dg.xls');
	var row = $('#datagrid-crud').datagrid('getSelected');
	if(row){
			$('#datagrid-crud').load('master/barang/barang_excel.php');
	}
}
function doSearch(value){
	$('#datagrid-crud').datagrid('load',{
        cari: value
    });
}

function doSearch1(value){
	$('#dgl').datagrid('load',{
        cari: value
    });
}
$(function(){
    $('#grup').combogrid({
				panelWidth:400,
				url: 'master/barang/get_grup.php?',
				idField:'kode',
				textField:'kode',
				mode:'remote',
				fitColumns:true,
			    columns:[[
			    {field:'kode',title:'kode',width:10},
			    {field:'nama',title:'nama',width:25},
			    ]],onClickRow:function(rowData){
			                                 var val = $('#grup').combogrid('grid').datagrid('getSelected');
											 $('#grup').textbox('setValue', val.kode);
			                                }
						});
});
</script>
</head>
<body>

	<div style="margin:10px 0;"></div>

	<table id="datagrid-crud" title="  ::  Master Barang :: " class="easyui-datagrid" style="width:auto; height:auto;" url="master/barang/json.php" toolbar="#tb" pagination="true" rownumbers="true" fitColumns="true" singleSelect="true" collapsible="true">
    <thead>
        <tr>
            <th data-options="field:'partnumber'" sortable="true">Part Number</th>
            <th data-options="field:'descript'" sortable="true">Nama</th>
			<th data-options="field:'satuan'" sortable="true">Satuan</th>
            <th data-options="field:'stock'">Stok</th>
            <th data-options="field:'price'">Price</th>
			<th data-options="field:'aktif'">Aktif</th>
        </tr>
    </thead>
	</table>
    <div id="tb" style="padding:2px;height:30px;">
		<div style="float:left;">
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="create()">Tambah</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="update()">Edit</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="hapus()">Hapus</a>
      <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="fresh()">Refresh</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-sum" plain="true" onclick="listb()">List Barang</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="fa fa-qrcode fa-lg" plain="true" onclick="cetakqr()">Cetak QR</a>
			<a href="master/barang/barang_excel.php" class="easyui-linkbutton" iconCls="icon-excel" plain="true">Export To Excel</a>
		</div>
		<div style="float:right;">
        	Pencarian <input id="cari" class="easyui-searchbox" data-options="prompt:'Cari partnumber / Nama..',searcher:doSearch" style="width:200px"></input>
		</div>
	</div>
<!-- Dialog List Barang -->
<div id="dialog-list" class="easyui-dialog" style="width:800px; height:520px; padding: 10px 20px" closed="true" buttons="#list-buttons">
	<table id="dgl" class="easyui-datagrid" style="width:auto; height:auto;" url="master/barang/jsonlist.php" toolbar="#tblist" pagination="true" rownumbers="true" fitColumns="true" singleSelect="true" collapsible="true">
    <thead>
        <tr>
            <th data-options="field:'partnumber',width:20" sortable="true">Part Number</th>
            <th data-options="field:'descript',width:80" sortable="true">Nama</th>
            <th data-options="field:'stock',width:10">Stok</th>
            <th data-options="field:'price',width:30">Price</th>
			<th data-options="field:'aktif',width:30">Aktif</th>
        </tr>
    </thead>
	</table>
	<div id="tblist" style="padding:2px;height:30px;">
	<div style="float:left;">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="update1()">Edit</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="hapus1()">Hapus</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="fresh1()">Refresh</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="fa fa-qrcode fa-lg" plain="true" onclick="cetakqr1()">Cetak QR</a>
		<a href="barang_excel1.php" class="easyui-linkbutton" iconCls="icon-excel" plain="true">Export To Excel</a>
	</div>
	<div style="float:right;">
				Pencarian <input id="cari" class="easyui-searchbox" data-options="prompt:'Cari partnumber / Nama..',searcher:doSearch1" style="width:200px"></input>
	</div>
</div>

<div id="list-buttons">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:jQuery('#dialog-list').dialog('close')">Close</a>
</div>

<!-- Dialog Form -->
<div id="dialog-form" class="easyui-dialog" style="width:400px; height:520px; padding: 10px 20px" closed="true" buttons="#dialog-buttons">
	<form id="form" method="post" novalidate>
		<div class="form-item">
			<label for="type">Part Number</label><br />
			<input type="text" name="partnumber" id="partnumber" class="easyui-textbox" required="true" style="width:100%" maxlength="20" />
		</div>
		<div class="form-item">
			<label for="descript">Nama</label><br />
			<input type="text" name="descript" id="descript" class="easyui-textbox" required="true" style="width:100%" maxlength="100" />
		</div>
		<div class="form-item">
			<label for="stock">Satuan</label><br />
			<select type="text" name="satuan" id="satuan" class="easyui-combobox" required="true" style="width:100%" maxlength="20" />
			<option value="ROLL">ROLL</option>
			<option value="PCS">PCS</option>
			<option value="BOX">BOX</option>
			<option value="MTR">MTR</option><
			<option value="PACK">PACK</option>
			<option value="SET">SET</option>
			<option value="STRIP">STRIP</option>
			<option value="BKS">BKS</option>
			<option value="LGTH">LGTH</option>
			<option value="KG">KG</option>
			<option value="DUS">DUS</option>
			<option value="JRG">JRG</option>
			<option value="LOT">LOT</option>
			<option value="GLN">GLN</option>
			<option value="LTR">LTR</option>
			<option value="TUBE">TUBE</option>
			<option value="BTL">BTL</option>
			<option value="LSN">LSN</option>
			<option value="UNT">UNT</option>
			<option value="KTK">KTK</option>
			<option value="KPG">KPG</option>
			<option value="LBR">LBR</option>
			<option value="TBG">TBG</option></select>
		</div>
		<div class="form-item">
			<label for="stock">Stock</label><br />
			<input type="text" name="stock" id="stock" class="easyui-textbox" required="true" style="width:100%" maxlength="20" />
		</div>
		<div class="form-item">
			<label for="price">Harga</label><br />
			<input type="text" name="price" id="price" class="easyui-textbox" required="true" style="width:100%" maxlength="50" />
		</div>
		<div class="form-item">
			<label for="aktif">Aktif</label><br />
			<select type="text" name="aktif" id="aktif" class="easyui-combobox" required="true" style="width:100%" maxlength="15" />
				<option value='Y'>Aktif</option>
				<option value='N'>Tidak</option>
			</select>
		</div>
	</form>
</div>

<!-- Dialog Button -->
<div id="dialog-buttons">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="save()">Simpan</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:jQuery('#dialog-form').dialog('close')">Batal</a>
</div>

<!-- Dialog Cetak QR -->
<div id="dialog-print" class="easyui-dialog" style="width:500px; height:250px; padding: 10px 20px" closed="true" buttons="#btn-excel">
	<form id="form-print" method="post" action="master/barang/cetakqr.php" target="_blank">
		<div class="form-item">
			<label for="tglinv">Part Number</label><br/>
			<input type="text" name="partnumber" id="partnumber" class="easyui-textbox" required="true" size="20" maxlength="20" />
		</div>
		<div class="form-item">
			<label for="noinvoice">Nama Barang</label><br/>
			<input type="text" name="descript" id="descript" class="easyui-textbox" style="width:100%" maxlength="20" />
		</div>
	</form>
</div>

<!-- Dialog Button -->
<div id="btn-excel">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" onclick="$('#form-print').submit();">Print</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:jQuery('#dialog-print').dialog('close')">Batal</a>
</div>
</body>
</html>
