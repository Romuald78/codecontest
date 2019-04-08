# EXAMPLE OF COMMAND LINE

# User script in Python
# ./venv/Scripts/python.exe ./genExercice.py exo001 question | ./venv/Scripts/python.exe ./dummy.py | ./venv/Scripts/python.exe ./genExercice.py exo001 answer

# User script in Java (Need to write a full shell script in order to check the compilation was ok, else, returns the error messages)
# javac Dummy.java
# .\venv\Scripts\python.exe .\genExercice.py exo001 question | "c:\Program Files\Java\jdk1.8.0_171\bin"\java Dummy | .\venv\Scripts\python.exe .\genExercice.py exo001 answer



# ----------------------------------------------------------------------------------------
# IMPORTS
# ----------------------------------------------------------------------------------------
import sys
import os


# ----------------------------------------------------------------------------------------
# PARAMETERS
# ----------------------------------------------------------------------------------------
EXO_DIR  = os.path.dirname(os.path.realpath(__file__))+"/"
ANSWER   = "answer"
QUESTION = "question"


# ----------------------------------------------------------------------------------------
# FUNCTIONS
# ----------------------------------------------------------------------------------------
def removeTrailingChars(s):
    trailingChars = ["\n", "\r", "\t", " "]
    while s[-1] in trailingChars:
        s = s[:-1]
    return s


# ----------------------------------------------------------------------------------------
# MAIN
# ----------------------------------------------------------------------------------------
# check input arguments
if len(sys.argv) != 3:
    exit("[ERROR] Bad number of arguments !")

# get exercice file name
exName = sys.argv[1]

# get activity (question or answer)
activity = sys.argv[2]
if activity != QUESTION and activity != ANSWER:
    exit("[ERROR] bad activity value !")

# open the exercice file
fp = open(EXO_DIR + exName + "_" + activity + ".txt", "r")

# Process either question or answer
if activity == QUESTION:
    # just send each line to the STDOUT
    for l in fp.readlines():
        print(l, end='', flush=True)
    # Close the resource
    fp.close()
else:
    # read the STDIN and check it fits with the current answer
    userCodeAns = sys.stdin.readlines()
    realCodeAns = fp.readlines()

    # Close the resource
    fp.close()
    
    # Join answers
    userCodeAns = removeTrailingChars( "".join(userCodeAns) )
    realCodeAns = removeTrailingChars( "".join(realCodeAns) )

    # compare answers
    if userCodeAns != realCodeAns:
        print("Your answer     : '"+userCodeAns+"'")
        print("Expected answer : '"+realCodeAns+"'")
        exit(-1)
        
    # everything was ok
    print("Your answer is correct : '"+userCodeAns+"'")
    exit(0)

    
    