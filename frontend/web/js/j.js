Ext.require([
    'Ext.grid.*',
    'Ext.data.*',
    'Ext.panel.*',
    'Ext.layout.container.Border'
]);

Ext.onReady(function(){
    Ext.define('Log',{
        extend: 'Ext.data.Model',
        proxy: {
            type: 'ajax',
            reader: 'json'
        },
        fields: [
            'ip',
            'browser',
            'os',
            'url_cnt_unique',
            'First url from',
            'Last url to'
        ]
    });

    var store = Ext.create('Ext.data.Store', {
        model: 'Log',
        proxy: {
            type: 'ajax',
            url: '/?r=site/log',
            reader: {
                type: 'json',
                record: 'Item',
                totalProperty  : 'total'
            }
        }
    });

    var grid = Ext.create('Ext.grid.Panel', {
        bufferedRenderer: false,
        store: store,
        columns: [
            {text: "Ip", width: 120, dataIndex: 'ip', sortable: false},
            {text: "Browser", width: 250, dataIndex: 'browser', sortable: true},
            {text: "Os", width: 125, dataIndex: 'os', sortable: true},
            {text: "Url cnt unique", width: 125, dataIndex: 'url_cnt_unique', sortable: false},
            {text: "First url from", width: 125, dataIndex: 'first_url_from', sortable: false},
            {text: "Last url to", width: 125, dataIndex: 'last_url_to', sortable: false}
        ],
        forceFit: true,
        height:210,
        split: true,
        region: 'north'
    });

    var logTplMarkup = [
        'Ip: {ip}<br/>',
        'Browser: {browser}<br/>',
        'Os: {os}<br/>',
        'Url cnt unique: {url_cnt_unique}<br/>',
        'First url from: {first_url_from}<br/>',
        'Last url to: {last_url_to}<br/>'
    ];
    var logTpl = Ext.create('Ext.Template', logTplMarkup);

    Ext.create('Ext.Panel', {
        renderTo: 'binding-example',
        frame: true,
        title: 'Log List',
        width: 580,
        height: 400,
        layout: 'border',
        items: [
            grid, {
                id: 'detailPanel',
                region: 'center',
                bodyPadding: 7,
                bodyStyle: "background: #ffffff;",
                html: 'Please select a log to see additional details.'
            }]
    });

    grid.getSelectionModel().on('selectionchange', function(sm, selectedRecord) {
        if (selectedRecord.length) {
            var detailPanel = Ext.getCmp('detailPanel');
            detailPanel.update(logTpl.apply(selectedRecord[0].data));
        }
    });

    store.load();
});