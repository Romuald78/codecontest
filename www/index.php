<?php
include_once("./back/const/const.inc.php");
include("layout.php");
?>

<html>
    <body>
    
        <h1>Code Contest Prototype</h2>
        
        <button id="buttC"    onclick="chooseLanguage('C#','buttC')"      >C         </button> 
        <button id="buttJava" onclick="chooseLanguage('JAVA','buttJava')"   >Java      </button> 
        <button id="buttJS"   onclick="chooseLanguage('JS','buttJS')"     >JavaScript</button> 
        <button id="buttPy"   onclick="chooseLanguage('PYTHON','buttPy')" >Python    </button> 
        <button id="buttPhp"  onclick="chooseLanguage('PHP','buttPhp')"    >Php       </button>
        <br>
        <br>
        <textarea rows=20 cols="80" id="codeArea" placeholder = "-- choose a programming language above --"></textarea>
        <br>
        <br>
        <button onclick="sendCode()" id="sendButt">Send code</button> 
        <br>
        <textarea rows=10 cols="80" id="outArea"></textarea>
            
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
            var contener = document.getElementById("codeArea");
            contener.value =  "-- Choose a programing language above --";
            document.getElementById("outArea").value =  "";
            
            function reinitButtons(activeButton){
                document.getElementById("buttC").style.background='#B0B0B0';
                document.getElementById("buttJava").style.background='#B0B0B0';
                document.getElementById("buttJS"  ).style.background='#B0B0B0';
                document.getElementById("buttPy"  ).style.background='#B0B0B0';
                document.getElementById("buttPhp" ).style.background='#B0B0B0';
                if(activeButton != null){
                    document.getElementById(activeButton).style.background='#F0F0B0';
                }
            }

            // functions that change the selected programming language
            function changeLanguage(){
                var codeZone = document.getElementById("codeArea");
                codeZone.value =  "User ID : '"+ userID +"' Exercice ID : '"+ exoID +"' Programming Language : '"+ langID + "'";            
            }
            function chooseLanguage(lang,button){
                switch(lang){
                    case "C#" : 
                        langID = <?php echo "'". FORM_LANG_ID_C ."'" ?>;
                        break;
                    case "JS" :
                        langID = <?php echo "'". FORM_LANG_ID_JS ."'" ?>;
                        break;
                    case "JAVA":
                        langID = <?php echo "'". FORM_LANG_ID_C ."'" ?>;
                        break;
                    case "PHP":
                        langID = <?php echo "'". FORM_LANG_ID_PHP ."'" ?>;
                        break;
                    case "PYTHON":
                        langID = <?php echo "'". FORM_LANG_ID_PYTHON ."'" ?>;
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
                        contener.placeholder = "";
                        reinitButtons(button);
                        changeLanguage();
                    }
                })
            }
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
            
            // function that sends the user code to the back-end
            function sendCode() {
                
                var outZone    = document.getElementById("outArea");
                var codeZone   = document.getElementById("codeArea");
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

                outZone.value  = "code sent in " + langID + " language" + "\n"
                outZone.value += "please wait..." + "\n";
                outZone.value += options.body;
                                
                var url     = "./back/checkExo.php" 

                fetch(url, options)
                .then(
                    function(response){

                        response.text()
                        .then(
                            function(jsonText){
                                jsonObj = JSON.parse(jsonText);
                                outZone.value  = "Result      : '" + jsonObj.result  + "'\n\n";
                                outZone.value += "User ID     : '" + jsonObj.userID  + "'\n";
                                outZone.value += "Exercice ID : '" + jsonObj.exoID   + "'\n";
                                outZone.value += "Language ID : '" + jsonObj.langID  + "'\n\n";
                                outZone.value += "Message     :\n" + jsonObj.message + " \n";

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
</html>