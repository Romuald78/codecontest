<?php
include_once("./back/const/const.inc.php");
include("layoutTop.php");
?>

<html>
    <body>
        <div id= "content">
            <h1 id="title">Code Contest Prototype</h2>
            <div id= "mainContent">
                <div id="exerciceBox" class ="box">
                    <div id="exerciceArea"><div>Ici il y aura les énnoncés</div></div>
                </div>
                <div id="codeBox" class ="box">
                    <div>
                        <div id= "langButtons">
                            <button id="buttC" class="btn"   onclick="chooseLanguage('C#','buttC')">C</button> 
                            <button id="buttJava" class="btn" onclick="chooseLanguage('JAVA','buttJava')">Java</button> 
                            <button id="buttJS"  class="btn" onclick="chooseLanguage('JS','buttJS')">JavaScript</button> 
                            <button id="buttPy" class="btn"  onclick="chooseLanguage('PYTHON','buttPy')">Python</button> 
                            <button id="buttPhp" class="btn" onclick="chooseLanguage('PHP','buttPhp')">Php</button>
                        </div>
                        <img id= "langIMG"/>
                    </div>
                    <div id="codeArea" class ="subBox">
                        <div id="langInfo"></div>
                        <div id="glotIoSlot">
                            <textarea rows=25 cols="95" style="background : transparent; border: none; color:white;">Ici on Ecrit du code et tout (Installer Glot.Io)</textarea>
                        </div>
                        <div id="codeOption">
                            <button onclick="sendCode()" class="btn" id="sendButt">Send code</button>
                            <button onclick="resetCode()" class="btn" id="resetButt">Reset code</button>  
                        </div>
                    </div>
                    <div id="outArea" class ="subBox">
                        <div id="rTitle">Results</div>
                        <div id="result"></div>
                    </div>
                </div>
                
                <div id="infoBox" class ="box">
                    <div id="infoArea"><div>Ici il y aura les informations générales, du type : Nombres de codeurs en tête</div></div>
                </div>
            </div>  
        </div>
            
                
            <?php  
            //----------------------------------------------------------------------
            if(isset($_GET['debug']))
            {
                ?>
                <form method="POST" action="./back/checkExo.php">
                    userID : <input type="text" name="userID" value="123" /><br>
                    exoID : <input type="text" name="exoID" /><br>
                    langID : <input type="text" name="langID" /><br>
                    codeText : <textarea name="codeText"></textarea>
                    <br>
                    <input type="submit" /><br>        
                </form>
                <?php
            }
            //----------------------------------------------------------------------
            ?>  
        </div>
        <script>

            <?php
            $userID = "";
            $exoID  = "";
            if ( isset($_GET["userID"]) ){
                $userID = $_GET["userID"];
            }
            if ( isset($_GET["exoID"]) ){
                $exoID = $_GET["exoID"];
            }
            echo "var userID = '$userID'; \n";
            echo "var exoID  = '$exoID';";
            ?>

            
            var langID = "-- choose a programming language --";
            var contener = document.getElementById("langInfo");
            contener.innerHTML =  "-- Choose a programing language above --";
            document.getElementById("result").innerHTML =  "";
            var img = document.getElementById("langIMG");
            var imgName = "";
            var iheight = "60px";
            var iwidht = "60px";
            img.style.height = iheight;
            img.style.width = iwidht;

            function reinitButtons(activeButton){
                iheight = "60px";
                iwidht = "60px";
                document.getElementById("buttC").style.background='turquoise';
                document.getElementById("buttJava").style.background='turquoise';
                document.getElementById("buttJS"  ).style.background='turquoise';
                document.getElementById("buttPy"  ).style.background='turquoise';
                document.getElementById("buttPhp" ).style.background='turquoise';
                if(activeButton != null){
                    document.getElementById(activeButton).style.background='#F0F0B0';
                }
            }

            // functions that change the selected programming language
            function changeLanguage(){
                var codeZone = document.getElementById("langInfo");
                codeZone.innerHTML =  "User ID : '"+ userID +"' Exercice ID : '"+ exoID +"' Programming Language : '"+ langID + "'";            
            }
            function chooseLanguage(lang,button){
                switch(lang){
                    case "C#" : 
                        langID = <?php echo "'". FORM_LANG_ID_C ."'" ?>;
                        imgName = "clogo.png";
                        console.log(img.backgroundImage, img.backgroundSize, img);
                        break;
                    case "JS" :
                        langID = <?php echo "'". FORM_LANG_ID_JS ."'" ?>;
                        imgName = "jslogo.png";
                        break;
                    case "JAVA":
                        langID = <?php echo "'". FORM_LANG_ID_C ."'" ?>;
                        imgName = "javalogo.png";
                        iheight = "75px";
                        break;
                    case "PHP":
                        langID = <?php echo "'". FORM_LANG_ID_PHP ."'" ?>;
                        imgName = "phplogo.png";
                        iwidht = "85px";
                        break;
                    case "PYTHON":
                        langID = <?php echo "'". FORM_LANG_ID_PYTHON ."'" ?>;
                        imgName = "pythonlogo.png";
                        break;
                    default:
                        langID = <?php echo "'Langage Invalide'" ?>;
                }
                swal({
                    title: 'Changer de langage',
                    text: "Attention, tu t'apprete a changer de langage, ta progression sera effacée",
                    icon: 'warning',
                    buttons:{
                        confirm : 
                        {
                            text: "Changer le langage",
                            change: true,
                        },
                        cancel : "Retour"
                    },
                    }).then((change) => {
                        if (change) {
                            var imgLink = "front/img/"+imgName+""
                            console.log(imgLink);
                            img.style.height = iheight;
                            img.style.width = iwidht;
                            img.src = imgLink;
                            contener.placeholder = "";
                            reinitButtons(button);
                            changeLanguage();
                        }
                })
            }
            {//Commented functions
                
                // function chooseC(){
                //     Swal.fire({
                //         title: 'Changer de langage',
                //         text: "Attention, tu t'apprete a changer de langage, ta progression sera effacée",
                //         type: 'warning',
                //         showCancelButton: true,
                //         confirmButtonColor: '#3085d6',
                //         cancelButtonColor: '#d33',
                //         confirmButtonText: 'Changer de langage'
                //         }).then((result) => {
                //         if (result.value) {
                //             contener.placeholder = "";
                //             langID = <?php echo "'". FORM_LANG_ID_C ."'" ?>;
                //             reinitButtons("buttC");
                //             changeLanguage();
                //         }
                //     })
                // }
                // function chooseJava(){
                //     Swal.fire({
                //         title: 'Changer de langage',
                //         text: "Attention, tu t'apprete a changer de langage, ta progression sera effacée",
                //         type: 'warning',
                //         showCancelButton: true,
                //         confirmButtonColor: '#3085d6',
                //         cancelButtonColor: '#d33',
                //         confirmButtonText: 'Changer de langage'
                //         }).then((result) => {
                //         if (result.value) {
                //             contener.placeholder = "";
                //             langID = <?php echo "'". FORM_LANG_ID_C ."'" ?>;
                //             reinitButtons("buttC");
                //             changeLanguage();
                //         }
                //     })
                //     contener.placeholder = "";
                //     langID = <?php echo "'". FORM_LANG_ID_JAVA ."'" ?>;
                //     reinitButtons("buttJava");
                //     changeLanguage();
                // }
                // function chooseJS(){
                //     Swal.fire({
                //         title: 'Changer de langage',
                //         text: "Attention, tu t'apprete a changer de langage, ta progression sera effacée",
                //         type: 'warning',
                //         showCancelButton: true,
                //         confirmButtonColor: '#3085d6',
                //         cancelButtonColor: '#d33',
                //         confirmButtonText: 'Changer de langage'
                //         }).then((result) => {
                //         if (result.value) {
                //             contener.placeholder = "";
                //             langID = <?php echo "'". FORM_LANG_ID_JS ."'" ?>;
                //             reinitButtons("buttJS");
                //             changeLanguage();
                //         }
                //     })
                // }
                // function choosePhp(){
                //     Swal.fire({
                //         title: 'Changer de langage',
                //         text: "Attention, tu t'apprete a changer de langage, ta progression sera effacée",
                //         type: 'warning',
                //         showCancelButton: true,
                //         confirmButtonColor: '#3085d6',
                //         cancelButtonColor: '#d33',
                //         confirmButtonText: 'Changer de langage'
                //         }).then((result) => {
                //         if (result.value) {
                //             contener.placeholder = "";
                //             langID = <?php echo "'". FORM_LANG_ID_PHP ."'" ?>;
                //             reinitButtons("buttPhp");
                //             changeLanguage();
                //         }
                //     })
                // }
                // function choosePython(){
                //     Swal.fire({
                //         title: 'Changer de langage',
                //         text: "Attention, tu t'apprete a changer de langage, ta progression sera effacée",
                //         type: 'warning',
                //         showCancelButton: true,
                //         confirmButtonColor: '#3085d6',
                //         cancelButtonColor: '#d33',
                //         confirmButtonText: 'Changer de langage'
                //         }).then((result) => {
                //         if (result.value) {
                //             contener.placeholder = "";
                //             langID = <?php echo "'". FORM_LANG_ID_PYTHON ."'" ?>;
                //             reinitButtons("buttPy");
                //             changeLanguage();
                //         }
                //     })
                // }
            }
            
            // function that sends the user code to the back-end
            function sendCode() {
                
                var outZone    = document.getElementById("result");
                var codeZone   = document.getElementById("langInfo");
                var sendButton = document.getElementById("sendButt");
                
                var urlBody = "";
                urlBody += <?php echo "'" . FORM_CHECKEXO_USER_ID   ."='" ?> + userID;
                urlBody += <?php echo "'&". FORM_CHECKEXO_EXO_ID    ."='" ?> + exoID;
                urlBody += <?php echo "'&". FORM_CHECKEXO_LANG_ID   ."='" ?> + langID;
                urlBody += <?php echo "'&". FORM_CHECKEXO_CODE_TEXT ."='" ?> + encodeURIComponent(codeZone.value);
                
                sendButton.disabled = true;

                const options = {
                    method: 'POST',
                    cache:'no-cache',
                    headers: {
                        'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    },
                    body:urlBody,
                };

                outZone.innerHTML  = "code sent in " + langID + " language" + "\n"
                outZone.innerHTML += "please wait..." + "\n";
                outZone.innerHTML += options.body;
                                
                var url     = "./back/checkExo.php" 

                fetch(url, options)
                .then(
                    function(response){

                        response.text()
                        .then(
                            function(jsonText){
                                jsonObj = JSON.parse(jsonText);
                                outZone.innerHTML  = "Result      : ' " + jsonObj.result  + " '<br><br>";
                                outZone.innerHTML += "User ID     : ' " + jsonObj.userID  + " '<br>";
                                outZone.innerHTML += "Exercice ID : ' " + jsonObj.exoID   + " '<br>";
                                outZone.innerHTML += "Language ID : ' " + jsonObj.langID  + " '<br><br>";
                                outZone.innerHTML += "Message     :<br>" + jsonObj.message + "<br>";

                                setTimeout( function(){ sendButton.disabled = false }, 1000 );
                            }
                        );
                    }
                );
            }
            
            // Reinit button display at startup
            window.onload = reinitButtons();

            </script>
    </body>
    <?php
        include("layoutFooter.php");
    ?>
</html>