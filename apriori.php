    <!DOCTYPE html>
    <html>

    <head>
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Apriori Algorithm</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,700,800" rel="stylesheet">
      <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css" />
      <script src="assets/js/main.js"></script>
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    </head>

    <body>
              <?php include('header.php') ?><br><br>
    <h5><center>Apriori Algorithm Code in Python:</center></h5>

        <figure>
      <figcaption></figcaption>
      <pre>
        <code>
    #This program takes a dataset (mathdataset.txt) and uses the Apriori algorithm to find association rules with support >50 and confidence >50

    import itertools

    #This function will find the initial set
    def findfirstset(fileset):
        array = {}
        settoreturn = []
        for line in fileset:
            for p in line:
                if p not in array:
                    array[p] = 1
                else:
                    array[p] = array[p] + 1
        for b in array:
            arraytemporary = []
            arraytemporary.append(b)
            settoreturn.append(arraytemporary)
            settoreturn.append(array[b])
            arraytemporary = []
        return settoreturn

    #This function will find the frequent itemsets and prune items that aren't frequent using the initialized support percentage
    def findfrequent(setofpotentialcand, transactions, minS, fileset, mainfrequentarray):
        arrayfreqitems = []
        for i in range(len(setofpotentialcand)):
            if i%2 != 0:
                sup = (setofpotentialcand[i] * 1.0 / transactions) * 100
                if sup >= minS:
                    arrayfreqitems.append(setofpotentialcand[i-1])
                    arrayfreqitems.append(setofpotentialcand[i])
                else:
                    pruneditems.append(setofpotentialcand[i-1])

        for k in arrayfreqitems:
            mainfrequentarray.append(k)

        if len(arrayfreqitems) == 2 or len(arrayfreqitems) == 0:
            settoreturn = mainfrequentarray
            return settoreturn

        else:
            findpotentialsets(fileset, pruneditems, arrayfreqitems, transactions, minS)

    #This function will go through all the frequent items and put them into sets with no duplicates
    def findpotentialsets(fileset, pruneditems, arrayfreqitems, transactions, minS):
        lines = []
        aftercombining = []
        potentialcandidates = []
        for i in range(len(arrayfreqitems)):
            if i%2 == 0:
                lines.append(arrayfreqitems[i])
        for g in lines:
            temporaryarray = []
            k = lines.index(g)
            for i in range(k + 1, len(lines)):
                for j in g:
                    if j not in temporaryarray:
                        temporaryarray.append(j)
                for m in lines[i]:
                    if m not in temporaryarray:
                        temporaryarray.append(m)
                aftercombining.append(temporaryarray)
                temporaryarray = []
        sortarray = []
        noduplicates = []
        for i in aftercombining:
            sortarray.append(sorted(i))
        for i in sortarray:
            if i not in noduplicates:
                noduplicates.append(i)
        aftercombining = noduplicates
        for g in aftercombining:
            count = 0
            for t in fileset:
                if set(g).issubset(set(t)):
                    count = count + 1
            if count != 0:
                potentialcandidates.append(g)
                potentialcandidates.append(count)
        findfrequent(potentialcandidates, transactions, minS, fileset, mainfrequentarray)

    #This function will calculate the sets and their confidence + support percentages
    #Only the sets with that have confidence percentages above the minimum confidence and support percentages above the minimum support will be generated and returned
    def findassociationrules(frequentsets):
        associations = []
        for g in frequentsets:
            if isinstance(g, list):
                if len(g) != 0:
                    leng = len(g) - 1
                    while leng > 0:
                        combos = list(itertools.combinations(g, leng))
                        temporary = []
                        lefthandside = []
                        for righthandside in combos:
                            lefthandside = set(g) - set(righthandside)
                            temporary.append(list(lefthandside))
                            temporary.append(list(righthandside))
                            #print(temporary)
                            associations.append(temporary)
                            temporary = []
                        leng = leng - 1
        return associations

    def findfinaloutputs(associationrules, fileset, minS, minC):
        returnset = []
        for associations in associationrules:
            Xsupport = 0
            Xsupportpercentage = 0
            XYsupport = 0
            XYsupportpercentage = 0
            for t in fileset:
                if set(associations[0]).issubset(set(t)):
                    Xsupport = Xsupport + 1
                if set(associations[0] + associations[1]).issubset(set(t)):
                    XYsupport = XYsupport + 1
            Xsupportpercentage = (Xsupport * 1.0 / transactions) * 100
            XYsupportpercentage = (XYsupport * 1.0 / transactions) * 100
            confidence = (XYsupportpercentage / Xsupportpercentage) * 100
            if confidence >= minC:
                Xsupportstring = "X Support: " + str(round(Xsupportpercentage, 2)) + "%"
                XYsupportstring = "X and Y Support: " + str(round(XYsupportpercentage)) + "%"
                confidencestring = "Confidence: " + str(round(confidence)) + "%"

                returnset.append(Xsupportstring)
                returnset.append(XYsupportstring)
                returnset.append(confidencestring)
                returnset.append(associations)

        return returnset

    #This will take the dataset file name (matdataset.txt)
    nameofFile = "mathdataset.txt"

    #This is the minimum support percentage (can initialize it to any percentage)
    #minS = 50

    #This is the minimum confidence percentage (can initialize it to any percentage)
    #minC = 90

    #Ask for minimum support and minimum confidence
    minS = int(input('Enter Minimum Support (0%-100%): '))
    minC = int(input('Enter Minimum Confidence (0%-100%): '))

    #This will print out the dataset name, support percentage and confidence percentage
    print("Data Set:", nameofFile)
    print("Minimum support:", minS)
    print("Minimum confidence:", minC)
    print("------------------------------------------------------------------------");

    #initialize the arrays and counters
    notfrequent = []
    allfrequent = []
    temporary = []
    fileset = []
    pruneditems = []
    transactions = 0
    mainfrequentarray = []
    increment = 0

    #open up the file and read it
    with open(nameofFile,'r') as doc:
        linebyline = doc.readlines()

    #split each line by delimiter ","
    for n in linebyline:
        n = n.rstrip()
        fileset.append(n.split(","))

    #call the functions to find the association rules
    transactions = len(fileset)
    set1 = findfirstset(fileset)
    freqset = findfrequent(set1, transactions, minS, fileset, mainfrequentarray)
    associations = findassociationrules(mainfrequentarray)
    finaloutputs = findfinaloutputs(associations, fileset, minS, minC)

    #print out the association rules, supports and confidences
    increment = 1
    if len(finaloutputs) == 0:
        print("No association rules with support of {}% and confidence of {}%".format(minS, minC))
    else:
        for i in finaloutputs:
            if increment == 4:
                print(str(i[0]) + " -> " + str(i[1]))
                increment = 0
            else:
                print(i, end=" | ")
            increment = increment + 1



        </code>
      </pre>
    </figure><br><br>

        <h5><center>Test 1 (Min Support = 70%, Min Confidence = 90%):</center></h5>
        <figure>
      <figcaption></figcaption>
      <pre>
        <code>
    Enter Minimum Support (0%-100%): 70
    Enter Minimum Confidence (0%-100%): 90
    Data Set: mathdataset.txt
    Minimum support: 70
    Minimum confidence: 90
    ------------------------------------------------------------------------
    X Support: 77.72% | X and Y Support: 72% | Confidence: 93% | ['address: U'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 84% | Confidence: 95% | ['school: GP'] -> ['higher: yes']
    X Support: 83.29% | X and Y Support: 75% | Confidence: 90% | ['internet: yes'] -> ['school: GP']
    X Support: 77.72% | X and Y Support: 74% | Confidence: 95% | ['address: U'] -> ['higher: yes']
    X Support: 78.99% | X and Y Support: 77% | Confidence: 98% | ['failures: 0'] -> ['higher: yes']
    X Support: 79.49% | X and Y Support: 76% | Confidence: 96% | ['nursery: yes'] -> ['higher: yes']
    X Support: 89.62% | X and Y Support: 85% | Confidence: 95% | ['Pstatus: T'] -> ['higher: yes']
    X Support: 87.09% | X and Y Support: 82% | Confidence: 94% | ['schoolsup: no'] -> ['higher: yes']
    X Support: 83.29% | X and Y Support: 79% | Confidence: 95% | ['internet: yes'] -> ['higher: yes']
    X Support: 87.09% | X and Y Support: 78% | Confidence: 90% | ['schoolsup: no'] -> ['Pstatus: T']
    X Support: 83.29% | X and Y Support: 75% | Confidence: 91% | ['internet: yes'] -> ['Pstatus: T']
    X Support: 78.73% | X and Y Support: 75% | Confidence: 95% | ['Pstatus: T', 'school: GP'] -> ['higher: yes']
    X Support: 75.44% | X and Y Support: 71% | Confidence: 95% | ['school: GP', 'schoolsup: no'] -> ['higher: yes']
    X Support: 75.19% | X and Y Support: 72% | Confidence: 95% | ['internet: yes', 'school: GP'] -> ['higher: yes']
    X Support: 79.24% | X and Y Support: 72% | Confidence: 90% | ['internet: yes', 'higher: yes'] -> ['school: GP']
    X Support: 78.48% | X and Y Support: 74% | Confidence: 94% | ['Pstatus: T', 'schoolsup: no'] -> ['higher: yes']
    X Support: 79.24% | X and Y Support: 72% | Confidence: 90% | ['internet: yes', 'higher: yes'] -> ['Pstatus: T']
    X Support: 75.44% | X and Y Support: 72% | Confidence: 95% | ['internet: yes', 'Pstatus: T'] -> ['higher: yes']
        </code>
      </pre>
    </figure>


        <h5><center>Test 2 (Min Support = 60%, Min Confidence = 90%):</center></h5>

        <figure>
      <figcaption></figcaption>
      <pre>
        <code>
    Enter Minimum Support (0%-100%): 60
    Enter Minimum Confidence (0%-100%): 90
    Data Set: mathdataset.txt
    Minimum support: 60
    Minimum confidence: 90
    ------------------------------------------------------------------------
    X Support: 77.72% | X and Y Support: 72% | Confidence: 93% | ['address: U'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 84% | Confidence: 95% | ['school: GP'] -> ['higher: yes']
    X Support: 69.87% | X and Y Support: 63% | Confidence: 90% | ['Dalc: 1'] -> ['school: GP']
    X Support: 65.06% | X and Y Support: 62% | Confidence: 95% | ['traveltime: 1'] -> ['school: GP']
    X Support: 83.29% | X and Y Support: 75% | Confidence: 90% | ['internet: yes'] -> ['school: GP']
    X Support: 77.72% | X and Y Support: 74% | Confidence: 95% | ['address: U'] -> ['higher: yes']
    X Support: 71.14% | X and Y Support: 68% | Confidence: 95% | ['famsize: GT3'] -> ['higher: yes']
    X Support: 71.14% | X and Y Support: 66% | Confidence: 93% | ['famsize: GT3'] -> ['Pstatus: T']
    X Support: 69.11% | X and Y Support: 66% | Confidence: 95% | ['guardian: mother'] -> ['higher: yes']
    X Support: 78.99% | X and Y Support: 77% | Confidence: 98% | ['failures: 0'] -> ['higher: yes']
    X Support: 79.49% | X and Y Support: 76% | Confidence: 96% | ['nursery: yes'] -> ['higher: yes']
    X Support: 66.58% | X and Y Support: 64% | Confidence: 97% | ['romantic: no'] -> ['higher: yes']
    X Support: 69.87% | X and Y Support: 68% | Confidence: 97% | ['Dalc: 1'] -> ['higher: yes']
    X Support: 89.62% | X and Y Support: 85% | Confidence: 95% | ['Pstatus: T'] -> ['higher: yes']
    X Support: 65.06% | X and Y Support: 63% | Confidence: 96% | ['traveltime: 1'] -> ['higher: yes']
    X Support: 87.09% | X and Y Support: 82% | Confidence: 94% | ['schoolsup: no'] -> ['higher: yes']
    X Support: 83.29% | X and Y Support: 79% | Confidence: 95% | ['internet: yes'] -> ['higher: yes']
    X Support: 66.58% | X and Y Support: 60% | Confidence: 90% | ['romantic: no'] -> ['Pstatus: T']
    X Support: 87.09% | X and Y Support: 78% | Confidence: 90% | ['schoolsup: no'] -> ['Pstatus: T']
    X Support: 83.29% | X and Y Support: 75% | Confidence: 91% | ['internet: yes'] -> ['Pstatus: T']
    X Support: 72.41% | X and Y Support: 69% | Confidence: 95% | ['address: U', 'school: GP'] -> ['higher: yes']
    X Support: 74.18% | X and Y Support: 69% | Confidence: 93% | ['address: U', 'higher: yes'] -> ['school: GP']
    X Support: 69.11% | X and Y Support: 64% | Confidence: 93% | ['Pstatus: T', 'address: U'] -> ['school: GP']
    X Support: 67.34% | X and Y Support: 62% | Confidence: 92% | ['address: U', 'schoolsup: no'] -> ['school: GP']
    X Support: 68.1% | X and Y Support: 64% | Confidence: 94% | ['internet: yes', 'address: U'] -> ['school: GP']
    X Support: 64.3% | X and Y Support: 61% | Confidence: 95% | ['Pstatus: T', 'address: U', 'school: GP'] -> ['higher: yes']
    X Support: 65.57% | X and Y Support: 61% | Confidence: 93% | ['Pstatus: T', 'address: U', 'higher: yes'] -> ['school: GP']
    X Support: 68.1% | X and Y Support: 62% | Confidence: 90% | ['internet: yes', 'address: U'] -> ['higher: yes', 'school: GP']
    X Support: 64.3% | X and Y Support: 62% | Confidence: 96% | ['internet: yes', 'address: U', 'school: GP'] -> ['higher: yes']
    X Support: 65.06% | X and Y Support: 62% | Confidence: 95% | ['internet: yes', 'address: U', 'higher: yes'] -> ['school: GP']
    X Support: 63.8% | X and Y Support: 60% | Confidence: 94% | ['school: GP', 'famsize: GT3'] -> ['higher: yes']
    X Support: 70.89% | X and Y Support: 69% | Confidence: 98% | ['failures: 0', 'school: GP'] -> ['higher: yes']
    X Support: 67.09% | X and Y Support: 61% | Confidence: 91% | ['failures: 0', 'internet: yes'] -> ['school: GP']
    X Support: 63.04% | X and Y Support: 62% | Confidence: 98% | ['Pstatus: T', 'failures: 0', 'school: GP'] -> ['higher: yes']
    X Support: 71.39% | X and Y Support: 68% | Confidence: 96% | ['nursery: yes', 'school: GP'] -> ['higher: yes']
    X Support: 66.33% | X and Y Support: 61% | Confidence: 92% | ['nursery: yes', 'internet: yes'] -> ['school: GP']
    X Support: 63.04% | X and Y Support: 61% | Confidence: 96% | ['Dalc: 1', 'school: GP'] -> ['higher: yes']
    X Support: 78.73% | X and Y Support: 75% | Confidence: 95% | ['Pstatus: T', 'school: GP'] -> ['higher: yes']
    X Support: 75.44% | X and Y Support: 71% | Confidence: 95% | ['school: GP', 'schoolsup: no'] -> ['higher: yes']
    X Support: 75.19% | X and Y Support: 72% | Confidence: 95% | ['internet: yes', 'school: GP'] -> ['higher: yes']
    X Support: 79.24% | X and Y Support: 72% | Confidence: 90% | ['internet: yes', 'higher: yes'] -> ['school: GP']
    X Support: 67.59% | X and Y Support: 64% | Confidence: 94% | ['Pstatus: T', 'school: GP', 'schoolsup: no'] -> ['higher: yes']
    X Support: 67.34% | X and Y Support: 64% | Confidence: 95% | ['Pstatus: T', 'internet: yes', 'school: GP'] -> ['higher: yes']
    X Support: 64.56% | X and Y Support: 61% | Confidence: 95% | ['internet: yes', 'school: GP', 'schoolsup: no'] -> ['higher: yes']
    X Support: 63.04% | X and Y Support: 62% | Confidence: 98% | ['failures: 0', 'address: U'] -> ['higher: yes']
    X Support: 62.78% | X and Y Support: 60% | Confidence: 96% | ['nursery: yes', 'address: U'] -> ['higher: yes']
    X Support: 69.11% | X and Y Support: 66% | Confidence: 95% | ['Pstatus: T', 'address: U'] -> ['higher: yes']
    X Support: 67.34% | X and Y Support: 64% | Confidence: 95% | ['address: U', 'schoolsup: no'] -> ['higher: yes']
    X Support: 68.1% | X and Y Support: 65% | Confidence: 96% | ['internet: yes', 'address: U'] -> ['higher: yes']
    X Support: 68.1% | X and Y Support: 62% | Confidence: 90% | ['internet: yes', 'address: U'] -> ['Pstatus: T']
    X Support: 67.59% | X and Y Support: 63% | Confidence: 93% | ['higher: yes', 'famsize: GT3'] -> ['Pstatus: T']
    X Support: 65.82% | X and Y Support: 63% | Confidence: 95% | ['Pstatus: T', 'famsize: GT3'] -> ['higher: yes']
    X Support: 64.3% | X and Y Support: 63% | Confidence: 98% | ['failures: 0', 'nursery: yes'] -> ['higher: yes']
    X Support: 70.63% | X and Y Support: 69% | Confidence: 98% | ['Pstatus: T', 'failures: 0'] -> ['higher: yes']
    X Support: 68.86% | X and Y Support: 67% | Confidence: 97% | ['failures: 0', 'schoolsup: no'] -> ['higher: yes']
    X Support: 67.09% | X and Y Support: 65% | Confidence: 97% | ['failures: 0', 'internet: yes'] -> ['higher: yes']
    X Support: 67.09% | X and Y Support: 61% | Confidence: 91% | ['failures: 0', 'schoolsup: no', 'higher: yes'] -> ['Pstatus: T']
    X Support: 62.28% | X and Y Support: 61% | Confidence: 98% | ['Pstatus: T', 'failures: 0', 'schoolsup: no'] -> ['higher: yes']
    X Support: 68.86% | X and Y Support: 62% | Confidence: 90% | ['failures: 0', 'schoolsup: no'] -> ['Pstatus: T']
    X Support: 67.09% | X and Y Support: 61% | Confidence: 90% | ['failures: 0', 'internet: yes'] -> ['Pstatus: T']
    X Support: 70.13% | X and Y Support: 67% | Confidence: 95% | ['Pstatus: T', 'nursery: yes'] -> ['higher: yes']
    X Support: 68.61% | X and Y Support: 65% | Confidence: 95% | ['nursery: yes', 'schoolsup: no'] -> ['higher: yes']
    X Support: 66.33% | X and Y Support: 63% | Confidence: 95% | ['nursery: yes', 'internet: yes'] -> ['higher: yes']
    X Support: 62.53% | X and Y Support: 61% | Confidence: 97% | ['Pstatus: T', 'Dalc: 1'] -> ['higher: yes']
    X Support: 78.48% | X and Y Support: 74% | Confidence: 94% | ['Pstatus: T', 'schoolsup: no'] -> ['higher: yes']
    X Support: 79.24% | X and Y Support: 72% | Confidence: 90% | ['internet: yes', 'higher: yes'] -> ['Pstatus: T']
    X Support: 75.44% | X and Y Support: 72% | Confidence: 95% | ['Pstatus: T', 'internet: yes'] -> ['higher: yes']
    X Support: 68.86% | X and Y Support: 62% | Confidence: 90% | ['internet: yes', 'schoolsup: no', 'higher: yes'] -> ['Pstatus: T']
    X Support: 65.82% | X and Y Support: 62% | Confidence: 95% | ['Pstatus: T', 'internet: yes', 'schoolsup: no'] -> ['higher: yes']
    X Support: 72.66% | X and Y Support: 69% | Confidence: 95% | ['internet: yes', 'schoolsup: no'] -> ['higher: yes']
    X Support: 72.66% | X and Y Support: 66% | Confidence: 91% | ['internet: yes', 'schoolsup: no'] -> ['Pstatus: T']
    X Support: 64.3% | X and Y Support: 61% | Confidence: 95% | ['Pstatus: T', 'address: U', 'school: GP'] -> ['higher: yes']
    X Support: 65.57% | X and Y Support: 61% | Confidence: 93% | ['Pstatus: T', 'address: U', 'higher: yes'] -> ['school: GP']
    X Support: 68.1% | X and Y Support: 62% | Confidence: 90% | ['internet: yes', 'address: U'] -> ['higher: yes', 'school: GP']
    X Support: 64.3% | X and Y Support: 62% | Confidence: 96% | ['internet: yes', 'address: U', 'school: GP'] -> ['higher: yes']
    X Support: 65.06% | X and Y Support: 62% | Confidence: 95% | ['internet: yes', 'address: U', 'higher: yes'] -> ['school: GP']
    X Support: 63.04% | X and Y Support: 62% | Confidence: 98% | ['Pstatus: T', 'failures: 0', 'school: GP'] -> ['higher: yes']
    X Support: 67.59% | X and Y Support: 64% | Confidence: 94% | ['Pstatus: T', 'school: GP', 'schoolsup: no'] -> ['higher: yes']
    X Support: 67.34% | X and Y Support: 64% | Confidence: 95% | ['Pstatus: T', 'internet: yes', 'school: GP'] -> ['higher: yes']
    X Support: 64.56% | X and Y Support: 61% | Confidence: 95% | ['internet: yes', 'school: GP', 'schoolsup: no'] -> ['higher: yes']
    X Support: 67.09% | X and Y Support: 61% | Confidence: 91% | ['failures: 0', 'schoolsup: no', 'higher: yes'] -> ['Pstatus: T']
    X Support: 62.28% | X and Y Support: 61% | Confidence: 98% | ['Pstatus: T', 'failures: 0', 'schoolsup: no'] -> ['higher: yes']
    X Support: 68.86% | X and Y Support: 62% | Confidence: 90% | ['internet: yes', 'schoolsup: no', 'higher: yes'] -> ['Pstatus: T']
    X Support: 65.82% | X and Y Support: 62% | Confidence: 95% | ['Pstatus: T', 'internet: yes', 'schoolsup: no'] -> ['higher: yes']
        </code>
      </pre>
    </figure>
        
                <h5><center>Test 3 (Min Support = 60%, Min Confidence = 60%) :</center></h5>

        
        <figure>
  <figcaption></figcaption>
  <pre>
    <code>
    Enter Minimum Support (0%-100%): 60
    Enter Minimum Confidence (0%-100%): 60
    Data Set: mathdataset.txt
    Minimum support: 60
    Minimum confidence: 60
    ------------------------------------------------------------------------
    X Support: 88.35% | X and Y Support: 72% | Confidence: 82% | ['school: GP'] -> ['address: U']
    X Support: 77.72% | X and Y Support: 72% | Confidence: 93% | ['address: U'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 64% | Confidence: 72% | ['school: GP'] -> ['famsize: GT3']
    X Support: 71.14% | X and Y Support: 64% | Confidence: 90% | ['famsize: GT3'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 62% | Confidence: 70% | ['school: GP'] -> ['guardian: mother']
    X Support: 69.11% | X and Y Support: 62% | Confidence: 90% | ['guardian: mother'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 71% | Confidence: 80% | ['school: GP'] -> ['failures: 0']
    X Support: 78.99% | X and Y Support: 71% | Confidence: 90% | ['failures: 0'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 71% | Confidence: 81% | ['school: GP'] -> ['nursery: yes']
    X Support: 79.49% | X and Y Support: 71% | Confidence: 90% | ['nursery: yes'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 84% | Confidence: 95% | ['school: GP'] -> ['higher: yes']
    X Support: 94.94% | X and Y Support: 84% | Confidence: 89% | ['higher: yes'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 63% | Confidence: 71% | ['school: GP'] -> ['Dalc: 1']
    X Support: 69.87% | X and Y Support: 63% | Confidence: 90% | ['Dalc: 1'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 79% | Confidence: 89% | ['school: GP'] -> ['Pstatus: T']
    X Support: 89.62% | X and Y Support: 79% | Confidence: 88% | ['Pstatus: T'] -> ['school: GP']
    X Support: 65.06% | X and Y Support: 62% | Confidence: 95% | ['traveltime: 1'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 62% | Confidence: 70% | ['school: GP'] -> ['traveltime: 1']
    X Support: 87.09% | X and Y Support: 75% | Confidence: 87% | ['schoolsup: no'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 75% | Confidence: 85% | ['school: GP'] -> ['schoolsup: no']
    X Support: 88.35% | X and Y Support: 75% | Confidence: 85% | ['school: GP'] -> ['internet: yes']
    X Support: 83.29% | X and Y Support: 75% | Confidence: 90% | ['internet: yes'] -> ['school: GP']
    X Support: 78.99% | X and Y Support: 63% | Confidence: 80% | ['failures: 0'] -> ['address: U']
    X Support: 77.72% | X and Y Support: 63% | Confidence: 81% | ['address: U'] -> ['failures: 0']
    X Support: 79.49% | X and Y Support: 63% | Confidence: 79% | ['nursery: yes'] -> ['address: U']
    X Support: 77.72% | X and Y Support: 63% | Confidence: 81% | ['address: U'] -> ['nursery: yes']
    X Support: 94.94% | X and Y Support: 74% | Confidence: 78% | ['higher: yes'] -> ['address: U']
    X Support: 77.72% | X and Y Support: 74% | Confidence: 95% | ['address: U'] -> ['higher: yes']
    X Support: 77.72% | X and Y Support: 69% | Confidence: 89% | ['address: U'] -> ['Pstatus: T']
    X Support: 89.62% | X and Y Support: 69% | Confidence: 77% | ['Pstatus: T'] -> ['address: U']
    X Support: 87.09% | X and Y Support: 67% | Confidence: 77% | ['schoolsup: no'] -> ['address: U']
    X Support: 77.72% | X and Y Support: 67% | Confidence: 87% | ['address: U'] -> ['schoolsup: no']
    X Support: 83.29% | X and Y Support: 68% | Confidence: 82% | ['internet: yes'] -> ['address: U']
    X Support: 77.72% | X and Y Support: 68% | Confidence: 88% | ['address: U'] -> ['internet: yes']
    X Support: 94.94% | X and Y Support: 68% | Confidence: 71% | ['higher: yes'] -> ['famsize: GT3']
    X Support: 71.14% | X and Y Support: 68% | Confidence: 95% | ['famsize: GT3'] -> ['higher: yes']
    X Support: 71.14% | X and Y Support: 66% | Confidence: 93% | ['famsize: GT3'] -> ['Pstatus: T']
    X Support: 89.62% | X and Y Support: 66% | Confidence: 73% | ['Pstatus: T'] -> ['famsize: GT3']
    X Support: 87.09% | X and Y Support: 62% | Confidence: 71% | ['schoolsup: no'] -> ['famsize: GT3']
    X Support: 71.14% | X and Y Support: 62% | Confidence: 86% | ['famsize: GT3'] -> ['schoolsup: no']
    X Support: 94.94% | X and Y Support: 66% | Confidence: 69% | ['higher: yes'] -> ['guardian: mother']
    X Support: 69.11% | X and Y Support: 66% | Confidence: 95% | ['guardian: mother'] -> ['higher: yes']
    X Support: 69.11% | X and Y Support: 61% | Confidence: 88% | ['guardian: mother'] -> ['Pstatus: T']
    X Support: 89.62% | X and Y Support: 61% | Confidence: 68% | ['Pstatus: T'] -> ['guardian: mother']
    X Support: 87.09% | X and Y Support: 60% | Confidence: 69% | ['schoolsup: no'] -> ['guardian: mother']
    X Support: 69.11% | X and Y Support: 60% | Confidence: 87% | ['guardian: mother'] -> ['schoolsup: no']
    X Support: 79.49% | X and Y Support: 64% | Confidence: 81% | ['nursery: yes'] -> ['failures: 0']
    X Support: 78.99% | X and Y Support: 64% | Confidence: 81% | ['failures: 0'] -> ['nursery: yes']
    X Support: 94.94% | X and Y Support: 77% | Confidence: 81% | ['higher: yes'] -> ['failures: 0']
    X Support: 78.99% | X and Y Support: 77% | Confidence: 98% | ['failures: 0'] -> ['higher: yes']
    X Support: 78.99% | X and Y Support: 71% | Confidence: 89% | ['failures: 0'] -> ['Pstatus: T']
    X Support: 89.62% | X and Y Support: 71% | Confidence: 79% | ['Pstatus: T'] -> ['failures: 0']
    X Support: 87.09% | X and Y Support: 69% | Confidence: 79% | ['schoolsup: no'] -> ['failures: 0']
    X Support: 78.99% | X and Y Support: 69% | Confidence: 87% | ['failures: 0'] -> ['schoolsup: no']
    X Support: 83.29% | X and Y Support: 67% | Confidence: 81% | ['internet: yes'] -> ['failures: 0']
    X Support: 78.99% | X and Y Support: 67% | Confidence: 85% | ['failures: 0'] -> ['internet: yes']
    X Support: 79.49% | X and Y Support: 76% | Confidence: 96% | ['nursery: yes'] -> ['higher: yes']
    X Support: 94.94% | X and Y Support: 76% | Confidence: 80% | ['higher: yes'] -> ['nursery: yes']
    X Support: 79.49% | X and Y Support: 70% | Confidence: 88% | ['nursery: yes'] -> ['Pstatus: T']
    X Support: 89.62% | X and Y Support: 70% | Confidence: 78% | ['Pstatus: T'] -> ['nursery: yes']
    X Support: 87.09% | X and Y Support: 69% | Confidence: 79% | ['schoolsup: no'] -> ['nursery: yes']
    X Support: 79.49% | X and Y Support: 69% | Confidence: 86% | ['nursery: yes'] -> ['schoolsup: no']
    X Support: 79.49% | X and Y Support: 66% | Confidence: 83% | ['nursery: yes'] -> ['internet: yes']
    X Support: 83.29% | X and Y Support: 66% | Confidence: 80% | ['internet: yes'] -> ['nursery: yes']
    X Support: 66.58% | X and Y Support: 64% | Confidence: 97% | ['romantic: no'] -> ['higher: yes']
    X Support: 94.94% | X and Y Support: 64% | Confidence: 68% | ['higher: yes'] -> ['romantic: no']
    X Support: 94.94% | X and Y Support: 68% | Confidence: 71% | ['higher: yes'] -> ['Dalc: 1']
    X Support: 69.87% | X and Y Support: 68% | Confidence: 97% | ['Dalc: 1'] -> ['higher: yes']
    X Support: 94.94% | X and Y Support: 85% | Confidence: 89% | ['higher: yes'] -> ['Pstatus: T']
    X Support: 89.62% | X and Y Support: 85% | Confidence: 95% | ['Pstatus: T'] -> ['higher: yes']
    X Support: 65.06% | X and Y Support: 63% | Confidence: 96% | ['traveltime: 1'] -> ['higher: yes']
    X Support: 94.94% | X and Y Support: 63% | Confidence: 66% | ['higher: yes'] -> ['traveltime: 1']
    X Support: 87.09% | X and Y Support: 82% | Confidence: 94% | ['schoolsup: no'] -> ['higher: yes']
    X Support: 94.94% | X and Y Support: 82% | Confidence: 87% | ['higher: yes'] -> ['schoolsup: no']
    X Support: 83.29% | X and Y Support: 79% | Confidence: 95% | ['internet: yes'] -> ['higher: yes']
    X Support: 94.94% | X and Y Support: 79% | Confidence: 83% | ['higher: yes'] -> ['internet: yes']
    X Support: 66.58% | X and Y Support: 60% | Confidence: 90% | ['romantic: no'] -> ['Pstatus: T']
    X Support: 89.62% | X and Y Support: 60% | Confidence: 67% | ['Pstatus: T'] -> ['romantic: no']
    X Support: 89.62% | X and Y Support: 63% | Confidence: 70% | ['Pstatus: T'] -> ['Dalc: 1']
    X Support: 69.87% | X and Y Support: 63% | Confidence: 89% | ['Dalc: 1'] -> ['Pstatus: T']
    X Support: 87.09% | X and Y Support: 60% | Confidence: 69% | ['schoolsup: no'] -> ['Dalc: 1']
    X Support: 69.87% | X and Y Support: 60% | Confidence: 86% | ['Dalc: 1'] -> ['schoolsup: no']
    X Support: 87.09% | X and Y Support: 78% | Confidence: 90% | ['schoolsup: no'] -> ['Pstatus: T']
    X Support: 89.62% | X and Y Support: 78% | Confidence: 88% | ['Pstatus: T'] -> ['schoolsup: no']
    X Support: 83.29% | X and Y Support: 75% | Confidence: 91% | ['internet: yes'] -> ['Pstatus: T']
    X Support: 89.62% | X and Y Support: 75% | Confidence: 84% | ['Pstatus: T'] -> ['internet: yes']
    X Support: 87.09% | X and Y Support: 73% | Confidence: 83% | ['schoolsup: no'] -> ['internet: yes']
    X Support: 83.29% | X and Y Support: 73% | Confidence: 87% | ['internet: yes'] -> ['schoolsup: no']
    X Support: 88.35% | X and Y Support: 69% | Confidence: 78% | ['school: GP'] -> ['address: U', 'higher: yes']
    X Support: 94.94% | X and Y Support: 69% | Confidence: 73% | ['higher: yes'] -> ['address: U', 'school: GP']
    X Support: 77.72% | X and Y Support: 69% | Confidence: 89% | ['address: U'] -> ['higher: yes', 'school: GP']
    X Support: 84.05% | X and Y Support: 69% | Confidence: 82% | ['school: GP', 'higher: yes'] -> ['address: U']
    X Support: 72.41% | X and Y Support: 69% | Confidence: 95% | ['school: GP', 'address: U'] -> ['higher: yes']
    X Support: 74.18% | X and Y Support: 69% | Confidence: 93% | ['address: U', 'higher: yes'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 64% | Confidence: 73% | ['school: GP'] -> ['Pstatus: T', 'address: U']
    X Support: 77.72% | X and Y Support: 64% | Confidence: 83% | ['address: U'] -> ['Pstatus: T', 'school: GP']
    X Support: 89.62% | X and Y Support: 64% | Confidence: 72% | ['Pstatus: T'] -> ['address: U', 'school: GP']
    X Support: 72.41% | X and Y Support: 64% | Confidence: 89% | ['school: GP', 'address: U'] -> ['Pstatus: T']
    X Support: 78.73% | X and Y Support: 64% | Confidence: 82% | ['Pstatus: T', 'school: GP'] -> ['address: U']
    X Support: 69.11% | X and Y Support: 64% | Confidence: 93% | ['Pstatus: T', 'address: U'] -> ['school: GP']
    X Support: 87.09% | X and Y Support: 62% | Confidence: 71% | ['schoolsup: no'] -> ['address: U', 'school: GP']
    X Support: 88.35% | X and Y Support: 62% | Confidence: 70% | ['school: GP'] -> ['address: U', 'schoolsup: no']
    X Support: 77.72% | X and Y Support: 62% | Confidence: 80% | ['address: U'] -> ['school: GP', 'schoolsup: no']
    X Support: 75.44% | X and Y Support: 62% | Confidence: 82% | ['school: GP', 'schoolsup: no'] -> ['address: U']
    X Support: 67.34% | X and Y Support: 62% | Confidence: 92% | ['address: U', 'schoolsup: no'] -> ['school: GP']
    X Support: 72.41% | X and Y Support: 62% | Confidence: 86% | ['school: GP', 'address: U'] -> ['schoolsup: no']
    X Support: 88.35% | X and Y Support: 64% | Confidence: 73% | ['school: GP'] -> ['address: U', 'internet: yes']
    X Support: 83.29% | X and Y Support: 64% | Confidence: 77% | ['internet: yes'] -> ['address: U', 'school: GP']
    X Support: 77.72% | X and Y Support: 64% | Confidence: 83% | ['address: U'] -> ['internet: yes', 'school: GP']
    X Support: 75.19% | X and Y Support: 64% | Confidence: 86% | ['internet: yes', 'school: GP'] -> ['address: U']
    X Support: 72.41% | X and Y Support: 64% | Confidence: 89% | ['school: GP', 'address: U'] -> ['internet: yes']
    X Support: 68.1% | X and Y Support: 64% | Confidence: 94% | ['internet: yes', 'address: U'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 61% | Confidence: 69% | ['school: GP'] -> ['Pstatus: T', 'address: U', 'higher: yes']
    X Support: 94.94% | X and Y Support: 61% | Confidence: 64% | ['higher: yes'] -> ['Pstatus: T', 'address: U', 'school: GP']
    X Support: 77.72% | X and Y Support: 61% | Confidence: 79% | ['address: U'] -> ['Pstatus: T', 'higher: yes', 'school: GP']
    X Support: 89.62% | X and Y Support: 61% | Confidence: 68% | ['Pstatus: T'] -> ['address: U', 'higher: yes', 'school: GP']
    X Support: 84.05% | X and Y Support: 61% | Confidence: 73% | ['school: GP', 'higher: yes'] -> ['Pstatus: T', 'address: U']
    X Support: 72.41% | X and Y Support: 61% | Confidence: 84% | ['school: GP', 'address: U'] -> ['Pstatus: T', 'higher: yes']
    X Support: 74.18% | X and Y Support: 61% | Confidence: 82% | ['address: U', 'higher: yes'] -> ['Pstatus: T', 'school: GP']
    X Support: 78.73% | X and Y Support: 61% | Confidence: 77% | ['Pstatus: T', 'school: GP'] -> ['address: U', 'higher: yes']
    X Support: 84.81% | X and Y Support: 61% | Confidence: 72% | ['Pstatus: T', 'higher: yes'] -> ['address: U', 'school: GP']
    X Support: 69.11% | X and Y Support: 61% | Confidence: 88% | ['Pstatus: T', 'address: U'] -> ['higher: yes', 'school: GP']
    X Support: 69.11% | X and Y Support: 61% | Confidence: 88% | ['address: U', 'school: GP', 'higher: yes'] -> ['Pstatus: T']
    X Support: 74.68% | X and Y Support: 61% | Confidence: 82% | ['Pstatus: T', 'school: GP', 'higher: yes'] -> ['address: U']
    X Support: 64.3% | X and Y Support: 61% | Confidence: 95% | ['Pstatus: T', 'school: GP', 'address: U'] -> ['higher: yes']
    X Support: 65.57% | X and Y Support: 61% | Confidence: 93% | ['address: U', 'Pstatus: T', 'higher: yes'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 62% | Confidence: 70% | ['school: GP'] -> ['address: U', 'higher: yes', 'internet: yes']
    X Support: 83.29% | X and Y Support: 62% | Confidence: 74% | ['internet: yes'] -> ['address: U', 'higher: yes', 'school: GP']
    X Support: 94.94% | X and Y Support: 62% | Confidence: 65% | ['higher: yes'] -> ['address: U', 'internet: yes', 'school: GP']
    X Support: 77.72% | X and Y Support: 62% | Confidence: 79% | ['address: U'] -> ['higher: yes', 'internet: yes', 'school: GP']
    X Support: 75.19% | X and Y Support: 62% | Confidence: 82% | ['internet: yes', 'school: GP'] -> ['address: U', 'higher: yes']
    X Support: 84.05% | X and Y Support: 62% | Confidence: 73% | ['school: GP', 'higher: yes'] -> ['address: U', 'internet: yes']
    X Support: 79.24% | X and Y Support: 62% | Confidence: 78% | ['internet: yes', 'higher: yes'] -> ['address: U', 'school: GP']
    X Support: 72.41% | X and Y Support: 62% | Confidence: 85% | ['school: GP', 'address: U'] -> ['higher: yes', 'internet: yes']
    X Support: 68.1% | X and Y Support: 62% | Confidence: 90% | ['internet: yes', 'address: U'] -> ['higher: yes', 'school: GP']
    X Support: 74.18% | X and Y Support: 62% | Confidence: 83% | ['address: U', 'higher: yes'] -> ['internet: yes', 'school: GP']
    X Support: 71.65% | X and Y Support: 62% | Confidence: 86% | ['internet: yes', 'school: GP', 'higher: yes'] -> ['address: U']
    X Support: 64.3% | X and Y Support: 62% | Confidence: 96% | ['internet: yes', 'school: GP', 'address: U'] -> ['higher: yes']
    X Support: 69.11% | X and Y Support: 62% | Confidence: 89% | ['address: U', 'school: GP', 'higher: yes'] -> ['internet: yes']
    X Support: 65.06% | X and Y Support: 62% | Confidence: 95% | ['address: U', 'internet: yes', 'higher: yes'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 60% | Confidence: 68% | ['school: GP'] -> ['famsize: GT3', 'higher: yes']
    X Support: 94.94% | X and Y Support: 60% | Confidence: 63% | ['higher: yes'] -> ['famsize: GT3', 'school: GP']
    X Support: 71.14% | X and Y Support: 60% | Confidence: 85% | ['famsize: GT3'] -> ['higher: yes', 'school: GP']
    X Support: 84.05% | X and Y Support: 60% | Confidence: 72% | ['school: GP', 'higher: yes'] -> ['famsize: GT3']
    X Support: 63.8% | X and Y Support: 60% | Confidence: 94% | ['famsize: GT3', 'school: GP'] -> ['higher: yes']
    X Support: 67.59% | X and Y Support: 60% | Confidence: 89% | ['famsize: GT3', 'higher: yes'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 69% | Confidence: 79% | ['school: GP'] -> ['failures: 0', 'higher: yes']
    X Support: 94.94% | X and Y Support: 69% | Confidence: 73% | ['higher: yes'] -> ['failures: 0', 'school: GP']
    X Support: 78.99% | X and Y Support: 69% | Confidence: 88% | ['failures: 0'] -> ['higher: yes', 'school: GP']
    X Support: 84.05% | X and Y Support: 69% | Confidence: 83% | ['school: GP', 'higher: yes'] -> ['failures: 0']
    X Support: 70.89% | X and Y Support: 69% | Confidence: 98% | ['failures: 0', 'school: GP'] -> ['higher: yes']
    X Support: 77.22% | X and Y Support: 69% | Confidence: 90% | ['failures: 0', 'higher: yes'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 63% | Confidence: 71% | ['school: GP'] -> ['Pstatus: T', 'failures: 0']
    X Support: 78.99% | X and Y Support: 63% | Confidence: 80% | ['failures: 0'] -> ['Pstatus: T', 'school: GP']
    X Support: 89.62% | X and Y Support: 63% | Confidence: 70% | ['Pstatus: T'] -> ['failures: 0', 'school: GP']
    X Support: 70.89% | X and Y Support: 63% | Confidence: 89% | ['failures: 0', 'school: GP'] -> ['Pstatus: T']
    X Support: 78.73% | X and Y Support: 63% | Confidence: 80% | ['Pstatus: T', 'school: GP'] -> ['failures: 0']
    X Support: 70.63% | X and Y Support: 63% | Confidence: 89% | ['Pstatus: T', 'failures: 0'] -> ['school: GP']
    X Support: 87.09% | X and Y Support: 61% | Confidence: 70% | ['schoolsup: no'] -> ['failures: 0', 'school: GP']
    X Support: 88.35% | X and Y Support: 61% | Confidence: 69% | ['school: GP'] -> ['failures: 0', 'schoolsup: no']
    X Support: 78.99% | X and Y Support: 61% | Confidence: 77% | ['failures: 0'] -> ['school: GP', 'schoolsup: no']
    X Support: 75.44% | X and Y Support: 61% | Confidence: 81% | ['school: GP', 'schoolsup: no'] -> ['failures: 0']
    X Support: 68.86% | X and Y Support: 61% | Confidence: 88% | ['failures: 0', 'schoolsup: no'] -> ['school: GP']
    X Support: 70.89% | X and Y Support: 61% | Confidence: 86% | ['failures: 0', 'school: GP'] -> ['schoolsup: no']
    X Support: 88.35% | X and Y Support: 61% | Confidence: 69% | ['school: GP'] -> ['failures: 0', 'internet: yes']
    X Support: 83.29% | X and Y Support: 61% | Confidence: 73% | ['internet: yes'] -> ['failures: 0', 'school: GP']
    X Support: 78.99% | X and Y Support: 61% | Confidence: 77% | ['failures: 0'] -> ['internet: yes', 'school: GP']
    X Support: 75.19% | X and Y Support: 61% | Confidence: 81% | ['school: GP', 'internet: yes'] -> ['failures: 0']
    X Support: 70.89% | X and Y Support: 61% | Confidence: 86% | ['failures: 0', 'school: GP'] -> ['internet: yes']
    X Support: 67.09% | X and Y Support: 61% | Confidence: 91% | ['failures: 0', 'internet: yes'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 62% | Confidence: 70% | ['school: GP'] -> ['Pstatus: T', 'failures: 0', 'higher: yes']
    X Support: 94.94% | X and Y Support: 62% | Confidence: 65% | ['higher: yes'] -> ['Pstatus: T', 'failures: 0', 'school: GP']
    X Support: 78.99% | X and Y Support: 62% | Confidence: 78% | ['failures: 0'] -> ['Pstatus: T', 'higher: yes', 'school: GP']
    X Support: 89.62% | X and Y Support: 62% | Confidence: 69% | ['Pstatus: T'] -> ['failures: 0', 'higher: yes', 'school: GP']
    X Support: 84.05% | X and Y Support: 62% | Confidence: 73% | ['school: GP', 'higher: yes'] -> ['Pstatus: T', 'failures: 0']
    X Support: 70.89% | X and Y Support: 62% | Confidence: 87% | ['failures: 0', 'school: GP'] -> ['Pstatus: T', 'higher: yes']
    X Support: 77.22% | X and Y Support: 62% | Confidence: 80% | ['failures: 0', 'higher: yes'] -> ['Pstatus: T', 'school: GP']
    X Support: 78.73% | X and Y Support: 62% | Confidence: 78% | ['Pstatus: T', 'school: GP'] -> ['failures: 0', 'higher: yes']
    X Support: 84.81% | X and Y Support: 62% | Confidence: 73% | ['Pstatus: T', 'higher: yes'] -> ['failures: 0', 'school: GP']
    X Support: 70.63% | X and Y Support: 62% | Confidence: 87% | ['Pstatus: T', 'failures: 0'] -> ['higher: yes', 'school: GP']
    X Support: 69.37% | X and Y Support: 62% | Confidence: 89% | ['failures: 0', 'school: GP', 'higher: yes'] -> ['Pstatus: T']
    X Support: 74.68% | X and Y Support: 62% | Confidence: 83% | ['Pstatus: T', 'school: GP', 'higher: yes'] -> ['failures: 0']
    X Support: 63.04% | X and Y Support: 62% | Confidence: 98% | ['Pstatus: T', 'failures: 0', 'school: GP'] -> ['higher: yes']
    X Support: 69.11% | X and Y Support: 62% | Confidence: 89% | ['Pstatus: T', 'failures: 0', 'higher: yes'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 68% | Confidence: 77% | ['school: GP'] -> ['higher: yes', 'nursery: yes']
    X Support: 79.49% | X and Y Support: 68% | Confidence: 86% | ['nursery: yes'] -> ['higher: yes', 'school: GP']
    X Support: 94.94% | X and Y Support: 68% | Confidence: 72% | ['higher: yes'] -> ['nursery: yes', 'school: GP']
    X Support: 71.39% | X and Y Support: 68% | Confidence: 96% | ['nursery: yes', 'school: GP'] -> ['higher: yes']
    X Support: 84.05% | X and Y Support: 68% | Confidence: 81% | ['school: GP', 'higher: yes'] -> ['nursery: yes']
    X Support: 75.95% | X and Y Support: 68% | Confidence: 90% | ['nursery: yes', 'higher: yes'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 63% | Confidence: 71% | ['school: GP'] -> ['Pstatus: T', 'nursery: yes']
    X Support: 79.49% | X and Y Support: 63% | Confidence: 79% | ['nursery: yes'] -> ['Pstatus: T', 'school: GP']
    X Support: 89.62% | X and Y Support: 63% | Confidence: 70% | ['Pstatus: T'] -> ['nursery: yes', 'school: GP']
    X Support: 71.39% | X and Y Support: 63% | Confidence: 88% | ['nursery: yes', 'school: GP'] -> ['Pstatus: T']
    X Support: 78.73% | X and Y Support: 63% | Confidence: 80% | ['Pstatus: T', 'school: GP'] -> ['nursery: yes']
    X Support: 70.13% | X and Y Support: 63% | Confidence: 90% | ['Pstatus: T', 'nursery: yes'] -> ['school: GP']
    X Support: 87.09% | X and Y Support: 61% | Confidence: 69% | ['schoolsup: no'] -> ['nursery: yes', 'school: GP']
    X Support: 88.35% | X and Y Support: 61% | Confidence: 68% | ['school: GP'] -> ['nursery: yes', 'schoolsup: no']
    X Support: 79.49% | X and Y Support: 61% | Confidence: 76% | ['nursery: yes'] -> ['school: GP', 'schoolsup: no']
    X Support: 75.44% | X and Y Support: 61% | Confidence: 80% | ['school: GP', 'schoolsup: no'] -> ['nursery: yes']
    X Support: 68.61% | X and Y Support: 61% | Confidence: 88% | ['nursery: yes', 'schoolsup: no'] -> ['school: GP']
    X Support: 71.39% | X and Y Support: 61% | Confidence: 85% | ['nursery: yes', 'school: GP'] -> ['schoolsup: no']
    X Support: 88.35% | X and Y Support: 61% | Confidence: 69% | ['school: GP'] -> ['internet: yes', 'nursery: yes']
    X Support: 79.49% | X and Y Support: 61% | Confidence: 77% | ['nursery: yes'] -> ['internet: yes', 'school: GP']
    X Support: 83.29% | X and Y Support: 61% | Confidence: 73% | ['internet: yes'] -> ['nursery: yes', 'school: GP']
    X Support: 71.39% | X and Y Support: 61% | Confidence: 85% | ['school: GP', 'nursery: yes'] -> ['internet: yes']
    X Support: 75.19% | X and Y Support: 61% | Confidence: 81% | ['internet: yes', 'school: GP'] -> ['nursery: yes']
    X Support: 66.33% | X and Y Support: 61% | Confidence: 92% | ['internet: yes', 'nursery: yes'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 61% | Confidence: 69% | ['school: GP'] -> ['Dalc: 1', 'higher: yes']
    X Support: 94.94% | X and Y Support: 61% | Confidence: 64% | ['higher: yes'] -> ['Dalc: 1', 'school: GP']
    X Support: 69.87% | X and Y Support: 61% | Confidence: 87% | ['Dalc: 1'] -> ['higher: yes', 'school: GP']
    X Support: 84.05% | X and Y Support: 61% | Confidence: 72% | ['school: GP', 'higher: yes'] -> ['Dalc: 1']
    X Support: 63.04% | X and Y Support: 61% | Confidence: 96% | ['Dalc: 1', 'school: GP'] -> ['higher: yes']
    X Support: 67.59% | X and Y Support: 61% | Confidence: 90% | ['Dalc: 1', 'higher: yes'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 75% | Confidence: 85% | ['school: GP'] -> ['Pstatus: T', 'higher: yes']
    X Support: 94.94% | X and Y Support: 75% | Confidence: 79% | ['higher: yes'] -> ['Pstatus: T', 'school: GP']
    X Support: 89.62% | X and Y Support: 75% | Confidence: 83% | ['Pstatus: T'] -> ['higher: yes', 'school: GP']
    X Support: 84.05% | X and Y Support: 75% | Confidence: 89% | ['school: GP', 'higher: yes'] -> ['Pstatus: T']
    X Support: 78.73% | X and Y Support: 75% | Confidence: 95% | ['Pstatus: T', 'school: GP'] -> ['higher: yes']
    X Support: 84.81% | X and Y Support: 75% | Confidence: 88% | ['Pstatus: T', 'higher: yes'] -> ['school: GP']
    X Support: 87.09% | X and Y Support: 71% | Confidence: 82% | ['schoolsup: no'] -> ['higher: yes', 'school: GP']
    X Support: 88.35% | X and Y Support: 71% | Confidence: 81% | ['school: GP'] -> ['higher: yes', 'schoolsup: no']
    X Support: 94.94% | X and Y Support: 71% | Confidence: 75% | ['higher: yes'] -> ['school: GP', 'schoolsup: no']
    X Support: 75.44% | X and Y Support: 71% | Confidence: 95% | ['school: GP', 'schoolsup: no'] -> ['higher: yes']
    X Support: 82.28% | X and Y Support: 71% | Confidence: 87% | ['higher: yes', 'schoolsup: no'] -> ['school: GP']
    X Support: 84.05% | X and Y Support: 71% | Confidence: 85% | ['school: GP', 'higher: yes'] -> ['schoolsup: no']
    X Support: 88.35% | X and Y Support: 72% | Confidence: 81% | ['school: GP'] -> ['higher: yes', 'internet: yes']
    X Support: 83.29% | X and Y Support: 72% | Confidence: 86% | ['internet: yes'] -> ['higher: yes', 'school: GP']
    X Support: 94.94% | X and Y Support: 72% | Confidence: 75% | ['higher: yes'] -> ['internet: yes', 'school: GP']
    X Support: 75.19% | X and Y Support: 72% | Confidence: 95% | ['internet: yes', 'school: GP'] -> ['higher: yes']
    X Support: 84.05% | X and Y Support: 72% | Confidence: 85% | ['school: GP', 'higher: yes'] -> ['internet: yes']
    X Support: 79.24% | X and Y Support: 72% | Confidence: 90% | ['internet: yes', 'higher: yes'] -> ['school: GP']
    X Support: 87.09% | X and Y Support: 64% | Confidence: 73% | ['schoolsup: no'] -> ['Pstatus: T', 'higher: yes', 'school: GP']
    X Support: 88.35% | X and Y Support: 64% | Confidence: 72% | ['school: GP'] -> ['Pstatus: T', 'higher: yes', 'schoolsup: no']
    X Support: 94.94% | X and Y Support: 64% | Confidence: 67% | ['higher: yes'] -> ['Pstatus: T', 'school: GP', 'schoolsup: no']
    X Support: 89.62% | X and Y Support: 64% | Confidence: 71% | ['Pstatus: T'] -> ['higher: yes', 'school: GP', 'schoolsup: no']
    X Support: 75.44% | X and Y Support: 64% | Confidence: 85% | ['school: GP', 'schoolsup: no'] -> ['Pstatus: T', 'higher: yes']
    X Support: 82.28% | X and Y Support: 64% | Confidence: 78% | ['higher: yes', 'schoolsup: no'] -> ['Pstatus: T', 'school: GP']
    X Support: 84.05% | X and Y Support: 64% | Confidence: 76% | ['school: GP', 'higher: yes'] -> ['Pstatus: T', 'schoolsup: no']
    X Support: 78.48% | X and Y Support: 64% | Confidence: 81% | ['Pstatus: T', 'schoolsup: no'] -> ['higher: yes', 'school: GP']
    X Support: 78.73% | X and Y Support: 64% | Confidence: 81% | ['Pstatus: T', 'school: GP'] -> ['higher: yes', 'schoolsup: no']
    X Support: 84.81% | X and Y Support: 64% | Confidence: 75% | ['Pstatus: T', 'higher: yes'] -> ['school: GP', 'schoolsup: no']
    X Support: 71.39% | X and Y Support: 64% | Confidence: 89% | ['higher: yes', 'school: GP', 'schoolsup: no'] -> ['Pstatus: T']
    X Support: 67.59% | X and Y Support: 64% | Confidence: 94% | ['Pstatus: T', 'school: GP', 'schoolsup: no'] -> ['higher: yes']
    X Support: 73.92% | X and Y Support: 64% | Confidence: 86% | ['higher: yes', 'Pstatus: T', 'schoolsup: no'] -> ['school: GP']
    X Support: 74.68% | X and Y Support: 64% | Confidence: 85% | ['Pstatus: T', 'school: GP', 'higher: yes'] -> ['schoolsup: no']
    X Support: 88.35% | X and Y Support: 64% | Confidence: 72% | ['school: GP'] -> ['Pstatus: T', 'higher: yes', 'internet: yes']
    X Support: 83.29% | X and Y Support: 64% | Confidence: 77% | ['internet: yes'] -> ['Pstatus: T', 'higher: yes', 'school: GP']
    X Support: 94.94% | X and Y Support: 64% | Confidence: 67% | ['higher: yes'] -> ['Pstatus: T', 'internet: yes', 'school: GP']
    X Support: 89.62% | X and Y Support: 64% | Confidence: 71% | ['Pstatus: T'] -> ['higher: yes', 'internet: yes', 'school: GP']
    X Support: 75.19% | X and Y Support: 64% | Confidence: 85% | ['internet: yes', 'school: GP'] -> ['Pstatus: T', 'higher: yes']
    X Support: 84.05% | X and Y Support: 64% | Confidence: 76% | ['school: GP', 'higher: yes'] -> ['Pstatus: T', 'internet: yes']
    X Support: 79.24% | X and Y Support: 64% | Confidence: 81% | ['internet: yes', 'higher: yes'] -> ['Pstatus: T', 'school: GP']
    X Support: 78.73% | X and Y Support: 64% | Confidence: 81% | ['Pstatus: T', 'school: GP'] -> ['higher: yes', 'internet: yes']
    X Support: 75.44% | X and Y Support: 64% | Confidence: 85% | ['Pstatus: T', 'internet: yes'] -> ['higher: yes', 'school: GP']
    X Support: 84.81% | X and Y Support: 64% | Confidence: 76% | ['Pstatus: T', 'higher: yes'] -> ['internet: yes', 'school: GP']
    X Support: 71.65% | X and Y Support: 64% | Confidence: 89% | ['internet: yes', 'school: GP', 'higher: yes'] -> ['Pstatus: T']
    X Support: 67.34% | X and Y Support: 64% | Confidence: 95% | ['Pstatus: T', 'internet: yes', 'school: GP'] -> ['higher: yes']
    X Support: 74.68% | X and Y Support: 64% | Confidence: 86% | ['Pstatus: T', 'school: GP', 'higher: yes'] -> ['internet: yes']
    X Support: 71.65% | X and Y Support: 64% | Confidence: 89% | ['Pstatus: T', 'internet: yes', 'higher: yes'] -> ['school: GP']
    X Support: 87.09% | X and Y Support: 61% | Confidence: 70% | ['schoolsup: no'] -> ['higher: yes', 'internet: yes', 'school: GP']
    X Support: 88.35% | X and Y Support: 61% | Confidence: 69% | ['school: GP'] -> ['higher: yes', 'internet: yes', 'schoolsup: no']
    X Support: 83.29% | X and Y Support: 61% | Confidence: 74% | ['internet: yes'] -> ['higher: yes', 'school: GP', 'schoolsup: no']
    X Support: 94.94% | X and Y Support: 61% | Confidence: 65% | ['higher: yes'] -> ['internet: yes', 'school: GP', 'schoolsup: no']
    X Support: 75.44% | X and Y Support: 61% | Confidence: 81% | ['school: GP', 'schoolsup: no'] -> ['higher: yes', 'internet: yes']
    X Support: 72.66% | X and Y Support: 61% | Confidence: 84% | ['internet: yes', 'schoolsup: no'] -> ['higher: yes', 'school: GP']
    X Support: 75.19% | X and Y Support: 61% | Confidence: 81% | ['internet: yes', 'school: GP'] -> ['higher: yes', 'schoolsup: no']
    X Support: 82.28% | X and Y Support: 61% | Confidence: 74% | ['higher: yes', 'schoolsup: no'] -> ['internet: yes', 'school: GP']
    X Support: 84.05% | X and Y Support: 61% | Confidence: 73% | ['school: GP', 'higher: yes'] -> ['internet: yes', 'schoolsup: no']
    X Support: 79.24% | X and Y Support: 61% | Confidence: 77% | ['internet: yes', 'higher: yes'] -> ['school: GP', 'schoolsup: no']
    X Support: 64.56% | X and Y Support: 61% | Confidence: 95% | ['internet: yes', 'school: GP', 'schoolsup: no'] -> ['higher: yes']
    X Support: 71.39% | X and Y Support: 61% | Confidence: 86% | ['higher: yes', 'school: GP', 'schoolsup: no'] -> ['internet: yes']
    X Support: 68.86% | X and Y Support: 61% | Confidence: 89% | ['higher: yes', 'internet: yes', 'schoolsup: no'] -> ['school: GP']
    X Support: 71.65% | X and Y Support: 61% | Confidence: 86% | ['internet: yes', 'school: GP', 'higher: yes'] -> ['schoolsup: no']
    X Support: 87.09% | X and Y Support: 68% | Confidence: 78% | ['schoolsup: no'] -> ['Pstatus: T', 'school: GP']
    X Support: 88.35% | X and Y Support: 68% | Confidence: 77% | ['school: GP'] -> ['Pstatus: T', 'schoolsup: no']
    X Support: 89.62% | X and Y Support: 68% | Confidence: 75% | ['Pstatus: T'] -> ['school: GP', 'schoolsup: no']
    X Support: 75.44% | X and Y Support: 68% | Confidence: 90% | ['school: GP', 'schoolsup: no'] -> ['Pstatus: T']
    X Support: 78.48% | X and Y Support: 68% | Confidence: 86% | ['Pstatus: T', 'schoolsup: no'] -> ['school: GP']
    X Support: 78.73% | X and Y Support: 68% | Confidence: 86% | ['Pstatus: T', 'school: GP'] -> ['schoolsup: no']
    X Support: 88.35% | X and Y Support: 67% | Confidence: 76% | ['school: GP'] -> ['Pstatus: T', 'internet: yes']
    X Support: 83.29% | X and Y Support: 67% | Confidence: 81% | ['internet: yes'] -> ['Pstatus: T', 'school: GP']
    X Support: 89.62% | X and Y Support: 67% | Confidence: 75% | ['Pstatus: T'] -> ['internet: yes', 'school: GP']
    X Support: 75.19% | X and Y Support: 67% | Confidence: 90% | ['internet: yes', 'school: GP'] -> ['Pstatus: T']
    X Support: 78.73% | X and Y Support: 67% | Confidence: 86% | ['Pstatus: T', 'school: GP'] -> ['internet: yes']
    X Support: 75.44% | X and Y Support: 67% | Confidence: 89% | ['Pstatus: T', 'internet: yes'] -> ['school: GP']
    X Support: 87.09% | X and Y Support: 65% | Confidence: 74% | ['schoolsup: no'] -> ['internet: yes', 'school: GP']
    X Support: 88.35% | X and Y Support: 65% | Confidence: 73% | ['school: GP'] -> ['internet: yes', 'schoolsup: no']
    X Support: 83.29% | X and Y Support: 65% | Confidence: 78% | ['internet: yes'] -> ['school: GP', 'schoolsup: no']
    X Support: 75.44% | X and Y Support: 65% | Confidence: 86% | ['school: GP', 'schoolsup: no'] -> ['internet: yes']
    X Support: 72.66% | X and Y Support: 65% | Confidence: 89% | ['internet: yes', 'schoolsup: no'] -> ['school: GP']
    X Support: 75.19% | X and Y Support: 65% | Confidence: 86% | ['internet: yes', 'school: GP'] -> ['schoolsup: no']
    X Support: 94.94% | X and Y Support: 62% | Confidence: 65% | ['higher: yes'] -> ['address: U', 'failures: 0']
    X Support: 78.99% | X and Y Support: 62% | Confidence: 79% | ['failures: 0'] -> ['address: U', 'higher: yes']
    X Support: 77.72% | X and Y Support: 62% | Confidence: 80% | ['address: U'] -> ['failures: 0', 'higher: yes']
    X Support: 77.22% | X and Y Support: 62% | Confidence: 80% | ['failures: 0', 'higher: yes'] -> ['address: U']
    X Support: 74.18% | X and Y Support: 62% | Confidence: 84% | ['address: U', 'higher: yes'] -> ['failures: 0']
    X Support: 63.04% | X and Y Support: 62% | Confidence: 98% | ['failures: 0', 'address: U'] -> ['higher: yes']
    X Support: 79.49% | X and Y Support: 60% | Confidence: 76% | ['nursery: yes'] -> ['address: U', 'higher: yes']
    X Support: 94.94% | X and Y Support: 60% | Confidence: 63% | ['higher: yes'] -> ['address: U', 'nursery: yes']
    X Support: 77.72% | X and Y Support: 60% | Confidence: 78% | ['address: U'] -> ['higher: yes', 'nursery: yes']
    X Support: 75.95% | X and Y Support: 60% | Confidence: 79% | ['nursery: yes', 'higher: yes'] -> ['address: U']
    X Support: 62.78% | X and Y Support: 60% | Confidence: 96% | ['nursery: yes', 'address: U'] -> ['higher: yes']
    X Support: 74.18% | X and Y Support: 60% | Confidence: 81% | ['address: U', 'higher: yes'] -> ['nursery: yes']
    X Support: 94.94% | X and Y Support: 66% | Confidence: 69% | ['higher: yes'] -> ['Pstatus: T', 'address: U']
    X Support: 77.72% | X and Y Support: 66% | Confidence: 84% | ['address: U'] -> ['Pstatus: T', 'higher: yes']
    X Support: 89.62% | X and Y Support: 66% | Confidence: 73% | ['Pstatus: T'] -> ['address: U', 'higher: yes']
    X Support: 74.18% | X and Y Support: 66% | Confidence: 88% | ['address: U', 'higher: yes'] -> ['Pstatus: T']
    X Support: 84.81% | X and Y Support: 66% | Confidence: 77% | ['Pstatus: T', 'higher: yes'] -> ['address: U']
    X Support: 69.11% | X and Y Support: 66% | Confidence: 95% | ['Pstatus: T', 'address: U'] -> ['higher: yes']
    X Support: 87.09% | X and Y Support: 64% | Confidence: 74% | ['schoolsup: no'] -> ['address: U', 'higher: yes']
    X Support: 94.94% | X and Y Support: 64% | Confidence: 67% | ['higher: yes'] -> ['address: U', 'schoolsup: no']
    X Support: 77.72% | X and Y Support: 64% | Confidence: 82% | ['address: U'] -> ['higher: yes', 'schoolsup: no']
    X Support: 82.28% | X and Y Support: 64% | Confidence: 78% | ['schoolsup: no', 'higher: yes'] -> ['address: U']
    X Support: 67.34% | X and Y Support: 64% | Confidence: 95% | ['address: U', 'schoolsup: no'] -> ['higher: yes']
    X Support: 74.18% | X and Y Support: 64% | Confidence: 86% | ['address: U', 'higher: yes'] -> ['schoolsup: no']
    X Support: 83.29% | X and Y Support: 65% | Confidence: 78% | ['internet: yes'] -> ['address: U', 'higher: yes']
    X Support: 94.94% | X and Y Support: 65% | Confidence: 69% | ['higher: yes'] -> ['address: U', 'internet: yes']
    X Support: 77.72% | X and Y Support: 65% | Confidence: 84% | ['address: U'] -> ['higher: yes', 'internet: yes']
    X Support: 79.24% | X and Y Support: 65% | Confidence: 82% | ['internet: yes', 'higher: yes'] -> ['address: U']
    X Support: 68.1% | X and Y Support: 65% | Confidence: 96% | ['internet: yes', 'address: U'] -> ['higher: yes']
    X Support: 74.18% | X and Y Support: 65% | Confidence: 88% | ['address: U', 'higher: yes'] -> ['internet: yes']
    X Support: 87.09% | X and Y Support: 61% | Confidence: 69% | ['schoolsup: no'] -> ['Pstatus: T', 'address: U']
    X Support: 77.72% | X and Y Support: 61% | Confidence: 78% | ['address: U'] -> ['Pstatus: T', 'schoolsup: no']
    X Support: 89.62% | X and Y Support: 61% | Confidence: 68% | ['Pstatus: T'] -> ['address: U', 'schoolsup: no']
    X Support: 67.34% | X and Y Support: 61% | Confidence: 90% | ['address: U', 'schoolsup: no'] -> ['Pstatus: T']
    X Support: 78.48% | X and Y Support: 61% | Confidence: 77% | ['Pstatus: T', 'schoolsup: no'] -> ['address: U']
    X Support: 69.11% | X and Y Support: 61% | Confidence: 88% | ['Pstatus: T', 'address: U'] -> ['schoolsup: no']
    X Support: 83.29% | X and Y Support: 62% | Confidence: 74% | ['internet: yes'] -> ['Pstatus: T', 'address: U']
    X Support: 77.72% | X and Y Support: 62% | Confidence: 79% | ['address: U'] -> ['Pstatus: T', 'internet: yes']
    X Support: 89.62% | X and Y Support: 62% | Confidence: 69% | ['Pstatus: T'] -> ['address: U', 'internet: yes']
    X Support: 68.1% | X and Y Support: 62% | Confidence: 90% | ['internet: yes', 'address: U'] -> ['Pstatus: T']
    X Support: 75.44% | X and Y Support: 62% | Confidence: 82% | ['Pstatus: T', 'internet: yes'] -> ['address: U']
    X Support: 69.11% | X and Y Support: 62% | Confidence: 89% | ['Pstatus: T', 'address: U'] -> ['internet: yes']
    X Support: 94.94% | X and Y Support: 63% | Confidence: 66% | ['higher: yes'] -> ['Pstatus: T', 'famsize: GT3']
    X Support: 71.14% | X and Y Support: 63% | Confidence: 88% | ['famsize: GT3'] -> ['Pstatus: T', 'higher: yes']
    X Support: 89.62% | X and Y Support: 63% | Confidence: 70% | ['Pstatus: T'] -> ['famsize: GT3', 'higher: yes']
    X Support: 67.59% | X and Y Support: 63% | Confidence: 93% | ['famsize: GT3', 'higher: yes'] -> ['Pstatus: T']
    X Support: 84.81% | X and Y Support: 63% | Confidence: 74% | ['Pstatus: T', 'higher: yes'] -> ['famsize: GT3']
    X Support: 65.82% | X and Y Support: 63% | Confidence: 95% | ['Pstatus: T', 'famsize: GT3'] -> ['higher: yes']
    X Support: 79.49% | X and Y Support: 63% | Confidence: 79% | ['nursery: yes'] -> ['failures: 0', 'higher: yes']
    X Support: 94.94% | X and Y Support: 63% | Confidence: 66% | ['higher: yes'] -> ['failures: 0', 'nursery: yes']
    X Support: 78.99% | X and Y Support: 63% | Confidence: 79% | ['failures: 0'] -> ['higher: yes', 'nursery: yes']
    X Support: 75.95% | X and Y Support: 63% | Confidence: 83% | ['nursery: yes', 'higher: yes'] -> ['failures: 0']
    X Support: 64.3% | X and Y Support: 63% | Confidence: 98% | ['failures: 0', 'nursery: yes'] -> ['higher: yes']
    X Support: 77.22% | X and Y Support: 63% | Confidence: 81% | ['failures: 0', 'higher: yes'] -> ['nursery: yes']
    X Support: 94.94% | X and Y Support: 69% | Confidence: 73% | ['higher: yes'] -> ['Pstatus: T', 'failures: 0']
    X Support: 78.99% | X and Y Support: 69% | Confidence: 88% | ['failures: 0'] -> ['Pstatus: T', 'higher: yes']
    X Support: 89.62% | X and Y Support: 69% | Confidence: 77% | ['Pstatus: T'] -> ['failures: 0', 'higher: yes']
    X Support: 77.22% | X and Y Support: 69% | Confidence: 90% | ['failures: 0', 'higher: yes'] -> ['Pstatus: T']
    X Support: 84.81% | X and Y Support: 69% | Confidence: 81% | ['Pstatus: T', 'higher: yes'] -> ['failures: 0']
    X Support: 70.63% | X and Y Support: 69% | Confidence: 98% | ['Pstatus: T', 'failures: 0'] -> ['higher: yes']
    X Support: 87.09% | X and Y Support: 67% | Confidence: 77% | ['schoolsup: no'] -> ['failures: 0', 'higher: yes']
    X Support: 94.94% | X and Y Support: 67% | Confidence: 71% | ['higher: yes'] -> ['failures: 0', 'schoolsup: no']
    X Support: 78.99% | X and Y Support: 67% | Confidence: 85% | ['failures: 0'] -> ['higher: yes', 'schoolsup: no']
    X Support: 82.28% | X and Y Support: 67% | Confidence: 82% | ['higher: yes', 'schoolsup: no'] -> ['failures: 0']
    X Support: 68.86% | X and Y Support: 67% | Confidence: 97% | ['failures: 0', 'schoolsup: no'] -> ['higher: yes']
    X Support: 77.22% | X and Y Support: 67% | Confidence: 87% | ['failures: 0', 'higher: yes'] -> ['schoolsup: no']
    X Support: 83.29% | X and Y Support: 65% | Confidence: 78% | ['internet: yes'] -> ['failures: 0', 'higher: yes']
    X Support: 94.94% | X and Y Support: 65% | Confidence: 69% | ['higher: yes'] -> ['failures: 0', 'internet: yes']
    X Support: 78.99% | X and Y Support: 65% | Confidence: 83% | ['failures: 0'] -> ['higher: yes', 'internet: yes']
    X Support: 79.24% | X and Y Support: 65% | Confidence: 82% | ['internet: yes', 'higher: yes'] -> ['failures: 0']
    X Support: 67.09% | X and Y Support: 65% | Confidence: 97% | ['failures: 0', 'internet: yes'] -> ['higher: yes']
    X Support: 77.22% | X and Y Support: 65% | Confidence: 85% | ['failures: 0', 'higher: yes'] -> ['internet: yes']
    X Support: 87.09% | X and Y Support: 61% | Confidence: 70% | ['schoolsup: no'] -> ['Pstatus: T', 'failures: 0', 'higher: yes']
    X Support: 94.94% | X and Y Support: 61% | Confidence: 64% | ['higher: yes'] -> ['Pstatus: T', 'failures: 0', 'schoolsup: no']
    X Support: 78.99% | X and Y Support: 61% | Confidence: 77% | ['failures: 0'] -> ['Pstatus: T', 'higher: yes', 'schoolsup: no']
    X Support: 89.62% | X and Y Support: 61% | Confidence: 68% | ['Pstatus: T'] -> ['failures: 0', 'higher: yes', 'schoolsup: no']
    X Support: 82.28% | X and Y Support: 61% | Confidence: 74% | ['higher: yes', 'schoolsup: no'] -> ['Pstatus: T', 'failures: 0']
    X Support: 68.86% | X and Y Support: 61% | Confidence: 88% | ['failures: 0', 'schoolsup: no'] -> ['Pstatus: T', 'higher: yes']
    X Support: 77.22% | X and Y Support: 61% | Confidence: 79% | ['failures: 0', 'higher: yes'] -> ['Pstatus: T', 'schoolsup: no']
    X Support: 78.48% | X and Y Support: 61% | Confidence: 77% | ['Pstatus: T', 'schoolsup: no'] -> ['failures: 0', 'higher: yes']
    X Support: 84.81% | X and Y Support: 61% | Confidence: 72% | ['Pstatus: T', 'higher: yes'] -> ['failures: 0', 'schoolsup: no']
    X Support: 70.63% | X and Y Support: 61% | Confidence: 86% | ['Pstatus: T', 'failures: 0'] -> ['higher: yes', 'schoolsup: no']
    X Support: 67.09% | X and Y Support: 61% | Confidence: 91% | ['higher: yes', 'failures: 0', 'schoolsup: no'] -> ['Pstatus: T']
    X Support: 73.92% | X and Y Support: 61% | Confidence: 82% | ['higher: yes', 'Pstatus: T', 'schoolsup: no'] -> ['failures: 0']
    X Support: 62.28% | X and Y Support: 61% | Confidence: 98% | ['Pstatus: T', 'failures: 0', 'schoolsup: no'] -> ['higher: yes']
    X Support: 69.11% | X and Y Support: 61% | Confidence: 88% | ['Pstatus: T', 'failures: 0', 'higher: yes'] -> ['schoolsup: no']
    X Support: 87.09% | X and Y Support: 62% | Confidence: 72% | ['schoolsup: no'] -> ['Pstatus: T', 'failures: 0']
    X Support: 78.99% | X and Y Support: 62% | Confidence: 79% | ['failures: 0'] -> ['Pstatus: T', 'schoolsup: no']
    X Support: 89.62% | X and Y Support: 62% | Confidence: 69% | ['Pstatus: T'] -> ['failures: 0', 'schoolsup: no']
    X Support: 68.86% | X and Y Support: 62% | Confidence: 90% | ['failures: 0', 'schoolsup: no'] -> ['Pstatus: T']
    X Support: 78.48% | X and Y Support: 62% | Confidence: 79% | ['Pstatus: T', 'schoolsup: no'] -> ['failures: 0']
    X Support: 70.63% | X and Y Support: 62% | Confidence: 88% | ['Pstatus: T', 'failures: 0'] -> ['schoolsup: no']
    X Support: 83.29% | X and Y Support: 61% | Confidence: 73% | ['internet: yes'] -> ['Pstatus: T', 'failures: 0']
    X Support: 78.99% | X and Y Support: 61% | Confidence: 77% | ['failures: 0'] -> ['Pstatus: T', 'internet: yes']
    X Support: 89.62% | X and Y Support: 61% | Confidence: 68% | ['Pstatus: T'] -> ['failures: 0', 'internet: yes']
    X Support: 67.09% | X and Y Support: 61% | Confidence: 90% | ['failures: 0', 'internet: yes'] -> ['Pstatus: T']
    X Support: 75.44% | X and Y Support: 61% | Confidence: 80% | ['Pstatus: T', 'internet: yes'] -> ['failures: 0']
    X Support: 70.63% | X and Y Support: 61% | Confidence: 86% | ['Pstatus: T', 'failures: 0'] -> ['internet: yes']
    X Support: 79.49% | X and Y Support: 67% | Confidence: 84% | ['nursery: yes'] -> ['Pstatus: T', 'higher: yes']
    X Support: 94.94% | X and Y Support: 67% | Confidence: 70% | ['higher: yes'] -> ['Pstatus: T', 'nursery: yes']
    X Support: 89.62% | X and Y Support: 67% | Confidence: 74% | ['Pstatus: T'] -> ['higher: yes', 'nursery: yes']
    X Support: 75.95% | X and Y Support: 67% | Confidence: 88% | ['nursery: yes', 'higher: yes'] -> ['Pstatus: T']
    X Support: 70.13% | X and Y Support: 67% | Confidence: 95% | ['Pstatus: T', 'nursery: yes'] -> ['higher: yes']
    X Support: 84.81% | X and Y Support: 67% | Confidence: 79% | ['Pstatus: T', 'higher: yes'] -> ['nursery: yes']
    X Support: 87.09% | X and Y Support: 65% | Confidence: 75% | ['schoolsup: no'] -> ['higher: yes', 'nursery: yes']
    X Support: 79.49% | X and Y Support: 65% | Confidence: 82% | ['nursery: yes'] -> ['higher: yes', 'schoolsup: no']
    X Support: 94.94% | X and Y Support: 65% | Confidence: 69% | ['higher: yes'] -> ['nursery: yes', 'schoolsup: no']
    X Support: 68.61% | X and Y Support: 65% | Confidence: 95% | ['nursery: yes', 'schoolsup: no'] -> ['higher: yes']
    X Support: 82.28% | X and Y Support: 65% | Confidence: 79% | ['higher: yes', 'schoolsup: no'] -> ['nursery: yes']
    X Support: 75.95% | X and Y Support: 65% | Confidence: 86% | ['nursery: yes', 'higher: yes'] -> ['schoolsup: no']
    X Support: 79.49% | X and Y Support: 63% | Confidence: 79% | ['nursery: yes'] -> ['higher: yes', 'internet: yes']
    X Support: 83.29% | X and Y Support: 63% | Confidence: 76% | ['internet: yes'] -> ['higher: yes', 'nursery: yes']
    X Support: 94.94% | X and Y Support: 63% | Confidence: 66% | ['higher: yes'] -> ['internet: yes', 'nursery: yes']
    X Support: 66.33% | X and Y Support: 63% | Confidence: 95% | ['internet: yes', 'nursery: yes'] -> ['higher: yes']
    X Support: 75.95% | X and Y Support: 63% | Confidence: 83% | ['nursery: yes', 'higher: yes'] -> ['internet: yes']
    X Support: 79.24% | X and Y Support: 63% | Confidence: 80% | ['internet: yes', 'higher: yes'] -> ['nursery: yes']
    X Support: 87.09% | X and Y Support: 61% | Confidence: 70% | ['schoolsup: no'] -> ['Pstatus: T', 'nursery: yes']
    X Support: 79.49% | X and Y Support: 61% | Confidence: 77% | ['nursery: yes'] -> ['Pstatus: T', 'schoolsup: no']
    X Support: 89.62% | X and Y Support: 61% | Confidence: 68% | ['Pstatus: T'] -> ['nursery: yes', 'schoolsup: no']
    X Support: 68.61% | X and Y Support: 61% | Confidence: 89% | ['nursery: yes', 'schoolsup: no'] -> ['Pstatus: T']
    X Support: 78.48% | X and Y Support: 61% | Confidence: 78% | ['Pstatus: T', 'schoolsup: no'] -> ['nursery: yes']
    X Support: 70.13% | X and Y Support: 61% | Confidence: 87% | ['Pstatus: T', 'nursery: yes'] -> ['schoolsup: no']
    X Support: 94.94% | X and Y Support: 61% | Confidence: 64% | ['higher: yes'] -> ['Dalc: 1', 'Pstatus: T']
    X Support: 89.62% | X and Y Support: 61% | Confidence: 68% | ['Pstatus: T'] -> ['Dalc: 1', 'higher: yes']
    X Support: 69.87% | X and Y Support: 61% | Confidence: 87% | ['Dalc: 1'] -> ['Pstatus: T', 'higher: yes']
    X Support: 84.81% | X and Y Support: 61% | Confidence: 71% | ['Pstatus: T', 'higher: yes'] -> ['Dalc: 1']
    X Support: 67.59% | X and Y Support: 61% | Confidence: 90% | ['Dalc: 1', 'higher: yes'] -> ['Pstatus: T']
    X Support: 62.53% | X and Y Support: 61% | Confidence: 97% | ['Pstatus: T', 'Dalc: 1'] -> ['higher: yes']
    X Support: 87.09% | X and Y Support: 74% | Confidence: 85% | ['schoolsup: no'] -> ['Pstatus: T', 'higher: yes']
    X Support: 94.94% | X and Y Support: 74% | Confidence: 78% | ['higher: yes'] -> ['Pstatus: T', 'schoolsup: no']
    X Support: 89.62% | X and Y Support: 74% | Confidence: 82% | ['Pstatus: T'] -> ['higher: yes', 'schoolsup: no']
    X Support: 82.28% | X and Y Support: 74% | Confidence: 90% | ['higher: yes', 'schoolsup: no'] -> ['Pstatus: T']
    X Support: 78.48% | X and Y Support: 74% | Confidence: 94% | ['Pstatus: T', 'schoolsup: no'] -> ['higher: yes']
    X Support: 84.81% | X and Y Support: 74% | Confidence: 87% | ['Pstatus: T', 'higher: yes'] -> ['schoolsup: no']
    X Support: 83.29% | X and Y Support: 72% | Confidence: 86% | ['internet: yes'] -> ['Pstatus: T', 'higher: yes']
    X Support: 94.94% | X and Y Support: 72% | Confidence: 75% | ['higher: yes'] -> ['Pstatus: T', 'internet: yes']
    X Support: 89.62% | X and Y Support: 72% | Confidence: 80% | ['Pstatus: T'] -> ['higher: yes', 'internet: yes']
    X Support: 79.24% | X and Y Support: 72% | Confidence: 90% | ['internet: yes', 'higher: yes'] -> ['Pstatus: T']
    X Support: 75.44% | X and Y Support: 72% | Confidence: 95% | ['Pstatus: T', 'internet: yes'] -> ['higher: yes']
    X Support: 84.81% | X and Y Support: 72% | Confidence: 84% | ['Pstatus: T', 'higher: yes'] -> ['internet: yes']
    X Support: 87.09% | X and Y Support: 62% | Confidence: 72% | ['schoolsup: no'] -> ['Pstatus: T', 'higher: yes', 'internet: yes']
    X Support: 83.29% | X and Y Support: 62% | Confidence: 75% | ['internet: yes'] -> ['Pstatus: T', 'higher: yes', 'schoolsup: no']
    X Support: 94.94% | X and Y Support: 62% | Confidence: 66% | ['higher: yes'] -> ['Pstatus: T', 'internet: yes', 'schoolsup: no']
    X Support: 89.62% | X and Y Support: 62% | Confidence: 69% | ['Pstatus: T'] -> ['higher: yes', 'internet: yes', 'schoolsup: no']
    X Support: 72.66% | X and Y Support: 62% | Confidence: 86% | ['internet: yes', 'schoolsup: no'] -> ['Pstatus: T', 'higher: yes']
    X Support: 82.28% | X and Y Support: 62% | Confidence: 76% | ['higher: yes', 'schoolsup: no'] -> ['Pstatus: T', 'internet: yes']
    X Support: 79.24% | X and Y Support: 62% | Confidence: 79% | ['internet: yes', 'higher: yes'] -> ['Pstatus: T', 'schoolsup: no']
    X Support: 78.48% | X and Y Support: 62% | Confidence: 79% | ['Pstatus: T', 'schoolsup: no'] -> ['higher: yes', 'internet: yes']
    X Support: 75.44% | X and Y Support: 62% | Confidence: 83% | ['Pstatus: T', 'internet: yes'] -> ['higher: yes', 'schoolsup: no']
    X Support: 84.81% | X and Y Support: 62% | Confidence: 73% | ['Pstatus: T', 'higher: yes'] -> ['internet: yes', 'schoolsup: no']
    X Support: 68.86% | X and Y Support: 62% | Confidence: 90% | ['higher: yes', 'internet: yes', 'schoolsup: no'] -> ['Pstatus: T']
    X Support: 65.82% | X and Y Support: 62% | Confidence: 95% | ['Pstatus: T', 'internet: yes', 'schoolsup: no'] -> ['higher: yes']
    X Support: 73.92% | X and Y Support: 62% | Confidence: 84% | ['higher: yes', 'Pstatus: T', 'schoolsup: no'] -> ['internet: yes']
    X Support: 71.65% | X and Y Support: 62% | Confidence: 87% | ['Pstatus: T', 'internet: yes', 'higher: yes'] -> ['schoolsup: no']
    X Support: 87.09% | X and Y Support: 69% | Confidence: 79% | ['schoolsup: no'] -> ['higher: yes', 'internet: yes']
    X Support: 83.29% | X and Y Support: 69% | Confidence: 83% | ['internet: yes'] -> ['higher: yes', 'schoolsup: no']
    X Support: 94.94% | X and Y Support: 69% | Confidence: 73% | ['higher: yes'] -> ['internet: yes', 'schoolsup: no']
    X Support: 72.66% | X and Y Support: 69% | Confidence: 95% | ['internet: yes', 'schoolsup: no'] -> ['higher: yes']
    X Support: 82.28% | X and Y Support: 69% | Confidence: 84% | ['higher: yes', 'schoolsup: no'] -> ['internet: yes']
    X Support: 79.24% | X and Y Support: 69% | Confidence: 87% | ['internet: yes', 'higher: yes'] -> ['schoolsup: no']
    X Support: 87.09% | X and Y Support: 66% | Confidence: 76% | ['schoolsup: no'] -> ['Pstatus: T', 'internet: yes']
    X Support: 83.29% | X and Y Support: 66% | Confidence: 79% | ['internet: yes'] -> ['Pstatus: T', 'schoolsup: no']
    X Support: 89.62% | X and Y Support: 66% | Confidence: 73% | ['Pstatus: T'] -> ['internet: yes', 'schoolsup: no']
    X Support: 72.66% | X and Y Support: 66% | Confidence: 91% | ['internet: yes', 'schoolsup: no'] -> ['Pstatus: T']
    X Support: 78.48% | X and Y Support: 66% | Confidence: 84% | ['Pstatus: T', 'schoolsup: no'] -> ['internet: yes']
    X Support: 75.44% | X and Y Support: 66% | Confidence: 87% | ['Pstatus: T', 'internet: yes'] -> ['schoolsup: no']
    X Support: 88.35% | X and Y Support: 61% | Confidence: 69% | ['school: GP'] -> ['Pstatus: T', 'address: U', 'higher: yes']
    X Support: 94.94% | X and Y Support: 61% | Confidence: 64% | ['higher: yes'] -> ['Pstatus: T', 'address: U', 'school: GP']
    X Support: 77.72% | X and Y Support: 61% | Confidence: 79% | ['address: U'] -> ['Pstatus: T', 'higher: yes', 'school: GP']
    X Support: 89.62% | X and Y Support: 61% | Confidence: 68% | ['Pstatus: T'] -> ['address: U', 'higher: yes', 'school: GP']
    X Support: 84.05% | X and Y Support: 61% | Confidence: 73% | ['school: GP', 'higher: yes'] -> ['Pstatus: T', 'address: U']
    X Support: 72.41% | X and Y Support: 61% | Confidence: 84% | ['school: GP', 'address: U'] -> ['Pstatus: T', 'higher: yes']
    X Support: 74.18% | X and Y Support: 61% | Confidence: 82% | ['address: U', 'higher: yes'] -> ['Pstatus: T', 'school: GP']
    X Support: 78.73% | X and Y Support: 61% | Confidence: 77% | ['Pstatus: T', 'school: GP'] -> ['address: U', 'higher: yes']
    X Support: 84.81% | X and Y Support: 61% | Confidence: 72% | ['Pstatus: T', 'higher: yes'] -> ['address: U', 'school: GP']
    X Support: 69.11% | X and Y Support: 61% | Confidence: 88% | ['Pstatus: T', 'address: U'] -> ['higher: yes', 'school: GP']
    X Support: 69.11% | X and Y Support: 61% | Confidence: 88% | ['address: U', 'school: GP', 'higher: yes'] -> ['Pstatus: T']
    X Support: 74.68% | X and Y Support: 61% | Confidence: 82% | ['Pstatus: T', 'school: GP', 'higher: yes'] -> ['address: U']
    X Support: 64.3% | X and Y Support: 61% | Confidence: 95% | ['Pstatus: T', 'school: GP', 'address: U'] -> ['higher: yes']
    X Support: 65.57% | X and Y Support: 61% | Confidence: 93% | ['address: U', 'Pstatus: T', 'higher: yes'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 62% | Confidence: 70% | ['school: GP'] -> ['address: U', 'higher: yes', 'internet: yes']
    X Support: 83.29% | X and Y Support: 62% | Confidence: 74% | ['internet: yes'] -> ['address: U', 'higher: yes', 'school: GP']
    X Support: 94.94% | X and Y Support: 62% | Confidence: 65% | ['higher: yes'] -> ['address: U', 'internet: yes', 'school: GP']
    X Support: 77.72% | X and Y Support: 62% | Confidence: 79% | ['address: U'] -> ['higher: yes', 'internet: yes', 'school: GP']
    X Support: 75.19% | X and Y Support: 62% | Confidence: 82% | ['internet: yes', 'school: GP'] -> ['address: U', 'higher: yes']
    X Support: 84.05% | X and Y Support: 62% | Confidence: 73% | ['school: GP', 'higher: yes'] -> ['address: U', 'internet: yes']
    X Support: 79.24% | X and Y Support: 62% | Confidence: 78% | ['internet: yes', 'higher: yes'] -> ['address: U', 'school: GP']
    X Support: 72.41% | X and Y Support: 62% | Confidence: 85% | ['school: GP', 'address: U'] -> ['higher: yes', 'internet: yes']
    X Support: 68.1% | X and Y Support: 62% | Confidence: 90% | ['internet: yes', 'address: U'] -> ['higher: yes', 'school: GP']
    X Support: 74.18% | X and Y Support: 62% | Confidence: 83% | ['address: U', 'higher: yes'] -> ['internet: yes', 'school: GP']
    X Support: 71.65% | X and Y Support: 62% | Confidence: 86% | ['internet: yes', 'school: GP', 'higher: yes'] -> ['address: U']
    X Support: 64.3% | X and Y Support: 62% | Confidence: 96% | ['internet: yes', 'school: GP', 'address: U'] -> ['higher: yes']
    X Support: 69.11% | X and Y Support: 62% | Confidence: 89% | ['address: U', 'school: GP', 'higher: yes'] -> ['internet: yes']
    X Support: 65.06% | X and Y Support: 62% | Confidence: 95% | ['address: U', 'internet: yes', 'higher: yes'] -> ['school: GP']
    X Support: 88.35% | X and Y Support: 62% | Confidence: 70% | ['school: GP'] -> ['Pstatus: T', 'failures: 0', 'higher: yes']
    X Support: 94.94% | X and Y Support: 62% | Confidence: 65% | ['higher: yes'] -> ['Pstatus: T', 'failures: 0', 'school: GP']
    X Support: 78.99% | X and Y Support: 62% | Confidence: 78% | ['failures: 0'] -> ['Pstatus: T', 'higher: yes', 'school: GP']
    X Support: 89.62% | X and Y Support: 62% | Confidence: 69% | ['Pstatus: T'] -> ['failures: 0', 'higher: yes', 'school: GP']
    X Support: 84.05% | X and Y Support: 62% | Confidence: 73% | ['school: GP', 'higher: yes'] -> ['Pstatus: T', 'failures: 0']
    X Support: 70.89% | X and Y Support: 62% | Confidence: 87% | ['failures: 0', 'school: GP'] -> ['Pstatus: T', 'higher: yes']
    X Support: 77.22% | X and Y Support: 62% | Confidence: 80% | ['failures: 0', 'higher: yes'] -> ['Pstatus: T', 'school: GP']
    X Support: 78.73% | X and Y Support: 62% | Confidence: 78% | ['Pstatus: T', 'school: GP'] -> ['failures: 0', 'higher: yes']
    X Support: 84.81% | X and Y Support: 62% | Confidence: 73% | ['Pstatus: T', 'higher: yes'] -> ['failures: 0', 'school: GP']
    X Support: 70.63% | X and Y Support: 62% | Confidence: 87% | ['Pstatus: T', 'failures: 0'] -> ['higher: yes', 'school: GP']
    X Support: 69.37% | X and Y Support: 62% | Confidence: 89% | ['failures: 0', 'school: GP', 'higher: yes'] -> ['Pstatus: T']
    X Support: 74.68% | X and Y Support: 62% | Confidence: 83% | ['Pstatus: T', 'school: GP', 'higher: yes'] -> ['failures: 0']
    X Support: 63.04% | X and Y Support: 62% | Confidence: 98% | ['Pstatus: T', 'failures: 0', 'school: GP'] -> ['higher: yes']
    X Support: 69.11% | X and Y Support: 62% | Confidence: 89% | ['Pstatus: T', 'failures: 0', 'higher: yes'] -> ['school: GP']
    X Support: 87.09% | X and Y Support: 64% | Confidence: 73% | ['schoolsup: no'] -> ['Pstatus: T', 'higher: yes', 'school: GP']
    X Support: 88.35% | X and Y Support: 64% | Confidence: 72% | ['school: GP'] -> ['Pstatus: T', 'higher: yes', 'schoolsup: no']
    X Support: 94.94% | X and Y Support: 64% | Confidence: 67% | ['higher: yes'] -> ['Pstatus: T', 'school: GP', 'schoolsup: no']
    X Support: 89.62% | X and Y Support: 64% | Confidence: 71% | ['Pstatus: T'] -> ['higher: yes', 'school: GP', 'schoolsup: no']
    X Support: 75.44% | X and Y Support: 64% | Confidence: 85% | ['school: GP', 'schoolsup: no'] -> ['Pstatus: T', 'higher: yes']
    X Support: 82.28% | X and Y Support: 64% | Confidence: 78% | ['higher: yes', 'schoolsup: no'] -> ['Pstatus: T', 'school: GP']
    X Support: 84.05% | X and Y Support: 64% | Confidence: 76% | ['school: GP', 'higher: yes'] -> ['Pstatus: T', 'schoolsup: no']
    X Support: 78.48% | X and Y Support: 64% | Confidence: 81% | ['Pstatus: T', 'schoolsup: no'] -> ['higher: yes', 'school: GP']
    X Support: 78.73% | X and Y Support: 64% | Confidence: 81% | ['Pstatus: T', 'school: GP'] -> ['higher: yes', 'schoolsup: no']
    X Support: 84.81% | X and Y Support: 64% | Confidence: 75% | ['Pstatus: T', 'higher: yes'] -> ['school: GP', 'schoolsup: no']
    X Support: 71.39% | X and Y Support: 64% | Confidence: 89% | ['higher: yes', 'school: GP', 'schoolsup: no'] -> ['Pstatus: T']
    X Support: 67.59% | X and Y Support: 64% | Confidence: 94% | ['Pstatus: T', 'school: GP', 'schoolsup: no'] -> ['higher: yes']
    X Support: 73.92% | X and Y Support: 64% | Confidence: 86% | ['higher: yes', 'Pstatus: T', 'schoolsup: no'] -> ['school: GP']
    X Support: 74.68% | X and Y Support: 64% | Confidence: 85% | ['Pstatus: T', 'school: GP', 'higher: yes'] -> ['schoolsup: no']
    X Support: 88.35% | X and Y Support: 64% | Confidence: 72% | ['school: GP'] -> ['Pstatus: T', 'higher: yes', 'internet: yes']
    X Support: 83.29% | X and Y Support: 64% | Confidence: 77% | ['internet: yes'] -> ['Pstatus: T', 'higher: yes', 'school: GP']
    X Support: 94.94% | X and Y Support: 64% | Confidence: 67% | ['higher: yes'] -> ['Pstatus: T', 'internet: yes', 'school: GP']
    X Support: 89.62% | X and Y Support: 64% | Confidence: 71% | ['Pstatus: T'] -> ['higher: yes', 'internet: yes', 'school: GP']
    X Support: 75.19% | X and Y Support: 64% | Confidence: 85% | ['internet: yes', 'school: GP'] -> ['Pstatus: T', 'higher: yes']
    X Support: 84.05% | X and Y Support: 64% | Confidence: 76% | ['school: GP', 'higher: yes'] -> ['Pstatus: T', 'internet: yes']
    X Support: 79.24% | X and Y Support: 64% | Confidence: 81% | ['internet: yes', 'higher: yes'] -> ['Pstatus: T', 'school: GP']
    X Support: 78.73% | X and Y Support: 64% | Confidence: 81% | ['Pstatus: T', 'school: GP'] -> ['higher: yes', 'internet: yes']
    X Support: 75.44% | X and Y Support: 64% | Confidence: 85% | ['Pstatus: T', 'internet: yes'] -> ['higher: yes', 'school: GP']
    X Support: 84.81% | X and Y Support: 64% | Confidence: 76% | ['Pstatus: T', 'higher: yes'] -> ['internet: yes', 'school: GP']
    X Support: 71.65% | X and Y Support: 64% | Confidence: 89% | ['internet: yes', 'school: GP', 'higher: yes'] -> ['Pstatus: T']
    X Support: 67.34% | X and Y Support: 64% | Confidence: 95% | ['Pstatus: T', 'internet: yes', 'school: GP'] -> ['higher: yes']
    X Support: 74.68% | X and Y Support: 64% | Confidence: 86% | ['Pstatus: T', 'school: GP', 'higher: yes'] -> ['internet: yes']
    X Support: 71.65% | X and Y Support: 64% | Confidence: 89% | ['Pstatus: T', 'internet: yes', 'higher: yes'] -> ['school: GP']
    X Support: 87.09% | X and Y Support: 61% | Confidence: 70% | ['schoolsup: no'] -> ['higher: yes', 'internet: yes', 'school: GP']
    X Support: 88.35% | X and Y Support: 61% | Confidence: 69% | ['school: GP'] -> ['higher: yes', 'internet: yes', 'schoolsup: no']
    X Support: 83.29% | X and Y Support: 61% | Confidence: 74% | ['internet: yes'] -> ['higher: yes', 'school: GP', 'schoolsup: no']
    X Support: 94.94% | X and Y Support: 61% | Confidence: 65% | ['higher: yes'] -> ['internet: yes', 'school: GP', 'schoolsup: no']
    X Support: 75.44% | X and Y Support: 61% | Confidence: 81% | ['school: GP', 'schoolsup: no'] -> ['higher: yes', 'internet: yes']
    X Support: 72.66% | X and Y Support: 61% | Confidence: 84% | ['internet: yes', 'schoolsup: no'] -> ['higher: yes', 'school: GP']
    X Support: 75.19% | X and Y Support: 61% | Confidence: 81% | ['internet: yes', 'school: GP'] -> ['higher: yes', 'schoolsup: no']
    X Support: 82.28% | X and Y Support: 61% | Confidence: 74% | ['higher: yes', 'schoolsup: no'] -> ['internet: yes', 'school: GP']
    X Support: 84.05% | X and Y Support: 61% | Confidence: 73% | ['school: GP', 'higher: yes'] -> ['internet: yes', 'schoolsup: no']
    X Support: 79.24% | X and Y Support: 61% | Confidence: 77% | ['internet: yes', 'higher: yes'] -> ['school: GP', 'schoolsup: no']
    X Support: 64.56% | X and Y Support: 61% | Confidence: 95% | ['internet: yes', 'school: GP', 'schoolsup: no'] -> ['higher: yes']
    X Support: 71.39% | X and Y Support: 61% | Confidence: 86% | ['higher: yes', 'school: GP', 'schoolsup: no'] -> ['internet: yes']
    X Support: 68.86% | X and Y Support: 61% | Confidence: 89% | ['higher: yes', 'internet: yes', 'schoolsup: no'] -> ['school: GP']
    X Support: 71.65% | X and Y Support: 61% | Confidence: 86% | ['internet: yes', 'school: GP', 'higher: yes'] -> ['schoolsup: no']
    X Support: 87.09% | X and Y Support: 61% | Confidence: 70% | ['schoolsup: no'] -> ['Pstatus: T', 'failures: 0', 'higher: yes']
    X Support: 94.94% | X and Y Support: 61% | Confidence: 64% | ['higher: yes'] -> ['Pstatus: T', 'failures: 0', 'schoolsup: no']
    X Support: 78.99% | X and Y Support: 61% | Confidence: 77% | ['failures: 0'] -> ['Pstatus: T', 'higher: yes', 'schoolsup: no']
    X Support: 89.62% | X and Y Support: 61% | Confidence: 68% | ['Pstatus: T'] -> ['failures: 0', 'higher: yes', 'schoolsup: no']
    X Support: 82.28% | X and Y Support: 61% | Confidence: 74% | ['higher: yes', 'schoolsup: no'] -> ['Pstatus: T', 'failures: 0']
    X Support: 68.86% | X and Y Support: 61% | Confidence: 88% | ['failures: 0', 'schoolsup: no'] -> ['Pstatus: T', 'higher: yes']
    X Support: 77.22% | X and Y Support: 61% | Confidence: 79% | ['failures: 0', 'higher: yes'] -> ['Pstatus: T', 'schoolsup: no']
    X Support: 78.48% | X and Y Support: 61% | Confidence: 77% | ['Pstatus: T', 'schoolsup: no'] -> ['failures: 0', 'higher: yes']
    X Support: 84.81% | X and Y Support: 61% | Confidence: 72% | ['Pstatus: T', 'higher: yes'] -> ['failures: 0', 'schoolsup: no']
    X Support: 70.63% | X and Y Support: 61% | Confidence: 86% | ['Pstatus: T', 'failures: 0'] -> ['higher: yes', 'schoolsup: no']
    X Support: 67.09% | X and Y Support: 61% | Confidence: 91% | ['higher: yes', 'failures: 0', 'schoolsup: no'] -> ['Pstatus: T']
    X Support: 73.92% | X and Y Support: 61% | Confidence: 82% | ['higher: yes', 'Pstatus: T', 'schoolsup: no'] -> ['failures: 0']
    X Support: 62.28% | X and Y Support: 61% | Confidence: 98% | ['Pstatus: T', 'failures: 0', 'schoolsup: no'] -> ['higher: yes']
    X Support: 69.11% | X and Y Support: 61% | Confidence: 88% | ['Pstatus: T', 'failures: 0', 'higher: yes'] -> ['schoolsup: no']
    X Support: 87.09% | X and Y Support: 62% | Confidence: 72% | ['schoolsup: no'] -> ['Pstatus: T', 'higher: yes', 'internet: yes']
    X Support: 83.29% | X and Y Support: 62% | Confidence: 75% | ['internet: yes'] -> ['Pstatus: T', 'higher: yes', 'schoolsup: no']
    X Support: 94.94% | X and Y Support: 62% | Confidence: 66% | ['higher: yes'] -> ['Pstatus: T', 'internet: yes', 'schoolsup: no']
    X Support: 89.62% | X and Y Support: 62% | Confidence: 69% | ['Pstatus: T'] -> ['higher: yes', 'internet: yes', 'schoolsup: no']
    X Support: 72.66% | X and Y Support: 62% | Confidence: 86% | ['internet: yes', 'schoolsup: no'] -> ['Pstatus: T', 'higher: yes']
    X Support: 82.28% | X and Y Support: 62% | Confidence: 76% | ['higher: yes', 'schoolsup: no'] -> ['Pstatus: T', 'internet: yes']
    X Support: 79.24% | X and Y Support: 62% | Confidence: 79% | ['internet: yes', 'higher: yes'] -> ['Pstatus: T', 'schoolsup: no']
    X Support: 78.48% | X and Y Support: 62% | Confidence: 79% | ['Pstatus: T', 'schoolsup: no'] -> ['higher: yes', 'internet: yes']
    X Support: 75.44% | X and Y Support: 62% | Confidence: 83% | ['Pstatus: T', 'internet: yes'] -> ['higher: yes', 'schoolsup: no']
    X Support: 84.81% | X and Y Support: 62% | Confidence: 73% | ['Pstatus: T', 'higher: yes'] -> ['internet: yes', 'schoolsup: no']
    X Support: 68.86% | X and Y Support: 62% | Confidence: 90% | ['higher: yes', 'internet: yes', 'schoolsup: no'] -> ['Pstatus: T']
    X Support: 65.82% | X and Y Support: 62% | Confidence: 95% | ['Pstatus: T', 'internet: yes', 'schoolsup: no'] -> ['higher: yes']
    X Support: 73.92% | X and Y Support: 62% | Confidence: 84% | ['higher: yes', 'Pstatus: T', 'schoolsup: no'] -> ['internet: yes']
    X Support: 71.65% | X and Y Support: 62% | Confidence: 87% | ['Pstatus: T', 'internet: yes', 'higher: yes'] -> ['schoolsup: no']


</code>
  </pre>
</figure>
        



                  <?php include('footer.php') ?>

    </body>

    </html>