# ----------------------------------------------------------------------------------------
# DESCRIPTION
# ----------------------------------------------------------------------------------------
# this exercice will generate a random value N, between 10 and 100
# then it will generate N integer values, each between 1 and 1000 using the seed given in parameter
# The goal will be to compute the sum of the N integer values 



# ----------------------------------------------------------------------------------------
# IMPORTS
# ----------------------------------------------------------------------------------------
import sys
import os
import random


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
if len(sys.argv) != 2:
    exit("[ERROR] Bad number of arguments !")
    
# Store seed argument
seed = sys.argv[1]

# Generate number of data
random.seed(seed)
N = random.randint(10,100)

# generate N integers and compute the sum
total = 0
for i in range(N):
    total += random.randint(1,1000)

# prepare the output to be compared to
realCodeAns = str(total)    
    
# Read the user code information from the std input
userCodeAns = sys.stdin.readlines()
userCodeAns = removeTrailingChars( "".join(userCodeAns) )

# Compare results
if userCodeAns != realCodeAns:
    print("Your answer     : '"+userCodeAns+"'")
    print("Expected answer : '"+realCodeAns+"'")
    result = -1
else:    
    print("Your answer is correct : '"+userCodeAns+"'")
    result = 0

# flush std err if needed   
sys.stderr.close()

# exit
exit(result)
