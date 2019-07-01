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
print(N)

# generate N integers
for i in range(N):
    print( random.randint(1,1000) )

# flush std err if needed   
sys.stderr.close()    