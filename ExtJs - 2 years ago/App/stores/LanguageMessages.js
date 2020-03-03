/**
 * Created by e.monsvetov on 17/01/17.
 */

Ext.define('App.stores.LanguageMessages', {
    extend: 'Sjx.data.Store',
    proxyUrl: 'app.language-messages.grid',
    model: 'App.models.LanguageMessages',
    id: 'LanguageMessagesStore',
    paging: true,
    autoLoad: true,
    pageSize: 100
});