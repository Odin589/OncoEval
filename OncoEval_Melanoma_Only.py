#Built for Python 3
import sys
from operator import itemgetter
import csv
import subprocess
import datetime
#---------------------------------------------------------------------------------------------------------------
#part 1: compile top biomarkers and reference probes into arrays

Biomarkers_methylated_melanoma = {'cg17229678':0.1003}
Biomarkers_unmethylated_melanoma = {'cg06745753':0.9603, 'cg04029366':0.8461, 'cg13477548':0.9587, 'cg07192243':0.8324,
                                    'cg11335321':0.823, 'cg26598899':0.5923, 'cg20322003':0.8104, 'cg19739482':0.8125,
                                    'cg12436612':0.8357, 'cg06194738':0.6008, 'cg12044210':0.8042, 'cg20652371':0.6543,
                                    'cg06051154':0.9038}
#----------------------------------------------------------------------------------------------------------------
#part 2: browse Illumina BeadChip results

positive_biomarkers = 0
n = 0
margin = 0.0
margin_threshold_melanoma = (.1003+.9603+.8461+.9587+.8324+.823+.5923+.8104+.8125+.8357+.6008+.8042+.6543+.9038)/14 * .1

#defining cancer type
margin_threshold = margin_threshold_melanoma
Biomarkers_methylated = Biomarkers_methylated_melanoma
Biomarkers_unmethylated = Biomarkers_unmethylated_melanoma

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
if n == 14:
    results.write(str(positive_biomarkers))
else:
    results.write('Missing Data for 1 or More Biomarkers')
results.close()

#updating log
results = open("log.csv", "a")
results.write('\n')
results.write('Melanoma,')
if n == 14:
    results.write(str(positive_biomarkers))
else:
    results.write('ERROR_'+str(14-n))
results.write(',')
results.write('{:%Y-%m-%d_%H:%M:%S}'.format(datetime.datetime.now()))
results.close()
