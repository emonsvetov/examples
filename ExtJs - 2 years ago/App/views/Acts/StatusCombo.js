/**
 * Created by e.monsvetov on 27/01/17.
 */

Ext.define('App.views.Acts.StatusCombo', {
    extend: 'Sjx.form.field.ClearCombo',
    requires: ['App.stores.ActStatuses'],
    store:  'App.stores.ActStatuses',
    hiddenName: 'statusid',
    displayField: 'statusname',
    valueField:   'statusid'
});