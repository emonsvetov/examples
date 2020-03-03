/**
 * Created by e.monsvetov on 17/01/17.
 */

Ext.define('App.controllers.Acts', {
    extend: 'Ext.app.Controller',

    views: [
        'App.views.Acts.Grid',
        'App.views.Acts.Import'
    ],

    init: function(){
        this.control({
            'ActsGrid': {
                download:  Ext.bind(this.onDownload, this),
                printitem: Ext.bind(this.onPrintItem, this),
                linkitem:  Ext.bind(this.onLinkAct, this),
                deleteitem: Ext.bind(this.onDeleteAct, this)
            },
            'ActsIndex': {
                groupprint: Ext.bind(this.onGroupPrint, this),
                reimport:   Ext.bind(this.onReImport, this)
            }
        });
    },

    onDownload: function( grid, actid ){
        var url = Sjx.Ajax.getUrl('buch.agts.acts.download', { actid: actid });
        document.location = url;
    },

    _print: function(content){

        Ext.create('Ext.window.Window', {
            height: 600,
            width: 800, layout: 'fit',
            closable: true, html: content,
            autoScroll: true, modal: true,
            title: 'Preview',
            dockedItems: [{
                xtype: 'toolbar',
                dock: 'top',
                items: [
                    { xtype: 'button', text: 'Print', handler: function(){
                        var data = '';
                        Ext.each(Ext.query('.item'), function( el ){
                            for(i=0; i<el.childNodes.length; i++){
                                var child = el.childNodes[i];
                                if(child.innerHTML){
                                    var qLastRows = 4;
                                    if(child.getAttribute('usetags')){
                                        qLastRows = 5;
                                    }
                                    var printDocument = new App.PrintDocument(child, qLastRows);
                                    data += printDocument.getHTML();
                                    data += '<div style="page-break-after: always;"></div>';
                                }
                            }
                        });

                        var w_width=730;
                        var w_height=600;
                        w_sx=(window.screen.width-w_width)/2;
                        if (window.screen.height<w_height) { w_height=window.screen.height; }
                        w_sy=(window.screen.height-w_height)/2-20;

                        pr = open("", "displayWindows", "width="+w_width+", height="+w_height+", screenX="+w_sx+", screenY="+w_sy+", scrollbars=yes");
                        pr.focus();

                        var style  = '<style>';
                        style     += 'body {font-size:12px; font-family:Verdana; margin:0; padding:10px; width:690px;}\n';
                        style     += 'td {font-size: 12px; font-family: Verdana;}';
                        style     += '</style>';
                        pr.document.write( style );

                        pr.document.write( style );
                        pr.document.write( data );
                        pr.document.close();
                        pr.print();
                    }}
                ]
            }],
            bodyStyle: {
                background: '#fff'
            },
            bodyPadding: '5 10 10 10'
        }).show();
    },

    onPrintItem: function(grid, actid){

        var me = this;
        Sjx.Ajax.request({
            url: Sjx.Ajax.getUrl('buch.agts.acts.print', {actid: actid}),
            params: {
                withStamp: 1
            },
            loadMask: {
                el: grid.getEl()
            },
            success: function(res){
                if(res.success){
                    var html = '<div class="item" ><div class="act">' + res.html + '</div></div>';
                    me._print(html);
                }else{
                    Ext.Msg.alert(_js('Error!'), res.message);
                }
            }
        });
    },

    onGroupPrint: function( view, grid, records, withStamp ){

        var params = [];
        Ext.each(records, function (record){
            params.push({actid: record.get('actid')});
        });

        var me = this;
        Sjx.Ajax.request({
            url: Sjx.Ajax.getUrl('buch.agts.acts.group-print'),
            params: {
                data:      Ext.JSON.encode(params),
                withStamp: withStamp
            },
            loadMask: {
                el: grid.getEl()
            },
            success: function( res ){

                var html = '';
                Ext.each(res.items, function(item){
                    html += '<div class="item" >';
                    html +=  '<div class="bill" usetags="'+item.usetags+'" >' + item.bill + '</div>';
                    html +=  '<div class="act"  >' + item.act  + '</div>';
                    html +=  '<div class="act"  >' + item.act  + '</div>';
                    html += '</div>';
                });

                me._print(html);
            }
        });
    },

    onLinkAct: function( actsGrid, actid ){

        var record = actsGrid.getStore().findRec('actid', actid);

        if(!this.actsFinders){
            this.actsFinders = {}
        }

        var me = this;
        var clientId = record.get('clientid');
        if(!this.actsFinders[actid]){
            this.actsFinders[actid] = Ext.create('App.modules.buch.views.bills.Finder', {
                clientId: clientId,
                height: 400, width: 650,
                uniqueStore: true,
                listeners: {
                    select: function( grid, records ){
                        if(record.get('billid') != records[0].get('billid')){
                            Sjx.Ajax.request({
                                url: Sjx.Ajax.getUrl('buch.agts.acts.relink', {
                                    actid:    actid,
                                    billid:   records[0].get('billid'),
                                    clientid: records[0].get('clientid')
                                }),
                                loadMask: {
                                    el: me.actsFinders[actid].getEl(),
                                    msg: 'Update'
                                },
                                success: function(res){
                                    record.set('clientid',   res.act.clientid);
                                    record.set('clientname', res.act.clientname);
                                    record.set('billid',     res.act.billid);
                                    record.set('billn',      res.act.billn);
                                    record.commit();
                                    actsGrid.getView().refresh();
                                    me.actsFinders[actid].close();
                                }
                            });
                            return false;
                        }
                    }
                }
            });
        }

       this.actsFinders[actid].show();
    },

    onDeleteAct: function (grid, actid) {
        Sjx.Ajax.request({
            url: Sjx.Ajax.getUrl('buch.agts.acts.delete', {actid: actid}),
            loadMask: {
                el: grid.getEl()
            },
            success: function(){
                grid.getStore().reload();
            }
        });
    },

    onReImport: function(){
        var importActs = Ext.create('App.views.Acts.Import');
        importActs.show();
    }
});