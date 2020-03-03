/**
 * Created by e.monsvetov on 17/01/17.
 */

/**
 * Created by e.monsvetov on 16/01/17.
 */

Ext.define('App.models.LanguageMessages', {

    extend: 'Sjx.data.Model',
    idProperty: 'lngmsgid',
    fields: [
        {name: 'lngmsgid',        type: 'int'},
        {name: 'lngmsgtranstext', type: 'string'},
        {name: 'lngmsgcomment',   type: 'string'},
        {name: 'lngmsgcoderef',   type: 'string'},
        {name: 'translations',    type: 'string'},
        {name: 'transtext',       type: 'object'}
    ]
});