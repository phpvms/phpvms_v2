<h3><?php echo $title?></h3>

<table id="grid"></table>
<div id="pager"></div>
<br />

<link rel="stylesheet" type="text/css" media="screen" href="<?php echo fileurl('/lib/js/jqgrid/css/ui.jqgrid.css');?>" />
<script src="<?php echo fileurl('/lib/js/jqgrid/js/i18n/grid.locale-en.js');?>" type="text/javascript"></script>
<script src="<?php echo fileurl('/lib/js/jqgrid/js/jquery.jqGrid.min.js');?>" type="text/javascript"></script>
<style type="text/css">
.ui-dialog : { z-index:1000 }
</style>
<script type="text/javascript">
$("#grid").jqGrid({
   url: '<?php echo SITE_URL.'/admin/action.php/operations/schedulegrid';?>',
   datatype: 'json',
   mtype: 'GET',
   colModel : [
		{label: 'Code', index: 'code', name : 'code', width: 40, sortable : true, align: 'center', search: 'true'},
		{label: 'Flight Num', index: 'flightnum', name : 'flightnum', width: 65, sortable : true, align: 'center'},
		{label: 'Departure', index: 'depicao', name : 'depicao', width: 60, sortable : true, align: 'center'},
		{label: 'Arrival', index: 'arricao', name : 'arricao', width: 60, sortable : true, align: 'center'},
		{label: 'Aircraft', index: 'a.name', name : 'aircraft', width: 100, sortable : true, align: 'center'},
		{label: 'Registration', index: 'a.registration', name : 'registration', width: 100, sortable : true, align: 'center'},
		{label: 'Route', index: 'route', name : 'route', width: 100, sortable : true, align: 'center'},
		{label: 'Days', index: 'daysofweek', name : 'route', width: 100, sortable : true, align: 'center', search: 'false'},
		{label: 'Distance', index: 'distance', name : 'distance', width: 100, sortable : true, align: 'center', search: 'false'},
		{label: 'Flown', index: 'timesflown', name : 'flown', width: 100, sortable : true, align: 'center', search: 'false'},
		{label: 'Edit', index: '', name : '', width: 100, sortable : true, align: 'center', search: 'false'},
		{label: 'Delete', index: '', name : '', width: 100, sortable : true, align: 'center', search: 'false'}
	],
    pager: '#pager', rowNum: 25,
    sortname: 'flightnum', sortorder: 'asc',
    viewrecords: true, autowidth: true,
    height: '100%'
});

$("#grid").jqGrid('navGrid','#pager',{edit:false,add:false,del:false}); 

function deleteschedule(id)
{
	$.post("<?php echo SITE_URL?>/admin/action.php/operations/schedules", { action: "deleteschedule", id: id },
	function() { $("#grid").trigger("reloadGrid"); });
}
</script>