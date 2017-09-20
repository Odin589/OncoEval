#Built for Python 3
import time
from operator import itemgetter
start_time = time.time()
now = time.ctime(int(time.time()))
print('--- ', str(now), ' ---')

#greeting
print('Welcome to Alternatively (CH3)pliced Prognostic Analysis. The program will')
print('analyze the top biomarkers identified by Alternatively (CH3)pliced (Jerome)')
print('in each sample. Input file type must be xlsx file with cg probes in the first')
print('column and beta values in the second. Data must be in the first sheet in the file.\n')
#------------------------------------------------------------------------------------
#part 1: compile top biomarkers into a dictionary

Biomarkers = {'cg22366897':0.808, 'cg04942832':0.7849, 'cg11985341':0.7296, 'cg16337273':0.7832, 'cg20459238':0.8399, 'cg17209284':0.8474,
              'cg02179478':0.6575, 'cg19470372':0.0335, 'cg17252645':0.6514}


print("--- %s minutes ---" % (float((time.time() - start_time)/60)))
print('Compiling dictionaries\n')
#----------------------------------------------------------------------------------------------------------------
#part 2: browse Illumina BeadChip results

import xlrd

positive_biomarkers = 0
n = 0

input_file = xlrd.open_workbook(input('Enter the exact input file name (including .xlsx): '))
results = input_file.sheet_by_index(0)

for i in range(results.nrows): 
    cg_probe = results.cell(i,0)
    cg_probe_string = cg_probe.value
    if cg_probe_string in Biomarkers:
        beta_value = results.cell(i,1)
        beta_value_float = beta_value.value
        if type(beta_value_float) is float:
            n += 1
            if beta_value_float >= Biomarkers[cg_probe_string]:
                positive_biomarkers += 1

#-------------------------------------------------------------------------------------------------
#printing results

print("--- %s minutes ---" % (float((time.time() - start_time)/60)))
if n == 9:
    print('Number of positive biomarkers:')
    print(positive_biomarkers, '\n')
else:
    print('Incomplete data')
    print('Missing ', 9-n, ' biomarkers')

print("--- %s minutes ---" % (float((time.time() - start_time)/60)))
print('End program')

            


        
        
