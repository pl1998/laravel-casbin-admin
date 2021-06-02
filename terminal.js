const pty = require('node-pty');
var url = require('url');
const os = require('os');
const request = require('sync-request');
const WebServer = require('ws');
const shell = os.platform() === 'win32' ? 'powershell.exe' : 'bash';
//链接前进行身份校验
const wss = new WebServer.Server({port: 4001,url:'/webssh',verifyClient:auth});

wss.on('connection', (ws) => {
    const ptyProcess = pty.spawn(shell, [], {
        name: 'xterm-color',
        cols: 80,
        rows: 30,
        cwd: process.env.HOME,
        env: process.env
    });
    //接受数据
    ws.on('message', (res) => {
        ptyProcess.write(res)
    });
    //发送数据
    ptyProcess.on('data', function (data) {
        process.stdout.write(data);
        ws.send(data)
    });
});

/**
 * 同步调用PHP接口进行校验
 * @param info
 * @returns {boolean}
 * @constructor
 */
function auth(info){
    let fat = false;
    var params = url.parse(info.req.url, true).query;
    if(params['api'] && params['token']) {
       var res =  request('GET',params['api']+"/admin/terminal",{
            'headers': {
                'Authorization': 'Bearer '+params['token']
            }
        })
        let data = JSON.parse(res.getBody('utf8'))
        if(data.code === 200) {
            return true
        }
    }
    return fat
}
