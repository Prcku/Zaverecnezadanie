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
<!--    <select>-->
<!--        <option>EN</option>-->
<!--        <option>SK</option>-->
<!--    </select>-->
<!--    <label for="name" id="name">Name</label>-->
<!--    <input type="text" id="name" name="name">-->

        <label for="textname" id="text">Command</label>
        <input type="text" id="textname" name="textname">
        <button type="button"  onclick="Post_INVENTOR()">POST</button>

<div id="answerDiv">

</div>
<script>
    // let language = document.querySelector("#select");
    // let button = document.querySelector("#button");
    // button.addEventListener("click", () => {
    //     console.log("ahoj")
    //     let text = document.getElementById("text");
    //     let name = document.getElementById("name");
    //     let answerDiv = document.querySelector("#answerDiv");
    //     answerDiv.innerHTML = "";
    //     fetch(`/api.php`  ,{
    //         method: 'POST'
    //     }).then(response => response.json())
    //         .then(console.log)
    //         .then(result => answerDiv.innerHTML = JSON.stringify(result,undefined,4));
    // }
    const Post_INVENTOR = () => {
        const answerDiv = document.querySelector("#answerDiv");
        answerDiv.innerHTML = "";
        fetch(`/zaverecnezadanie/ServiceApi.php`  ,{
            method: 'GET'
        }).then(response => response.json())
            .then(console.log)
            .then(result => answerDiv.innerHTML = JSON.stringify(result,undefined,4));
    }
</script>
</body>

</html>

