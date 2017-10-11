#Built for Python 3
import sys
from operator import itemgetter
import csv
import subprocess
import datetime
#---------------------------------------------------------------------------------------------------------------
#part 1: compile top biomarkers and reference probes into arrays

Biomarkers_methylated_breast = {'cg22366897':0.808, 'cg04942832':0.7849, 'cg11985341':0.7296, 'cg16337273':0.7832,
                                'cg20459238':0.8399, 'cg17209284':0.8474, 'cg02179478':0.6575, 'cg19470372':0.0335,
                                'cg17252645':0.6514}
Biomarkers_unmethylated_breast = {}
#----------------------------------------------------------------------------------------------------------------
#part 2: browse Illumina BeadChip results

positive_biomarkers = 0
n = 0
margin = 0.0
margin_threshold_breast = (.808+.7849+.7296+.7832+.8399+.8474+.6575+.0335+.6514)/9 * .1

#defining cancer type
margin_threshold = margin_threshold_breast
Biomarkers_methylated = Biomarkers_methylated_breast
Biomarkers_unmethylated = Biomarkers_unmethylated_breast

#opening worksheet with cg probes and values
results_dict = {}
with open(str(sys.argv[1]), 'r') as csvfile:
    results = csv.reader(csvfile, delimiter=',')
    for row in results:
        if len(row) == 2 and row[0].startswith('cg') and (row[1].startswith('0') or row[1].startswith('1') or row[1].startswith('.')):
            value = float(row[1])
            results_dict[row[0]] = value

for i in Biomarkers_methylated:
    if i in results_dict and type(results_dict[i]) is float:
        n += 1
        if abs(results_dict[i]-Biomarkers_methylated[i]) <= Biomarkers_methylated[i]*.1:
            margin += (results_dict[i] - Biomarkers_methylated[i])
        else:
            if results_dict[i] >= Biomarkers_methylated[i]:
                positive_biomarkers += 1

for i in Biomarkers_unmethylated:
    if i in results_dict and type(results_dict[i]) is float:
        n += 1
        if abs(results_dict[i]-Biomarkers_unmethylated[i]) <= Biomarkers_unmethylated[i]*.1:
            margin += (Biomarkers_unmethylated[i] - results_dict[i])
        else:
            if results_dict[i] <= Biomarkers_unmethylated[i]:
                positive_biomarkers += 1

        
m = margin // margin_threshold

if m >= 0:
    m_int = int(m)
    positive_biomarkers += m_int

#-------------------------------------------------------------------------------------------------
#writing results

results = open("Results.csv", "w")
if n == 9:
    results.write(str(positive_biomarkers))
else:
    results.write('Missing Data for 1 or More Biomarkers')
results.close()

#updating log
results = open("log.csv", "a")
results.write('\n')
results.write('Breast,')
if n == 16:
    results.write(str(positive_biomarkers))
else:
    results.write('ERROR_'+str(9-n))
results.write(',')
results.write('{:%Y-%m-%d_%H:%M:%S}'.format(datetime.datetime.now()))
results.close()

            


        
        
