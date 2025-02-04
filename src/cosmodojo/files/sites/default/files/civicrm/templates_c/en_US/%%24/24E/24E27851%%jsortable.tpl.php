<?php /* Smarty version 2.6.27, created on 2016-02-14 08:31:02
         compiled from CRM/common/jsortable.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/common/jsortable.tpl', 1, false),array('block', 'ts', 'CRM/common/jsortable.tpl', 116, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo '
<script type="text/javascript">
  CRM.$(function($) {

    function getElementClass(element) {
      return $(element).attr(\'class\') || \'\';
    }

    // fetch the occurrence of element
    function getRowId(row, str) {
      var optionId;
      $.each(row, function(i, n) {
        if (str === $(n).attr(\'class\')) {
          optionId = i;
        }
      });
      return optionId;
    }

    // for date sorting see http://wiki.civicrm.org/confluence/display/CRMDOC/Sorting+Date+Fields+in+dataTables+Widget
    var useAjax = '; ?>
<?php if ($this->_tpl_vars['useAjax']): ?>1<?php else: ?>0<?php endif; ?><?php echo ',
      sourceUrl = \'\',
      useClass  = \'display\',
      tcount = 1,
      tableId = [];

    if ( useAjax ) {
      '; ?>
<?php if (isset ( $this->_tpl_vars['sourceUrl'] )): ?>sourceUrl = "<?php echo $this->_tpl_vars['sourceUrl']; ?>
";<?php endif; ?><?php echo '
      useClass = \'pagerDisplay\';
      tcount = 5;
    }

    CRM.dataTableCount = CRM.dataTableCount || 1;

    // FIXME: Rewriting DOM ids is probably a bad idea, and could be avoided
    $(\'table.\' + useClass).not(\'.dataTable\').each(function() {
      $(this).attr(\'id\',\'option\' + tcount + CRM.dataTableCount);
      tableId.push(CRM.dataTableCount);
      CRM.dataTableCount++;
    });

    $.each(tableId, function(i,n){
      var tabId = \'#option\' + tcount + n;
      //get the object of first tr data row.
      var tdObject = $(tabId + \' tr:nth(1) td\');
      var id = -1; var count = 0; var columns=\'\'; var sortColumn = \'\';
      //build columns array for sorting or not sorting
      $(tabId + \' th\').each( function( ) {
        var option = $(this).prop(\'id\').split("_");
        option  = ( option.length > 1 ) ? option[1] : option[0];
        var stype   = \'numeric\';
        switch( option ) {
          case \'sortable\':
            sortColumn += \'[\' + count + \', "asc" ],\';
            columns += \'{"sClass": "\'+ getElementClass( this ) +\'"},\';
            break;
          case \'date\':
            stype = \'date\';
          case \'order\':
            if ( $(this).attr(\'class\') == \'sortable\' ){
              sortColumn += \'[\' + count + \', "asc" ],\';
            }
            var sortId   = getRowId(tdObject, $(this).attr(\'id\') +\' hiddenElement\' );
            columns += \'{ "render": function ( data, type, row ) { return "<div style=\\\'display:none\\\'>"+ data +"</div>" + row[sortId] ; }, "targets": sortColumn,"bUseRendered": false},\';
            break;
          case \'nosort\':
            columns += \'{ "bSortable": false, "sClass": "\'+ getElementClass( this ) +\'"},\';
            break;
          case \'currency\':
            columns += \'{ "sType": "currency" },\';
            break;
          case \'link\':
            columns += \'{"sType": "html"},\';
            break;
          default:
            if ( $(this).text() ) {
              columns += \'{"sClass": "\'+ getElementClass( this ) +\'"},\';
            } else {
              columns += \'{ "bSortable": false },\';
            }
            break;
        }
        count++;
      });
      // Fixme: this could be done without eval
      columns    = columns.substring(0, columns.length - 1 );
      sortColumn = sortColumn.substring(0, sortColumn.length - 1 );
      eval(\'sortColumn =[\' + sortColumn + \']\');
      eval(\'columns =[\' + columns + \']\');

      var noRecordFoundMsg  = '; ?>
'<?php $this->_tag_stack[] = array('ts', array('escape' => 'js')); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>None found.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>'<?php echo ';

      var oTable;
      if ( useAjax ) {
        oTable = $(tabId).dataTable({
          "bFilter": false,
          "bAutoWidth": false,
          "aaSorting": sortColumn,
          "aoColumns": columns,
          "bProcessing": true,
          "bJQueryUI": true,
          "asStripClasses": [ "odd-row", "even-row" ],
          "sPaginationType": "full_numbers",
          "sDom": \'<"crm-datatable-pager-top"lfp>rt<"crm-datatable-pager-bottom"ip>\',
          "bServerSide": true,
          "sAjaxSource": sourceUrl,
          "oLanguage":{
            "sEmptyTable": noRecordFoundMsg,
            "sZeroRecords": noRecordFoundMsg
          },
          "fnServerData": function ( sSource, aoData, fnCallback ) {
            $.ajax( {
              "dataType": \'json\',
              "type": "POST",
              "url": sSource,
              "data": aoData,
              "success": fnCallback
            });
          }
        });
      } else {
        oTable = $(tabId).dataTable({
          "aaSorting": sortColumn,
          "bPaginate": false,
          "bLengthChange": true,
          "bFilter": false,
          "bInfo": false,
          "asStripClasses": [ "odd-row", "even-row" ],
          "bAutoWidth": false,
          "aoColumns": columns,
          "bSort": true,
          "oLanguage":{
            "sEmptyTable": noRecordFoundMsg,
            "sZeroRecords": noRecordFoundMsg
          }
        });
      }
    });
  });

  //plugin to sort on currency
  cj.fn.dataTableExt.oSort[\'currency-asc\']  = function(a,b) {
    var symbol = "'; ?>
<?php echo $this->_tpl_vars['config']->defaultCurrencySymbol($this->_tpl_vars['config']->defaultSymbol); ?>
<?php echo '";
    var x = (a == "-") ? 0 : a.replace( symbol, "" );
    var y = (b == "-") ? 0 : b.replace( symbol, "" );
    x = parseFloat( x );
    y = parseFloat( y );
    return ((x < y) ? -1 : ((x > y) ?  1 : 0));
  };

  cj.fn.dataTableExt.oSort[\'currency-desc\'] = function(a,b) {
    var symbol = "'; ?>
<?php echo $this->_tpl_vars['config']->defaultCurrencySymbol($this->_tpl_vars['config']->defaultSymbol); ?>
<?php echo '";
    var x = (a == "-") ? 0 : a.replace( symbol, "" );
    var y = (b == "-") ? 0 : b.replace( symbol, "" );
    x = parseFloat( x );
    y = parseFloat( y );
    return ((x < y) ?  1 : ((x > y) ? -1 : 0));
  };
</script>
'; ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>