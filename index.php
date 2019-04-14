<html>
    <body>

        <h1>Code Contest Prototype</h2>
        
        <button onclick="chooseC()" disabled>C</button> 
        <button onclick="chooseJava()" disabled>Java</button> 
        <button onclick="chooseJava()" disabled>JavaScript</button> 
        <button onclick="choosePython()">Python</button> 
        <button onclick="choosePhp()">Php</button>
        <br>
        <br>
        <textarea rows=20 cols="80" id="codeArea">-- choose a programming language above --</textarea>
        <br>
        <br>
        <button onclick="sendCode()">Send code</button> 
        <br>
        <textarea rows=10 cols="80" id="outArea"></textarea>
            
        
        <!-- 
        <form method="POST" action="./back/checkExo.php">
        userID : <input type="text" name="userID" value="123" /><br>
        exoID : <input type="text" name="exoID" /><br>
        langID : <input type="text" name="langID" /><br>
        codeText : <input type="text" name="codeText"><br>
        <input type="submit" /><br>        
        </form>
        !-->

                
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
            
            function changeLanguage(){
                var codeZone = document.getElementById("codeArea");
                codeZone.value =  "User ID : '"+ userID +"' Exercice ID : '"+ exoID +"' Programming Language : '"+ langID + "'";            
            }
            
            function chooseC(){
                langID = "C";
                changeLanguage();
            }
            function chooseJava(){
                langID = "Java";
                changeLanguage();
            }
            function choosePython(){
                langID = "Python";
                changeLanguage();
            }
            function choosePhp(){
                langID = "Php";
                changeLanguage();
            }
            
            function sendCode() {
                // Retrieve 
                var outZone    = document.getElementById("outArea");
                var codeZone   = document.getElementById("codeArea");

                const options = {
                    method: 'post',
                    headers: {
                        'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    },
                    body: "userID="+ userID +"&exoID="+ exoID +"&langID="+ langID +"&codeText="+ encodeURIComponent(codeZone.value) 
                }

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
                            }
                        );
                    }
                );
                
            }
            
            
            
            
            
            
            
            
        </script>

    </body>
</html>