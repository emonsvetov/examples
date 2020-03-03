Ext.define('App.views.Acts.Finder', {
    extend: 'Sjx.Window',

    requires: [
        'App.views.Acts.Grid'
    ],

    config: {
        clientId: null,
        query: null,
        disableClient: false,
        multiSelect: false,
        uniqueStore: false,
        loadOnShow: true,
        filters: [],
        deselectOnShow: true,
        readAction: null
    },

    bodyStyle: {
        background: '#e0e3e8'
    },
    title: _js('Search'),
    layout: 'fit',
    closeAction: 'hide', hideScrollbars: true,
    modal: true,
    autoScroll: true, autoSize:   true,

    initComponent: function(){

        this.addEvents('select', 'add', 'cancel');
        var me = this;

        var hideColumns = ['paytype', 'billid', 'actcomment', 'actions'];

        me.grid = Ext.create('App.views.Acts.Grid', {
            height: 300,
            multiSelect: this.multiSelect,
            uniqueStore: this.uniqueStore,
            width: '100%',
            hideColumns: hideColumns
        });

        me.grid.columns[0].setWidth(150);
        me.grid.columns[1].setWidth(100);

        if( this.readAction ){
            me.grid.getStore().getProxy().setReadAction( me.readAction );
        }

        Ext.each(this.filters, function(filter){
            me.grid.getStore().getProxy().setExtraParam(filter.name, filter.value);
        });

        me.grid.getStore().on('load', function(){
            me.grid.recalcHeight();
            me.recalcHeight(me.grid);
        });

        this.fld_client = Ext.create('Ext.ux.SjxSearchField', {
            width: 440, fieldLabel: _js('Search'), value: me.query,
            labelWidth: 70,
            labelAlign: 'top',
            listeners: {
                search: Ext.bind(this.reload, this),
                clear:  Ext.bind(this.reload, this)
            }
        });

        if(this.disableClient){
            this.fld_client.disable();
        }

        me.form = Ext.create('Ext.form.Panel', {
            border: false,
            items: [{
                xtype: 'fieldcontainer', width: '100%', defaultType: 'textfield',
                layout: 'hbox', margin: '5', items: [this.fld_client, this.fld_search]
            }, me.grid]
        });

        Ext.apply(this, {
            items: [me.form],
            buttons: [
                {text: _js('Select'),  handler: function(){
                        var records = me.grid.getSelectionModel().getSelection();

                        if (!records.length) {
                            Sjx.Msg.error(_js('Error!'))
                        } else {
                            if (me.fireEvent('select', records)) {
                                me.close();
                            };
                        }
                    }},{
                    text: _js('Cancel'),
                    handler: function(button){
                        me.close();
                    }
                }
            ]
        });

        this.callParent(arguments);

        me.on('show', function(){
            if(me.deselectOnShow){
                me.getGrid().getSelectionModel().deselectAll();
            }
        });
    },

    reload: function(){
        this.grid.getStore().getProxy().setExtraParam('query', this.fld_client.getValue());
        this.grid.getStore().reload();
    },

    setClientId: function( clientId ){
        this.query = clientId;

        this.fld_client.setValue(clientId);
    },

    getGrid: function(){
        return this.grid;
    }
});