/**
 * Created by e.monsvetov on 17/01/17.
 */

Ext.define('App.views.LanguageMessages.List', {

    extend: 'Ext.panel.Panel',

    requires: [
        'App.views.languages.Combo',
        'App.views.LanguageMessages.View',
        'App.views.LanguageTranslationGenerators.ClearCombo'
    ],

    initComponent: function (){

        function reloadGrid(){
            store.getProxy().setExtraParam('langid', langCombo.getValue());
            store.getProxy().setExtraParam('ltgid',  cmb_generators.getValue());
            store.getProxy().setExtraParam('query',  searchFld.getValue());
            store.loadPage(1);
        }

        var langCombo = Ext.create('App.views.languages.Combo', {
            fieldLabel: _js('Language'), labelWidth: 50,
            clearable: true, margin: '0 0 0 5', width: 150,
            value: 1,
            listeners: {
                change: function (cmb, newVal){
                    languageMessagesView.setActiveLanguage(newVal);
                    reloadGrid();
                }
            }
        });

        var cmb_generators = Ext.create('App.views.LanguageTranslationGenerators.ClearCombo',{
            fieldLabel: _js('UI'), labelWidth: 60, margin: '0 0 0 5', width: 225,
            listeners: {
                change: reloadGrid,
                clear: reloadGrid
            }
        });

        var getParams = document.URL.split("query=");
        var urlParams = {
            query: getParams.length > 1 ? decodeURIComponent(decodeURIComponent(getParams[getParams.length - 1])) : ''
        };

        var store = Sjx.getStore('App.stores.LanguageMessages');
        store.getProxy().setExtraParam('langid', 1);
        store.getProxy().setExtraParam('query', urlParams.query);

        var searchFld = Ext.create('Ext.ux.SjxSearchField', {
            width: 190, fieldLabel: _js('Search'), labelWidth: 40, margin: '0 0 0 5',
            listeners: {
                search: reloadGrid,
                clear: reloadGrid
            }
        });

        searchFld.setValue(urlParams.query);

        this.tbar=[
            langCombo,
            searchFld,
            Ext.create('Ext.form.field.ComboBox', {
                width: 190, fieldLabel: _js('Статус'), labelWidth: 40, margin: '0 0 0 5',
                displayField: 'statusname', valueField: 'statusid',
                store: Ext.create('Ext.data.Store', {
                    fields: ['statusid', 'statusname'],
                    data: [
                        {"statusid": 1, "statusname": _js('All')},
                        {"statusid": 2, "statusname": _js('Translated')},
                        {"statusid": 3, "statusname": _js('Not Translated')}
                    ]
                }),
                listeners: {
                    select: function(cmb, records){
                        if(records[0].get('statusid') == 1){
                            store.getProxy().setExtraParam('status', -1);
                        }else if(records[0].get('statusid') == 2){
                            store.getProxy().setExtraParam('status', 1);
                        }else{
                            store.getProxy().setExtraParam('status', 0);
                        }
                        reloadGrid();
                    }
                }
            }), cmb_generators
        ];

        var languageMessagesView = Ext.create('App.views.LanguageMessages.View',{});
        languageMessagesView.setActiveLanguage(1);

        this.items = [languageMessagesView];
        this.callParent(arguments);
    },

    border: 0,


    dockedItems: [{
        xtype: 'pagingtoolbar',
        store: 'App.stores.LanguageMessages',
        dock: 'bottom',
        displayInfo: true,
        border: 1
    }]
});