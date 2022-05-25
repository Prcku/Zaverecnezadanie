<!doctype html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport"
content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.plot.ly/plotly-2.12.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/521/fabric.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<style>
    #graph {
        width: 1000px;
    }
</style>
<title>Document</title>
</head>
<body>
<select>
    <option>EN</option>
    <option>SK</option>
</select>
<label for="name" id="names">Name</label>
<input type="text" id="name" name="name">

<label for="textname" id="text">Command</label>
<input type="text" id="textname" name="textname">
<label for="textR" id="text">R</label>
<input type="text" id="textR" name="textR">
<button type="button" id = "button" onclick="Post_INVENTOR()">Send</button>
<span>

</span>

<div id="answerDiv"></div>

<canvas id="animation" width="600" height="550"></canvas>

<div id="graph"></div>
<button type="button" class="butt" onclick="">test</button>
<img src="ziczac.png" id="img" width="159" height="318" style="display: none;">

<script>
    const socket = new WebSocket('wss://site163.webte.fei.stuba.sk:9000');

    let language = document.querySelector("select");
    let button = document.querySelector("button");

    let trace1Name = "Automobile", traceName = "Wheel", titleGraph = "Graph"

    language.addEventListener('change',() =>{
        let labelCommand = document.getElementById("text");
        let name = document.getElementById("names");
        if (language.value == "EN"){
            button.innerHTML = "Send";
            labelCommand.innerText = "Command"
            name.innerHTML = "Name"
            trace1Name = "Automobile"
            traceName = "Wheel"
            titleGraph = "Graph"
        }
        if (language.value == "SK"){
            button.innerHTML = "Odoslať";
            labelCommand.innerText = "Príkaz"
            name.innerHTML = "Meno"
            trace1Name = "Karoséria"
            traceName = "Koleso"
            titleGraph = "Graf"
        }
    })

    const Post_INVENTOR = () => {
        let canvas = document.getElementById('animation');

        let numbers = []
        let inputCommand = document.getElementById("name");
        if (inputCommand.value.length == 0){
            inputCommand.value = "anonym"
        }
        const answerDiv = document.querySelector("#answerDiv");
        answerDiv.innerHTML = "";
        let text = document.getElementById("textname");
        let r = document.getElementById("textR");
        text = encodeURIComponent(text.value);
        answerDiv.innerHTML = "";
        if(text.length != 0){
            fetch(`/zaverecnezadanie/ServiceApi.php?name=`+inputCommand.value+`&command=`+text ,{
                method: 'GET',
                headers: {
                    'x-api-key': 'asdfwe4q489qfweasd'
                }
            }).then(response => response.json()).catch(error => console.log(error))
                .then(result => answerDiv.innerHTML = JSON.stringify(result,undefined,4));
        }
        if(r.value.length != 0) {
            fetch(`/zaverecnezadanie/ServiceApi.php?name=`+inputCommand.value+`&r=`+r.value ,{
                method: 'GET',
                headers: {
                    'x-api-key': 'asdfwe4q489qfweasd'
                }
            }).then(response => response.json()).catch(error => console.log(error))
                .then(
                    result => {
                        let animate;
                        //answerDiv.innerHTML = JSON.stringify(result, undefined, 4);
                        numbers = result;

                        let dataX = []
                        let dataY = []
                        let dataD = []
                        //let initialNumbers = numbers[0]

                        for (let i = 1; i < numbers.length; i++) {
                            dataX.push(numbers[i][1])
                            dataY.push(numbers[i][3])
                            dataD.push(numbers[i][2])
                        }
                        let inputCommandName = document.getElementById("name");
                        if (inputCommandName.value.length == 0){
                            inputCommandName.value = "anonym"
                        }


                        // GRAF
                        const graph = document.getElementById("graph");

                        let trace = {
                            name: traceName,
                            x: dataX,
                            y: dataY,
                            mode: 'lines'
                        };

                        let trace1 = {
                            name: trace1Name,
                            x:  dataX,
                            y:  dataD,
                            mode: 'lines'
                        };

                        let data = [trace, trace1];

                        let layout = {
                            title: titleGraph
                        };

                        new Add(dataX, dataY, dataD);
                        Plotly.newPlot(graph, data, layout);

                        let cnt = 0;

                        let interval = setInterval(function() {
                            let update1 = {
                                x: [dataX],
                                y: [dataY]
                            }
                            const grafData = {
                                "method": "grafData",
                                "name":   inputCommandName.value,
                                "XdataX": dataX,
                                "YdataY": dataY,
                                "DdataD": dataD,
                                "RdataR": r.value
                            }
                            socket.send(JSON.stringify(grafData));
                            //posielanie dat na websocket

                            Plotly.extendTraces(graph, update1, [0])

                            if(++cnt === 2000) clearInterval(interval);
                            document.getElementById("button").addEventListener('click', () => {
                                clearInterval(interval);
                            })
                        }, 50);


                        // ANIMATION
                        function Add(dataXx, dataYy, dataDd) {
                            let canvas = this.__canvas = new fabric.Canvas('animation');

                            fabric.Object.prototype.transparentCorners = true;

                            let car = new fabric.Rect({
                                left: 200,
                                top: 400,
                                fill: 'blue',
                                width: 100,
                                height: 40,
                                objectCaching: false,
                                stroke: 'lightgray',
                                strokeWidth: 3,
                                selectable: false
                            });

                            let wheel = new fabric.Rect({
                                left: 210,
                                top: 350,
                                fill: 'orange',
                                width: 80,
                                height: 30,
                                objectCaching: false,
                                stroke: 'lightgray',
                                strokeWidth: 3,
                                selectable: false
                            });

                            let line = new fabric.Line([100, 20, 100, 500], {
                                stroke: 'black',
                                selectable: false
                            })

                            let lineBottom = new fabric.Line([100, 500, 450, 500], {
                                stroke: 'black',
                                selectable: false
                            })

                            let zeroLine = new fabric.Line([90, 420, 110, 420], {
                                stroke: 'black',
                                selectable: false
                            })

                            let zeroNumber = new fabric.Text('0', {
                                fontFamily: 'Delicious_500',
                                fontSize: 15,
                                left: 70,
                                top: 410,
                                selectable: false
                            })

                            let topLine = new fabric.Line([90, 330, 110, 330], {
                                stroke: 'black',
                                selectable: false
                            })

                            let topNumber = new fabric.Text('0.15', {
                                fontFamily: 'Delicious_500',
                                fontSize: 15,
                                left: 50,
                                top: 320,
                                selectable: false
                            })

                            let topTopLine = new fabric.Line([90, 240, 110, 240], {
                                stroke: 'black',
                                selectable: false
                            })

                            let topTopNumber = new fabric.Text('0.3', {
                                fontFamily: 'Delicious_500',
                                fontSize: 15,
                                left: 60,
                                top: 230,
                                selectable: false
                            })

                            let topTopTopLine = new fabric.Line([90, 150, 110, 150], {
                                stroke: 'black',
                                selectable: false
                            })

                            let topTopTopNumber = new fabric.Text('0.45', {
                                fontFamily: 'Delicious_500',
                                fontSize: 15,
                                left: 50,
                                top: 140,
                                selectable: false
                            })

                            let topTopTopTopLine = new fabric.Line([90, 60, 110, 60], {
                                stroke: 'black',
                                selectable: false
                            })

                            let topTopTopTopNumber = new fabric.Text('0.6', {
                                fontFamily: 'Delicious_500',
                                fontSize: 15,
                                left: 60,
                                top: 50,
                                selectable: false
                            })

                            let ziczac = new fabric.Image(document.getElementById("img"), {
                                top: 350,
                                left: 230,
                                scaleX: 0.2,
                                scaleY: 0.2
                            })

                            if (r.value > 0) {
                                animate = setInterval(animateUp, 50);
                            }

                            if (r.value < 0) {
                                animate = setInterval(animateDown, 50);
                            }

                            function animateUp() {
                                let numberCar = dataYy[0];
                                let numberWheel = dataDd[0];

                                let scale = getScale (r.value, numberWheel);

                                wheel.animate({left: 210, top: 350 - 500 * numberWheel}, {
                                    onChange: canvas.renderAll.bind(canvas),
                                    duration: 2000,
                                    easing: fabric.util.ease.easeOutExpo
                                });

                                car.animate({left: 200, top: 400 - 500 * numberCar}, {
                                    onChange: canvas.renderAll.bind(canvas),
                                    duration: 2000,
                                    easing: fabric.util.ease.easeOutExpo
                                });

                                ziczac.animate({left: 230, bottom: 400,
                                    top: 360 - 500 * numberWheel, scaleY: scale*numberWheel+scale}, {
                                    onChange: canvas.renderAll.bind(canvas),
                                    duration: 2000,
                                    easing: fabric.util.ease.easeOutExpo
                                });

                                document.getElementById("button").addEventListener('click', () => {
                                    clearInterval(animate);
                                })

                                dataXx = arrayRotate(dataXx);
                                dataYy = arrayRotate(dataYy);
                                dataDd = arrayRotate(dataDd);
                            }

                            function animateDown() {
                                let numberCar = dataYy[0];
                                let numberWheel = dataDd[0];

                                let scale = getScale (r.value, Math.abs(numberWheel));

                                wheel.animate({left: 210, top: 400 + 500 * numberWheel}, {
                                    onChange: canvas.renderAll.bind(canvas),
                                    duration: 2000,
                                    easing: fabric.util.ease.easeOutExpo
                                });

                                car.animate({left: 200, top: 400 + 500 * numberCar}, {
                                    onChange: canvas.renderAll.bind(canvas),
                                    duration: 2000,
                                    easing: fabric.util.ease.easeOutExpo
                                });

                                ziczac.animate({left: 230, bottom: 400,
                                    top: 400 + 500 * numberWheel, scaleY: scale*numberWheel+scale}, {
                                    onChange: canvas.renderAll.bind(canvas),
                                    duration: 2000,
                                    easing: fabric.util.ease.easeOutExpo
                                });

                                document.getElementById("button").addEventListener('click', () => {
                                    clearInterval(animate);
                                })

                                dataXx = arrayRotate(dataXx);
                                dataYy = arrayRotate(dataYy);
                                dataDd = arrayRotate(dataDd);
                            }

                            canvas.add(ziczac);
                            canvas.add(car);
                            canvas.add(wheel);
                            canvas.add(line);
                            canvas.add(lineBottom);
                            canvas.add(zeroLine);
                            canvas.add(zeroNumber);
                            canvas.add(topLine);
                            canvas.add(topNumber);
                            canvas.add(topTopLine);
                            canvas.add(topTopNumber);
                            canvas.add(topTopTopLine);
                            canvas.add(topTopTopNumber);
                            canvas.add(topTopTopTopLine);
                            canvas.add(topTopTopTopNumber);


                        }
                    })
        }
    }



    function arrayRotate(arr) {
        arr.push(arr.shift())
        return arr;
    }

    function getScale(r, numberWheel) {
        let scale = 0.1;

        if (Math.abs(r) == 0.1) {
            if (numberWheel < 0.18) {
                scale = 0.3;
            }
            if (numberWheel < 0.12) {
                scale = 0.25;
            } else {
                scale = 0.45;
            }
        }
        if (Math.abs(r) == 0.2) {
            scale = 0.5;
            if (numberWheel < 0.29) {
                scale = 0.45;
            }
            if (numberWheel < 0.15) {
                scale = 0.3;
            }
        }
        if (Math.abs(r) == 0.3) {

            if (numberWheel < 0.30) {
                scale = 0.5;
            }
            if (numberWheel < 0.15) {
                scale = 0.3;
            } else {
                scale = 0.6;
            }
        }
        if (Math.abs(r) == 0.4) {
            if (numberWheel < 0.3) {
                scale = 0.28;
            }
            if (numberWheel < 0.46) {
                scale = 0.3;
            }
            if (numberWheel < 0.25) {
                scale = 0.25;
            } if (numberWheel < 0.15) {
                scale = 0.2;
            }else {
                scale = 0.63;
            }
        }
        if (Math.abs(r) == 0.5) {
            if (numberWheel < 0.62) {
                scale = 0.5;
            }
            if (numberWheel < 0.53) {
                scale = 0.38;
            }
            if (numberWheel < 0.45) {
                scale = 0.3;
            }
            if (numberWheel < 0.31) {
                scale = 0.25;
            }
            if (numberWheel < 0.15) {
                scale = 0.2;
            }
            else {
                scale = 0.71;
            }
        } return scale;
    }

    socket.addEventListener("message",msg => {
        let test2 = JSON.parse(msg.data)
        console.log(test2)
        //let div = document.getElementsByTagName("span");
        let div = document.querySelector("span")
        console.log(div)
        div.innerHTML='';
        let table= document.createElement("table");
        let th = document.createElement("th");
        table.appendChild(th);
        let tbody = document.createElement("tbody");
        for (let i = 0; i < test2.length;i++){
            let tr = document.createElement("tr");
            let butt = document.createElement("button");
            // butt.classList.add('butt')
            butt.addEventListener('click', () => {
                console.log("test")
                socket.send(JSON.stringify(test2[i][1].name))
            })
            butt.innerText = test2[i][1].name;
            tr.appendChild(butt);
            tbody.appendChild(tr);
        }


        table.appendChild(tbody);
        table.style.display = 'block';
        div.appendChild(table);
        if (test2.method == "grafData") {
            console.log("jebebebeee")
            let canvas = document.getElementById('animation');
            const answerDiv = document.querySelector("#answerDiv");
            answerDiv.innerHTML = "";
            let animate;
            //answerDiv.innerHTML = JSON.stringify(result, undefined, 4);
            let trace1Name = "Automobile", traceName = "Wheel", titleGraph = "Graph"
                let dataX = test2.XdataX

            let dataY = test2.YdataY
            let dataD = test2.DdataD
            let r = test2.RdataR
            //let initialNumbers = numbers[0]
                // GRAF

            const graph = document.getElementById("graph");
                let trace = {
                    name: traceName,
                    x: dataX,
                    y: dataY,
                    mode: 'lines'
                };

                let trace1 = {
                    name: trace1Name,
                    x: dataX,
                    y: dataD,
                    mode: 'lines'
                };

                let data = [trace, trace1];

                let layout = {
                    title: titleGraph
                };

                new Add(dataX, dataY, dataD);

            Plotly.newPlot(graph, data, layout);
                let cnt = 0;

                let interval = setInterval(function () {
                    let update1 = {
                        x: [dataX],
                        y: [dataY]
                    }
                    //posielanie dat na websocket

                    // const grafData = {
                    //     "method": "grafData",
                    //     "XdataX": dataX,
                    //     "YdataY": dataY
                    // }
                    // socket.send(JSON.stringify(grafData));

                    Plotly.extendTraces(graph, update1, [0])

                    if (++cnt === 2000) clearInterval(interval);
                    document.getElementById("button").addEventListener('click', () => {
                        clearInterval(interval);
                    })
                }, 50);

                // ANIMATION


            function Add(dataXx, dataYy, dataDd) {
                let canvas = this.__canvas = new fabric.Canvas('animation');
                    fabric.Object.prototype.transparentCorners = true;

                    let car = new fabric.Rect({
                        left: 200,
                        top: 400,
                        fill: 'blue',
                        width: 100,
                        height: 40,
                        objectCaching: false,
                        stroke: 'lightgray',
                        strokeWidth: 3,
                        selectable: false
                    });

                    let wheel = new fabric.Rect({
                        left: 210,
                        top: 350,
                        fill: 'orange',
                        width: 80,
                        height: 30,
                        objectCaching: false,
                        stroke: 'lightgray',
                        strokeWidth: 3,
                        selectable: false
                    });

                    let line = new fabric.Line([100, 20, 100, 500], {
                        stroke: 'black',
                        selectable: false
                    })

                    let lineBottom = new fabric.Line([100, 500, 450, 500], {
                        stroke: 'black',
                        selectable: false
                    })

                    let zeroLine = new fabric.Line([90, 420, 110, 420], {
                        stroke: 'black',
                        selectable: false
                    })

                    let zeroNumber = new fabric.Text('0', {
                        fontFamily: 'Delicious_500',
                        fontSize: 15,
                        left: 70,
                        top: 410,
                        selectable: false
                    })

                    let topLine = new fabric.Line([90, 330, 110, 330], {
                        stroke: 'black',
                        selectable: false
                    })

                    let topNumber = new fabric.Text('0.15', {
                        fontFamily: 'Delicious_500',
                        fontSize: 15,
                        left: 50,
                        top: 320,
                        selectable: false
                    })

                    let topTopLine = new fabric.Line([90, 240, 110, 240], {
                        stroke: 'black',
                        selectable: false
                    })

                    let topTopNumber = new fabric.Text('0.3', {
                        fontFamily: 'Delicious_500',
                        fontSize: 15,
                        left: 60,
                        top: 230,
                        selectable: false
                    })

                    let topTopTopLine = new fabric.Line([90, 150, 110, 150], {
                        stroke: 'black',
                        selectable: false
                    })

                    let topTopTopNumber = new fabric.Text('0.45', {
                        fontFamily: 'Delicious_500',
                        fontSize: 15,
                        left: 50,
                        top: 140,
                        selectable: false
                    })

                    let topTopTopTopLine = new fabric.Line([90, 60, 110, 60], {
                        stroke: 'black',
                        selectable: false
                    })

                    let topTopTopTopNumber = new fabric.Text('0.6', {
                        fontFamily: 'Delicious_500',
                        fontSize: 15,
                        left: 60,
                        top: 50,
                        selectable: false
                    })

                    let ziczac = new fabric.Image(document.getElementById("img"), {
                        top: 350,
                        left: 230,
                        scaleX: 0.2,
                        scaleY: 0.2
                    })

                    if (r > 0) {

                        animate = setInterval(animateUp, 50);
                    }
                    if (r < 0) {

                        animate = setInterval(animateDown, 50);
                    }
                    function animateUp() {

                        let numberCar = dataYy[0];
                        let numberWheel = dataDd[0];
                        let scale = getScale(r, numberWheel);

                        wheel.animate({left: 210, top: 350 - 500 * numberWheel}, {
                            onChange: canvas.renderAll.bind(canvas),
                            duration: 2000,
                            easing: fabric.util.ease.easeOutExpo
                        });

                        car.animate({left: 200, top: 400 - 500 * numberCar}, {
                            onChange: canvas.renderAll.bind(canvas),
                            duration: 2000,
                            easing: fabric.util.ease.easeOutExpo
                        });

                        ziczac.animate({
                            left: 230, bottom: 400,
                            top: 360 - 500 * numberWheel, scaleY: scale * numberWheel + scale
                        }, {
                            onChange: canvas.renderAll.bind(canvas),
                            duration: 2000,
                            easing: fabric.util.ease.easeOutExpo
                        });

                        document.getElementById("button").addEventListener('click', () => {
                            clearInterval(animate);
                        })

                        dataXx = arrayRotate(dataXx);

                        dataYy = arrayRotate(dataYy);
                        dataDd = arrayRotate(dataDd);
                    }
                    function animateDown() {

                        let numberCar = dataYy[0];
                        let numberWheel = dataDd[0];
                        let scale = getScale(r, Math.abs(numberWheel));

                        wheel.animate({left: 210, top: 400 + 500 * numberWheel}, {
                            onChange: canvas.renderAll.bind(canvas),
                            duration: 2000,
                            easing: fabric.util.ease.easeOutExpo
                        });

                        car.animate({left: 200, top: 400 + 500 * numberCar}, {
                            onChange: canvas.renderAll.bind(canvas),
                            duration: 2000,
                            easing: fabric.util.ease.easeOutExpo
                        });

                        ziczac.animate({
                            left: 230, bottom: 400,
                            top: 400 + 500 * numberWheel, scaleY: scale * numberWheel + scale
                        }, {
                            onChange: canvas.renderAll.bind(canvas),
                            duration: 2000,
                            easing: fabric.util.ease.easeOutExpo
                        });

                        document.getElementById("button").addEventListener('click', () => {
                            clearInterval(animate);
                        })

                        dataXx = arrayRotate(dataXx);

                        dataYy = arrayRotate(dataYy);
                        dataDd = arrayRotate(dataDd);
                    }
                    canvas.add(ziczac);

                canvas.add(car);
                canvas.add(wheel);
                canvas.add(line);
                canvas.add(lineBottom);
                canvas.add(zeroLine);
                canvas.add(zeroNumber);
                canvas.add(topLine);
                canvas.add(topNumber);
                canvas.add(topTopLine);
                canvas.add(topTopNumber);
                canvas.add(topTopTopLine);
                canvas.add(topTopTopNumber);
                canvas.add(topTopTopTopLine);
                canvas.add(topTopTopTopNumber);
            }
        }
            function arrayRotate(arr) {

                arr.push(arr.shift())
                return arr;
            }
            function getScale(r, numberWheel) {

                let scale = 0.1;
                if (Math.abs(r) == 0.1) {

                    if (numberWheel < 0.18) {
                        scale = 0.3;
                    }
                    if (numberWheel < 0.12) {
                        scale = 0.25;
                    } else {
                        scale = 0.45;
                    }
                }
                if (Math.abs(r) == 0.2) {
                    scale = 0.5;
                    if (numberWheel < 0.29) {
                        scale = 0.45;
                    }
                    if (numberWheel < 0.15) {
                        scale = 0.3;
                    }
                }
                if (Math.abs(r) == 0.3) {
                    if (numberWheel < 0.30) {

                        scale = 0.5;
                    }
                    if (numberWheel < 0.15) {
                        scale = 0.3;
                    } else {
                        scale = 0.6;
                    }
                }
                if (Math.abs(r) == 0.4) {
                    if (numberWheel < 0.3) {
                        scale = 0.28;
                    }
                    if (numberWheel < 0.46) {
                        scale = 0.3;
                    }
                    if (numberWheel < 0.25) {
                        scale = 0.25;
                    }
                    if (numberWheel < 0.15) {
                        scale = 0.2;
                    } else {
                        scale = 0.63;
                    }
                }
                if (Math.abs(r) == 0.5) {
                    if (numberWheel < 0.62) {
                        scale = 0.5;
                    }
                    if (numberWheel < 0.53) {
                        scale = 0.38;
                    }
                    if (numberWheel < 0.45) {
                        scale = 0.3;
                    }
                    if (numberWheel < 0.31) {
                        scale = 0.25;
                    }
                    if (numberWheel < 0.15) {
                        scale = 0.2;
                    } else {
                        scale = 0.71;
                    }
                }
                return scale;
            }
    })


    // for (let i=0;i<test2.length;i++){
    //
    // }

</script>
<script>
    // $(document).ready(function() {
    // $("button").click(function() {
    //     console.log($(this).text())
    // })})
    // $(document).ready(function() {
    //     $('.butt').click(function() {
    //         console.log("neroooobbb")
    //         console.log($(this).text())
    //         let jjj = $(this).text()
    //         socket.send(JSON.stringify(jjj))
    //     })
    // });

    // let canvas = document.getElementById('animation');
    //
    // let numbers = []
    // let inputCommand = document.getElementById("name");
    // if (inputCommand.value.length == 0) {
    //     inputCommand.value = "anonym"
    // }
    // const answerDiv = document.querySelector("#answerDiv");
    // answerDiv.innerHTML = "";
    // let text = document.getElementById("textname");
    // let r = document.getElementById("textR");
    // text = encodeURIComponent(text.value);
    // answerDiv.innerHTML = "";
    // if (text.length != 0) {
    //     fetch(`/zaverecnezadanie/ServiceApi.php?name=` + inputCommand.value + `&command=` + text, {
    //         method: 'GET',
    //         headers: {
    //             'x-api-key': 'asdfwe4q489qfweasd'
    //         }
    //     }).then(response => response.json()).catch(error => console.log(error))
    //         .then(result => answerDiv.innerHTML = JSON.stringify(result, undefined, 4));
    // }
    // if (r.value.length != 0) {
    //     fetch(`/zaverecnezadanie/ServiceApi.php?name=` + inputCommand.value + `&r=` + r.value, {
    //         method: 'GET',
    //         headers: {
    //             'x-api-key': 'asdfwe4q489qfweasd'
    //         }
    //     }).then(response => response.json()).catch(error => console.log(error))
    //         .then(
    //             result => {
    //                 let animate;
    //                 //answerDiv.innerHTML = JSON.stringify(result, undefined, 4);
    //                 numbers = result;
    //
    //                 let dataX = []
    //                 let dataY = []
    //                 let dataD = []
    //                 //let initialNumbers = numbers[0]
    //
    //                 for (let i = 1; i < numbers.length; i++) {
    //                     dataX.push(numbers[i][1])
    //                     dataY.push(numbers[i][3])
    //                     dataD.push(numbers[i][2])
    //                 }
    //
    //                 //branko
    //                 let inputCommandName = document.getElementById("name");
    //                 if (inputCommandName.value.length == 0){
    //                     inputCommandName.value = "anonym"
    //                 }
    //                 //
    //
    //                 // GRAF
    //                 const graph = document.getElementById("graph");
    //
    //                 let trace = {
    //                     name: traceName,
    //                     x: dataX,
    //                     y: dataY,
    //                     mode: 'lines'
    //                 };
    //
    //                 let trace1 = {
    //                     name: trace1Name,
    //                     x: dataX,
    //                     y: dataD,
    //                     mode: 'lines'
    //                 };
    //
    //                 let data = [trace, trace1];
    //
    //                 let layout = {
    //                     title: titleGraph
    //                 };
    //
    //                 new Add(dataX, dataY, dataD);
    //                 Plotly.newPlot(graph, data, layout);
    //
    //                 let cnt = 0;
    //
    //                 let interval = setInterval(function() {
    //                     let update1 = {
    //                         x: [dataX],
    //                         y: [dataY]
    //                     }
    //                     //branko
    //
    //                     const grafData = {
    //                         "method": "grafData",
    //                         "name":   inputCommandName.value,
    //                         "XdataX": dataX,
    //                         "YdataY": dataY,
    //                         "DdataD": dataD,
    //                         "RdataR": r.value
    //                     }
    //                     socket.send(JSON.stringify(grafData));
    //                     //
    //
    //                     Plotly.extendTraces(graph, update1, [0])
    //
    //                     if (++cnt === 2000) {
    //                         clearInterval(interval);
    //                     }
    //                     document.getElementById("button").addEventListener('click', () => {
    //                         clearInterval(interval);
    //                         clearInterval(animate);
    //                     })
    //                 }, 50);
    //
    //                 // ANIMATION
    //                 function Add(dataXx, dataYy, dataDd) {
    //
    //                     let canvas = this.__canvas = new fabric.Canvas('animation');
    //
    //                     fabric.Object.prototype.transparentCorners = true;
    //
    //                     let car = new fabric.Circle({radius: 30,
    //                         fill: 'blue',
    //                         originX: 'center',
    //                         originY: 'center',
    //                         left: 250,
    //                         top: 350+30,
    //                         objectCaching: false,
    //                         stroke: 'lightgray',
    //                         strokeWidth: 3,
    //                         selectable: false
    //                     })
    //
    //                     let wheel = new fabric.Rect({
    //                         left: 210,
    //                         top: 350,
    //                         fill: 'orange',
    //                         width: 80,
    //                         height: 30,
    //                         objectCaching: false,
    //                         stroke: 'lightgray',
    //                         strokeWidth: 3,
    //                         selectable: false
    //                     });
    //
    //                     let line = new fabric.Line([100, 20, 100, 500], {
    //                         stroke: 'black',
    //                         selectable: false
    //                     })
    //
    //                     let lineBottom = new fabric.Line([100, 500, 450, 500], {
    //                         stroke: 'black',
    //                         selectable: false
    //                     })
    //
    //                     let zeroLine = new fabric.Line([90, 420, 110, 420], {
    //                         stroke: 'black',
    //                         selectable: false
    //                     })
    //
    //                     let zeroNumber = new fabric.Text('0', {
    //                         fontFamily: 'Delicious_500',
    //                         fontSize: 15,
    //                         left: 70,
    //                         top: 410,
    //                         selectable: false
    //                     })
    //
    //                     let topLine = new fabric.Line([90, 330, 110, 330], {
    //                         stroke: 'black',
    //                         selectable: false
    //                     })
    //
    //                     let topNumber = new fabric.Text('0.15', {
    //                         fontFamily: 'Delicious_500',
    //                         fontSize: 15,
    //                         left: 50,
    //                         top: 320,
    //                         selectable: false
    //                     })
    //
    //                     let topTopLine = new fabric.Line([90, 240, 110, 240], {
    //                         stroke: 'black',
    //                         selectable: false
    //                     })
    //
    //                     let topTopNumber = new fabric.Text('0.3', {
    //                         fontFamily: 'Delicious_500',
    //                         fontSize: 15,
    //                         left: 60,
    //                         top: 230,
    //                         selectable: false
    //                     })
    //
    //                     let topTopTopLine = new fabric.Line([90, 150, 110, 150], {
    //                         stroke: 'black',
    //                         selectable: false
    //                     })
    //
    //                     let topTopTopNumber = new fabric.Text('0.45', {
    //                         fontFamily: 'Delicious_500',
    //                         fontSize: 15,
    //                         left: 50,
    //                         top: 140,
    //                         selectable: false
    //                     })
    //
    //                     let topTopTopTopLine = new fabric.Line([90, 60, 110, 60], {
    //                         stroke: 'black',
    //                         selectable: false
    //                     })
    //
    //                     let topTopTopTopNumber = new fabric.Text('0.6', {
    //                         fontFamily: 'Delicious_500',
    //                         fontSize: 15,
    //                         left: 60,
    //                         top: 50,
    //                         selectable: false
    //                     })
    //
    //                     if (r.value > 0) {
    //                         animate = setInterval(animateUp, 50);
    //                     }
    //
    //                     if (r.value < 0) {
    //                         animate = setInterval(animateDown, 50);
    //                     }
    //
    //                     let cnt = 0;
    //                     function animateUp() {
    //
    //                         let numberCar = dataYy[0];
    //                         let numberWheel = dataDd[0];
    //
    //                         if (++cnt === 2000) {
    //                             console.log("reset");
    //                             clearInterval(animate);
    //                         }
    //
    //                         wheel.animate({
    //                             left: 210,
    //                             top: 350 - 500 * numberWheel
    //                         }, {
    //                             onChange: canvas.renderAll.bind(canvas),
    //                             duration: 2000,
    //                             easing: fabric.util.ease.easeOutExpo
    //                         });
    //
    //                         car.animate({
    //                             left: 250,
    //                             top: 400 - 500 * numberCar
    //                         }, {
    //                             onChange: canvas.renderAll.bind(canvas),
    //                             duration: 2000,
    //                             easing: fabric.util.ease.easeOutExpo
    //                         });
    //
    //                         dataXx = rotate(dataXx);
    //                         dataYy = rotate(dataYy);
    //                         dataDd = rotate(dataDd);
    //                     }
    //
    //                     function animateDown() {
    //                         let numberCar = dataYy[0];
    //                         let numberWheel = dataDd[0];
    //
    //                         wheel.animate({
    //                             left: 210,
    //                             top: 400 + 500 * numberWheel
    //                         }, {
    //                             onChange: canvas.renderAll.bind(canvas),
    //                             duration: 2000,
    //                             easing: fabric.util.ease.easeOutExpo
    //                         });
    //
    //                         car.animate({
    //                             left: 250,
    //                             top: 400 + 500 * numberCar
    //                         }, {
    //                             onChange: canvas.renderAll.bind(canvas),
    //                             duration: 2000,
    //                             easing: fabric.util.ease.easeOutExpo
    //                         });
    //
    //                         dataXx = rotate(dataXx);
    //                         dataYy = rotate(dataYy);
    //                         dataDd = rotate(dataDd);
    //                     }
    //
    //                     canvas.add(car);
    //                     canvas.add(wheel);
    //                     canvas.add(line);
    //                     canvas.add(lineBottom);
    //                     canvas.add(zeroLine);
    //                     canvas.add(zeroNumber);
    //                     canvas.add(topLine);
    //                     canvas.add(topNumber);
    //                     canvas.add(topTopLine);
    //                     canvas.add(topTopNumber);
    //                     canvas.add(topTopTopLine);
    //                     canvas.add(topTopTopNumber);
    //                     canvas.add(topTopTopTopLine);
    //                     canvas.add(topTopTopTopNumber);
    //                 }
    //             })
    //     inputCommand.value = ""
    // }
    // }
    //
    // let rotate = function (arr) {
    //     arr.push(arr.shift())
    //     return arr;
    // };
</script>
</body>
</html>