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

    <link href="css/style.css" rel="stylesheet">
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
                    <a class="btn btn-secondary btn-lg h-50 m-3" href="description.php" id="popisApiPdf">Describe API</a>

                    <input type="hidden" name="message">
                    <button type="submit" name="send_mail_button" class="btn btn-secondary btn-lg m-3" id="sendMail">Send email</button>
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

        <div class="popup">
            <label for="textR" id="text">R</label>
            <input type="number" id="textR" name="textR" min="-0.5" max="0.5" class="form-control mb-3">
            <span class="popuptext" id="myPopup">Wrong input, range is </span>
        </div>
        <button type="button" id="button" onclick="ApiFunction()" class="btn btn-dark mb-3">Send</button>
    </div>
</form>

<div id="answerDiv"></div>

<canvas id="animation" width="600" height="550"></canvas>

<div id="graph"></div>

</div>

<script>
    let title = document.querySelector("title");
    let language = document.querySelector("select");
    let button = document.querySelector("button");
    let describeApiInPdfButton = document.getElementById("popisApiPdf");
    let sendMailButton = document.getElementById("sendMail");
    let buttonSendR = document.getElementById("button");

    let trace1Name = "Automobile",
        traceName = "Wheel",
        titleGraph = "Graph"

    language.addEventListener('change', () => {
        let labelCommand = document.getElementById("text");
        let name = document.getElementById("names");
        let nadpis = document.getElementById("Nadpis");
        if (language.value == "EN") {
            title.innerHTML = "Final assignment"
            button.innerHTML = "Send";
            labelCommand.innerText = "Command"
            name.innerHTML = "Name"
            trace1Name = "Automobile"
            traceName = "Wheel"
            titleGraph = "Graph"
            describeApiInPdfButton.innerHTML = "Describe API"
            sendMailButton.innerHTML = "Send mail"
            buttonSendR.innerHTML = "Send"
            nadpis.innerHTML = "Final assignment"
        }
        if (language.value == "SK") {
            title.innerHTML = "Záverečné zadanie"
            button.innerHTML = "Odoslať";
            labelCommand.innerText = "Príkaz"
            name.innerHTML = "Meno"
            trace1Name = "Karoséria"
            traceName = "Koleso"
            titleGraph = "Graf"
            describeApiInPdfButton.innerHTML = "Popis API"
            sendMailButton.innerHTML = "Odoslať mail"
            buttonSendR.innerHTML = "Odoslať"
            nadpis.innerHTML = "Záverečné zadanie"
        }
    })

    const ApiFunction = () => {
        let canvas = document.getElementById('animation');

        let numbers = []
        let inputCommand = document.getElementById("name");
        if (inputCommand.value.length == 0) {
            inputCommand.value = "anonym"
        }
        const answerDiv = document.querySelector("#answerDiv");
        answerDiv.innerHTML = "";
        let text = document.getElementById("textname");
        let r = document.getElementById("textR");
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

                        for (let i = 1; i < numbers.length-1; i++) {
                            dataXa.push(numbers[i][1])
                            dataYa.push(numbers[i][3])
                            dataDa.push(numbers[i][2])
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

                        let interval = setInterval(function() {
                            let update1 = {
                                x: [dataX],
                                y: [dataY]
                            }

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

                            let car = new fabric.Circle({radius: 30,
                                fill: 'blue',
                                originX: 'center',
                                originY: 'center',
                                left: 250,
                                top: 350+30,
                                objectCaching: false,
                                stroke: 'lightgray',
                                strokeWidth: 3,
                                selectable: false
                            })

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

                            if (r.value > 0) {
                                animate = setInterval(animateUp, 50);
                            }

                            if (r.value < 0) {
                                animate = setInterval(animateDown, 50);
                            }


                            function animateUp() {
                                dataX = rotate(dataX);
                                dataY = rotate(dataY);
                                dataD = rotate(dataD);
                                let numberCar = dataYy[0];
                                let numberWheel = dataDd[0];
                                wheel.animate({
                                    left: 210,
                                    top: 350 - 500 * numberWheel
                                }, {
                                    onChange: canvas.renderAll.bind(canvas),
                                    duration: 2000,
                                    easing: fabric.util.ease.easeOutExpo,
                                });

                                car.animate({
                                    left: 250,
                                    top: 400 - 500 * numberCar
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

                                wheel.animate({
                                    left: 210,
                                    top: 400 + 500 * numberWheel
                                }, {
                                    onChange: canvas.renderAll.bind(canvas),
                                    duration: 2000,
                                    easing: fabric.util.ease.easeOutExpo
                                });

                                car.animate({
                                    left: 250,
                                    top: 400 + 500 * numberCar
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
            inputCommand.value = ""
        }
    }

    let rotate = function (arr) {
        arr.push(arr.shift())
        return arr;
    };

</script>

</body>

</html>