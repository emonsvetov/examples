/**
 * Created by e.monsvetov on 17/01/17.
 */

Ext.define('App.controllers.LanguageMessages', {
    extend: 'Ext.app.Controller',

    stores: [
        'App.stores.Languages',
        'App.stores.LanguageMessages',
        'App.stores.LanguageMessagesTranslations'
    ],

    views: [
        'App.views.LanguageMessages.List',
        'App.views.LanguageMessages.ScanButton',
        'App.views.LanguageMessagesTranslations.Win',
        'App.views.LanguageMessages.MakeMOButton',
        'App.views.LanguageMessages.AddButton'
    ],

    init: function (){

        this.control({
            'LanguageMessagesScanButton': {
                click: Ext.bind(this.onScan, this)
            },
            'LanguageMessagesView': {
                itemtranslate: Ext.bind(this.onItemTranslate, this)
            },
            'LanguageMessagesMakeMOButton': {
                click: Ext.bind(this.generate, this)
            },
            'LanguageMessagesAddButton': {
                click: Ext.bind(this.showTranslateWin, this)
            }
        });
    },

    onScan: function(){
        var me = this;
        Sjx.Ajax.request({
            url: Sjx.Ajax.getUrl('app.language-messages.scan'),
            loadMask: {
                el: Ext.getBody(),
                msg: _js('loading ...')
            },
            success: function(res){
                me.getStore('App.stores.LanguageMessages').reload();
            }
        });
    },

    generate: function(){
        var me = this;
        Sjx.Ajax.request({
            url: Sjx.Ajax.getUrl('app.language-messages.generate'),
            loadMask: {
                el: Ext.getBody(),
                msg: _js('loading...')
            },
            success: function(res){

                var message = '';
                Ext.each(res.data, function(result){
                    message += _js('Language') + ': ' + result.langname + ' (' + result.filetype + ')' + "<br/>";
                    if(result.success){
                        message += _js('Success!') + "<br/>";
                    }else{
                        message += _js('Command')+': ' + result.command + "<br/>";
                        message += _js('Error')+': ' + result.error + "<br/>";
                    }
                    message += '<hr/>';
                });
                me.getStore('App.stores.LanguageMessages').reload();
            }
        });
    },

    showTranslateWin: function(){

        var records = this.getStore('App.stores.Languages').getRange();

        var model = Ext.create('App.models.LanguageMessages');
        var translateView = Ext.create('App.views.LanguageMessagesTranslations.Win', {
            model: model,
            translations: records
        });

        var me = this;

        translateView.on('save', function(data){
            me.onItemTranslateSave(data, translateView, true);
        });

        translateView.show();
    },

    onItemTranslateSave: function(data, win, addManually){
        var url = data['lngmsgid'] ?
                    'app.language-messages.edit':
                    'app.language-messages.add';

        var me = this;
        data['addManually'] = addManually;

        Sjx.Ajax.request({
            url: Sjx.Ajax.getUrl(url),
            params: {
                data: Ext.JSON.encode(data)
            },
            loadMask: {
                el: win.getEl()
            },
            success: function(res){
                win.close();
                me.getStore('App.stores.LanguageMessages').reload();
            }
        });
    },

    onItemTranslate: function( view, lngmsgid ){

        var loadMask = new Ext.FixLoadMask(view.getEl(), {
            msg: _js('loading...')
        });
        loadMask.show();

        var lmtStore = this.getStore('App.stores.LanguageMessagesTranslations');
        lmtStore.getProxy().setExtraParam('lngmsgid', lngmsgid);

        var me = this;
        lmtStore.load(function(records){

            loadMask.hide();
            var model = view.getStore().findRec( 'lngmsgid', lngmsgid );

            var translateView = Ext.create('App.views.LanguageMessagesTranslations.Win', {
                model: model,
                translations: records
            });

            translateView.on('save', function(data){
                me.onItemTranslateSave(data, translateView, false);
            });

            translateView.show();
        });
    }
});