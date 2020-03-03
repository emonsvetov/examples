/**
 * Created by e.monsvetov on 17/01/17.
 */

Ext.define('App.views.LanguageMessages.View', {

    extend: 'Ext.view.View',
    store: 'App.stores.LanguageMessages',

    alias: 'widget.LanguageMessagesView',

    id: 'LanguageMessagesView',

    initComponent: function(){

        var me = this;

        var languagesStore = Sjx.getStore('App.stores.Languages');
        var template =
            '<div style="min-height: 60px;" >' +
                '<table width="100%" cellpadding="1" border=0 cellspacing="1" class="viewTable"  >'+
                    '<tr>' +
                        '<th align="left">'+_js('Строка')+'</th>'+
                        '{[this.renderThLanguages(values)]}' +
                        '<th width="90" align="center">'+_js('Действия')+'</th>'+
                    '</tr>'+
                    '<tpl for=".">' +
                        '<tr class="tr_item" valign="top">'+
                            '<td>{[this.renderTranslateRow(values)]}</td>' +
                            '{[this.renderTranslate(values)]}' +
                            '<td align="center">'+
                                '<a style="cursor: pointer;" class="translate" lngmsgid="{lngmsgid}" >'+_js('Перевод')+'</a>' +
                            '</td>' +
                        '</tr>'+
                    '</tpl>' +
                    '<tpl if="this.isShowEmptyRow(values)">' +
                        '<tr><td colspan="2" class="noresults" >'+_js('Нет данных')+'</td></tr>'+
                    '</tpl>' +
                '</table>'+
            '</div>';

        this.tpl = new Ext.XTemplate( template, {
            isShowEmptyRow: function(values){
                return Ext.isEmpty(values) && me.getStore().isFirstLoad() ? true : false;
            },

            renderThLanguages: function(values){
                var activeLangId = me.getActiveLanguage();
                var result = '';
                languagesStore.each(function(record){
                    if(record.get('langid') != activeLangId){
                        result += '<th width="20"><img src="'+record.get('langimg')+'" /></th>';
                    }
                });
                return result;
            },

            renderTranslate: function(values){
                var activeLangId = me.getActiveLanguage();
                var result = '';
                languagesStore.each(function(record){
                    if(record.get('langid') != activeLangId){
                        var translate = values.translations[record.get('translationpos')-1];
                        if( parseInt(translate)){
                            result += '<td><img src="/Images/arrow.png" /></td>';
                        }else{
                            result += '<td><img src="/Images/warning.png" /></td>';
                        }
                    }
                });
                return result;
            },

            renderTranslateRow: function(values){
                var activeLangId = me.getActiveLanguage();
                var translation = values.lngmsgtranstext ? values.lngmsgtranstext : _js('Не переведно');
                translation = '<div style="font-size: 14px; white-space: pre-line;" >' + translation + '</div>';
                Ext.each(values.transtext, function(record){
                    if(record['langid'] != activeLangId){
                        translation += '<div style="margin-left: 20px; font-style: italic; color: grey;" >'+record['lngmsgtranstext']+'</div>';
                    }
                });
                return translation;
            }
        });

        this.listeners = {
            click: {
                element: 'el',
                fn: function (e, a){
                    if(Ext.get(a).hasCls('translate')){
                        me.fireEvent('itemtranslate', me, a.getAttribute('lngmsgid'));
                    }
                }
            }
        }

        this.callParent( arguments );
    },
    itemSelector: 'div.link-wrapper',

    setActiveLanguage: function( langId ){
        this.langid = langId;
    },

    getActiveLanguage: function(){
        return this.langid ? this.langid : null;
    }
});