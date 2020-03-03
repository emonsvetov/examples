/**
 * Created by e.monsvetov on 10/02/17.
 */

Ext.define('App.views.Acts.Grid', {
    extend: 'Sjx.grid.Panel',
    store: 'App.stores.Acts',
    alias: 'widget.ActsGrid',
    requires: [
        'App.modules.buch.stores.Bills'
    ],
    config: {
        height: 'auto',
        renderers: {
            billid: function(value, p, record){
                if(record.get('billid') > App.modules.buch.stores.Bills.MAX_V1_BILLS ){
                    var url = Sjx.Ajax.getUrl('buch.agts.bills.card', {billid: record.get('billid')});
                }else{
                    var url = Sjx.Ajax.getUrl('app.bill.card', {billid: record.get('billid')});
                }
                return Ext.String.format('<a href="'+url+'" >{1}</a>', record.get('billid'), record.get('billn'));
            },
            actions: function(value, p, record){
                var result = '';
                if( Sjx.isSORM() ){
                    result += '-';
                }else{
                    if(record.get('deleted')){
                        result += _js('Removed');
                    }else{
                        var el=Ext.core.DomHelper.createDom({
                            tag: 'span',
                            html: _js('Actions'),
                            cls: 'actions',
                            actid: record.get('actid')
                        });
                        result += el.outerHTML;
                    }
                }
                return result;
            },

            clientid: function(value, p, record){
                var url =  Sjx.Ajax.getUrl('app.client.card', {clientid: value, tab: 'i_tab_acts'});
                var template = '<a href="'+url+'" >{0}</a>';
                return value ? Ext.String.format(template, record.get('clientname')) : '';
            }
        },

        columns: [
            {index: 'actid'},
            {h: _js('Customer'),     index: 'clientid',       type:'int',    dw: 250},
            {h: _js('Doc #'),        index: 'actnumber',      type:'string', dw: 80 },
            {h: _js('Invoice #'),    index: 'billid',         type:'int',    dw: 120},
            {h: _js('Date'),         index: 'actdate',        type:'date',   dw: 100},
            {h: _js('Amount'),       index: 'amounttotal',    type:'string', dw: 100, align:'right'},
            {h: _js('Comment'),      index: 'actcomment',     type:'string', dw: 200 },
            {h: _js('Actions'),      index: 'actions',        type:'int',    dw: 90 }
        ]
    },

    _initMenu: function(){
        var me = this;
        Ext.each(Ext.query('.actions', me.getEl().dom ), function(span){
            var extSpan = Ext.get(span);
            extSpan.on('click', function(e){

                var actid = parseInt(extSpan.getAttribute('actid'));
                var menu = Ext.create('Ext.menu.Menu', {
                    items: [{
                        text: _js('Download PDF'),
                        iconCls: 'menu-icon-pdf',
                        handler: function(){
                            me.fireEvent('download', me, actid);
                        }
                    },{
                        text: _js('Print'),
                        iconCls: 'menu-icon-print',
                        handler: function(){
                            me.fireEvent('printitem', me, actid);
                        }
                    }, {
                        text: _js('Delete'),
                        iconCls: 'menu-icon-trash',
                        tooltip: _js('Delete'),
                        handler: function () {
                            me.fireEvent('deleteitem', me, actid);
                        }
                    }]
                });
                menu.showAt([extSpan.getLeft(), (extSpan.getTop() + extSpan.getHeight() + 5)]);
            });
        });
    },

    initComponent: function(){

        this.addEvents('download', 'printitem');
        this.callParent(arguments);
        this.getView().getRowClass = function(record, rowIndex, rowParams, store){
             return record.get('deleted') == 1 ? 'grid-row-gray' : '';
        }

        var me = this;
        this.getView().on('refresh', function(){
            me._initMenu();
        });
    }
});