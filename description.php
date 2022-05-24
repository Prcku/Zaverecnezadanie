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
    <title>API documentation</title>
</head>

<body>
<header>
    <h1 id="nadpis">API documentation</h1>
</header>

<div class="container">
    <div class="buttons">
        <div class="col-sm-5 mx-auto" >
            <select class="form-select m-3" aria-label="Default select example" id="selectik">
                <option>EN</option>
                <option>SK</option>
            </select>
        </div>

        <a class="btn btn-secondary btn-lg m-3" href="formnovy.php" id="linkToMainpage">Main page</a>
        <button class="btn btn-secondary btn-lg m-3" onclick="window.print()" id="generatePdfButton">Generate PDF</button>

        <p>popis APIny</p>


    </div>
</div>

<script>
    let language = document.querySelector("select");
    let generatePdfButton = document.getElementById("generatePdfButton");
    let linkToMainpage = document.getElementById("linkToMainpage");
    let title = document.querySelector("title");


    let trace1Name = "Automobile",
        traceName = "Wheel",
        titleGraph = "Graph"

    language.addEventListener('change', () => {
        let labelCommand = document.getElementById("text");
        let name = document.getElementById("names");
        let nadpis = document.getElementById("nadpis");

        if (language.value == "EN") {
            title.innerHTML = "API documentation";
            nadpis.innerHTML = "API documentation";
            generatePdfButton.innerHTML = "Generate PDF";
            linkToMainpage.innerHTML = "Main page";
        }
        if (language.value == "SK") {
            title.innerHTML = "API dokumentácia";
            nadpis.innerHTML = "Dokumentácia API";
            generatePdfButton.innerHTML = "Vygenerovať PDF";
            linkToMainpage.innerHTML = "Hlavná stránka";
        }
    })
</script>
</body>
</html>