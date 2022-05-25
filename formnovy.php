<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.plot.ly/plotly-2.12.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/521/fabric.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <link href="css/style1.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style2.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Final assignment</title>
</head>

<body>
    <section class="pattern">
    <div class="geeks">
    <header>
        <h1 id="Nadpis">Final assignment</h1>
    </header>

    <div class="container">
        <div class="buttons">
            <div class="col-sm-5 mx-auto" >
                <select class="form-select m-3" aria-label="Default select example">
                    <option>EN</option>
                    <option>SK</option>
                </select>
            </div>
            <form method="post" action="email.php">
                <a class="btn btn-secondary btn-lg h-50 m-3" href="description.php" id="popisApiPdf">User Guide</a>

                <input type="hidden" name="message">
                <button type="submit" name="send_mail_button" class="btn btn-secondary btn-lg m-3" id="sendMail">Send email</button>
                <button type="button" id="button1" onclick="getCSV()" class="btn btn-secondary btn-lg m-3">CSV</button>
            </form>
        </div>
        </section>
    </div>
    <form>
        <div class="form-group col-sm-5 mx-auto">
            <label for="name" id="names">Name</label>
            <input type="text" class="form-control mb-3" id="name" name="name">
            <label for="textname" id="text">Command</label>
            <input type="text" id="textname" name="textname" class="form-control mb-3">
            <div class="container">
                <label for="textR" id="textt">r</label>
                <div class="popup">
                        <input type="number" id="textR" name="textR" min="-0.5" max="0.5" class="form-control mb-3">
                        <span class="popuptext" id="myPopup">Wrong input, range is between 0.5 to -0.5</span>
                </div>
                <button type="button" id="button" onclick="ApiFunction()" class="btn btn-secondary btn-lg m-3">Send</button>
            </div>
        </div>
    </form>
    <div id="tabulka"> </div>
    <div id="checkBox">
        <div id="checkBo">
            <fieldset>
                <div id="checkB">
                    <legend id="legend">Choose:</legend><br>
                </div>
                <div id="check">
                    <div >
                        <input type="checkbox" id="GraphBox" name="GraphBox" checked>
                        <label id="GraphBoxlabel" for="GraphBox">Graph</label>
                    </div>
                    <div>
                        <input type="checkbox" id="AnimationBox" name="AnimationBox" checked>
                        <label id="AnimationBoxlabel" for="AnimationBox">Animation</label>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <div id="answerDiv"></div>
    <div id="graphics">
        <canvas id="animation" width="600" height="450"></canvas>
        <div id="graph" style="width: 60%;"></div>
    </div>
<div class="newLayer" id="newLayer"></div>
</body>
</html>
<script>
    let graphBox = document.getElementById("GraphBox");
    let animationBox = document.getElementById("AnimationBox");
    let graph = document.getElementById("graph");
    let animation = document.getElementById("animation");
    graphBox.addEventListener("change",() => {
        if(graphBox.checked == true){
            graph.style.visibility = "visible";
        }
        else {
            graph.style.visibility = "hidden";
        }
    })
    animationBox.addEventListener("change",() => {
        if(animationBox.checked == true){
            animation.style.visibility = "visible";
        }
        else {
            animation.style.visibility = "hidden";
        }
    })


    //branko
    const socket = new WebSocket('wss://site163.webte.fei.stuba.sk:9000');

    let title = document.querySelector("title");
    let language = document.querySelector("select");
    let button = document.querySelector("button");
    let describeApiInPdfButton = document.getElementById("popisApiPdf");
    let sendMailButton = document.getElementById("sendMail");
    let buttonSendR = document.getElementById("button");
    let userName;

    let trace1Name = "Automobile",
        traceName = "Wheel",
        titleGraph = "Graph"

    language.addEventListener('change', () => {
        let p = document.querySelector("p");
        let legend = document.getElementById("legend");
        let graphBoxLabel = document.getElementById("GraphBoxlabel");
        let animationBoxLabel = document.getElementById("AnimationBoxlabel");
        let labelCommand = document.getElementById("text");
        let name = document.getElementById("names");
        let nadpis = document.getElementById("Nadpis");
        let popup = document.getElementById("myPopup");
        if (language.value == "EN") {
            legend.innerHTML = "Choose"
            p.innerHTML = "Active users"
            graphBoxLabel.innerHTML = "Graph"
            animationBoxLabel.innerHTML = "Animation"
            popup.innerHTML = "Wrong input, range is between -0.5 to 0.5"
            title.innerHTML = "Final assignment"
            button.innerHTML = "Send";
            labelCommand.innerText = "Command"
            name.innerHTML = "Name"
            trace1Name = "Automobile"
            traceName = "Wheel"
            titleGraph = "Graph"
            describeApiInPdfButton.innerHTML = "User Guide"
            sendMailButton.innerHTML = "Send mail"
            buttonSendR.innerHTML = "Send"
            nadpis.innerHTML = "Final assignment"
        }
        if (language.value == "SK") {
            p.innerHTML = "Aktívni používatelia"
            legend.innerHTML = "Výber zobrazenia"
            graphBoxLabel.innerHTML = "Graf"
            animationBoxLabel.innerHTML = "Animácia"
            popup.innerHTML = "Zlý vstup, rozsah hodnôt je medzi -0.5 až 0.5"
            title.innerHTML = "Záverečné zadanie"
            button.innerHTML = "Odoslať";
            labelCommand.innerText = "Príkaz"
            name.innerHTML = "Meno"
            trace1Name = "Karoséria"
            traceName = "Koleso"
            titleGraph = "Graf"
            describeApiInPdfButton.innerHTML = "Používateľská príručka"
            sendMailButton.innerHTML = "Odoslať mail"
            buttonSendR.innerHTML = "Odoslať"
            nadpis.innerHTML = "Záverečné zadanie"
        }
    })
    const getCSV = () => {
            fetch(`/zaverecnezadanie/GetCsv.php`, {
                method: 'GET',
                headers: {
                    'x-api-key': 'asdfwe4q489qfweasd'
                }
            }).then(response => {
                var a = document.createElement('a');

                var link = document.createTextNode("Download CSV");
                
                if (language.value == "SK") {
                    link = document.createTextNode("Stiahnuť CSV");
                }

                // Append the text node to anchor element.
                a.appendChild(link);

                // Set the title.
                a.title = "CSV";

                // Set the href property.
                a.href = "logy.csv";

                // Append the anchor element to the body.
                //document.body.prepend(a);
                var layer = document.getElementById("newLayer")
                document.getElementById("newLayer").style.display = 'block';
                a.className = "aElement";

                var close = document.createElement("div");
                close.className = "close";
                layer.appendChild(close);
                layer.appendChild(a);

                close.addEventListener("click", function () {
                    layer.style.display = 'none';
                    layer.innerHTML = '';
                });
            })
    }
    const ApiFunction = () => {
        let canvas = document.getElementById('animation');
        let popup = document.getElementById("myPopup");
        popup.style.visibility = "hidden";
        let numbers = []
        var inputCommand = document.getElementById("name");
        if (inputCommand.value.length == 0) {
            inputCommand.value = "anonym"
        }
        const answerDiv = document.querySelector("#answerDiv");
        answerDiv.innerHTML = "";
        let text = document.getElementById("textname");
        let r = document.getElementById("textR");
        if(r.value < -0.6 || r.value > 0.6){
            if (inputCommand.value === "anonym") {
                inputCommand.value = "";
            }
            popup.style.visibility = "visible";
            return;
        }
        text = encodeURIComponent(text.value);
        answerDiv.innerHTML = "";
        if (text.length != 0) {
            fetch(`/zaverecnezadanie/ServiceApi.php?name=` + inputCommand.value + `&command=` + text, {
                method: 'GET',
                headers: {
                    'x-api-key': 'asdfwe4q489qfweasd'
                }
            }).then(response => response.json()).catch(error => console.log(error))
                .then(result => answerDiv.innerHTML = JSON.stringify(result, undefined, 4));
            if (inputCommand.value === "anonym") {
                inputCommand.value = "";
            }
        }
        if (r.value.length != 0) {
            fetch(`/zaverecnezadanie/ServiceApi.php?name=` + inputCommand.value + `&r=` + r.value, {
                method: 'GET',
                headers: {
                    'x-api-key': 'asdfwe4q489qfweasd'
                }
            }).then(response => response.json()).catch(error => console.log(error))
                .then(
                    result => {
                        if (inputCommand.value === "anonym") {
                            inputCommand.value = "";
                        }
                        let animate;
                        //answerDiv.innerHTML = JSON.stringify(result, undefined, 4);
                        numbers = result;

                        let dataX = []
                        let dataY = []
                        let dataD = []
                        let dataXa = []
                        let dataYa = []
                        let dataDa = []
                        //let initialNumbers = numbers[0]

                        for (let i = 1; i < numbers.length; i++) {
                            dataX.push(numbers[i][1])
                            dataY.push(numbers[i][3])
                            dataD.push(numbers[i][2])
                        }

                        for (let i = 1; i < numbers.length - 1; i++) {
                            dataXa.push(numbers[i][1])
                            dataYa.push(numbers[i][3])
                            dataDa.push(numbers[i][2])
                        }

                        //branko
                        let inputCommandName = inputCommand;
                        //

                        // GRAF
                        const graph = document.getElementById("graph");

                        let trace = {
                            name: trace1Name,
                            x: dataX,
                            y: dataY,
                            mode: 'lines'
                        };

                        let trace1 = {
                            name: traceName,
                            x: dataX,
                            y: dataD,
                            mode: 'lines'
                        };

                        let data = [trace, trace1];

                        let layout = {
                            title: titleGraph
                        };

                        new Add(dataXa, dataYa, dataDa);
                        Plotly.newPlot(graph, data, layout);

                        let cnt = 0;

                        let interval = setInterval(function () {
                            let update1 = {
                                x: [dataX],
                                y: [dataY]
                            }
                            //branko

                            const grafData = {
                                "method": "grafData",
                                "name": inputCommandName.value,
                                "XdataX": dataX,
                                "YdataY": dataY,
                                "DdataD": dataD,
                                "RdataR": r.value
                            }
                            if (inputCommand.value.length !== 0) {
                                socket.send(JSON.stringify(grafData));
                            }
                            //

                            Plotly.extendTraces(graph, update1, [0])

                            if (++cnt === 2000) {
                                clearInterval(interval);
                            }
                            document.getElementById("button").addEventListener('click', () => {
                                clearInterval(interval);
                                clearInterval(animate);
                            })
                        }, 50);

                        // ANIMATION
                        function Add(dataXx, dataYy, dataDd) {

                            let canvas = this.__canvas = new fabric.Canvas('animation');

                            fabric.Object.prototype.transparentCorners = true;

                            let wheel = new fabric.Circle({
                                radius: 30,
                                fill: 'orange',
                                originX: 'center',
                                originY: 'center',
                                left: 250,
                                top: 350 + 30,
                                objectCaching: false,
                                stroke: 'darkgray',
                                strokeWidth: 3,
                                selectable: false
                            })

                            let car = new fabric.Rect({
                                left: 210,
                                top: 200,
                                fill: 'blue',
                                width: 80,
                                height: 30,
                                objectCaching: false,
                                stroke: 'lightgray',
                                strokeWidth: 3,
                                selectable: false
                            });

                            let lineBottom = new fabric.Line([180, 410, 320, 410], {
                                stroke: 'black',
                                selectable: false
                            })

                            if (r.value > 0) {
                                animate = setInterval(animateUp, 50);
                            }

                            if (r.value < 0) {
                                animate = setInterval(animateDown, 50);
                            }

                            let cnt = 0;

                            function animateUp() {
                                dataX = rotate(dataX);
                                dataY = rotate(dataY);
                                dataD = rotate(dataD);
                                let numberCar = dataYy[0];
                                let numberWheel = dataDd[0];

                                if (++cnt === 2000) {
                                    console.log("reset");
                                    clearInterval(animate);
                                }

                                wheel.animate({
                                    left: 250,
                                    top: 350 - 100 * numberWheel
                                }, {
                                    onChange: canvas.renderAll.bind(canvas),
                                    duration: 2000,
                                    easing: fabric.util.ease.easeOutExpo
                                });

                                lineBottom.animate({
                                    left: 180,
                                    top: 380 - 100 * numberWheel
                                }, {
                                    onChange: canvas.renderAll.bind(canvas),
                                    duration: 2000,
                                    easing: fabric.util.ease.easeOutExpo
                                });

                                car.animate({
                                    left: 210,
                                    top: 200 - 200 * numberCar
                                }, {
                                    onChange: canvas.renderAll.bind(canvas),
                                    duration: 2000,
                                    easing: fabric.util.ease.easeOutExpo
                                });

                                dataXx = rotate(dataXx);
                                dataYy = rotate(dataYy);
                                dataDd = rotate(dataDd);
                            }

                            function animateDown() {
                                dataX = rotate(dataX);
                                dataY = rotate(dataY);
                                dataD = rotate(dataD);
                                let numberCar = dataYy[0];
                                let numberWheel = dataDd[0];

                                car.animate({
                                    left: 210,
                                    top: 200 - 200 * numberCar
                                }, {
                                    onChange: canvas.renderAll.bind(canvas),
                                    duration: 2000,
                                    easing: fabric.util.ease.easeOutExpo
                                });

                                lineBottom.animate({
                                    left: 180,
                                    top: 280 - 100 * numberWheel
                                }, {
                                    onChange: canvas.renderAll.bind(canvas),
                                    duration: 2000,
                                    easing: fabric.util.ease.easeOutExpo
                                });

                                wheel.animate({
                                    left: 250,
                                    top: 250 - 100 * numberWheel
                                }, {
                                    onChange: canvas.renderAll.bind(canvas),
                                    duration: 2000,
                                    easing: fabric.util.ease.easeOutExpo
                                });

                                dataXx = rotate(dataXx);
                                dataYy = rotate(dataYy);
                                dataDd = rotate(dataDd);
                            }

                            canvas.add(car);
                            canvas.add(wheel);
                            canvas.add(lineBottom);
                        }
                    })
            userName = inputCommand.value;
        }
    }
    let rotate = function (arr) {
        arr.push(arr.shift())
        return arr;
    };

    socket.addEventListener("message",msg => {
        let test2 = JSON.parse(msg.data)
        let div = document.getElementById('tabulka')
        div.innerHTML = '';
        let table = document.createElement("table");
        let th = document.createElement("th");
        let text = document.createElement("p");

        th.style.width = "18rem"
        th.style.border = "1pxsolidblack"
        th.className = "tabulkaBranko"
        text.innerText = "Active users"
        th.appendChild(text);
        table.appendChild(th);
        let tbody = document.createElement("tbody");
        for (let i = 0; i < test2.length; i++) {
            if (test2[i][1].name === userName ){
                break;
            }else{
                let tr = document.createElement("tr");
                let butt = document.createElement("button");
                butt.style.width = "18rem"
                butt.className = "btn btn-outline-primary ";
                butt.addEventListener('click', () => {
                    console.log("test")
                    socket.send(JSON.stringify(test2[i][1].name))
                })
                butt.innerText = test2[i][1].name;
                tr.appendChild(butt);
                tbody.appendChild(tr);
            }
        }


        table.appendChild(tbody);
        table.style.display = 'block';
        div.appendChild(table);
        if (test2.method == "grafData") {
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
                name: trace1Name,
                x: dataX,
                y: dataY,
                mode: 'lines'
            };

            let trace1 = {
                name: traceName,
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

                let wheel = new fabric.Circle({radius: 30,
                    fill: 'orange',
                    originX: 'center',
                    originY: 'center',
                    left: 250,
                    top: 350+30,
                    objectCaching: false,
                    stroke: 'darkgray',
                    strokeWidth: 3,
                    selectable: false
                })

                let car = new fabric.Rect({
                    left: 210,
                    top: 200,
                    fill: 'blue',
                    width: 80,
                    height: 30,
                    objectCaching: false,
                    stroke: 'lightgray',
                    strokeWidth: 3,
                    selectable: false
                });

                let lineBottom = new fabric.Line([180, 410, 320, 410], {
                    stroke: 'black',
                    selectable: false
                })


                if (r > 0) {

                    animate = setInterval(animateUp, 50);
                }
                if (r < 0) {

                    animate = setInterval(animateDown, 50);
                }
                let cnt = 0;

                function animateUp() {

                    let numberCar = dataYy[0];
                    let numberWheel = dataDd[0];

                    if (++cnt === 2000) {
                        console.log("reset");
                        clearInterval(animate);
                    }

                    wheel.animate({
                        left: 250,
                        top: 350 - 100 * numberWheel
                    }, {
                        onChange: canvas.renderAll.bind(canvas),
                        duration: 2000,
                        easing: fabric.util.ease.easeOutExpo
                    });

                    lineBottom.animate({
                        left: 180,
                        top: 380 - 100 * numberWheel
                    }, {
                        onChange: canvas.renderAll.bind(canvas),
                        duration: 2000,
                        easing: fabric.util.ease.easeOutExpo
                    });

                    car.animate({
                        left: 210,
                        top: 200 - 200 * numberCar
                    }, {
                        onChange: canvas.renderAll.bind(canvas),
                        duration: 2000,
                        easing: fabric.util.ease.easeOutExpo
                    });

                    dataXx = rotate(dataXx);
                    dataYy = rotate(dataYy);
                    dataDd = rotate(dataDd);
                }

                function animateDown() {
                    let numberCar = dataYy[0];
                    let numberWheel = dataDd[0];

                    car.animate({
                        left: 210,
                        top: 200 - 200 * numberCar
                    }, {
                        onChange: canvas.renderAll.bind(canvas),
                        duration: 2000,
                        easing: fabric.util.ease.easeOutExpo
                    });

                    lineBottom.animate({
                        left: 180,
                        top: 280 - 100 * numberWheel
                    }, {
                        onChange: canvas.renderAll.bind(canvas),
                        duration: 2000,
                        easing: fabric.util.ease.easeOutExpo
                    });

                    wheel.animate({
                        left: 250,
                        top: 250 - 100 * numberWheel
                    }, {
                        onChange: canvas.renderAll.bind(canvas),
                        duration: 2000,
                        easing: fabric.util.ease.easeOutExpo
                    });

                    dataXx = rotate(dataXx);
                    dataYy = rotate(dataYy);
                    dataDd = rotate(dataDd);
                }

                canvas.add(car);
                canvas.add(wheel);
                canvas.add(lineBottom);
            }
        }
        let rotate = function (arr) {
            arr.push(arr.shift())
            return arr;
        };
    })
</script>
