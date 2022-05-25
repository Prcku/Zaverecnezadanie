const WebSocket = require('ws');
const https = require('https');
const fs = require('fs');

const server = https.createServer({
    cert: fs.readFileSync('webte.fei.stuba.sk-chain-cert.pem'),
    key: fs.readFileSync('webte.fei.stuba.sk.key')
});
server.listen(9000);
const ws = new WebSocket.Server( { server } );


const allData = new Map();
let id= -1;

ws.on('connection',(socket) => {
    console.log("New Connection");
    socket.id = ++id;
   const entries = Array.from(allData);
   socket.send(JSON.stringify(entries));
    socket.on("message", (data) => {
        const result = JSON.parse(data);

        if (result.method == "grafData"){
            allData.set(socket.id,result);
            const entries = Array.from(allData);
            socket.send(JSON.stringify(entries));
        }else {
            allData.forEach( element => {
                // console.log(element)
                if (element.name == result){
                    // const entries = Array.from(element);
                    socket.send(JSON.stringify(element));
                }
            })
        }
    });
    socket.addEventListener('close', event => {

        allData.delete(socket.id)
        console.log(allData);
        console.log("niekto sa odpojil");
    })

});

// ws.on('close', (socket) => {
//     console.log("niekto sa odpojil");
// })

