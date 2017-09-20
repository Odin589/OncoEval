#Built for Python 3
import sys
from operator import itemgetter
import csv
#---------------------------------------------------------------------------------------------------------------
#part 1: compile top biomarkers into dictionaries

Biomarkers_methylated_breast = {'cg22366897':0.808, 'cg04942832':0.7849, 'cg11985341':0.7296, 'cg16337273':0.7832,
                                'cg20459238':0.8399, 'cg17209284':0.8474, 'cg02179478':0.6575, 'cg19470372':0.0335,
                                'cg17252645':0.6514}
Biomarkers_unmethylated_breast = {}
#Biomarkers_methylated_lung = {'cg10400804':0.7904, 'cg09461851':0.7474, 'cg16446585':0.8353, 'cg15703585':0.5497,
#                              'cg05892817':0.7825, 'cg16496687':0.0817, 'cg16150571':0.6892, 'cg05346287':0.5735,
#                              'cg26140240':0.6434}
#Biomarkers_unmethylated_lung = {'cg02495915':0.9083, 'cg04770364':0.8027, 'cg11299371':0.7217, 'cg14904697':0.8402,
#                                'cg27024271':0.7781, 'cg09848218':0.9627, 'cg01875451':0.7875}

#----------------------------------------------------------------------------------------------------------------
#part 2: browse Illumina BeadChip results

positive_biomarkers = 0
n = 0
margin = 0.0
margin_threshold_breast = (.808+.7849+.7296+.7832+.8399+.8474+.6575+.0335+.6514)/9 * .1
#margin_threshold_lung = (.7904 + .7474 + .8353 + .5497 + .7825 + .0817 + .6892 + .5735 + .6434 + .9083 + .8027 + .7217 + .8402 + .7781 + .9627 + .7875)/16 * .1

#defining cancer type from third argument
#cancer_type = str(sys.argv[2])
#if cancer_type == 'Breast':
margin_threshold = margin_threshold_breast
Biomarkers_methylated = Biomarkers_methylated_breast
Biomarkers_unmethylated = Biomarkers_unmethylated_breast
#elif cancer_type == 'Lung':
#    margin_threshold = margin_threshold_lung
#    Biomarkers_methylated = Biomarkers_methylated_lung
#    Biomarkers_unmethylated = Biomarkers_unmethylated_lung

#opening worksheet with cg probes and values
results_dict = {}
with open(str(sys.argv[1]), 'r') as csvfile:
    results = csv.reader(csvfile, delimiter=',')
    for row in results:
        if len(row) == 2 and row[0].startswith('cg') and (row[1].startswith('0') or row[1].startswith('1')):
            value = float(row[1])
            results_dict[row[0]] = value

for i in Biomarkers_methylated:
    if type(results_dict[i]) is float:
        n += 1
        if abs(results_dict[i]-Biomarkers_methylated[i]) <= Biomarkers_methylated[i]*.1:
            margin += (results_dict[i] - Biomarkers_methylated[i])
        else:
            if results_dict[i] >= Biomarkers_methylated[i]:
                positive_biomarkers += 1

for i in Biomarkers_unmethylated:
    if type(results_dict[i]) is float:
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
results.write(str(sys.argv[1]))
results.write(',')
#results.write(str(sys.argv[2]))
#results.write('\n')
#if sys.argv[2] == 'Breast':
if n == 9:
    results.write(str(positive_biomarkers))
else:
    results.write('Incomplete data: ')
    results.write('Missing '+str(9-n)+' biomarkers')
#elif sys.argv[2] == 'Lung':
#    if n == 16:
#        results.write(str(positive_biomarkers))
#    else:
#        results.write('Incomplete data: ')
#        results.write('Missing '+str(16-n)+' biomarkers')
results.close()

            


        
        
