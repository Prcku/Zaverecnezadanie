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
    <link href="css/style1.css" rel="stylesheet">
    <link href="css/style2.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Documentation</title>
</head>

<body>
<section class="pattern">
    <div class="geeks">
        <header>
            <h1 id="nadpis">Documentation</h1>
        </header>

        <div class="container">
            <div class="buttons">
                <div class="col-sm-5 mx-auto">
                    <label for="selectik" id="labelSelect">Choose language:</label>
                    <select class="form-select m-3" aria-label="Default select example" id="selectik">
                        <option>EN</option>
                        <option>SK</option>
                    </select>
                </div>

                <a class="btn btn-secondary btn-lg m-3" href="formnovy.php" id="linkToMainpage">Back to main page</a>
                <button class="btn btn-secondary btn-lg m-3" onclick="window.print()" id="generatePdfButton">Generate PDF</button>
                <a class="btn btn-secondary btn-lg m-3" href="https://documenter.getpostman.com/view/15702498/Uz59Pfaq" id="ApiDocumentation">API documentation</a>
</section>


<div id="descriptiony">
    <div id="slovakDescription" class="card" style="width: 50rem;">
        <div class="card-body">
            <h4>Návod na používanie hlavnej stránky</h4>
            <ul class="lead">
                <li>V rozbaľovacom menu si používateľ môže zvoliť jazyk, v akom chce pracovať so stránkou.</li>
                <li>Tlačidlo <b>Používateľská príručka</b> presmeruje používateľa na návod na obsluhu hlavnej stránky. Na tomto mieste si môže túto príručku stiahnuť vo formáte PDF.</li>
                <li>Tlačidlo <b>Odoslať mail</b> odošle e-mail so všetkými požiadavkami zasielanými do CAS.</li>
                <li>Tlačidlo <b>CSV</b> zobrazí okno s linkom, z ktorého sa búdu dať stiahnuť logy v CSV formáte.</li>
                <li>Do vstupného poľa <b>Meno</b> vie používateľ zadať svoje meno. V prípade jeho nezadania bude v logu vystupovať ako <i>anonym</i>.</li>
                <li>Do vstupného poľa <b>Príkaz</b> vie užívateľ zadávať príkazy, ktoré sa následne vykonajú a zapíšu na miesto výstupu.</li>
                <li>Do vstupného poľa <b>r</b> používateľ zadáva želanú hodnotu parametra <i>r</i>.</li>
                <li>Tlačidlo <b>Odoslať</b> slúži na odoslanie API požiadavky na server. Používateľ musí byť autorizovaný pomocou API kľúča.</li>
                <li>V tabuľke <b>Aktívni Používatelia</b> sa zobrazia všetci používatelia, ktorí zadali svoje meno a aktuálne vykonávajú experiment. Používateľ si bude
                môcť vybrať, koho experimentovanie chce sledovať.</li>
                <li>Zakliknutím na <b>Graf</b> sa zobrazí graf vykresľovania karosérie a kolesa auta po vjazde na prekážku.
                    Zakliknutím na <b>Animáciu</b> sa zobrazí animácia vykresľovania karosérie a kolesa auta po vjazde na prekážku.</li>

            </ul>
        </div>
    </div>

    <div id="englishDescription" class="card" style="width: 50rem;">
        <div class="card-body">
            <h4>Instructions to use the main page</h4>
            <ul class="lead">
                <li>User can choose language in a select menu.</li>
                <li>Button <b>User guide</b> transfers user to the page containing User guide.</li>
                <li>Button <b>Send mail</b> sends mail with all the requirements sent to CAS.</li>
                <li>Button <b>CSV</b> will generate a window with link. which will allows you to download logs in CVS format.</li>
                <li>User can enter his name to the <b>Name</b> input field. If it is not entered, it will appear in the log as <i>anonym</i>.</li>
                <li>User can enter commands to <b>Command</b> input field which will be executed under the form.</li>
                <li>User can enter <i>r</i> value in the <b>r</b> input field.</li>
                <li>Button <b>Send</b> sends request to server. User must be authorized by API key.</li>
                <li>All users who have entered their name and are currently running an experiment are displayed in the table <b>Active users</b>.</li>
                <li>Clicking on checkbox <b>Graph</b> or <b>Animation</b> will display a graph of the car's body and wheel after entering an obstacle.</li>

            </ul>
        </div>
    </div>
</div>

<script>
    let language = document.querySelector("select");
    let generatePdfButton = document.getElementById("generatePdfButton");
    let linkToMainpage = document.getElementById("linkToMainpage");
    let title = document.querySelector("title");
    let labelSelect = document.getElementById("labelSelect");
    let englishDescription = document.getElementById("englishDescription");
    let slovakDescription = document.getElementById("slovakDescription");

    let trace1Name = "Automobile",
        traceName = "Wheel",
        titleGraph = "Graph"

    language.addEventListener('change', () => {
        let labelCommand = document.getElementById("text");
        let name = document.getElementById("names");
        let nadpis = document.getElementById("nadpis");
        let api = document.getElementById("ApiDocumentation");
        if (language.value == "EN") {
            api.innerHTML = "API documentation"
            title.innerHTML = "Documentation";
            nadpis.innerHTML = "Documentation";
            generatePdfButton.innerHTML = "Generate PDF";
            linkToMainpage.innerHTML = "Back to main page";
            labelSelect.innerHTML = "Choose language:"
            slovakDescription.style.display = 'none'
            englishDescription.style.display = 'block'
        }
        if (language.value == "SK") {
            api.innerHTML = "API dokumentácia"
            title.innerHTML = "Dokumentácia";
            nadpis.innerHTML = "Dokumentácia";
            generatePdfButton.innerHTML = "Vygenerovať PDF";
            linkToMainpage.innerHTML = "Späť na hlavnú stránku";
            labelSelect.innerHTML = "Vyberte si jazyk:"
            englishDescription.style.display = 'none'
            slovakDescription.style.display = 'block'
        }
    })
</script>
</body>

</html>