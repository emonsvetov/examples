/**
 * Created by e.monsvetov on 16/01/17.
 */

Ext.define('App.models.Acts', {
    extend: 'Sjx.data.Model',
    idProperty: 'actid',
    fields: [
        { name: 'actid',       type: 'int'},
        { name: 'clientid',    type: 'int'},
        { name: 'clientname',  type: 'string'},
        { name: 'billid',      type: 'int'},
        { name: 'billn',       type: 'string'},
        { name: 'actdate',     type: 'date'},
        { name: 'amounttotal', type: 'string'},
        { name: 'actcomment',  type: 'string'},
        { name: 'actnumber',   type: 'string'},
        { name: 'status',      type: 'integer'},
        { name: 'deleted',     type: 'integer'}
    ]
});