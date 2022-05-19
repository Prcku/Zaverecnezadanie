<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
    <button type="button"  onclick="Get()">Send</button>


    <label for="texttWatch" id="textW">Watch your friends animation</label>
    <input type="text" id="textWatch" name="texttWatch">
    <button type="button"  onclick="Watch()">Send</button>
<div id="answerDiv">

</div>
<script>
     let language = document.querySelector("select");
     let button = document.querySelector("button");
     language.addEventListener('change',() =>{

         let labelCommand = document.getElementById("text");
         let name = document.getElementById("names");
         if (language.value == "EN"){
             button.innerHTML = "Send";
             labelCommand.innerText = "Command"
             name.innerHTML = "Name"
         }
         if (language.value == "SK"){
             button.innerHTML = "Odoslať";
             labelCommand.innerText = "Príkaz"
             name.innerHTML = "Meno"
         }
     })

    const Get = () => {
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
        if(r.value.length != 0){
            fetch(`/zaverecnezadanie/ServiceApi.php?name=`+inputCommand.value+`&r=`+r.value ,{
                method: 'GET',
                headers: {
                    'x-api-key': 'asdfwe4q489qfweasd'
                }
            }).then(response => response.json()).catch(error => console.log(error))
                .then(result => answerDiv.innerHTML = JSON.stringify(result,undefined,4));
        }
        inputCommand.value = ""
    }
     const Watch = () => {
         let text = document.getElementById("textWatch");
         if(text.value.length != 0){
             fetch(`/zaverecnezadanie/ServiceApi.php?friend=`+text.value,{
                 method: 'GET',
                 headers: {
                     'x-api-key': 'asdfwe4q489qfweasd'
                 }
             }).then(response => response.json()).catch(error => console.log(error))
                 .then(result => answerDiv.innerHTML = JSON.stringify(result,undefined,4));
         }
     }</script>
</body>
</html>

