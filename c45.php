<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>C4.5 Algorithm</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,700,800" rel="stylesheet">
  <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css" />
  <script src="assets/js/main.js"></script>
       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>

<body>
          <?php include('header.php') ?>
    <br><br>
 <h5><center>C4.5 Algorithm Code in Python:</center></h5>
<figure>
  <figcaption></figcaption>
  <pre>
    <code>
    import pandas 
    import numpy 
    from pprint import pprint
    import math 

    def entropy(dataFrame):
        # Initializing the necessary variables. 
        e = 0.0
        class_attribute = dataFrame.keys()[-1] # The -1 needs to be changed to the index of the class attribute (final grade) 

        # Determining the unique values of the class attribute. 
        unique_values = dataFrame[class_attribute].unique()

        # Counting the total number of tuples (used to calculate probabilities). 
        total = len(dataFrame[class_attribute])

        # Calculating the entropy
        for i in unique_values:
            x = dataFrame[class_attribute].value_counts()[i]    
            prob = x/total
            #incrementing the entropy. 
            e = e - prob*math.log2(prob)
        return e 


    def entropy_attribute(dataFrame, attribute):
        # Initializing the necessary variables. 
        e_attr = 0.0 
        class_attribute = dataFrame.keys()[-1] # The -1 needs to be changed to the index of the class attribute (final grade)
        class_values = dataFrame[class_attribute].unique()
        attr_values = dataFrame[attribute].unique()


        # Iterating through each unique value of the attribute. 
        for x in attr_values:
            e = 0.0 

            # Counting the total number rows with attribute x. 
            total = len(dataFrame[attribute][dataFrame[attribute]==x])
            # Iterating through each unique value of the class attribute. 
            for y in class_values:
                # Counting the total number of rows that have attribute x and belong to class y. 
                count = len(dataFrame[attribute][dataFrame[attribute] == x][dataFrame[class_attribute]==y])

                # Calculating the entropy for the specific value.  
                prob = count / total  # Might get div by 0 error (if so must implement eps)
                if prob>0:
                    e += -prob*math.log2(prob)

            # Incrementing the weighted entropy for the attribute. 
            weight = total/len(dataFrame)
            e_attr = e_attr - weight*e  

        return abs(e_attr)


    def info_gain(dataFrame):

        # we need to find the attribute which has the least
        # entropy to choose our next attribute in the tree
        # this is also know most information gain  


        # create a list of information gain for each attribute  
        # by using the gain criterion
        info_gain = []

        for attribute in dataFrame.columns[:-1]:  
            info_gain.append(df_entropy-entropy_attribute(dataFrame,attribute))

        attr = dataFrame.columns[:-1][numpy.argmax(info_gain)]

        return attr


    def get_deduced_table(dataFrame,value, node):
        return dataFrame[dataFrame[node] == value].reset_index(drop=True)


    def createTree(dataFrame,dec_tree=None): 

        # Create variable for for output variable 
        output = dataFrame.keys()[-1]

        #First to create the splitting attribute with the most information gain
        spl_attr = info_gain(dataFrame)
        #Create list of the unique attribute values to create children nodes
        uniqueAttValues = numpy.unique(dataFrame[spl_attr])

        # The code below will build the decision tree; we must use recursion to create multiple nodes
        # Initialize the decision tree by creating empty dictionary 

        if dec_tree is None:                    
            dec_tree={}
            dec_tree[spl_attr] = {}

        # now we must continue to grow the decision tree

        # so for each children node we will treat it like a decision tree on its own
        for val in uniqueAttValues:

            # first we create a smaller dataFrame of only where the children node == value 
            deduced_table = get_deduced_table(dataFrame,val,spl_attr)

            #Similar to above we will create list of the unique attribute values to create test nodes
            elements,count = numpy.unique(deduced_table[output],return_counts = True)

            # if the deduced dataFrame has only one unique value for that attribute we have reached a leaf node 
            if len(count)==1:#Checking purity of subset
                dec_tree[spl_attr][val] = elements[0]                                                    
            else:        
                dec_tree[spl_attr][val] = createTree(deduced_table) #Recursive call until there are no more test nodes

        return dec_tree



    # Read data from csv and label columns accordingly:
    data = pandas.read_csv('student-matG3.csv', header=0)

    #Create pandas dataFrame
    df = pandas.DataFrame(data)
    # find entropy of output variable G3 
    df_entropy = entropy(df)

    # list of initial information gain against G3 final grades for math 
    print("Initial Information Gain against Final Grades for Math")
    print("Entropy of the starting set G3:",  df_entropy)
    for attribute in df.columns[:-1]:  
        print(attribute , ":" , (df_entropy-entropy_attribute(df,attribute)))

    # build decision tree based on gain criterion 
    t=createTree(df)
    pprint(t)

    
    </code></pre></figure>
    
    
                <h5><center>Output (Running the Program on G3 - Final Grades)</center></h5>
<figure>
  <figcaption></figcaption>
  <pre>
    <code>

    Initial Information Gain against Final Grades for Math
    Entropy of the starting set G3: 3.756759454146232
    school : 0.024827095054409387
    sex : 0.039918520415610725
    age : 0.1640502222927549
    address : 0.04559369639631328
    famsize : 0.03215912615638805
    Pstatus : 0.029091354530635716
    Medu : 0.14034818124180015
    Fedu : 0.1198081917651268
    Mjob : 0.16158398846718036
    Fjob : 0.13670146339165834
    reason : 0.09928663550259786
    guardian : 0.054766697650281415
    traveltime : 0.07190801151244175
    studytime : 0.10629654752452922
    failures : 0.2164648152494011
    schoolsup : 0.06670144453695759
    famsup : 0.02758885893650964
    paid : 0.05815876565110978
    activities : 0.030166310181409806
    nursery : 0.03407906568550301
    higher : 0.051866307972130876
    internet : 0.039336137476174926
    romantic : 0.06107954813929295
    famrel : 0.10279513072434776
    freetime : 0.11195039104802929
    goout : 0.13913697495657074
    Dalc : 0.1370939140508054
    Walc : 0.15888362859314542
    health : 0.12378646803899374
    absences : 0.8343376903935691

    {'absences': {0: {'age': {15: {'goout': {1: {'reason': {'course': 11,
                                                            'home': 15,
                                                            'other': 6,
                                                            'reputation': {'Fedu': {3: 15,
                                                                                    4: 17}}}},
                                             2: {'freetime': {2: {'famsize': {'GT3': 0,
                                                                              'LE3': 19}},
                                                              3: {'Mjob': {'health': 0,
                                                                           'other': {'sex': {'F': 11,
                                                                                             'M': 12}},
                                                                           'services': 15,
                                                                           'teacher': 15}},
                                                              4: {'reason': {'course': 11,
                                                                             'home': 12,
                                                                             'other': 15}},
                                                              5: 16}},
                                             3: {'Mjob': {'at_home': 0,
                                                          'other': 10,
                                                          'services': 0,
                                                          'teacher': 9}},
                                             4: {'famrel': {3: 11, 4: 10, 5: 18}},
                                             5: {'famsize': {'GT3': 12,
                                                             'LE3': 10}}}},
                              16: {'Mjob': {'at_home': 0,
                                            'health': {'Fedu': {2: 16, 4: 14}},
                                            'other': {'health': {2: 0,
                                                                 3: {'Fedu': {1: 8,
                                                                              2: 11,
                                                                              3: {'sex': {'F': 8,
                                                                                          'M': 14}}}},
                                                                 4: {'famrel': {3: 14,
                                                                                4: 8,
                                                                                5: 0}},
                                                                 5: {'reason': {'course': {'Medu': {1: 0,
                                                                                                    2: 15,
                                                                                                    3: 0}},
                                                                                'home': {'goout': {2: 13,
                                                                                                   3: 15,
                                                                                                   4: 8}},
                                                                                'other': 15,
                                                                                'reputation': {'famsize': {'GT3': 15,
                                                                                                           'LE3': 12}}}}}},
                                            'services': {'reason': {'course': 12,
                                                                    'other': 17,
                                                                    'reputation': 10}},
                                            'teacher': {'studytime': {1: 0,
                                                                      2: 10,
                                                                      3: 14}}}},
                              17: {'goout': {1: {'Medu': {1: 0,
                                                          2: 0,
                                                          4: {'sex': {'F': 12,
                                                                      'M': 10}}}},
                                             2: {'Mjob': {'at_home': 0,
                                                          'health': {'Medu': {3: 18,
                                                                              4: 15}},
                                                          'other': {'address': {'R': 12,
                                                                                'U': 0}},
                                                          'services': {'famsize': {'GT3': 17,
                                                                                   'LE3': 15}}}},
                                             3: {'Medu': {0: 15,
                                                          1: 9,
                                                          4: {'Fedu': {2: 14,
                                                                       3: 15}}}},
                                             4: {'Medu': {1: 0,
                                                          3: 10,
                                                          4: {'school': {'GP': 10,
                                                                         'MS': 15}}}},
                                             5: {'famsize': {'GT3': 0, 'LE3': 7}}}},
                              18: {'Fedu': {1: {'Mjob': {'at_home': 16,
                                                         'other': 0,
                                                         'services': 0,
                                                         'teacher': 8}},
                                            2: {'Dalc': {1: 0,
                                                         2: 8,
                                                         3: {'school': {'GP': 0,
                                                                        'MS': 10}},
                                                         5: 13}},
                                            3: {'health': {1: 15,
                                                           3: {'school': {'GP': 14,
                                                                          'MS': 10}},
                                                           4: {'school': {'GP': 0,
                                                                          'MS': 10}},
                                                           5: 9}},
                                            4: {'reason': {'course': 13,
                                                           'home': 0,
                                                           'other': 13,
                                                           'reputation': 19}}}},
                              19: {'guardian': {'father': 12,
                                                'mother': 0,
                                                'other': 0}},
                              20: 18}},
                  1: {'age': {17: 15, 18: 12}},
                  2: {'age': {15: {'Fjob': {'at_home': 9,
                                            'health': {'Medu': {2: 8, 3: 11}},
                                            'other': {'reason': {'course': 11,
                                                                 'home': {'Medu': {1: 10,
                                                                                   2: 11,
                                                                                   4: 14}},
                                                                 'other': 13,
                                                                 'reputation': 5}},
                                            'services': {'freetime': {1: 8,
                                                                      2: {'Fedu': {2: 15,
                                                                                   3: 12,
                                                                                   4: 11}},
                                                                      3: 14,
                                                                      4: {'address': {'R': 8,
                                                                                      'U': 18}}}},
                                            'teacher': {'reason': {'course': 18,
                                                                   'other': 7,
                                                                   'reputation': 14}}}},
                              16: {'Fedu': {0: 16,
                                            1: {'Medu': {1: 13, 2: 10, 3: 14}},
                                            2: {'freetime': {2: {'sex': {'F': 16,
                                                                         'M': 15}},
                                                             3: {'sex': {'F': 13,
                                                                         'M': 11}},
                                                             4: 11,
                                                             5: 16}},
                                            3: {'health': {1: 15,
                                                           2: 16,
                                                           3: {'sex': {'F': 17,
                                                                       'M': 12}},
                                                           4: {'sex': {'F': 9,
                                                                       'M': 15}},
                                                           5: 18}},
                                            4: {'Mjob': {'health': 13,
                                                         'other': 13,
                                                         'teacher': 16}}}},
                              17: {'Walc': {1: {'address': {'R': 11, 'U': 10}},
                                            2: {'goout': {2: 14,
                                                          3: 11,
                                                          4: 8,
                                                          5: 6}},
                                            3: {'Mjob': {'health': 13,
                                                         'other': 15,
                                                         'services': 12,
                                                         'teacher': 12}},
                                            4: 10}},
                              18: {'freetime': {3: {'school': {'GP': 8, 'MS': 10}},
                                                4: {'school': {'GP': 11, 'MS': 12}},
                                                5: 8}},
                              19: 9}},
                  3: {'Walc': {2: 11,
                               3: {'age': {18: 12, 21: 7}},
                               4: {'school': {'GP': 8, 'MS': 16}},
                               5: 16}},
                  4: {'age': {15: {'Fedu': {1: 12,
                                            2: {'sex': {'F': 14, 'M': 15}},
                                            3: {'sex': {'F': 18, 'M': 11}},
                                            4: {'Mjob': {'other': 12,
                                                         'services': 14,
                                                         'teacher': 15}}}},
                              16: {'freetime': {1: 10,
                                                2: 20,
                                                3: {'Fedu': {1: 6,
                                                             2: {'sex': {'F': 6,
                                                                         'M': 10}},
                                                             3: 10,
                                                             4: 11}},
                                                4: {'reason': {'home': 14,
                                                               'other': 16,
                                                               'reputation': 9}},
                                                5: 12}},
                              17: {'Fedu': {1: {'Mjob': {'at_home': 6,
                                                         'other': 10}},
                                            2: {'freetime': {1: 10,
                                                             3: 10,
                                                             4: {'sex': {'F': 11,
                                                                         'M': 13}},
                                                             5: 11}},
                                            3: {'goout': {2: 13,
                                                          3: {'sex': {'F': 9,
                                                                      'M': 11}},
                                                          4: {'famsize': {'GT3': 6,
                                                                          'LE3': 9}},
                                                          5: 11}},
                                            4: {'reason': {'course': 14,
                                                           'home': 16,
                                                           'other': 13}}}},
                              18: {'freetime': {2: {'school': {'GP': 8, 'MS': 14}},
                                                3: 10,
                                                4: 10,
                                                5: 14}},
                              19: {'Medu': {1: 8, 2: 11, 3: 9}},
                              20: 15}},
                  5: {'Medu': {1: 9,
                               2: {'school': {'GP': 19, 'MS': 7}},
                               3: 7,
                               4: 16}},
                  6: {'Mjob': {'at_home': {'Medu': {1: 10,
                                                    2: {'sex': {'F': 13, 'M': 14}},
                                                    3: 10,
                                                    4: 6}},
                               'health': {'age': {15: 10, 16: 11, 17: 9}},
                               'other': {'Walc': {1: {'sex': {'F': 6, 'M': 18}},
                                                  2: {'sex': {'F': 14, 'M': 13}},
                                                  3: 10,
                                                  4: {'sex': {'F': 13, 'M': 7}},
                                                  5: 13}},
                               'services': {'freetime': {1: 18,
                                                         2: 14,
                                                         3: {'age': {15: 14,
                                                                     16: 11,
                                                                     17: 12}},
                                                         4: {'age': {15: 8,
                                                                     17: 12}},
                                                         5: {'sex': {'F': 11,
                                                                     'M': 15}}}},
                               'teacher': {'studytime': {1: 19,
                                                         2: 10,
                                                         3: 7,
                                                         4: 13}}}},
                  7: {'Mjob': {'at_home': {'school': {'GP': 14, 'MS': 8}},
                               'other': {'sex': {'F': 14, 'M': 15}},
                               'services': 17,
                               'teacher': {'school': {'GP': 9, 'MS': 6}}}},
                  8: {'Fedu': {0: 10,
                               1: {'age': {15: 9, 16: 10, 18: 14, 19: 8}},
                               2: {'famrel': {2: {'age': {16: 9, 17: 13}},
                                              3: 11,
                                              4: {'sex': {'F': 13, 'M': 10}},
                                              5: {'sex': {'F': 8, 'M': 14}}}},
                               3: {'Mjob': {'other': {'sex': {'F': 6, 'M': 5}},
                                            'services': 10,
                                            'teacher': 10}},
                               4: {'freetime': {3: 11, 4: 12, 5: 18}}}},
                  9: {'Fjob': {'other': 9, 'services': 16, 'teacher': 15}},
                  10: {'Walc': {1: {'health': {1: 13, 2: 13, 3: 19, 4: 11, 5: 6}},
                                2: {'age': {16: 15,
                                            17: 12,
                                            18: {'school': {'GP': 8, 'MS': 11}},
                                            19: 8}},
                                3: {'Mjob': {'at_home': 10,
                                             'health': 10,
                                             'other': 15,
                                             'teacher': 8}},
                                4: 9,
                                5: 13}},
                  11: {'Medu': {2: 9, 3: 15, 4: 11}},
                  12: {'Fedu': {1: {'age': {15: 16, 18: 13, 19: 13}},
                                2: {'Mjob': {'at_home': 8,
                                             'other': {'famsize': {'GT3': 9,
                                                                   'LE3': 12}},
                                             'services': 10}},
                                3: 11,
                                4: {'age': {16: 5, 17: 10}}}},
                  13: {'famrel': {3: 8, 4: 17, 5: 13}},
                  14: {'freetime': {1: 13,
                                    2: {'health': {1: 12, 3: 14, 4: 11, 5: 8}},
                                    3: 9,
                                    4: 5,
                                    5: {'Mjob': {'health': 12,
                                                 'other': 5,
                                                 'services': 5,
                                                 'teacher': 9}}}},
                  15: {'age': {18: 8, 19: 9}},
                  16: {'age': {16: {'Fedu': {2: 12, 4: 11}},
                               17: {'sex': {'F': 15, 'M': 5}},
                               18: 7,
                               22: 8}},
                  17: 10,
                  18: {'Mjob': {'at_home': 10,
                                'health': 13,
                                'other': 6,
                                'teacher': 7}},
                  19: 10,
                  20: {'age': {16: 12, 17: 8, 19: 13}},
                  21: 18,
                  22: {'age': {17: 4, 18: 9, 19: 11}},
                  23: 13,
                  24: 18,
                  25: 11,
                  26: 6,
                  28: 9,
                  30: 8,
                  38: 8,
                  40: 11,
                  54: 11,
                  56: 8,
                  75: 9}}

    </code></pre></figure>
              <?php include('footer.php') ?>

</body>

</html>