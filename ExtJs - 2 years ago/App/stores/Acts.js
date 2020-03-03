/**
 * Created by e.monsvetov on 10/02/17.
 */

Ext.define('App.stores.Acts', {
    extend: 'Sjx.data.Store',
    model: 'App.models.Acts',
    proxyUrl: 'buch.agts.acts.grid'
});