
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Create / View Invoice</title>
	<link rel="icon" type="image/png" href="../../images/icon.png">
	<link rel="stylesheet" type="text/css" href="../../jeasyui/themes/material-teal/easyui.css">
	<link rel="stylesheet" type="text/css" href="../../jeasyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="../../nasikin.css" />
	<script type="text/javascript" src="../../jeasyui/jquery.min.js"></script>
	<script type="text/javascript" src="../../jeasyui/jquery.easyui.min.js"></script>

<script type="text/javascript">
var url;
function createinv(){
	$('#dialog-inv').dialog('open').dialog('setTitle','Create Master Invoice');
	$('#tglinv').textbox('setValue', '');
	$('#noinvoice').textbox('setValue', '');
	$('#customer').textbox('setValue', '');
	$('#gajierangan').textbox('setValue', '');
}
function AddDetail(){
	var row = $('#datagrid-crud').datagrid('getSelected');
	if(row.posting == 'N'){
		$('#dialog-anak1').dialog('open').dialog('setTitle','Detail for Invoice');
		$('#detail').form('load',row);
	}else{
		$.messager.show({
			title:'Info',
			msg:'Maaf, Tidak bisa ditambah container krn sdh diposting',
			timeout:2000,
			showType:'slide'
		});
	}
	cariadd();
}

function save(){
	var noinvoice = $("#noinvoice").val();
	var string = $("#form-inv").serialize();
	if(tglinv.length==0){
		$.messager.show({
			title:'Info',
			msg:'Maaf, No Referensi tidak boleh kosong',
			timeout:2000,
			showType:'slide'
		});
		$("#noinvoice").focus();
		return false();
	}

	$.ajax({
		type	: "POST",
		url		: "account/inv/simpan.php",
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

function save1(){
	var noinvoice = $("#noinvoice").val();
	var string = $("#frm-invanak").serialize();
	if(nocontainer.length==0){
		$.messager.show({
			title:'Info',
			msg:'Maaf, No Referensi tidak boleh kosong',
			timeout:2000,
			showType:'slide'
		});
		$("#nocontainer").focus();
		return false();
	}

	$.ajax({
		type	: "POST",
		url		: "account/inv/simpan1.php",
		data	: string,
		success	: function(data){
			$.messager.show({
				title:'Info',
				msg:data, //'Password Tidak Boleh Kosong.',
				timeout:2000,
				showType:'slide'
			});
			$('#combogrid').datagrid('reload');
			$('#nocontainer').textbox('setValue', '');
			$('#tujuan').textbox('setValue', '');
			$('#penerima').textbox('setValue', '');
			$('#platno').textbox('setValue', '');
			$('#nilai').textbox('setValue', '');
			$('#kapal').textbox('setValue', '');
			$('#uangsolar').textbox('setValue', '');
			$('#gaji').textbox('setValue', '');
			$('#datagrid-crud').datagrid('reload');
		}
	});
}

function saveedit(){
	var noinvoice = $("#noinvoice").val();
	var string = $("#frm-edit").serialize();
	if(nilaie.length==0){
		$.messager.show({
			title:'Info',
			msg:'Maaf, No Referensi tidak boleh kosong',
			timeout:2000,
			showType:'slide'
		});
		$("#nilaie").focus();
		return false();
	}

	$.ajax({
		type	: "POST",
		url		: "account/inv/editnilai.php",
		data	: string,
		success	: function(data){
			$.messager.show({
				title:'Info',
				msg:data, //'Password Tidak Boleh Kosong.',
				timeout:2000,
				showType:'slide'
			});
			$('#combogrid').datagrid('reload');
		}
	});
}

function hapus(){
	var row = $('#datagrid-crud').datagrid('getSelected');
	if (row.posting == 'N'){
		$.messager.confirm('Confirm','Yakin akan menghapus data ?',function(r){
			if (r){
				$.ajax({
					type	: "POST",
					url		: "account/inv/hapus.php",
					data	: 'id='+row.noinvoice,
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
	}else{
			$.messager.show({
			title:'Info',
			msg:'Data tidak bisa dihapus, krn sudah di posting',
			timeout:2000,
			showType:'slide'
		});
	}
}

function posting(){
	var row = $('#datagrid-crud').datagrid('getSelected');
	if (row.posting =='N'){
		$.messager.confirm('Confirm','Yakin akan memposting data ?',function(r){
			if (r){
				$.ajax({
					type	: "POST",
					url		: "account/inv/posting.php",
					data	: 'id='+row.noinvoice,
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
	}else{
			$.messager.show({
			title:'Info',
			msg:'Data sudah di Posting',
			timeout:2000,
			showType:'slide'
		});
	}
}
function completed(){
	var row = $('#datagrid-crud').datagrid('getSelected');
	if (row.customer =='DIVFWD'){
		$.messager.confirm('Confirm','Yakin akan Completed Invoice?',function(r){
			if (r){
				$.ajax({
					type	: "POST",
					url		: "account/inv/completed.php",
					data	: 'id='+row.noinvoice,
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
	}else{
			$.messager.show({
			title:'Info',
			msg:'Data bukan Div. Forwarding Tidak Boleh di Completed',
			timeout:2000,
			showType:'slide'
		});
	}
}
function hapuslagi(){
	var row = $('#data-crud').datagrid('getSelected');
	var posit = $('#datagrid-crud').datagrid('getSelected');
	if (posit.posting =='N'){
	if (row){
		$.messager.confirm('Confirm','Yakin akan menghapus data ?',function(r){
			if (r){
				$.ajax({
					type	: "POST",
					url		: "account/inv/hapuslagi.php",
					data	: 'id='+row.noinvoice+row.nocontainer+row.tujuan+row.platno+row.penerima+row.nilai,
					success	: function(data){
						$.messager.show({
							title:'Info',
							msg:data, //'Password Tidak Boleh Kosong.',
							timeout:2000,
							showType:'slide'
						});
						$('#data-crud').datagrid('reload');
					}
				});
			}
		});
	}
}else{
		$.messager.show({
			title:'Info',
			msg:"Data Tidak Bisa dihapus, krn sudah diposting",
			timeout:2000,
			showType:'slide'
		});
	}
}


function cetakexcel(){
	var row = $('#datagrid-crud').datagrid('getSelected');
	if(row){
		$('#dialog-excel').dialog('open').dialog('setTitle','Print to Excel');
		$('#form-excel').form('load',row);
	}
}

function editmaster(){
	var row = $('#datagrid-crud').datagrid('getSelected');
	if(row){
		$('#dialog-inv').dialog('open').dialog('setTitle','Edit Data Master');
		$('#form-inv').form('load',row);
	}
}

function editlagi(){
	var row = $('#data-crud').datagrid('getSelected');
	var posit = $('#datagrid-crud').datagrid('getSelected');
	if (posit.posting == 'N'){
		if(row){
			$('#dlg-invanak').dialog('open').dialog('setTitle','Edit Detail Data');
			$('#frm-invanak').form('load',row);
		}
	}else{
		$.messager.show({
			title:'Info',
			msg:"Data Tidak Bisa diedit, krn sudah diposting",
			timeout:2000,
			showType:'slide'
		});
	}
}

function view(){
	var row = $('#datagrid-crud').datagrid('getSelected');
	if(row){
		$('#dialog-anak').dialog('open').dialog('setTitle','View Detail');
		$('#form2').form('load',row);
		carilagi();
	}
}

function editnilai(){
	var row = $('#datagrid-crud').datagrid('getSelected');
	if(row){
		$('#dialog-edit').dialog('open').dialog('setTitle','Edit Nilai');
		$('#form-edit').form('load',row);
	}
}

function fresh(){
	$('#datagrid-crud').datagrid('reload');
}
function doSearch(value){
	$('#datagrid-crud').datagrid('load',{
        cari: value
    });
}

function doLihat(value){
	$('#data-crud').datagrid('load',{
        cari: value
    });
}
function cariadd(){
	$('#data-crud1').datagrid('load',{
				nopo: $('#test1').val(),
				noinv: $('#test2').val()
    });
}

function carilagi(){
	$('#data-crud').datagrid('load',{
				noinv: $('#test3').val()
    });
}

$(function(){
    $('#customer').combogrid({
				panelWidth:700,
				url: 'account/inv/get_cust.php?',
				idField:'kode',
				textField:'kode',
				mode:'remote',
				fitColumns:true,
			    columns:[[
				{field:'kode',title:'kode',width:10},
			    {field:'nama',title:'nama',width:25},
			    {field:'kota',title:'kota',width:20},
			    ]],onClickRow:function(rowData){
			                                 var val =$('#customer').combogrid('grid').datagrid('getSelected');

			                                }
						});


    $('#tujuan').combogrid({
				panelWidth:400,
				url		: 'account/inv/get_keg.php?',
				idField:'kode',
				textField:'kode',
				mode:'remote',
				fitColumns:true,
			    columns:[[
				{field:'kode',title:'kode',width:10},
			    {field:'nama',title:'nama',width:15},
			    {field:'tarif',title:'tarif',width:10},
			    ]],onClickRow:function(rowData){
			              var val11 =$('#tujuan').combogrid('grid').datagrid('getSelected');
											 $('#nilai').textbox('setValue', val11.tarif);
			                                }
						});

    $('#noref').combogrid({
				panelWidth:800,
				url		: 'account/inv/get_msj.php?',
				idField:'notrans',
				textField:'notrans',
				mode:'remote',
				fitColumns:true,
			    columns:[[
				{field:'notrans',title:'No. Trans',width:10},
			    {field:'noplat',title:'Plat#',width:15},
			    {field:'consignee',title:'Penerima',width:10},
				{field:'nocont',title:'Container',width:10},
				{field:'gaji',title:'Gaji',width:10},
				{field:'uangsolar',title:'Solar',width:10},
				{field:'kapal',title:'Kapal',width:10},
				{field:'novoy',title:'Voy.',width:10},
			    ]],onClickRow:function(rowData){
			                                 var val =$('#noref').combogrid('grid').datagrid('getSelected');
											 $('#platno').textbox('setValue', val.noplat);
											 $('#penerima').textbox('setValue', val.consignee);
											 $('#nocontainer').textbox('setValue', val.nocont);
											 $('#kapal').textbox('setValue', val.kapal+' - '+val.novoy);
											 $('#gaji').textbox('setValue', val.gaji);
											 $('#tujuan').textbox('setValue', val.tujuan);
											 $('#uangsolar').textbox('setValue', val.uangsolar);
											 $('#cargo').textbox('setValue', val.cargo);
											 $('#tanggal').textbox('setValue', val.tanggal);
			                                }
						});
    $('#cabang').combogrid({
				panelWidth:700,
				url: 'transaksi/po/get_cabang.php?',
				idField:'kode',
				textField:'kode',
				mode:'remote',
				fitColumns:true,
			    columns:[[
				{field:'kode',title:'kode',width:10},
			    {field:'nama',title:'nama',width:25},
			    ]],onClickRow:function(rowData){
			                                 var val =$('#cabang').combogrid('grid').datagrid('getSelected');

			                                }
						});
    $('#nopo').combogrid({
				panelWidth:700,
				url: 'account/inv/get_po.php?',
				idField:'notrans',
				textField:'notrans',
				mode:'remote',
				fitColumns:true,
			    columns:[[
				{field:'notrans',title:'notrans'},
			    {field:'nama',title:'nama'},
				{field:'remarks',title:'No PO'},
			    ]],onClickRow:function(rowData){
			                                 var val =$('#cabang').combogrid('grid').datagrid('getSelected');

			                                }
						});
	$('#form-excel1').form(
        {
            url:'excelinv.php',
            success:function(data){
                if(data)//check if data returned
                {
                alert('yes');
                }}
            }
    )
});

function getSelections(){
			var ids = [];
			var rows = $('#data-crud1').datagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				ids.push(rows[i].urut+rows[i].notrans);
        		$.ajax({
        			type	: "POST",
        			url		: "account/inv/simpan_detail.php",
        			data	: 'menuid='+rows[i].urut+"        "+rows[i].noinv,
        			success	: function(data){
        			$.messager.show({
        				title:'Info',
        				msg:data, //'Password Tidak Boleh Kosong.',
        				timeout:2000,
        				height:150,
        				width:300,
        				showType:'slide'
        				});
        			$('#datagrid-crud').datagrid('reload');
					$('#data1-crud').datagrid('reload');
        			}
        		});
			}
            cariadd();
}
</script>
</head>
<body>

	<div style="margin:10px 0;"></div>

	<table id="datagrid-crud" title="Create / View Invoice" class="easyui-datagrid" style="width:auto; height: auto;" url="account/inv/json.php" toolbar="#tb" pagination="true" rownumbers="true" fitColumns="true" singleSelect="true" collapsible="true">
    <thead>
        <tr>
			<th data-options="field:'noinvoice'" sortable="true">No Transaksi</th>
            <th data-options="field:'tglinv'">Tanggal</th>
            <th data-options="field:'customer'">customer</th>
			<th data-options="field:'nama'">Nama</th>
			<th data-options="field:'keterangan'">Supply To</th>
			<th data-options="field:'nopo'">No PO</th>
			<th data-options="field:'deskripsi'">Port</th>
			<th data-options="field:'nilai'" align="right">Nilai</th> 
			<th data-options="field:'ppn'" align="right">PPN</th>
			<th data-options="field:'diskon'" align="right">Diskon</th>
			<th data-options="field:'transport'" align="right">Transport</th>
    </thead>
	</table>
    <div id="tb" style="padding:2px;height:30px">
		<div style="float:left;">
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="createinv()">Add Master</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editmaster()">Edit Master</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="hapus()">Delete Master</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-detail" plain="true" onclick="AddDetail()">Add Detail</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-view" plain="true" onclick="view()">View Detail</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="fresh()">Refresh</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="cetakexcel()">Print to Excel</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="posting()">Posting</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="completed()">Completed</a>
			<?php
error_reporting(0); if ( $_SESSION[tombol] == 'semua') {
				echo "<a href='javascript:void(0)'' class='easyui-linkbutton' iconCls='icon-undo' plain='true' onclick='editnilai()'>Edit Nilai</a>";
			} ?>
		</div>
		<div style="float:right;">
        	Pencarian <input id="cari" class="easyui-searchbox" data-options="prompt:'No. Trans / Tgl (yyyy-mm-dd)..',searcher:doSearch" style="width:230px"></input>
		</div>
	</div>

<!-- Dialog Form -->
<div id="dialog-inv" class="easyui-dialog" style="width:500px; height:450px; padding: 10px 20px" closed="true" buttons="#btn-bkk">
	<form id="form-inv" method="post" novalidate>
		<div class="form-item">
			<label for="tglinv">Tanggal &emsp;  &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp;No. Invoice</label><br />
			<input type="text" name="tglinv" id="tglinv" class="easyui-datebox" required="true" data-options="prompt:'YYYY-MM-DD'" style="width:40%"/>
			<input type="text" name="noinvoice" id="noinvoice" class="easyui-textbox" required="true" style="width:59%" />
		</div>

		<div class="form-item">
			<label for="customer">Customer</label><br />
			<input type="text" name="customer" id="customer" class="easyui-textbox" required="true" style="width:100%"/>
			<input type="hidden" name="username" id="username" value="<?php
error_reporting(0); echo $_SESSION['username']?>"/>
		</div>
		<div class="form-item">
			<label for="noinvoice">PO Customer</label><br/>
			<input type="text" name="nopo" id="nopo" class="easyui-textbox" style="width:100%" maxlength="20" />
		</div>
		<div class="form-item">
			<label for="keterangan">Supply To</label><br/>
			<input type="text" name="keterangan" id="keterangan" class="easyui-textbox" required="true"  style="width:100%" maxlength="20" />
		</div>
		<div class="form-item">
			<label for="deskripsi">Port</label><br/>
			<input type="text" name="deskripsi" id="deskripsi" class="easyui-textbox" required="true"  style="width:100%" maxlength="20" />
		</div>
	</form>
</div>

<!-- Dialog Button -->
<div id="btn-bkk">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="save()">Simpan</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:jQuery('#dialog-inv').dialog('close')">Batal</a>
</div>

<div id="dialog-excel" class="easyui-dialog" style="width:500px; height:450px; padding: 10px 20px" closed="true" buttons="#btn-excel">
	<form id="form-excel" method="post" action="account/inv/excelinv.php">
		<div class="form-item">
			<label for="tglinv">Tanggal</label><br/>
			<input type="text" name="tglinv" id="tglinv" class="easyui-textbox" required="true" size="20" maxlength="20" />
		</div>
		<div class="form-item">
			<label for="noinvoice">No Transaksi</label><br/>
			<input type="text" name="noinvoice" id="noinvoice" class="easyui-textbox" style="width:100%" maxlength="20" />
		</div>
		<div class="form-item">
			<label for="keterangan">Supply To / Port</label><br/>
			<input type="text" name="keterangan" id="keterangan" class="easyui-textbox" style="width:50%"/>
			<input type="text" name="deskripsi" id="deskripsi" class="easyui-textbox" style="width:49%"/>
		</div>
		<div class="form-item">
			<label for="customer">Customer</label><br />
			<input type="text" name="customer" id="customer" class="easyui-textbox" required="true" style="width:100%"/>
			<input type="hidden" name="username" id="username" value="<?php
error_reporting(0); echo $_SESSION['username']?>"/>
            <label for="transport">Biaya Transports</label><br/>
            <input type="text" name="transport" id="transport" class="easyui-textbox" required="true" style="width:100%"/>
		</div>
		<div class="form-item">
			<label for="keterangan">Diskon &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;PPN</label><br/>
			<input type="text" name="diskon" id="diskon" value="3" class="easyui-textbox" style="width:50%"/>
			<input type="text" name="ppn" id="ppn" value="11" class="easyui-textbox" style="width:49%"/>
		</div>
	</form>
</div>

<!-- Dialog Button -->
<div id="btn-excel">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="$('#form-excel').submit();">Print</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:jQuery('#dialog-excel').dialog('close')">Batal</a>
</div>

<!-- Edit Nilai -->
<div id="dialog-edit" class="easyui-dialog" style="width:500px; height:450px; padding: 10px 20px" closed="true" buttons="#btn-edit">
	<form id="form-edit" method="post" novalidate>
		<div class="form-item">
			<label for="noinvoice">No Transaksi</label><br/>
			<input type="text" name="noinvoice" id="noinvoice" class="easyui-textbox" style="width:100%" maxlength="20" />
		</div>
		<div class="form-item">
			<label for="keterangan">Nilai Awal</label><br/>
			<input type="text" name="nilai" id="nilaia" class="easyui-textbox" style="width:100%" maxlength="20" />
		</div>
		<div class="form-item">
			<label for="customer">Nilai Perubahan</label><br />
			<input type="text" name="nilaie" id="nilaie" class="easyui-textbox" required="true" style="width:100%"/>
			<input type="hidden" name="username" id="username" value="<?php
error_reporting(0); echo $_SESSION['username']?>"/>
		</div>
	</form>
</div>

<!-- Dialog Button -->
<div id="btn-edit">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveedit();">Print</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:jQuery('#dialog-edit').dialog('close')">Batal</a>
</div>

<div id="dialog-anak1" class="easyui-dialog" style="width:1024px; height:500px;padding: 10x 20px" closed="true" buttons="#dg-btn1">
	<table id="data-crud1" class="easyui-datagrid" style="width:auto; height:450;" url="account/inv/get_add.php" pagination="true" rownumbers="true" toolbar="#ta1" fitColumns="true" >
    <thead>
        <tr>
			<th data-options="field:'ck',checkbox:true"></th>
			<th data-options="field:'nopo'" sortable="true">No Transaksi</th>
            <th data-options="field:'partnumber'">Kode Brg</th>
			<th data-options="field:'nama'">Nama Brg</th>
            <th data-options="field:'qty'">Qty</th>
			<th data-options="field:'harga'">Harga</th>
            <th data-options="field:'total'">Total</th>
			<th data-options="field:'notrans'" hidden="true">No Transaksi</th>
			<th data-options="field:'panjangi'">panjang</th>
			<th data-options="field:'urut'">Urut</th>
			<th data-options="field:'noinv'">No INV</th>
        </tr>
    </thead>
	</table>
    <div id="ta1" style="padding:2px;height:30px">
	<form id="detail" method="post" action="#" novalidate>
		<div style="float:left;">
			No INV <input type="text" id="test2" name="noinvoice" class="easyui-textbox"  style="width:150px"></input>
            No PO <input type="text" id="test1" name="nopo" class="easyui-textbox"  style="width:200px"></input>
			
		</div>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="cariadd()">Cari Data</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="getSelections()">Proses Data</a>
	</form>
	</div>
</div>
<div id="dg-btn1">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:jQuery('#dialog-anak1').dialog('close')">Batal</a>
</div>

<div id="dialog-anak" class="easyui-dialog" style="width:1024px; height:500px;padding: 10x 20px" closed="true" buttons="#dg-btn">
<form id="form2">
	<table id="data-crud" class="easyui-datagrid" style="width:auto; height:450;" url="account/inv/get_detail.php" pagination="true" rownumbers="true" toolbar="#ta" fitColumns="true" singleSelect="true">
    <thead>
        <tr>
			<th data-options="field:'nopo'" sortable="true">No PO</th>
            <th data-options="field:'partnumber'">Kode Brg</th>
			<th data-options="field:'nama'">Nama Brg</th>
            <th data-options="field:'qty'">Qty</th>
			<th data-options="field:'harga'">Harga</th>
            <th data-options="field:'total'">Total</th>
        </tr>
    </thead>
	</table>
</form>
    <div id="ta" style="padding:2px;height:30px">
		<form id="detail" method="post" action="#" novalidate>
		<div style="float:left;">
			No INV <input type="text" id="test3" name="noinvoice" class="easyui-textbox"  style="width:150px"></input>
			
		</div>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="carilagi()">Cari Data</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editlagi()">Edit</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="hapuslagi()">Hapus</a>
	</form>
	</div>
</div>
<div id="dg-btn">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:jQuery('#dialog-anak').dialog('close')">Batal</a>
</div>
</body>
</html>
