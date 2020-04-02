<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>K-Means Algorithm</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,700,800" rel="stylesheet">
  <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css" />
  <script src="assets/js/main.js"></script>
    
</head>
<body>
           <?php include('header.php') ?><br><br>
 
<h5><center>K-Means Algorithm Code in Python:</center></h5>
<figure>
  <figcaption></figcaption>
  <pre>
    <code>
    import csv
    import random
    import statistics
    import copy
    list = []
    school = ['GP', 'MS'] #1
    sex = ['F', 'M'] #2
    student = ['U', 'R'] #4
    famsize = ['LE3', 'GT3'] #5
    pstatus = ['T', 'A'] #6
    Mjob = ['teacher', 'health', 'services', 'at_home', 'other'] #9
    Fjob = ['teacher', 'health', 'services', 'at_home', 'other'] #10
    reason = ['home', 'reputation', 'course', 'other'] #11
    guardian = ['mother', 'father', 'other'] #12
    schoolsup = ['yes', 'no'] #16
    famsup = ['yes', 'no'] #17
    paid = ['yes', 'no'] #18
    activities = ['yes', 'no'] #19
    nursery = ['yes', 'no'] #20
    higher = ['yes', 'no'] #21
    internet =  ['yes', 'no'] #22
    romantic = ['yes', 'no'] #23



    k = int(input("HOW MANY CLUSTERS DO YOU WANT? "))

    count_list = [1,2,4,5,6,9,10,11,12,16,17,18,19,20,21,22,23] # Values that are not part of 
    range_val = []
    range_index = [3,7,8,13,14,15,24,25,26,27,28,29,30,31,32,33]    # the index of all numerical values that we are going to take into account in k-means calculations
                                                                    # Used for reading the file 
    range_length =[7,4,4,3,3,3,4,4,4,4, 4,4,93,20,20,20]    # range of each numerical stat (each value corresponds to the range index above)

    category_list = ["age", "Medu", "Fedu", "traveltime", "studytime",
                  "failures", "famrel", "freetime","goout","Dalc","Walc","health",
                 "absences", "G1" , "G2","G3" ]
    full_list = []

    # Loops through each line in the file to get the list full of stats of each student performance
    with open("mathdataset.txt", "r") as filestream:
        for line in filestream:
            line = line.rstrip("\n")
            currentline = line.split(",")

            settings_list = [] 
            temp_list = []
            count = 1
            line.rstrip()
            for x in currentline:


                if count not in count_list:
                    temp_line = x.split(":")
                    temp_list.append(int(temp_line[1]))     # Appends the stat for any category that doesn't exists in the count_list
                    settings_list.append(temp_line[0])      # For debugging purposes
                count = count + 1       #For debugging purposes
            full_list.append(temp_list) # contains just the stats for each corresponding category in form of [#,#,#,....,#]

        num_points = len(full_list)
        k_points = [];
        original_k_points = []

        #print("NUM OF POINTS: " + str(num_points))         # debugging purposes

        l = random.sample(range(0, num_points-1),k)         # initializes the list of k random numbers that we will use to initialize centroids



        center_points = []
        center_index = []                   # Center index will keep track of index values of all nodes in each cluster
        original_center_index = []          
        compare_center_index = []           # will be used to ensure that clusters are the same so as to stop the k-means algorithm
        compare_center_points = []          # will be used to ensure that clusters are the same so as to stop the k-means algorithm

        # initialize centroids and center index
        for i in l:
            x = full_list[i]                         
            center_points.append(x)                 # initialize the centroid values for each cluster (make them one of the k nodes)
            k_points.append([x])    
            original_center_index.append([i])       # initialize the original points for the center which will be reused on each iteration
            original_k_points.append([x])           # initialize the list of nodes in each cluster which will be reused on each iteration
            compare_center_points.append(x)         # set the compare arry which will initially be the same as the centroid points for each  cluster
            compare_center_index.append([i])        #

        count_check = 0
        check_centroids = False

        while (check_centroids == False):
            compare_center_points = copy.deepcopy (center_points)   # set the center points to compare at the end of while loop to be the same as center points that we got from previous iteration
            center_index = copy.deepcopy (original_center_index)    # Set the center index (list of nodes (index form)) to be the same as the original center index 
            k_points = copy.deepcopy(original_k_points)             # set the points in each cluster to be the points of the initially selected clusters

            for s in range(0,len(full_list)):
                if s not in l:
                    j = full_list[s]
                    distance_list = []                      # list used to get distance of select point to each cluster
                    for m in center_points:                 # loop through the different centroid points
                        temp_val = 0;               
                        for i in range(0, len(m)):          # loop through each element in the point
                            temp_val = temp_val + ((abs((j[i]/range_length[i])-(m[i]/range_length[i])))**2)     # normalize each distance calculation and add to get sum of difference
                        distance_list.append(temp_val)                          # append the distance value to the distance list    
                    select_center = distance_list.index(min(distance_list))     # Get the index value for the minimum distance
                    center_index[select_center].append(s)                       # add the node index to the cluster (center)  with which  it has the minimum distance
                    k_points[select_center].append(j)                           # add the node value to the cluster (center) with which  it has the minimum distance

            # Once done, you have gone through each point, now is the time to update the centroid value
            for i in range(0, len(center_points)):              
                center_points[i] = [statistics.mean(q) for q in zip(*k_points[i])]          # update the center point to be the average of all the points in its cluster


            count_check = count_check + 1

            check_centroids = False

            if (compare_center_points == center_points):                # if the centroid has not change from the previous iteration, then

                for i in range(0, len(center_index)):                   # loop through the center index to see that the cluster has the same values as the previous iteration

                    if (len(center_index[i]) == len(compare_center_index[i])):  # if the clusters have the same length as before, then
                        if (center_index[i] == compare_center_index[i]):        # check to see that they are the same as before
                            check_centroids = True          
                        else:                                                   # if they are not the same, continue with the while loop
                            check_centroids = False
                            compare_center_index = copy.deepcopy(center_index)  # copy current center index to use for next iteration
                            break
                    else:                                                   # if lengths are not the same
                        check_centroids = False
                        compare_center_index = copy.deepcopy(center_index)  # copy current center index to use for next iteration
                        break


            else:
                 compare_center_index = copy.deepcopy(center_index) # if the centroid has changed, then copy the current center index to use for next iteration 



        row_format ="{:>10}" * (len(category_list) + 1)
        print_avg ="{:>10}" + "{:>10.2f}" * (len(category_list))

        for i in range(k):        # loop through the different clusters and print out the values that are in each cluster
            center_index_temp = center_index[i]                        # Get the ith center index position
            print("#" * 40)
            print ("CLUSTER " + str(i+1) + " (Size = " + str(len(center_index[i])) + " data points)")
            print("#" * 40)
            print(row_format.format("", *category_list))            # this is the header row
            print(print_avg.format("CENTROID", *center_points[i]))    # this is the row showing centroid values
            for m in range(len(k_points[i])):
                print(row_format.format(str(center_index_temp[m]), *k_points[i][m]))

</code>
  </pre>
</figure>
            <h5><center>Test 1 (3 Clusters):</center></h5>

    <figure>
  <figcaption></figcaption>
  <pre>
    <code>
HOW MANY CLUSTERS DO YOU WANT? 3
########################################
CLUSTER 1 (Size = 101 data points)
########################################
                 age      Medu      Fedutraveltime studytime  failures    famrel  freetime     goout      Dalc      Walc    health  absences        G1        G2        G3
  CENTROID     17.00      1.69      1.64      1.55      1.76      0.87      3.95      3.25      3.11      1.32      2.27      3.92      4.27      8.36      7.51      6.45
       255        17         1         1         2         1         1         4         4         4         1         2         5         2         7         9         8
         1        17         1         1         1         2         0         5         3         3         1         1         3         4         5         5         6
         2        15         1         1         1         2         3         4         3         2         2         3         3        10         7         8        10
        18        17         3         2         1         1         3         5         5         5         2         4         5        16         6         5         5
        25        16         2         2         1         1         2         1         2         2         1         3         5        14         6         9         8
        35        15         2         3         2         1         0         3         5         1         1         1         5         0         8         7         6
        40        16         2         2         2         2         1         3         3         3         1         2         3        25         7        10        11
        44        16         2         2         2         2         1         4         3         3         2         2         5        14        10        10         9
        58        15         1         2         1         2         0         4         3         2         1         1         5         2         9        10         9
        67        16         3         1         1         4         0         4         3         3         1         2         5         4         7         7         6
        68        15         2         2         2         2         0         4         1         3         1         3         4         2         8         9         8
        72        15         1         1         1         2         2         3         3         4         2         4         5         2         8         6         5
        78        17         2         1         2         1         3         4         5         1         1         1         3         2         8         8        10
        82        15         3         2         1         2         0         4         4         4         1         1         5        10         7         6         6
        84        15         1         1         1         2         0         4         3         2         2         3         4         2         9        10        10
        86        16         2         2         1         2         0         4         3         4         1         2         2         4         8         7         6
        92        16         3         1         1         2         0         3         3         3         2         3         2         4         7         6         6
        97        16         2         1         1         2         0         4         3         5         1         1         5         2         8         9        10
       114        15         2         1         1         2         0         5         4         2         1         1         5         8         9         9         9
       118        17         1         3         3         2         1         5         2         4         1         4         5        20         9         7         8
       124        16         2         2         1         2         0         5         4         4         1         1         5         0         8         7         8
       127        19         0         1         1         2         3         3         4         2         1         1         5         2         7         8         9
       128        18         2         2         1         1         2         3         3         3         1         2         4         0         7         4         0
       130        15         3         4         2         3         2         4         2         2         2         2         5         0        12         0         0
       131        15         1         1         3         1         0         4         3         3         1         2         4         0         8         0         0
       134        15         3         4         4         2         0         5         3         3         1         1         5         0         9         0         0
       137        16         3         3         2         1         2         4         3         2         1         1         5         0         4         0         0
       138        16         1         1         1         2         1         4         4         4         1         3         5         0        14        12        12
       141        16         2         2         2         1         2         2         3         3         2         2         2         8         9         9         9
       144        17         2         1         1         1         3         5         4         5         1         2         5         0         5         0         0
       145        15         1         1         1         2         0         4         4         2         1         2         5         0         8        11        11
       146        15         3         2         1         2         3         3         3         2         1         1         3         0         6         7         0
       147        15         1         2         1         2         0         4         3         2         1         1         5         2        10        11        11
       149        15         2         1         4         1         3         4         5         5         2         5         5         0         8         9        10
       150        18         1         1         1         1         3         2         3         5         2         5         4         0         6         5         0
       153        19         3         2         1         1         3         4         5         4         1         1         4         0         5         0         0
       156        17         1         2         1         1         0         2         2         2         3         3         5         8        16        12        13
       157        18         1         1         3         1         3         5         2         5         1         5         4         6         9         8        10
       160        17         2         1         2         1         2         3         3         2         2         2         5         0         7         6         0
       161        15         3         2         2         2         2         4         4         4         1         4         3         6         5         9         7
       162        16         1         2         2         1         1         4         4         4         2         4         5         0         7         0         0
       163        17         1         3         1         1         0         5         3         3         1         4         2         2        10        10        10
       164        17         1         1         4         2         3         5         3         5         1         5         5         0         5         8         7
       168        16         2         2         1         2         0         5         1         5         1         1         4         0         6         7         0
       173        16         1         3         1         2         3         4         3         5         1         1         3         0         8         7         0
       186        16         1         2         1         1         0         3         3         3         1         2         3         2        11        12        11
       189        17         1         2         1         2         0         3         1         3         1         5         3         4         8         9        10
       191        17         1         1         1         2         0         5         3         3         1         1         3         0         8         8         9
       201        16         2         3         1         2         0         4         4         3         1         3         4         6         8        10        10
       202        17         1         1         1         2         0         4         4         4         1         3         1         4         9         9        10
       203        17         2         2         1         1         0         5         3         2         1         2         3        18         7         6         6
       206        16         3         1         1         2         3         2         3         3         2         2         4         5         7         7         7
       208        16         1         1         2         1         0         4         3         2         1         4         5         6         9         9        10
       213        18         2         2         1         2         1         4         4         4         2         4         5        15         6         7         8
       220        17         2         1         2         2         0         4         2         5         1         2         5         2         6         6         6
       221        17         1         1         1         3         1         4         3         4         1         1         5         0         6         5         0
       225        18         3         1         1         2         1         5         3         3         1         1         4        16         9         8         7
       234        16         1         1         2         2         0         3         4         2         1         1         5        18         9         7         6
       237        16         2         1         1         1         0         4         5         2         1         1         5        20        13        12        12
       242        16         4         3         1         1         0         5         4         5         1         1         3         0         6         0         0
       244        18         2         1         2         3         0         4         4         4         1         1         3         0         7         0         0
       248        18         3         3         1         2         1         4         3         3         1         3         5         8         3         5         5
       249        16         0         2         1         1         0         4         3         2         2         4         5         0        13        15        15
       253        16         2         1         2         1         0         3         3         2         1         3         3         0         8         9         8
       254        17         2         1         1         1         0         4         4         2         2         4         5         0         8        12        12
       264        18         2         2         1         3         0         4         3         3         1         1         3         0         9        10         0
       269        18         2         1         2         2         0         4         3         5         1         2         3         0         6         0         0
       272        18         1         1         2         2         0         4         4         3         1         1         3         2        11        11        11
       283        18         1         1         2         2         0         5         4         4         1         1         4         4         8         9        10
       284        17         2         2         1         2         0         5         4         5         1         2         5         4        10         9        11
       285        17         1         1         1         2         0         4         3         3         1         2         4         2        12        10        11
       292        18         2         1         1         2         1         5         4         3         1         1         5        12        12        12        13
       309        19         1         1         1         2         1         4         4         3         1         3         3        18        12        10        10
       310        19         1         2         1         2         1         4         2         4         2         2         3         0         9         9         0
       312        19         1         2         1         2         1         4         5         2         2         2         4         3        13        11        11
       314        19         1         1         1         3         2         4         1         2         1         1         3        14        15        13        13
       316        18         2         1         2         2         0         5         3         3         1         2         1         0         8         8         0
       332        18         3         3         1         2         0         5         3         4         1         1         4         0         7         0         0
       333        18         2         2         1         2         0         4         3         3         1         1         2         0         8         8         0
       334        18         2         2         2         4         0         4         4         4         1         1         4         0        10         9         0
       337        17         3         2         1         2         0         4         3         2         2         3         2         0         7         8         0
       340        19         2         1         1         3         1         4         3         4         1         3         3         4        11        12        11
       343        17         2         2         1         2         1         3         3         1         1         2         4         0         9         8         0
       350        19         1         1         3         2         3         5         4         4         3         3         2         8         8         7         8
       352        18         1         3         1         1         1         4         3         3         2         3         3         7         8         7         8
       353        19         1         1         3         1         1         4         4         4         3         3         5         4         8         8         8
       358        18         1         1         2         1         0         3         3         2         1         2         3         4        10        10        10
       361        18         1         1         2         2         1         4         4         3         2         3         5         2        13        12        12
       367        17         1         1         3         1         1         5         2         1         1         2         1         0         7         6         0
       368        18         2         3         2         1         0         5         2         3         1         2         4         0        11        10        10
       370        19         3         2         2         2         2         3         2         2         1         1         3         4         7         7         9
       371        18         1         2         3         1         0         4         3         3         2         3         3         3        14        12        12
       373        17         1         2         1         1         0         3         5         5         1         3         1        14         6         5         5
       375        18         1         1         4         3         0         4         3         2         1         2         4         2         8         8        10
       381        18         2         1         2         1         0         4         4         3         1         3         5         5         7         6         7
       383        19         1         1         2         1         1         4         3         2         1         3         5         0         6         5         0
       385        18         2         2         2         3         0         5         3         3         1         3         4         2        10         9        10
       387        19         2         3         1         3         1         5         4         2         1         2         5         0         7         5         0
       389        18         1         1         2         2         1         1         1         1         1         1         5         0         6         5         0
       392        21         1         1         1         1         3         5         5         3         3         3         3         3        10         8         7
       394        19         1         1         1         1         0         3         2         3         3         3         5         5         8         9         9
########################################
CLUSTER 2 (Size = 100 data points)
########################################
                 age      Medu      Fedutraveltime studytime  failures    famrel  freetime     goout      Dalc      Walc    health  absences        G1        G2        G3
  CENTROID     16.83      3.21      2.92      1.53      1.78      0.25      3.75      3.63      3.97      2.41      3.82      3.78      7.84     10.78     10.69     10.51
       271        18         2         3         1         4         0         4         5         5         1         3         2         4        15        14        14
        12        15         4         4         1         1         0         4         3         3         1         3         5         2        14        14        14
        19        16         4         3         1         1         0         3         1         3         1         3         5         4         8        10        10
        23        16         2         2         2         2         0         5         4         4         2         4         5         0        13        13        12
        27        15         4         2         1         1         0         2         2         4         2         4         1         4        15        16        15
        29        16         4         4         1         2         0         4         4         5         5         5         5        16        10        12        11
        30        15         4         4         1         2         0         5         4         2         3         4         5         0         9        11        12
        41        15         4         4         1         1         0         5         4         3         2         4         5         8        12        12        12
        46        16         3         3         1         2         0         2         3         5         1         4         3        12        11        12        11
        50        16         2         2         3         2         0         4         3         3         2         3         4         2        12        13        13
        52        15         4         2         2         1         1         5         5         5         3         4         5         6        11        11        10
        53        15         4         4         1         1         0         3         3         4         2         3         5         0         8        10        11
        54        15         3         3         1         1         0         5         3         4         4         4         1         6        10        13        13
        60        16         4         4         1         2         0         2         4         4         2         3         4         6        10        11        11
        61        16         1         1         4         1         0         5         5         5         5         5         5         6        10         8        11
        63        16         4         3         1         3         0         3         4         4         2         4         4         2        10         9         9
        64        15         4         3         1         2         0         4         4         4         2         4         2         0        10        10        10
        66        15         4         4         1         4         0         1         3         3         5         5         3         4        13        13        12
        74        16         3         3         1         2         0         4         3         3         2         4         5        54        11        12        11
        75        15         4         3         1         2         0         4         3         3         2         3         5         6         9         9        10
        85        15         4         4         2         2         2         4         4         4         2         3         5         6         7         9         8
        89        16         4         4         1         2         0         4         1         3         3         5         5        18         8         6         7
        91        15         4         3         1         1         0         4         5         5         1         3         1         4        16        17        18
       100        16         4         4         1         1         0         4         5         5         5         5         4        14         7         7         5
       108        15         4         4         4         4         0         1         3         5         3         5         1         6        10        13        13
       123        16         4         4         1         1         0         3         4         4         1         4         5        18        14        11        13
       125        15         3         4         1         1         0         5         5         5         3         2         5         0        13        13        12
       129        16         4         4         1         1         0         3         5         5         2         5         4         8        18        18        18
       132        17         2         2         1         1         0         3         4         4         1         3         5        12        10        13        12
       133        16         3         4         1         1         0         3         2         1         1         4         5        16        12        11        11
       136        17         3         4         3         2         0         5         4         5         2         4         5         0        10         0         0
       143        16         1         1         1         1         0         3         4         4         3         3         1         2        14        14        13
       151        16         2         1         1         1         1         4         4         4         3         5         5         6        12        13        14
       159        16         3         3         1         2         1         4         5         5         4         4         5         4        10        12        12
       166        16         2         2         1         2         0         4         3         5         2         4         4         4        10        10        10
       170        16         3         4         3         1         2         3         4         5         2         4         2         0         6         5         0
       172        17         4         4         1         2         0         4         4         4         1         3         5         0        13        11        10
       175        17         4         3         2         2         0         4         4         4         4         4         4         4        10         9         9
       176        16         2         2         2         2         0         3         4         4         1         4         5         2        13        13        11
       177        17         3         3         1         2         0         4         3         4         1         4         4         4         6         5         6
       178        16         4         2         1         1         0         4         3         3         3         4         3        10        10         8         9
       180        16         4         3         1         2         0         3         4         3         2         3         3        10         9         8         8
       185        17         3         3         1         2         0         4         3         4         2         3         4        12        12        12        11
       192        17         1         2         2         2         0         4         4         4         4         5         5        12         7         8         8
       193        16         3         3         1         1         0         4         3         2         3         4         5         8         8         9        10
       197        16         3         3         3         1         0         3         3         4         3         5         3         8         9         9        10
       198        17         4         4         2         1         1         4         2         4         2         3         2        24        18        18        18
       200        16         4         3         1         2         0         4         3         5         1         5         2         2        16        16        16
       205        17         3         4         1         3         1         4         4         3         3         4         5        28        10         9         9
       211        17         4         4         1         2         0         5         3         5         4         5         3        13        12        12        13
       216        17         4         3         1         2         2         3         4         5         2         4         1        22         6         6         4
       217        18         3         3         1         2         1         3         2         4         2         4         4        13         6         6         8
       218        17         2         3         2         1         0         3         3         3         1         4         3         3         7         7         8
       223        18         2         2         2         2         0         3         3         3         5         5         4         0        12        13        13
       228        18         2         1         4         2         0         4         3         2         4         5         3        14        10         8         9
       232        17         4         4         1         2         0         4         5         5         1         3         2        14        11         9         9
       233        16         4         4         1         2         0         4         2         4         2         4         1         2        14        13        13
       236        17         2         2         1         2         0         4         4         2         5         5         4         4        14        13        13
       239        18         2         2         1         2         1         5         5         4         3         5         2         0         7         7         0
       240        17         4         3         2         2         0         2         5         5         1         4         5        14        12        12        12
       241        17         4         4         2         2         0         3         3         3         2         3         4         2        10        11        12
       247        22         3         1         1         1         3         5         4         5         5         5         1        16         6         8         8
       250        18         3         2         2         1         1         4         4         5         2         4         5         0         6         8         8
       252        18         2         1         1         1         1         3         2         5         2         5         5         4         6         9         8
       265        18         3         4         2         2         0         4         2         5         3         4         1        13        17        17        17
       266        17         3         1         1         2         0         5         4         4         3         4         5         2         9         9        10
       267        18         4         4         2         2         0         4         3         4         2         2         4         8        12        10        11
       268        18         4         2         1         2         0         5         4         5         1         3         5        10        10         9        10
       270        19         3         3         1         2         2         4         3         5         3         3         5        15         9         9         9
       275        17         2         2         2         2         0         4         4         4         2         3         5         6        12        12        12
       277        18         4         4         2         1         0         3         2         4         1         4         3        22         9         9         9
       280        17         4         1         2         1         0         4         5         4         2         4         5        30         8         8         8
       281        17         3         2         1         1         1         4         4         4         3         4         3        19        11         9        10
       290        18         4         2         1         2         0         4         3         2         1         4         5        11        12        11        11
       295        17         3         3         1         1         0         4         4         3         1         3         5         4        14        12        11
       296        19         4         4         2         2         0         2         3         4         2         3         2         0        10         9         0
       297        18         4         3         2         2         0         4         4         5         1         2         2        10        10         8         8
       318        17         3         4         1         3         0         4         3         4         2         5         5         0        11        11        10
       319        18         4         4         1         2         0         4         4         4         3         3         5         2        11        11        11
       323        17         3         1         1         3         0         3         4         3         2         3         5         1        12        14        15
       326        17         3         3         1         1         0         4         3         5         3         5         5         3        14        15        16
       327        17         2         2         4         1         0         4         4         5         5         5         4         8        11        10        10
       328        17         4         4         1         3         0         5         4         4         1         3         4         7        10         9         9
       330        18         2         2         1         4         0         4         5         5         2         4         5         2         9         8         8
       335        17         3         4         1         3         0         4         4         5         1         3         5        16        16        15        15
       347        18         4         3         1         3         0         5         4         5         2         3         5         0        10        10         9
       349        18         3         2         2         1         1         2         5         5         5         5         5        10        11        13        13
       351        17         3         3         2         2         0         4         5         4         2         3         3         2        13        13        13
       354        17         4         3         2         2         0         4         5         5         1         3         2         4        13        11        11
       360        18         1         4         3         2         0         4         3         4         1         4         5         0        13        13        13
       365        18         1         3         2         2         0         3         3         4         2         4         3         4        10        10        10
       369        18         4         4         3         2         0         3         2         2         4         2         5        10        14        12        11
       377        18         4         4         1         2         0         5         4         3         3         4         2         4         8         9        10
       379        17         3         1         1         2         0         4         5         4         2         3         1        17        10        10        10
       380        18         4         4         1         2         0         3         2         4         1         4         2         4        15        14        14
       384        18         4         2         2         1         1         5         4         3         4         3         3        14         6         5         5
       386        18         4         4         3         1         0         4         4         3         2         2         5         7         6         5         6
       390        20         2         2         1         2         2         5         5         4         4         5         4        11         9         9         9
       391        17         3         1         2         1         0         2         4         5         3         4         2         3        14        16        16
       393        18         3         2         3         1         0         4         4         1         3         4         5         0        11        12        10
########################################
CLUSTER 3 (Size = 194 data points)
########################################
                 age      Medu      Fedutraveltime studytime  failures    famrel  freetime     goout      Dalc      Walc    health  absences        G1        G2        G3
  CENTROID     16.47      3.06      2.77      1.35      2.31      0.10      4.04      3.03      2.66      1.09      1.52      3.25      5.36     12.30     12.39     12.43
        71        15         4         2         1         4         0         3         3         3         1         1         3         0        10        10        10
         0        18         4         4         2         2         0         4         3         4         1         1         3         6         5         6         6
         3        15         4         2         1         3         0         3         2         2         1         1         5         2        15        14        15
         4        16         3         3         1         2         0         4         3         2         1         2         5         4         6        10        10
         5        16         4         3         1         2         0         5         4         2         1         2         5        10        15        15        15
         6        16         2         2         1         2         0         4         4         4         1         1         3         0        12        12        11
         7        17         4         4         2         2         0         4         1         4         1         1         1         6         6         5         6
         8        15         3         2         1         2         0         4         2         2         1         1         1         0        16        18        19
         9        15         3         4         1         2         0         5         5         1         1         1         5         0        14        15        15
        10        15         4         4         1         2         0         3         3         3         1         2         2         0        10         8         9
        11        15         2         1         3         3         0         5         2         2         1         1         4         4        10        12        12
        13        15         4         3         2         2         0         5         4         3         1         2         3         2        10        10        11
        14        15         2         2         1         3         0         4         5         2         1         1         3         0        14        16        16
        15        16         4         4         1         1         0         4         4         4         1         2         2         4        14        14        14
        16        16         4         4         1         3         0         3         2         3         1         2         2         6        13        14        14
        17        16         3         3         3         2         0         5         3         2         1         1         4         4         8        10        10
        20        15         4         3         1         2         0         4         4         1         1         1         1         0        13        14        15
        21        15         4         4         1         1         0         5         4         2         1         1         5         0        12        15        15
        22        16         4         2         1         2         0         4         5         1         1         3         5         2        15        15        16
        24        15         2         4         1         3         0         4         3         2         1         1         5         2        10         9         8
        26        15         2         2         1         1         0         4         2         2         1         2         5         2        12        12        11
        28        16         3         4         1         2         0         5         3         3         1         1         5         4        11        11        11
        31        15         4         4         2         2         0         4         3         1         1         1         5         0        17        16        17
        32        15         4         3         1         2         0         4         5         2         1         1         5         0        17        16        16
        33        15         3         3         1         2         0         5         3         2         1         1         2         0         8        10        12
        34        16         3         2         1         1         0         5         4         3         1         1         5         0        12        14        15
        36        15         4         3         1         3         0         5         4         3         1         1         4         2        15        16        18
        37        16         4         4         2         3         0         2         4         3         1         1         5         7        15        16        15
        38        15         3         4         1         3         0         4         3         2         1         1         5         2        12        12        11
        39        15         2         2         1         1         0         4         3         1         1         1         2         8        14        13        13
        42        15         4         4         1         2         0         4         3         3         1         1         5         2        19        18        18
        43        15         2         2         1         1         0         5         4         1         1         1         1         0         8         8        11
        45        15         4         3         1         2         0         5         2         2         1         1         5         8         8         8         6
        47        16         4         3         1         4         0         4         2         2         1         1         2         4        19        19        20
        48        15         4         2         1         2         0         4         3         3         2         2         5         2        15        15        14
        49        15         4         4         1         2         1         4         4         4         1         1         3         2         7         7         7
        51        15         4         2         1         2         0         4         3         3         1         1         5         2        11        13        13
        55        16         2         1         1         2         0         5         3         4         1         1         2         8         8         9        10
        56        15         4         3         1         2         0         4         3         2         1         1         1         0        14        15        15
        57        15         4         4         1         2         0         3         2         2         1         1         5         4        14        15        15
        59        16         4         2         1         2         0         4         2         3         1         1         5         2        15        16        16
        62        16         1         2         1         2         0         4         4         3         1         1         1         4         8        10         9
        65        16         4         3         3         2         0         5         4         3         1         2         1         2        16        15        15
        69        15         3         1         2         4         0         4         4         2         2         3         3        12        16        16        16
        70        16         3         1         2         4         0         4         3         2         1         1         5         0        13        15        15
        73        16         3         1         1         1         0         5         3         2         2         2         5         2        12        12        14
        76        15         4         0         2         4         0         3         4         3         1         1         1         8        11        11        10
        77        16         2         2         1         4         0         5         2         3         1         3         3         0        11        11        11
        79        16         3         4         1         2         0         2         4         3         1         2         3        12         5         5         5
        80        15         2         3         1         1         0         3         2         2         1         3         3         2        10        12        12
        81        15         2         3         1         3         0         5         3         2         1         2         5         4        11        10        11
        83        15         2         2         2         2         0         5         3         3         1         3         4         4        15        15        15
        87        15         4         2         1         3         0         5         3         3         1         3         1         4        13        14        14
        88        16         2         2         2         2         1         4         4         2         1         1         3        12        11        10        10
        90        16         3         3         1         3         0         4         3         3         1         3         4         0         7         7         8
        93        16         4         2         2         2         0         5         3         3         1         1         1         0        11        10        10
        94        15         2         2         1         4         0         4         3         4         1         1         4         6        11        13        14
        95        15         1         1         2         4         1         3         1         2         1         1         1         2         7        10        10
        96        16         4         3         2         1         0         3         3         3         1         1         4         2        11        15        15
        98        16         4         4         1         1         0         5         3         4         1         2         1         6        11        14        14
        99        16         4         3         1         3         0         5         3         5         1         1         3         0         7         9         8
       101        16         4         4         1         3         0         4         4         3         1         1         4         0        16        17        17
       102        15         4         4         1         1         0         5         3         3         1         1         5         4        10        13        14
       103        15         3         2         2         2         0         4         3         5         1         1         2        26         7         6         6
       104        15         3         4         1         2         0         5         4         4         1         1         1         0        16        18        18
       105        15         3         3         1         4         0         4         3         3         1         1         4        10        10        11        11
       106        15         2         2         1         4         0         5         1         2         1         1         3         8         7         8         8
       107        16         3         3         1         3         0         5         3         3         1         1         5         2        16        18        18
       109        16         4         4         1         3         0         5         4         5         1         1         4         4        14        15        16
       110        15         4         4         1         1         0         5         5         3         1         1         4         6        18        19        19
       111        16         3         3         1         3         1         4         1         2         1         1         2         0         7        10        10
       112        16         2         2         1         2         1         3         1         2         1         1         5         6        10        13        13
       113        15         4         2         1         1         0         3         5         2         1         1         3        10        18        19        19
       115        16         4         4         1         2         0         5         4         4         1         2         5         2        15        15        16
       116        15         4         4         2         2         0         4         4         3         1         1         2         2        11        13        14
       117        16         3         3         2         1         0         5         4         2         1         1         5         0        13        14        13
       119        15         3         4         1         1         0         3         4         3         1         2         4         6        14        13        13
       120        15         1         2         1         2         0         3         2         3         1         2         1         2        16        15        15
       121        15         2         2         1         4         0         5         5         4         1         2         5         6        16        14        15
       122        16         2         4         2         2         0         4         2         2         1         2         5         2        13        13        13
       126        15         3         4         1         2         0         5         3         2         1         1         1         0         7        10        11
       135        15         4         4         1         3         0         4         3         3         1         1         5         0        11         0         0
       139        15         4         4         2         1         0         4         3         2         1         1         5         0        16        16        15
       140        15         4         3         2         4         0         2         2         2         1         1         3         0         7         9         0
       142        15         4         4         1         3         0         4         2         2         1         1         5         2         9        11        11
       148        16         4         4         1         1         0         3         3         2         2         1         5         0         7         6         0
       152        15         3         3         2         3         2         4         2         1         2         3         3         8        10        10        10
       154        17         4         4         1         1         0         4         2         1         1         1         4         0        11        11        12
       155        15         2         3         1         2         0         4         4         4         1         1         1         2        11         8         8
       158        16         2         2         3         1         0         4         2         2         1         2         3         2        17        15        15
       165        16         3         2         2         1         1         4         5         2         1         1         2        16        12        11        12
       167        16         4         2         1         2         0         4         2         3         1         1         3         0        14        15        16
       169        16         4         4         1         2         0         4         4         2         1         1         3         0        14        14        14
       171        16         1         0         2         2         0         4         3         2         1         1         3         2        13        15        16
       174        16         3         3         2         2         0         4         4         5         1         1         4         4        10        11         9
       179        17         4         3         1         2         0         5         2         3         1         1         2         4        10        10        11
       181        16         3         3         1         2         0         4         2         3         1         2         3         2        12        13        12
       182        17         2         4         1         2         0         5         4         2         2         3         5         0        16        17        17
       183        17         3         3         1         2         0         5         3         3         2         3         1        56         9         9         8
       184        16         3         2         1         2         0         1         2         2         1         2         1        14        12        13        12
       187        16         2         1         1         2         0         4         2         3         1         2         5         0        15        15        15
       188        17         3         3         1         2         0         3         3         3         1         3         3         6         8         7         9
       190        16         2         3         1         2         0         4         3         3         1         1         2        10        11        12        13
       194        16         2         3         2         1         0         5         3         3         1         1         3         0        13        14        14
       195        17         2         4         1         2         0         4         3         2         1         1         5         0        14        15        15
       196        17         4         4         1         1         0         5         2         3         1         2         5         4        17        15        16
       199        16         4         4         1         2         0         4         5         2         1         2         3         0         9         9        10
       204        16         2         2         2         4         0         5         3         5         1         1         5         6        10        10        11
       207        16         4         3         1         2         0         1         3         2         1         1         1        10        11        12        13
       209        17         4         3         2         3         0         4         4         2         1         1         4         6         7         7         7
       210        19         3         3         1         4         0         4         3         3         1         2         3        10         8         8         8
       212        16         2         2         1         2         0         3         3         4         1         1         4         0        12        13        14
       214        17         4         4         1         1         0         5         2         1         1         2         3        12         8        10        10
       215        17         3         2         2         2         0         4         4         4         1         3         1         2        14        15        15
       219        17         2         2         1         3         0         4         3         3         1         1         4         4         9        10        10
       222        16         2         3         1         2         0         2         3         1         1         1         3         2        16        16        17
       224        16         4         4         1         3         0         5         3         2         1         1         5         0        13        13        14
       226        17         3         2         1         2         0         5         3         4         1         3         3        10        16        15        15
       227        17         2         3         1         2         0         5         3         3         1         3         3         2        12        11        12
       229        17         2         1         2         3         0         3         2         3         1         2         3        10        12        10        12
       230        17         4         3         1         2         0         3         2         3         1         2         3        14        13        13        14
       231        17         2         2         2         2         0         4         5         2         1         1         1         4        11        11        11
       235        16         3         2         2         3         0         5         3         3         1         3         2        10        11         9        10
       238        17         2         1         3         2         0         2         1         1         1         1         3         2        13        11        11
       243        16         4         4         1         1         0         5         3         2         1         2         5         0        13        12        12
       245        16         2         1         3         1         0         4         3         3         1         1         4         6        18        18        18
       246        17         2         3         2         1         0         5         2         2         1         1         2         4        12        12        13
       251        16         3         3         3         2         0         5         3         3         1         3         2         6         7        10        10
       256        17         4         2         1         4         0         4         2         3         1         1         4         6        14        12        13
       257        19         4         3         1         2         0         4         3         1         1         1         1        12        11        11        11
       258        18         2         1         1         2         0         5         2         4         1         2         4         8        15        14        14
       259        17         2         2         1         4         0         3         4         1         1         1         2         0        10         9         0
       260        18         4         3         1         2         0         3         1         2         1         3         2        21        17        18        18
       261        18         4         3         1         2         0         4         3         2         1         1         3         2         8         8         8
       262        18         3         2         1         3         0         5         3         2         1         1         3         1        13        12        12
       263        17         3         3         1         3         0         3         2         3         1         1         4         4        10         9         9
       273        17         1         2         1         2         0         3         5         2         2         2         1         2        15        14        14
       274        17         2         4         2         2         0         4         3         3         1         1         1         2        10        10        10
       276        18         3         2         2         2         0         4         1         1         1         1         5        75        10         9         9
       278        18         4         4         1         2         1         2         4         4         1         1         4        15         9         8         8
       279        18         4         3         2         1         0         4         2         3         1         2         1         8        10        11        10
       282        18         1         1         2         4         0         5         2         2         1         1         3         1        12        12        12
       286        18         2         2         1         3         0         4         3         3         1         2         2         5        18        18        19
       287        17         1         1         1         3         0         4         3         3         1         1         3         6        13        12        12
       288        18         2         1         1         3         0         4         2         4         1         3         2         6        15        14        14
       289        18         4         4         1         2         0         5         4         3         1         1         2         9        15        13        15
       291        17         4         3         1         3         0         4         2         2         1         2         3         0        15        15        15
       293        17         3         1         2         4         0         3         1         2         1         1         3         6        18        18        18
       294        18         3         2         2         3         0         5         4         2         1         1         4         8        14        13        14
       298        18         4         3         1         4         0         4         3         3         1         1         3         0        14        13        14
       299        18         4         4         1         1         0         1         4         2         2         2         1         5        16        15        16
       300        18         4         4         1         2         0         4         2         4         1         1         4        14        12        10        11
       301        17         4         4         2         1         0         4         1         1         2         2         5         0        11        11        10
       302        17         4         2         2         3         0         4         3         3         1         1         3         0        15        12        14
       303        17         3         2         1         4         0         5         2         2         1         2         5         0        17        17        18
       304        19         3         3         1         2         1         4         4         4         1         1         3        20        15        14        13
       305        18         2         4         1         2         1         4         4         3         1         1         3         8        14        12        12
       306        20         3         2         1         1         0         5         5         3         1         1         5         0        17        18        18
       307        19         4         4         2         1         1         4         3         4         1         1         4        38         8         9         8
       308        19         3         3         1         2         1         4         5         3         1         2         5         0        15        12        12
       311        19         2         1         3         2         0         3         4         1         1         1         2        20        14        12        13
       313        19         3         2         2         2         1         4         2         2         1         2         1        22        13        10        11
       315        19         2         3         1         3         1         4         1         2         1         1         3        40        13        11        11
       317        18         4         3         1         3         0         4         3         4         1         1         5         9         9        10         9
       320        17         4         3         1         2         0         5         2         2         1         2         5        23        13        13        13
       321        17         2         2         1         2         0         4         2         2         1         1         3        12        11         9         9
       322        17         2         2         1         3         0         3         3         2         2         2         3         3        11        11        11
       324        17         0         2         2         3         0         3         3         3         2         3         2         0        16        15        15
       325        18         4         4         1         3         0         4         3         3         2         2         3         3         9        12        11
       329        17         4         4         2         3         0         4         3         3         1         2         4         4        14        14        14
       331        17         2         4         1         3         0         4         4         3         1         1         5         7        12        14        14
       336        19         3         1         1         3         1         5         4         3         1         2         5        12        14        13        13
       338        18         3         3         1         4         0         5         3         3         1         1         1         7        16        15        17
       339        17         3         2         1         2         0         4         3         3         2         3         2         4         9        10        10
       341        18         4         4         1         2         1         4         3         3         2         2         2         0        10        10         0
       342        18         3         4         1         2         0         4         3         3         1         3         5        11        16        15        15
       344        18         2         3         1         3         0         4         3         3         1         2         3         4        11        10        10
       345        18         3         2         1         3         0         5         4         3         2         3         1         7        13        13        14
       346        18         4         3         1         3         0         5         3         2         1         2         4         9        16        15        16
       348        17         4         3         1         3         0         4         4         3         1         3         4         0        13        15        15
       355        18         3         3         1         2         0         5         3         4         1         1         5         0        10         9         9
       356        17         4         4         2         2         0         4         3         3         1         2         5         4        12        13        13
       357        17         3         2         2         2         0         1         2         3         1         2         5         2        12        12        11
       359        18         1         1         2         3         0         5         3         2         1         1         4         0        18        16        16
       362        18         3         3         2         2         0         4         3         2         1         3         3         0        11        11        10
       363        17         4         4         1         2         0         2         3         4         1         1         1         0        16        15        15
       364        17         1         2         2         2         0         3         2         2         1         2         3         0        12        11        12
       366        18         4         4         2         3         0         4         2         2         2         2         5         0        13        13        13
       372        17         2         2         1         3         0         3         4         3         1         1         3         8        13        11        11
       374        18         4         4         2         3         0         5         4         4         1         1         1         0        19        18        19
       376        20         4         2         2         3         2         5         4         3         1         1         3         4        15        14        15
       378        18         3         3         1         2         0         4         1         3         1         2         1         0        15        15        15
       382        17         2         3         2         2         0         4         4         3         1         1         3         2        11        11        10
       388        18         3         1         1         2         0         4         3         4         1         1         1         0         7         9         8

</code>
  </pre>
</figure>
    
        <h5><center>Test 2 (7 Clusters):</center></h5>

    <figure>
  <figcaption>Your code title</figcaption>
  <pre>
    <code>
HOW MANY CLUSTERS DO YOU WANT? 7
########################################
CLUSTER 1 (Size = 52 data points)
########################################
                 age      Medu      Fedutraveltime studytime  failures    famrel  freetime     goout      Dalc      Walc    health  absences        G1        G2        G3
  CENTROID     16.96      2.50      1.90      1.40      3.40      0.21      4.00      2.83      2.69      1.10      1.54      3.19      5.08     12.15     11.79     11.40
       376        20         4         2         2         3         2         5         4         3         1         1         3         4        15        14        15
        11        15         2         1         3         3         0         5         2         2         1         1         4         4        10        12        12
        14        15         2         2         1         3         0         4         5         2         1         1         3         0        14        16        16
        47        16         4         3         1         4         0         4         2         2         1         1         2         4        19        19        20
        67        16         3         1         1         4         0         4         3         3         1         2         5         4         7         7         6
        69        15         3         1         2         4         0         4         4         2         2         3         3        12        16        16        16
        71        15         4         2         1         4         0         3         3         3         1         1         3         0        10        10        10
        76        15         4         0         2         4         0         3         4         3         1         1         1         8        11        11        10
        77        16         2         2         1         4         0         5         2         3         1         3         3         0        11        11        11
        90        16         3         3         1         3         0         4         3         3         1         3         4         0         7         7         8
        94        15         2         2         1         4         0         4         3         4         1         1         4         6        11        13        14
        95        15         1         1         2         4         1         3         1         2         1         1         1         2         7        10        10
       105        15         3         3         1         4         0         4         3         3         1         1         4        10        10        11        11
       106        15         2         2         1         4         0         5         1         2         1         1         3         8         7         8         8
       111        16         3         3         1         3         1         4         1         2         1         1         2         0         7        10        10
       121        15         2         2         1         4         0         5         5         4         1         2         5         6        16        14        15
       140        15         4         3         2         4         0         2         2         2         1         1         3         0         7         9         0
       152        15         3         3         2         3         2         4         2         1         2         3         3         8        10        10        10
       204        16         2         2         2         4         0         5         3         5         1         1         5         6        10        10        11
       210        19         3         3         1         4         0         4         3         3         1         2         3        10         8         8         8
       219        17         2         2         1         3         0         4         3         3         1         1         4         4         9        10        10
       229        17         2         1         2         3         0         3         2         3         1         2         3        10        12        10        12
       244        18         2         1         2         3         0         4         4         4         1         1         3         0         7         0         0
       256        17         4         2         1         4         0         4         2         3         1         1         4         6        14        12        13
       259        17         2         2         1         4         0         3         4         1         1         1         2         0        10         9         0
       262        18         3         2         1         3         0         5         3         2         1         1         3         1        13        12        12
       263        17         3         3         1         3         0         3         2         3         1         1         4         4        10         9         9
       264        18         2         2         1         3         0         4         3         3         1         1         3         0         9        10         0
       282        18         1         1         2         4         0         5         2         2         1         1         3         1        12        12        12
       286        18         2         2         1         3         0         4         3         3         1         2         2         5        18        18        19
       287        17         1         1         1         3         0         4         3         3         1         1         3         6        13        12        12
       288        18         2         1         1         3         0         4         2         4         1         3         2         6        15        14        14
       291        17         4         3         1         3         0         4         2         2         1         2         3         0        15        15        15
       293        17         3         1         2         4         0         3         1         2         1         1         3         6        18        18        18
       294        18         3         2         2         3         0         5         4         2         1         1         4         8        14        13        14
       298        18         4         3         1         4         0         4         3         3         1         1         3         0        14        13        14
       302        17         4         2         2         3         0         4         3         3         1         1         3         0        15        12        14
       303        17         3         2         1         4         0         5         2         2         1         2         5         0        17        17        18
       311        19         2         1         3         2         0         3         4         1         1         1         2        20        14        12        13
       314        19         1         1         1         3         2         4         1         2         1         1         3        14        15        13        13
       315        19         2         3         1         3         1         4         1         2         1         1         3        40        13        11        11
       322        17         2         2         1         3         0         3         3         2         2         2         3         3        11        11        11
       323        17         3         1         1         3         0         3         4         3         2         3         5         1        12        14        15
       324        17         0         2         2         3         0         3         3         3         2         3         2         0        16        15        15
       334        18         2         2         2         4         0         4         4         4         1         1         4         0        10         9         0
       336        19         3         1         1         3         1         5         4         3         1         2         5        12        14        13        13
       338        18         3         3         1         4         0         5         3         3         1         1         1         7        16        15        17
       340        19         2         1         1         3         1         4         3         4         1         3         3         4        11        12        11
       344        18         2         3         1         3         0         4         3         3         1         2         3         4        11        10        10
       359        18         1         1         2         3         0         5         3         2         1         1         4         0        18        16        16
       372        17         2         2         1         3         0         3         4         3         1         1         3         8        13        11        11
       385        18         2         2         2         3         0         5         3         3         1         3         4         2        10         9        10
########################################
CLUSTER 2 (Size = 80 data points)
########################################
                 age      Medu      Fedutraveltime studytime  failures    famrel  freetime     goout      Dalc      Walc    health  absences        G1        G2        G3
  CENTROID     16.30      3.61      3.42      1.29      2.05      0.04      4.09      3.21      2.65      1.14      1.61      4.59      4.45     12.68     12.69     12.68
        70        16         3         1         2         4         0         4         3         2         1         1         5         0        13        15        15
         3        15         4         2         1         3         0         3         2         2         1         1         5         2        15        14        15
         4        16         3         3         1         2         0         4         3         2         1         2         5         4         6        10        10
         5        16         4         3         1         2         0         5         4         2         1         2         5        10        15        15        15
         9        15         3         4         1         2         0         5         5         1         1         1         5         0        14        15        15
        12        15         4         4         1         1         0         4         3         3         1         3         5         2        14        14        14
        17        16         3         3         3         2         0         5         3         2         1         1         4         4         8        10        10
        19        16         4         3         1         1         0         3         1         3         1         3         5         4         8        10        10
        21        15         4         4         1         1         0         5         4         2         1         1         5         0        12        15        15
        22        16         4         2         1         2         0         4         5         1         1         3         5         2        15        15        16
        24        15         2         4         1         3         0         4         3         2         1         1         5         2        10         9         8
        28        16         3         4         1         2         0         5         3         3         1         1         5         4        11        11        11
        31        15         4         4         2         2         0         4         3         1         1         1         5         0        17        16        17
        32        15         4         3         1         2         0         4         5         2         1         1         5         0        17        16        16
        34        16         3         2         1         1         0         5         4         3         1         1         5         0        12        14        15
        36        15         4         3         1         3         0         5         4         3         1         1         4         2        15        16        18
        37        16         4         4         2         3         0         2         4         3         1         1         5         7        15        16        15
        38        15         3         4         1         3         0         4         3         2         1         1         5         2        12        12        11
        42        15         4         4         1         2         0         4         3         3         1         1         5         2        19        18        18
        45        15         4         3         1         2         0         5         2         2         1         1         5         8         8         8         6
        48        15         4         2         1         2         0         4         3         3         2         2         5         2        15        15        14
        51        15         4         2         1         2         0         4         3         3         1         1         5         2        11        13        13
        57        15         4         4         1         2         0         3         2         2         1         1         5         4        14        15        15
        59        16         4         2         1         2         0         4         2         3         1         1         5         2        15        16        16
        75        15         4         3         1         2         0         4         3         3         2         3         5         6         9         9        10
        81        15         2         3         1         3         0         5         3         2         1         2         5         4        11        10        11
        96        16         4         3         2         1         0         3         3         3         1         1         4         2        11        15        15
       101        16         4         4         1         3         0         4         4         3         1         1         4         0        16        17        17
       102        15         4         4         1         1         0         5         3         3         1         1         5         4        10        13        14
       107        16         3         3         1         3         0         5         3         3         1         1         5         2        16        18        18
       109        16         4         4         1         3         0         5         4         5         1         1         4         4        14        15        16
       110        15         4         4         1         1         0         5         5         3         1         1         4         6        18        19        19
       113        15         4         2         1         1         0         3         5         2         1         1         3        10        18        19        19
       115        16         4         4         1         2         0         5         4         4         1         2         5         2        15        15        16
       117        16         3         3         2         1         0         5         4         2         1         1         5         0        13        14        13
       119        15         3         4         1         1         0         3         4         3         1         2         4         6        14        13        13
       122        16         2         4         2         2         0         4         2         2         1         2         5         2        13        13        13
       133        16         3         4         1         1         0         3         2         1         1         4         5        16        12        11        11
       134        15         3         4         4         2         0         5         3         3         1         1         5         0         9         0         0
       135        15         4         4         1         3         0         4         3         3         1         1         5         0        11         0         0
       139        15         4         4         2         1         0         4         3         2         1         1         5         0        16        16        15
       142        15         4         4         1         3         0         4         2         2         1         1         5         2         9        11        11
       148        16         4         4         1         1         0         3         3         2         2         1         5         0         7         6         0
       154        17         4         4         1         1         0         4         2         1         1         1         4         0        11        11        12
       167        16         4         2         1         2         0         4         2         3         1         1         3         0        14        15        16
       169        16         4         4         1         2         0         4         4         2         1         1         3         0        14        14        14
       172        17         4         4         1         2         0         4         4         4         1         3         5         0        13        11        10
       174        16         3         3         2         2         0         4         4         5         1         1         4         4        10        11         9
       195        17         2         4         1         2         0         4         3         2         1         1         5         0        14        15        15
       196        17         4         4         1         1         0         5         2         3         1         2         5         4        17        15        16
       199        16         4         4         1         2         0         4         5         2         1         2         3         0         9         9        10
       209        17         4         3         2         3         0         4         4         2         1         1         4         6         7         7         7
       222        16         2         3         1         2         0         2         3         1         1         1         3         2        16        16        17
       224        16         4         4         1         3         0         5         3         2         1         1         5         0        13        13        14
       230        17         4         3         1         2         0         3         2         3         1         2         3        14        13        13        14
       241        17         4         4         2         2         0         3         3         3         2         3         4         2        10        11        12
       243        16         4         4         1         1         0         5         3         2         1         2         5         0        13        12        12
       267        18         4         4         2         2         0         4         3         4         2         2         4         8        12        10        11
       278        18         4         4         1         2         1         2         4         4         1         1         4        15         9         8         8
       290        18         4         2         1         2         0         4         3         2         1         4         5        11        12        11        11
       295        17         3         3         1         1         0         4         4         3         1         3         5         4        14        12        11
       300        18         4         4         1         2         0         4         2         4         1         1         4        14        12        10        11
       301        17         4         4         2         1         0         4         1         1         2         2         5         0        11        11        10
       306        20         3         2         1         1         0         5         5         3         1         1         5         0        17        18        18
       307        19         4         4         2         1         1         4         3         4         1         1         4        38         8         9         8
       308        19         3         3         1         2         1         4         5         3         1         2         5         0        15        12        12
       317        18         4         3         1         3         0         4         3         4         1         1         5         9         9        10         9
       320        17         4         3         1         2         0         5         2         2         1         2         5        23        13        13        13
       325        18         4         4         1         3         0         4         3         3         2         2         3         3         9        12        11
       328        17         4         4         1         3         0         5         4         4         1         3         4         7        10         9         9
       329        17         4         4         2         3         0         4         3         3         1         2         4         4        14        14        14
       331        17         2         4         1         3         0         4         4         3         1         1         5         7        12        14        14
       335        17         3         4         1         3         0         4         4         5         1         3         5        16        16        15        15
       342        18         3         4         1         2         0         4         3         3         1         3         5        11        16        15        15
       346        18         4         3         1         3         0         5         3         2         1         2         4         9        16        15        16
       348        17         4         3         1         3         0         4         4         3         1         3         4         0        13        15        15
       355        18         3         3         1         2         0         5         3         4         1         1         5         0        10         9         9
       356        17         4         4         2         2         0         4         3         3         1         2         5         4        12        13        13
       366        18         4         4         2         3         0         4         2         2         2         2         5         0        13        13        13
       369        18         4         4         3         2         0         3         2         2         4         2         5        10        14        12        11
########################################
CLUSTER 3 (Size = 67 data points)
########################################
                 age      Medu      Fedutraveltime studytime  failures    famrel  freetime     goout      Dalc      Walc    health  absences        G1        G2        G3
  CENTROID     16.51      2.85      2.75      1.34      1.84      0.10      4.13      3.15      2.91      1.09      1.66      1.88      5.45     10.19     10.15      9.76
       388        18         3         1         1         2         0         4         3         4         1         1         1         0         7         9         8
         0        18         4         4         2         2         0         4         3         4         1         1         3         6         5         6         6
         6        16         2         2         1         2         0         4         4         4         1         1         3         0        12        12        11
         7        17         4         4         2         2         0         4         1         4         1         1         1         6         6         5         6
         8        15         3         2         1         2         0         4         2         2         1         1         1         0        16        18        19
        10        15         4         4         1         2         0         3         3         3         1         2         2         0        10         8         9
        13        15         4         3         2         2         0         5         4         3         1         2         3         2        10        10        11
        20        15         4         3         1         2         0         4         4         1         1         1         1         0        13        14        15
        33        15         3         3         1         2         0         5         3         2         1         1         2         0         8        10        12
        39        15         2         2         1         1         0         4         3         1         1         1         2         8        14        13        13
        43        15         2         2         1         1         0         5         4         1         1         1         1         0         8         8        11
        49        15         4         4         1         2         1         4         4         4         1         1         3         2         7         7         7
        55        16         2         1         1         2         0         5         3         4         1         1         2         8         8         9        10
        56        15         4         3         1         2         0         4         3         2         1         1         1         0        14        15        15
        62        16         1         2         1         2         0         4         4         3         1         1         1         4         8        10         9
        65        16         4         3         3         2         0         5         4         3         1         2         1         2        16        15        15
        79        16         3         4         1         2         0         2         4         3         1         2         3        12         5         5         5
        80        15         2         3         1         1         0         3         2         2         1         3         3         2        10        12        12
        86        16         2         2         1         2         0         4         3         4         1         2         2         4         8         7         6
        92        16         3         1         1         2         0         3         3         3         2         3         2         4         7         6         6
        93        16         4         2         2         2         0         5         3         3         1         1         1         0        11        10        10
        98        16         4         4         1         1         0         5         3         4         1         2         1         6        11        14        14
        99        16         4         3         1         3         0         5         3         5         1         1         3         0         7         9         8
       103        15         3         2         2         2         0         4         3         5         1         1         2        26         7         6         6
       104        15         3         4         1         2         0         5         4         4         1         1         1         0        16        18        18
       116        15         4         4         2         2         0         4         4         3         1         1         2         2        11        13        14
       120        15         1         2         1         2         0         3         2         3         1         2         1         2        16        15        15
       126        15         3         4         1         2         0         5         3         2         1         1         1         0         7        10        11
       155        15         2         3         1         2         0         4         4         4         1         1         1         2        11         8         8
       163        17         1         3         1         1         0         5         3         3         1         4         2         2        10        10        10
       165        16         3         2         2         1         1         4         5         2         1         1         2        16        12        11        12
       179        17         4         3         1         2         0         5         2         3         1         1         2         4        10        10        11
       181        16         3         3         1         2         0         4         2         3         1         2         3         2        12        13        12
       183        17         3         3         1         2         0         5         3         3         2         3         1        56         9         9         8
       184        16         3         2         1         2         0         1         2         2         1         2         1        14        12        13        12
       188        17         3         3         1         2         0         3         3         3         1         3         3         6         8         7         9
       190        16         2         3         1         2         0         4         3         3         1         1         2        10        11        12        13
       194        16         2         3         2         1         0         5         3         3         1         1         3         0        13        14        14
       202        17         1         1         1         2         0         4         4         4         1         3         1         4         9         9        10
       207        16         4         3         1         2         0         1         3         2         1         1         1        10        11        12        13
       214        17         4         4         1         1         0         5         2         1         1         2         3        12         8        10        10
       227        17         2         3         1         2         0         5         3         3         1         3         3         2        12        11        12
       231        17         2         2         2         2         0         4         5         2         1         1         1         4        11        11        11
       235        16         3         2         2         3         0         5         3         3         1         3         2        10        11         9        10
       242        16         4         3         1         1         0         5         4         5         1         1         3         0         6         0         0
       246        17         2         3         2         1         0         5         2         2         1         1         2         4        12        12        13
       251        16         3         3         3         2         0         5         3         3         1         3         2         6         7        10        10
       257        19         4         3         1         2         0         4         3         1         1         1         1        12        11        11        11
       261        18         4         3         1         2         0         4         3         2         1         1         3         2         8         8         8
       273        17         1         2         1         2         0         3         5         2         2         2         1         2        15        14        14
       274        17         2         4         2         2         0         4         3         3         1         1         1         2        10        10        10
       279        18         4         3         2         1         0         4         2         3         1         2         1         8        10        11        10
       289        18         4         4         1         2         0         5         4         3         1         1         2         9        15        13        15
       304        19         3         3         1         2         1         4         4         4         1         1         3        20        15        14        13
       305        18         2         4         1         2         1         4         4         3         1         1         3         8        14        12        12
       313        19         3         2         2         2         1         4         2         2         1         2         1        22        13        10        11
       316        18         2         1         2         2         0         5         3         3         1         2         1         0         8         8         0
       332        18         3         3         1         2         0         5         3         4         1         1         4         0         7         0         0
       333        18         2         2         1         2         0         4         3         3         1         1         2         0         8         8         0
       337        17         3         2         1         2         0         4         3         2         2         3         2         0         7         8         0
       339        17         3         2         1         2         0         4         3         3         2         3         2         4         9        10        10
       341        18         4         4         1         2         1         4         3         3         2         2         2         0        10        10         0
       362        18         3         3         2         2         0         4         3         2         1         3         3         0        11        11        10
       367        17         1         1         3         1         1         5         2         1         1         2         1         0         7         6         0
       373        17         1         2         1         1         0         3         5         5         1         3         1        14         6         5         5
       378        18         3         3         1         2         0         4         1         3         1         2         1         0        15        15        15
       382        17         2         3         2         2         0         4         4         3         1         1         3         2        11        11        10
########################################
CLUSTER 4 (Size = 35 data points)
########################################
                 age      Medu      Fedutraveltime studytime  failures    famrel  freetime     goout      Dalc      Walc    health  absences        G1        G2        G3
  CENTROID     16.74      3.60      3.11      1.37      2.03      0.09      3.43      3.43      4.03      1.80      3.26      1.74      7.26     13.09     13.11     12.83
       271        18         2         3         1         4         0         4         5         5         1         3         2         4        15        14        14
        15        16         4         4         1         1         0         4         4         4         1         2         2         4        14        14        14
        16        16         4         4         1         3         0         3         2         3         1         2         2         6        13        14        14
        27        15         4         2         1         1         0         2         2         4         2         4         1         4        15        16        15
        46        16         3         3         1         2         0         2         3         5         1         4         3        12        11        12        11
        54        15         3         3         1         1         0         5         3         4         4         4         1         6        10        13        13
        64        15         4         3         1         2         0         4         4         4         2         4         2         0        10        10        10
        66        15         4         4         1         4         0         1         3         3         5         5         3         4        13        13        12
        87        15         4         2         1         3         0         5         3         3         1         3         1         4        13        14        14
        91        15         4         3         1         1         0         4         5         5         1         3         1         4        16        17        18
       108        15         4         4         4         4         0         1         3         5         3         5         1         6        10        13        13
       143        16         1         1         1         1         0         3         4         4         3         3         1         2        14        14        13
       180        16         4         3         1         2         0         3         4         3         2         3         3        10         9         8         8
       198        17         4         4         2         1         1         4         2         4         2         3         2        24        18        18        18
       200        16         4         3         1         2         0         4         3         5         1         5         2         2        16        16        16
       215        17         3         2         2         2         0         4         4         4         1         3         1         2        14        15        15
       216        17         4         3         1         2         2         3         4         5         2         4         1        22         6         6         4
       226        17         3         2         1         2         0         5         3         4         1         3         3        10        16        15        15
       232        17         4         4         1         2         0         4         5         5         1         3         2        14        11         9         9
       233        16         4         4         1         2         0         4         2         4         2         4         1         2        14        13        13
       260        18         4         3         1         2         0         3         1         2         1         3         2        21        17        18        18
       265        18         3         4         2         2         0         4         2         5         3         4         1        13        17        17        17
       277        18         4         4         2         1         0         3         2         4         1         4         3        22         9         9         9
       296        19         4         4         2         2         0         2         3         4         2         3         2         0        10         9         0
       297        18         4         3         2         2         0         4         4         5         1         2         2        10        10         8         8
       299        18         4         4         1         1         0         1         4         2         2         2         1         5        16        15        16
       345        18         3         2         1         3         0         5         4         3         2         3         1         7        13        13        14
       351        17         3         3         2         2         0         4         5         4         2         3         3         2        13        13        13
       354        17         4         3         2         2         0         4         5         5         1         3         2         4        13        11        11
       363        17         4         4         1         2         0         2         3         4         1         1         1         0        16        15        15
       374        18         4         4         2         3         0         5         4         4         1         1         1         0        19        18        19
       377        18         4         4         1         2         0         5         4         3         3         4         2         4         8         9        10
       379        17         3         1         1         2         0         4         5         4         2         3         1        17        10        10        10
       380        18         4         4         1         2         0         3         2         4         1         4         2         4        15        14        14
       391        17         3         1         2         1         0         2         4         5         3         4         2         3        14        16        16
########################################
CLUSTER 5 (Size = 55 data points)
########################################
                 age      Medu      Fedutraveltime studytime  failures    famrel  freetime     goout      Dalc      Walc    health  absences        G1        G2        G3
  CENTROID     16.73      3.00      2.85      1.60      1.73      0.20      3.93      3.80      3.95      2.69      4.05      4.53      8.05     10.29     10.15     10.31
       182        17         2         4         1         2         0         5         4         2         2         3         5         0        16        17        17
        23        16         2         2         2         2         0         5         4         4         2         4         5         0        13        13        12
        29        16         4         4         1         2         0         4         4         5         5         5         5        16        10        12        11
        30        15         4         4         1         2         0         5         4         2         3         4         5         0         9        11        12
        41        15         4         4         1         1         0         5         4         3         2         4         5         8        12        12        12
        52        15         4         2         2         1         1         5         5         5         3         4         5         6        11        11        10
        53        15         4         4         1         1         0         3         3         4         2         3         5         0         8        10        11
        60        16         4         4         1         2         0         2         4         4         2         3         4         6        10        11        11
        61        16         1         1         4         1         0         5         5         5         5         5         5         6        10         8        11
        63        16         4         3         1         3         0         3         4         4         2         4         4         2        10         9         9
        74        16         3         3         1         2         0         4         3         3         2         4         5        54        11        12        11
        85        15         4         4         2         2         2         4         4         4         2         3         5         6         7         9         8
        89        16         4         4         1         2         0         4         1         3         3         5         5        18         8         6         7
       100        16         4         4         1         1         0         4         5         5         5         5         4        14         7         7         5
       123        16         4         4         1         1         0         3         4         4         1         4         5        18        14        11        13
       125        15         3         4         1         1         0         5         5         5         3         2         5         0        13        13        12
       129        16         4         4         1         1         0         3         5         5         2         5         4         8        18        18        18
       136        17         3         4         3         2         0         5         4         5         2         4         5         0        10         0         0
       151        16         2         1         1         1         1         4         4         4         3         5         5         6        12        13        14
       159        16         3         3         1         2         1         4         5         5         4         4         5         4        10        12        12
       166        16         2         2         1         2         0         4         3         5         2         4         4         4        10        10        10
       175        17         4         3         2         2         0         4         4         4         4         4         4         4        10         9         9
       176        16         2         2         2         2         0         3         4         4         1         4         5         2        13        13        11
       177        17         3         3         1         2         0         4         3         4         1         4         4         4         6         5         6
       178        16         4         2         1         1         0         4         3         3         3         4         3        10        10         8         9
       185        17         3         3         1         2         0         4         3         4         2         3         4        12        12        12        11
       192        17         1         2         2         2         0         4         4         4         4         5         5        12         7         8         8
       193        16         3         3         1         1         0         4         3         2         3         4         5         8         8         9        10
       197        16         3         3         3         1         0         3         3         4         3         5         3         8         9         9        10
       205        17         3         4         1         3         1         4         4         3         3         4         5        28        10         9         9
       211        17         4         4         1         2         0         5         3         5         4         5         3        13        12        12        13
       213        18         2         2         1         2         1         4         4         4         2         4         5        15         6         7         8
       217        18         3         3         1         2         1         3         2         4         2         4         4        13         6         6         8
       218        17         2         3         2         1         0         3         3         3         1         4         3         3         7         7         8
       223        18         2         2         2         2         0         3         3         3         5         5         4         0        12        13        13
       228        18         2         1         4         2         0         4         3         2         4         5         3        14        10         8         9
       236        17         2         2         1         2         0         4         4         2         5         5         4         4        14        13        13
       240        17         4         3         2         2         0         2         5         5         1         4         5        14        12        12        12
       250        18         3         2         2         1         1         4         4         5         2         4         5         0         6         8         8
       266        17         3         1         1         2         0         5         4         4         3         4         5         2         9         9        10
       268        18         4         2         1         2         0         5         4         5         1         3         5        10        10         9        10
       275        17         2         2         2         2         0         4         4         4         2         3         5         6        12        12        12
       280        17         4         1         2         1         0         4         5         4         2         4         5        30         8         8         8
       281        17         3         2         1         1         1         4         4         4         3         4         3        19        11         9        10
       318        17         3         4         1         3         0         4         3         4         2         5         5         0        11        11        10
       319        18         4         4         1         2         0         4         4         4         3         3         5         2        11        11        11
       326        17         3         3         1         1         0         4         3         5         3         5         5         3        14        15        16
       327        17         2         2         4         1         0         4         4         5         5         5         4         8        11        10        10
       330        18         2         2         1         4         0         4         5         5         2         4         5         2         9         8         8
       347        18         4         3         1         3         0         5         4         5         2         3         5         0        10        10         9
       349        18         3         2         2         1         1         2         5         5         5         5         5        10        11        13        13
       360        18         1         4         3         2         0         4         3         4         1         4         5         0        13        13        13
       365        18         1         3         2         2         0         3         3         4         2         4         3         4        10        10        10
       386        18         4         4         3         1         0         4         4         3         2         2         5         7         6         5         6
       393        18         3         2         3         1         0         4         4         1         3         4         5         0        11        12        10
########################################
CLUSTER 6 (Size = 40 data points)
########################################
                 age      Medu      Fedutraveltime studytime  failures    famrel  freetime     goout      Dalc      Walc    health  absences        G1        G2        G3
  CENTROID     17.50      1.95      1.82      1.68      1.52      2.08      3.88      3.42      3.55      1.85      2.98      3.90      4.62      7.05      6.08      4.72
       153        19         3         2         1         1         3         4         5         4         1         1         4         0         5         0         0
         2        15         1         1         1         2         3         4         3         2         2         3         3        10         7         8        10
        18        17         3         2         1         1         3         5         5         5         2         4         5        16         6         5         5
        25        16         2         2         1         1         2         1         2         2         1         3         5        14         6         9         8
        72        15         1         1         1         2         2         3         3         4         2         4         5         2         8         6         5
        78        17         2         1         2         1         3         4         5         1         1         1         3         2         8         8        10
       118        17         1         3         3         2         1         5         2         4         1         4         5        20         9         7         8
       127        19         0         1         1         2         3         3         4         2         1         1         5         2         7         8         9
       128        18         2         2         1         1         2         3         3         3         1         2         4         0         7         4         0
       130        15         3         4         2         3         2         4         2         2         2         2         5         0        12         0         0
       137        16         3         3         2         1         2         4         3         2         1         1         5         0         4         0         0
       141        16         2         2         2         1         2         2         3         3         2         2         2         8         9         9         9
       144        17         2         1         1         1         3         5         4         5         1         2         5         0         5         0         0
       146        15         3         2         1         2         3         3         3         2         1         1         3         0         6         7         0
       149        15         2         1         4         1         3         4         5         5         2         5         5         0         8         9        10
       150        18         1         1         1         1         3         2         3         5         2         5         4         0         6         5         0
       157        18         1         1         3         1         3         5         2         5         1         5         4         6         9         8        10
       160        17         2         1         2         1         2         3         3         2         2         2         5         0         7         6         0
       161        15         3         2         2         2         2         4         4         4         1         4         3         6         5         9         7
       162        16         1         2         2         1         1         4         4         4         2         4         5         0         7         0         0
       164        17         1         1         4         2         3         5         3         5         1         5         5         0         5         8         7
       170        16         3         4         3         1         2         3         4         5         2         4         2         0         6         5         0
       173        16         1         3         1         2         3         4         3         5         1         1         3         0         8         7         0
       206        16         3         1         1         2         3         2         3         3         2         2         4         5         7         7         7
       239        18         2         2         1         2         1         5         5         4         3         5         2         0         7         7         0
       247        22         3         1         1         1         3         5         4         5         5         5         1        16         6         8         8
       248        18         3         3         1         2         1         4         3         3         1         3         5         8         3         5         5
       252        18         2         1         1         1         1         3         2         5         2         5         5         4         6         9         8
       269        18         2         1         2         2         0         4         3         5         1         2         3         0         6         0         0
       270        19         3         3         1         2         2         4         3         5         3         3         5        15         9         9         9
       310        19         1         2         1         2         1         4         2         4         2         2         3         0         9         9         0
       350        19         1         1         3         2         3         5         4         4         3         3         2         8         8         7         8
       352        18         1         3         1         1         1         4         3         3         2         3         3         7         8         7         8
       353        19         1         1         3         1         1         4         4         4         3         3         5         4         8         8         8
       370        19         3         2         2         2         2         3         2         2         1         1         3         4         7         7         9
       383        19         1         1         2         1         1         4         3         2         1         3         5         0         6         5         0
       384        18         4         2         2         1         1         5         4         3         4         3         3        14         6         5         5
       387        19         2         3         1         3         1         5         4         2         1         2         5         0         7         5         0
       390        20         2         2         1         2         2         5         5         4         4         5         4        11         9         9         9
       392        21         1         1         1         1         3         5         5         3         3         3         3         3        10         8         7
########################################
CLUSTER 7 (Size = 66 data points)
########################################
                 age      Medu      Fedutraveltime studytime  failures    famrel  freetime     goout      Dalc      Walc    health  absences        G1        G2        G3
  CENTROID     16.62      1.62      1.52      1.56      1.71      0.21      3.86      2.98      2.74      1.20      1.95      4.23      5.88     10.21     10.06      9.82
       237        16         2         1         1         1         0         4         5         2         1         1         5        20        13        12        12
         1        17         1         1         1         2         0         5         3         3         1         1         3         4         5         5         6
        26        15         2         2         1         1         0         4         2         2         1         2         5         2        12        12        11
        35        15         2         3         2         1         0         3         5         1         1         1         5         0         8         7         6
        40        16         2         2         2         2         1         3         3         3         1         2         3        25         7        10        11
        44        16         2         2         2         2         1         4         3         3         2         2         5        14        10        10         9
        50        16         2         2         3         2         0         4         3         3         2         3         4         2        12        13        13
        58        15         1         2         1         2         0         4         3         2         1         1         5         2         9        10         9
        68        15         2         2         2         2         0         4         1         3         1         3         4         2         8         9         8
        73        16         3         1         1         1         0         5         3         2         2         2         5         2        12        12        14
        82        15         3         2         1         2         0         4         4         4         1         1         5        10         7         6         6
        83        15         2         2         2         2         0         5         3         3         1         3         4         4        15        15        15
        84        15         1         1         1         2         0         4         3         2         2         3         4         2         9        10        10
        88        16         2         2         2         2         1         4         4         2         1         1         3        12        11        10        10
        97        16         2         1         1         2         0         4         3         5         1         1         5         2         8         9        10
       112        16         2         2         1         2         1         3         1         2         1         1         5         6        10        13        13
       114        15         2         1         1         2         0         5         4         2         1         1         5         8         9         9         9
       124        16         2         2         1         2         0         5         4         4         1         1         5         0         8         7         8
       131        15         1         1         3         1         0         4         3         3         1         2         4         0         8         0         0
       132        17         2         2         1         1         0         3         4         4         1         3         5        12        10        13        12
       138        16         1         1         1         2         1         4         4         4         1         3         5         0        14        12        12
       145        15         1         1         1         2         0         4         4         2         1         2         5         0         8        11        11
       147        15         1         2         1         2         0         4         3         2         1         1         5         2        10        11        11
       156        17         1         2         1         1         0         2         2         2         3         3         5         8        16        12        13
       158        16         2         2         3         1         0         4         2         2         1         2         3         2        17        15        15
       168        16         2         2         1         2         0         5         1         5         1         1         4         0         6         7         0
       171        16         1         0         2         2         0         4         3         2         1         1         3         2        13        15        16
       186        16         1         2         1         1         0         3         3         3         1         2         3         2        11        12        11
       187        16         2         1         1         2         0         4         2         3         1         2         5         0        15        15        15
       189        17         1         2         1         2         0         3         1         3         1         5         3         4         8         9        10
       191        17         1         1         1         2         0         5         3         3         1         1         3         0         8         8         9
       201        16         2         3         1         2         0         4         4         3         1         3         4         6         8        10        10
       203        17         2         2         1         1         0         5         3         2         1         2         3        18         7         6         6
       208        16         1         1         2         1         0         4         3         2         1         4         5         6         9         9        10
       212        16         2         2         1         2         0         3         3         4         1         1         4         0        12        13        14
       220        17         2         1         2         2         0         4         2         5         1         2         5         2         6         6         6
       221        17         1         1         1         3         1         4         3         4         1         1         5         0         6         5         0
       225        18         3         1         1         2         1         5         3         3         1         1         4        16         9         8         7
       234        16         1         1         2         2         0         3         4         2         1         1         5        18         9         7         6
       238        17         2         1         3         2         0         2         1         1         1         1         3         2        13        11        11
       245        16         2         1         3         1         0         4         3         3         1         1         4         6        18        18        18
       249        16         0         2         1         1         0         4         3         2         2         4         5         0        13        15        15
       253        16         2         1         2         1         0         3         3         2         1         3         3         0         8         9         8
       254        17         2         1         1         1         0         4         4         2         2         4         5         0         8        12        12
       255        17         1         1         2         1         1         4         4         4         1         2         5         2         7         9         8
       258        18         2         1         1         2         0         5         2         4         1         2         4         8        15        14        14
       272        18         1         1         2         2         0         4         4         3         1         1         3         2        11        11        11
       276        18         3         2         2         2         0         4         1         1         1         1         5        75        10         9         9
       283        18         1         1         2         2         0         5         4         4         1         1         4         4         8         9        10
       284        17         2         2         1         2         0         5         4         5         1         2         5         4        10         9        11
       285        17         1         1         1         2         0         4         3         3         1         2         4         2        12        10        11
       292        18         2         1         1         2         1         5         4         3         1         1         5        12        12        12        13
       309        19         1         1         1         2         1         4         4         3         1         3         3        18        12        10        10
       312        19         1         2         1         2         1         4         5         2         2         2         4         3        13        11        11
       321        17         2         2         1         2         0         4         2         2         1         1         3        12        11         9         9
       343        17         2         2         1         2         1         3         3         1         1         2         4         0         9         8         0
       357        17         3         2         2         2         0         1         2         3         1         2         5         2        12        12        11
       358        18         1         1         2         1         0         3         3         2         1         2         3         4        10        10        10
       361        18         1         1         2         2         1         4         4         3         2         3         5         2        13        12        12
       364        17         1         2         2         2         0         3         2         2         1         2         3         0        12        11        12
       368        18         2         3         2         1         0         5         2         3         1         2         4         0        11        10        10
       371        18         1         2         3         1         0         4         3         3         2         3         3         3        14        12        12
       375        18         1         1         4         3         0         4         3         2         1         2         4         2         8         8        10
       381        18         2         1         2         1         0         4         4         3         1         3         5         5         7         6         7
       389        18         1         1         2         2         1         1         1         1         1         1         5         0         6         5         0
       394        19         1         1         1         1         0         3         2         3         3         3         5         5         8         9         9
    </code>
  </pre>
</figure>
    
        <h5><center>Test 3 (1 Cluster):</center></h5>

    <figure>
  <figcaption>Your code title</figcaption>
  <pre>
    <code>
HOW MANY CLUSTERS DO YOU WANT? 1
########################################
CLUSTER 1 (Size = 395 data points)
########################################
                 age      Medu      Fedutraveltime studytime  failures    famrel  freetime     goout      Dalc      Walc    health  absences        G1        G2        G3
  CENTROID     16.70      2.75      2.52      1.45      2.04      0.33      3.94      3.24      3.11      1.48      2.29      3.55      5.71     10.91     10.71     10.42
       104        15         3         4         1         2         0         5         4         4         1         1         1         0        16        18        18
         0        18         4         4         2         2         0         4         3         4         1         1         3         6         5         6         6
         1        17         1         1         1         2         0         5         3         3         1         1         3         4         5         5         6
         2        15         1         1         1         2         3         4         3         2         2         3         3        10         7         8        10
         3        15         4         2         1         3         0         3         2         2         1         1         5         2        15        14        15
         4        16         3         3         1         2         0         4         3         2         1         2         5         4         6        10        10
         5        16         4         3         1         2         0         5         4         2         1         2         5        10        15        15        15
         6        16         2         2         1         2         0         4         4         4         1         1         3         0        12        12        11
         7        17         4         4         2         2         0         4         1         4         1         1         1         6         6         5         6
         8        15         3         2         1         2         0         4         2         2         1         1         1         0        16        18        19
         9        15         3         4         1         2         0         5         5         1         1         1         5         0        14        15        15
        10        15         4         4         1         2         0         3         3         3         1         2         2         0        10         8         9
        11        15         2         1         3         3         0         5         2         2         1         1         4         4        10        12        12
        12        15         4         4         1         1         0         4         3         3         1         3         5         2        14        14        14
        13        15         4         3         2         2         0         5         4         3         1         2         3         2        10        10        11
        14        15         2         2         1         3         0         4         5         2         1         1         3         0        14        16        16
        15        16         4         4         1         1         0         4         4         4         1         2         2         4        14        14        14
        16        16         4         4         1         3         0         3         2         3         1         2         2         6        13        14        14
        17        16         3         3         3         2         0         5         3         2         1         1         4         4         8        10        10
        18        17         3         2         1         1         3         5         5         5         2         4         5        16         6         5         5
        19        16         4         3         1         1         0         3         1         3         1         3         5         4         8        10        10
        20        15         4         3         1         2         0         4         4         1         1         1         1         0        13        14        15
        21        15         4         4         1         1         0         5         4         2         1         1         5         0        12        15        15
        22        16         4         2         1         2         0         4         5         1         1         3         5         2        15        15        16
        23        16         2         2         2         2         0         5         4         4         2         4         5         0        13        13        12
        24        15         2         4         1         3         0         4         3         2         1         1         5         2        10         9         8
        25        16         2         2         1         1         2         1         2         2         1         3         5        14         6         9         8
        26        15         2         2         1         1         0         4         2         2         1         2         5         2        12        12        11
        27        15         4         2         1         1         0         2         2         4         2         4         1         4        15        16        15
        28        16         3         4         1         2         0         5         3         3         1         1         5         4        11        11        11
        29        16         4         4         1         2         0         4         4         5         5         5         5        16        10        12        11
        30        15         4         4         1         2         0         5         4         2         3         4         5         0         9        11        12
        31        15         4         4         2         2         0         4         3         1         1         1         5         0        17        16        17
        32        15         4         3         1         2         0         4         5         2         1         1         5         0        17        16        16
        33        15         3         3         1         2         0         5         3         2         1         1         2         0         8        10        12
        34        16         3         2         1         1         0         5         4         3         1         1         5         0        12        14        15
        35        15         2         3         2         1         0         3         5         1         1         1         5         0         8         7         6
        36        15         4         3         1         3         0         5         4         3         1         1         4         2        15        16        18
        37        16         4         4         2         3         0         2         4         3         1         1         5         7        15        16        15
        38        15         3         4         1         3         0         4         3         2         1         1         5         2        12        12        11
        39        15         2         2         1         1         0         4         3         1         1         1         2         8        14        13        13
        40        16         2         2         2         2         1         3         3         3         1         2         3        25         7        10        11
        41        15         4         4         1         1         0         5         4         3         2         4         5         8        12        12        12
        42        15         4         4         1         2         0         4         3         3         1         1         5         2        19        18        18
        43        15         2         2         1         1         0         5         4         1         1         1         1         0         8         8        11
        44        16         2         2         2         2         1         4         3         3         2         2         5        14        10        10         9
        45        15         4         3         1         2         0         5         2         2         1         1         5         8         8         8         6
        46        16         3         3         1         2         0         2         3         5         1         4         3        12        11        12        11
        47        16         4         3         1         4         0         4         2         2         1         1         2         4        19        19        20
        48        15         4         2         1         2         0         4         3         3         2         2         5         2        15        15        14
        49        15         4         4         1         2         1         4         4         4         1         1         3         2         7         7         7
        50        16         2         2         3         2         0         4         3         3         2         3         4         2        12        13        13
        51        15         4         2         1         2         0         4         3         3         1         1         5         2        11        13        13
        52        15         4         2         2         1         1         5         5         5         3         4         5         6        11        11        10
        53        15         4         4         1         1         0         3         3         4         2         3         5         0         8        10        11
        54        15         3         3         1         1         0         5         3         4         4         4         1         6        10        13        13
        55        16         2         1         1         2         0         5         3         4         1         1         2         8         8         9        10
        56        15         4         3         1         2         0         4         3         2         1         1         1         0        14        15        15
        57        15         4         4         1         2         0         3         2         2         1         1         5         4        14        15        15
        58        15         1         2         1         2         0         4         3         2         1         1         5         2         9        10         9
        59        16         4         2         1         2         0         4         2         3         1         1         5         2        15        16        16
        60        16         4         4         1         2         0         2         4         4         2         3         4         6        10        11        11
        61        16         1         1         4         1         0         5         5         5         5         5         5         6        10         8        11
        62        16         1         2         1         2         0         4         4         3         1         1         1         4         8        10         9
        63        16         4         3         1         3         0         3         4         4         2         4         4         2        10         9         9
        64        15         4         3         1         2         0         4         4         4         2         4         2         0        10        10        10
        65        16         4         3         3         2         0         5         4         3         1         2         1         2        16        15        15
        66        15         4         4         1         4         0         1         3         3         5         5         3         4        13        13        12
        67        16         3         1         1         4         0         4         3         3         1         2         5         4         7         7         6
        68        15         2         2         2         2         0         4         1         3         1         3         4         2         8         9         8
        69        15         3         1         2         4         0         4         4         2         2         3         3        12        16        16        16
        70        16         3         1         2         4         0         4         3         2         1         1         5         0        13        15        15
        71        15         4         2         1         4         0         3         3         3         1         1         3         0        10        10        10
        72        15         1         1         1         2         2         3         3         4         2         4         5         2         8         6         5
        73        16         3         1         1         1         0         5         3         2         2         2         5         2        12        12        14
        74        16         3         3         1         2         0         4         3         3         2         4         5        54        11        12        11
        75        15         4         3         1         2         0         4         3         3         2         3         5         6         9         9        10
        76        15         4         0         2         4         0         3         4         3         1         1         1         8        11        11        10
        77        16         2         2         1         4         0         5         2         3         1         3         3         0        11        11        11
        78        17         2         1         2         1         3         4         5         1         1         1         3         2         8         8        10
        79        16         3         4         1         2         0         2         4         3         1         2         3        12         5         5         5
        80        15         2         3         1         1         0         3         2         2         1         3         3         2        10        12        12
        81        15         2         3         1         3         0         5         3         2         1         2         5         4        11        10        11
        82        15         3         2         1         2         0         4         4         4         1         1         5        10         7         6         6
        83        15         2         2         2         2         0         5         3         3         1         3         4         4        15        15        15
        84        15         1         1         1         2         0         4         3         2         2         3         4         2         9        10        10
        85        15         4         4         2         2         2         4         4         4         2         3         5         6         7         9         8
        86        16         2         2         1         2         0         4         3         4         1         2         2         4         8         7         6
        87        15         4         2         1         3         0         5         3         3         1         3         1         4        13        14        14
        88        16         2         2         2         2         1         4         4         2         1         1         3        12        11        10        10
        89        16         4         4         1         2         0         4         1         3         3         5         5        18         8         6         7
        90        16         3         3         1         3         0         4         3         3         1         3         4         0         7         7         8
        91        15         4         3         1         1         0         4         5         5         1         3         1         4        16        17        18
        92        16         3         1         1         2         0         3         3         3         2         3         2         4         7         6         6
        93        16         4         2         2         2         0         5         3         3         1         1         1         0        11        10        10
        94        15         2         2         1         4         0         4         3         4         1         1         4         6        11        13        14
        95        15         1         1         2         4         1         3         1         2         1         1         1         2         7        10        10
        96        16         4         3         2         1         0         3         3         3         1         1         4         2        11        15        15
        97        16         2         1         1         2         0         4         3         5         1         1         5         2         8         9        10
        98        16         4         4         1         1         0         5         3         4         1         2         1         6        11        14        14
        99        16         4         3         1         3         0         5         3         5         1         1         3         0         7         9         8
       100        16         4         4         1         1         0         4         5         5         5         5         4        14         7         7         5
       101        16         4         4         1         3         0         4         4         3         1         1         4         0        16        17        17
       102        15         4         4         1         1         0         5         3         3         1         1         5         4        10        13        14
       103        15         3         2         2         2         0         4         3         5         1         1         2        26         7         6         6
       105        15         3         3         1         4         0         4         3         3         1         1         4        10        10        11        11
       106        15         2         2         1         4         0         5         1         2         1         1         3         8         7         8         8
       107        16         3         3         1         3         0         5         3         3         1         1         5         2        16        18        18
       108        15         4         4         4         4         0         1         3         5         3         5         1         6        10        13        13
       109        16         4         4         1         3         0         5         4         5         1         1         4         4        14        15        16
       110        15         4         4         1         1         0         5         5         3         1         1         4         6        18        19        19
       111        16         3         3         1         3         1         4         1         2         1         1         2         0         7        10        10
       112        16         2         2         1         2         1         3         1         2         1         1         5         6        10        13        13
       113        15         4         2         1         1         0         3         5         2         1         1         3        10        18        19        19
       114        15         2         1         1         2         0         5         4         2         1         1         5         8         9         9         9
       115        16         4         4         1         2         0         5         4         4         1         2         5         2        15        15        16
       116        15         4         4         2         2         0         4         4         3         1         1         2         2        11        13        14
       117        16         3         3         2         1         0         5         4         2         1         1         5         0        13        14        13
       118        17         1         3         3         2         1         5         2         4         1         4         5        20         9         7         8
       119        15         3         4         1         1         0         3         4         3         1         2         4         6        14        13        13
       120        15         1         2         1         2         0         3         2         3         1         2         1         2        16        15        15
       121        15         2         2         1         4         0         5         5         4         1         2         5         6        16        14        15
       122        16         2         4         2         2         0         4         2         2         1         2         5         2        13        13        13
       123        16         4         4         1         1         0         3         4         4         1         4         5        18        14        11        13
       124        16         2         2         1         2         0         5         4         4         1         1         5         0         8         7         8
       125        15         3         4         1         1         0         5         5         5         3         2         5         0        13        13        12
       126        15         3         4         1         2         0         5         3         2         1         1         1         0         7        10        11
       127        19         0         1         1         2         3         3         4         2         1         1         5         2         7         8         9
       128        18         2         2         1         1         2         3         3         3         1         2         4         0         7         4         0
       129        16         4         4         1         1         0         3         5         5         2         5         4         8        18        18        18
       130        15         3         4         2         3         2         4         2         2         2         2         5         0        12         0         0
       131        15         1         1         3         1         0         4         3         3         1         2         4         0         8         0         0
       132        17         2         2         1         1         0         3         4         4         1         3         5        12        10        13        12
       133        16         3         4         1         1         0         3         2         1         1         4         5        16        12        11        11
       134        15         3         4         4         2         0         5         3         3         1         1         5         0         9         0         0
       135        15         4         4         1         3         0         4         3         3         1         1         5         0        11         0         0
       136        17         3         4         3         2         0         5         4         5         2         4         5         0        10         0         0
       137        16         3         3         2         1         2         4         3         2         1         1         5         0         4         0         0
       138        16         1         1         1         2         1         4         4         4         1         3         5         0        14        12        12
       139        15         4         4         2         1         0         4         3         2         1         1         5         0        16        16        15
       140        15         4         3         2         4         0         2         2         2         1         1         3         0         7         9         0
       141        16         2         2         2         1         2         2         3         3         2         2         2         8         9         9         9
       142        15         4         4         1         3         0         4         2         2         1         1         5         2         9        11        11
       143        16         1         1         1         1         0         3         4         4         3         3         1         2        14        14        13
       144        17         2         1         1         1         3         5         4         5         1         2         5         0         5         0         0
       145        15         1         1         1         2         0         4         4         2         1         2         5         0         8        11        11
       146        15         3         2         1         2         3         3         3         2         1         1         3         0         6         7         0
       147        15         1         2         1         2         0         4         3         2         1         1         5         2        10        11        11
       148        16         4         4         1         1         0         3         3         2         2         1         5         0         7         6         0
       149        15         2         1         4         1         3         4         5         5         2         5         5         0         8         9        10
       150        18         1         1         1         1         3         2         3         5         2         5         4         0         6         5         0
       151        16         2         1         1         1         1         4         4         4         3         5         5         6        12        13        14
       152        15         3         3         2         3         2         4         2         1         2         3         3         8        10        10        10
       153        19         3         2         1         1         3         4         5         4         1         1         4         0         5         0         0
       154        17         4         4         1         1         0         4         2         1         1         1         4         0        11        11        12
       155        15         2         3         1         2         0         4         4         4         1         1         1         2        11         8         8
       156        17         1         2         1         1         0         2         2         2         3         3         5         8        16        12        13
       157        18         1         1         3         1         3         5         2         5         1         5         4         6         9         8        10
       158        16         2         2         3         1         0         4         2         2         1         2         3         2        17        15        15
       159        16         3         3         1         2         1         4         5         5         4         4         5         4        10        12        12
       160        17         2         1         2         1         2         3         3         2         2         2         5         0         7         6         0
       161        15         3         2         2         2         2         4         4         4         1         4         3         6         5         9         7
       162        16         1         2         2         1         1         4         4         4         2         4         5         0         7         0         0
       163        17         1         3         1         1         0         5         3         3         1         4         2         2        10        10        10
       164        17         1         1         4         2         3         5         3         5         1         5         5         0         5         8         7
       165        16         3         2         2         1         1         4         5         2         1         1         2        16        12        11        12
       166        16         2         2         1         2         0         4         3         5         2         4         4         4        10        10        10
       167        16         4         2         1         2         0         4         2         3         1         1         3         0        14        15        16
       168        16         2         2         1         2         0         5         1         5         1         1         4         0         6         7         0
       169        16         4         4         1         2         0         4         4         2         1         1         3         0        14        14        14
       170        16         3         4         3         1         2         3         4         5         2         4         2         0         6         5         0
       171        16         1         0         2         2         0         4         3         2         1         1         3         2        13        15        16
       172        17         4         4         1         2         0         4         4         4         1         3         5         0        13        11        10
       173        16         1         3         1         2         3         4         3         5         1         1         3         0         8         7         0
       174        16         3         3         2         2         0         4         4         5         1         1         4         4        10        11         9
       175        17         4         3         2         2         0         4         4         4         4         4         4         4        10         9         9
       176        16         2         2         2         2         0         3         4         4         1         4         5         2        13        13        11
       177        17         3         3         1         2         0         4         3         4         1         4         4         4         6         5         6
       178        16         4         2         1         1         0         4         3         3         3         4         3        10        10         8         9
       179        17         4         3         1         2         0         5         2         3         1         1         2         4        10        10        11
       180        16         4         3         1         2         0         3         4         3         2         3         3        10         9         8         8
       181        16         3         3         1         2         0         4         2         3         1         2         3         2        12        13        12
       182        17         2         4         1         2         0         5         4         2         2         3         5         0        16        17        17
       183        17         3         3         1         2         0         5         3         3         2         3         1        56         9         9         8
       184        16         3         2         1         2         0         1         2         2         1         2         1        14        12        13        12
       185        17         3         3         1         2         0         4         3         4         2         3         4        12        12        12        11
       186        16         1         2         1         1         0         3         3         3         1         2         3         2        11        12        11
       187        16         2         1         1         2         0         4         2         3         1         2         5         0        15        15        15
       188        17         3         3         1         2         0         3         3         3         1         3         3         6         8         7         9
       189        17         1         2         1         2         0         3         1         3         1         5         3         4         8         9        10
       190        16         2         3         1         2         0         4         3         3         1         1         2        10        11        12        13
       191        17         1         1         1         2         0         5         3         3         1         1         3         0         8         8         9
       192        17         1         2         2         2         0         4         4         4         4         5         5        12         7         8         8
       193        16         3         3         1         1         0         4         3         2         3         4         5         8         8         9        10
       194        16         2         3         2         1         0         5         3         3         1         1         3         0        13        14        14
       195        17         2         4         1         2         0         4         3         2         1         1         5         0        14        15        15
       196        17         4         4         1         1         0         5         2         3         1         2         5         4        17        15        16
       197        16         3         3         3         1         0         3         3         4         3         5         3         8         9         9        10
       198        17         4         4         2         1         1         4         2         4         2         3         2        24        18        18        18
       199        16         4         4         1         2         0         4         5         2         1         2         3         0         9         9        10
       200        16         4         3         1         2         0         4         3         5         1         5         2         2        16        16        16
       201        16         2         3         1         2         0         4         4         3         1         3         4         6         8        10        10
       202        17         1         1         1         2         0         4         4         4         1         3         1         4         9         9        10
       203        17         2         2         1         1         0         5         3         2         1         2         3        18         7         6         6
       204        16         2         2         2         4         0         5         3         5         1         1         5         6        10        10        11
       205        17         3         4         1         3         1         4         4         3         3         4         5        28        10         9         9
       206        16         3         1         1         2         3         2         3         3         2         2         4         5         7         7         7
       207        16         4         3         1         2         0         1         3         2         1         1         1        10        11        12        13
       208        16         1         1         2         1         0         4         3         2         1         4         5         6         9         9        10
       209        17         4         3         2         3         0         4         4         2         1         1         4         6         7         7         7
       210        19         3         3         1         4         0         4         3         3         1         2         3        10         8         8         8
       211        17         4         4         1         2         0         5         3         5         4         5         3        13        12        12        13
       212        16         2         2         1         2         0         3         3         4         1         1         4         0        12        13        14
       213        18         2         2         1         2         1         4         4         4         2         4         5        15         6         7         8
       214        17         4         4         1         1         0         5         2         1         1         2         3        12         8        10        10
       215        17         3         2         2         2         0         4         4         4         1         3         1         2        14        15        15
       216        17         4         3         1         2         2         3         4         5         2         4         1        22         6         6         4
       217        18         3         3         1         2         1         3         2         4         2         4         4        13         6         6         8
       218        17         2         3         2         1         0         3         3         3         1         4         3         3         7         7         8
       219        17         2         2         1         3         0         4         3         3         1         1         4         4         9        10        10
       220        17         2         1         2         2         0         4         2         5         1         2         5         2         6         6         6
       221        17         1         1         1         3         1         4         3         4         1         1         5         0         6         5         0
       222        16         2         3         1         2         0         2         3         1         1         1         3         2        16        16        17
       223        18         2         2         2         2         0         3         3         3         5         5         4         0        12        13        13
       224        16         4         4         1         3         0         5         3         2         1         1         5         0        13        13        14
       225        18         3         1         1         2         1         5         3         3         1         1         4        16         9         8         7
       226        17         3         2         1         2         0         5         3         4         1         3         3        10        16        15        15
       227        17         2         3         1         2         0         5         3         3         1         3         3         2        12        11        12
       228        18         2         1         4         2         0         4         3         2         4         5         3        14        10         8         9
       229        17         2         1         2         3         0         3         2         3         1         2         3        10        12        10        12
       230        17         4         3         1         2         0         3         2         3         1         2         3        14        13        13        14
       231        17         2         2         2         2         0         4         5         2         1         1         1         4        11        11        11
       232        17         4         4         1         2         0         4         5         5         1         3         2        14        11         9         9
       233        16         4         4         1         2         0         4         2         4         2         4         1         2        14        13        13
       234        16         1         1         2         2         0         3         4         2         1         1         5        18         9         7         6
       235        16         3         2         2         3         0         5         3         3         1         3         2        10        11         9        10
       236        17         2         2         1         2         0         4         4         2         5         5         4         4        14        13        13
       237        16         2         1         1         1         0         4         5         2         1         1         5        20        13        12        12
       238        17         2         1         3         2         0         2         1         1         1         1         3         2        13        11        11
       239        18         2         2         1         2         1         5         5         4         3         5         2         0         7         7         0
       240        17         4         3         2         2         0         2         5         5         1         4         5        14        12        12        12
       241        17         4         4         2         2         0         3         3         3         2         3         4         2        10        11        12
       242        16         4         3         1         1         0         5         4         5         1         1         3         0         6         0         0
       243        16         4         4         1         1         0         5         3         2         1         2         5         0        13        12        12
       244        18         2         1         2         3         0         4         4         4         1         1         3         0         7         0         0
       245        16         2         1         3         1         0         4         3         3         1         1         4         6        18        18        18
       246        17         2         3         2         1         0         5         2         2         1         1         2         4        12        12        13
       247        22         3         1         1         1         3         5         4         5         5         5         1        16         6         8         8
       248        18         3         3         1         2         1         4         3         3         1         3         5         8         3         5         5
       249        16         0         2         1         1         0         4         3         2         2         4         5         0        13        15        15
       250        18         3         2         2         1         1         4         4         5         2         4         5         0         6         8         8
       251        16         3         3         3         2         0         5         3         3         1         3         2         6         7        10        10
       252        18         2         1         1         1         1         3         2         5         2         5         5         4         6         9         8
       253        16         2         1         2         1         0         3         3         2         1         3         3         0         8         9         8
       254        17         2         1         1         1         0         4         4         2         2         4         5         0         8        12        12
       255        17         1         1         2         1         1         4         4         4         1         2         5         2         7         9         8
       256        17         4         2         1         4         0         4         2         3         1         1         4         6        14        12        13
       257        19         4         3         1         2         0         4         3         1         1         1         1        12        11        11        11
       258        18         2         1         1         2         0         5         2         4         1         2         4         8        15        14        14
       259        17         2         2         1         4         0         3         4         1         1         1         2         0        10         9         0
       260        18         4         3         1         2         0         3         1         2         1         3         2        21        17        18        18
       261        18         4         3         1         2         0         4         3         2         1         1         3         2         8         8         8
       262        18         3         2         1         3         0         5         3         2         1         1         3         1        13        12        12
       263        17         3         3         1         3         0         3         2         3         1         1         4         4        10         9         9
       264        18         2         2         1         3         0         4         3         3         1         1         3         0         9        10         0
       265        18         3         4         2         2         0         4         2         5         3         4         1        13        17        17        17
       266        17         3         1         1         2         0         5         4         4         3         4         5         2         9         9        10
       267        18         4         4         2         2         0         4         3         4         2         2         4         8        12        10        11
       268        18         4         2         1         2         0         5         4         5         1         3         5        10        10         9        10
       269        18         2         1         2         2         0         4         3         5         1         2         3         0         6         0         0
       270        19         3         3         1         2         2         4         3         5         3         3         5        15         9         9         9
       271        18         2         3         1         4         0         4         5         5         1         3         2         4        15        14        14
       272        18         1         1         2         2         0         4         4         3         1         1         3         2        11        11        11
       273        17         1         2         1         2         0         3         5         2         2         2         1         2        15        14        14
       274        17         2         4         2         2         0         4         3         3         1         1         1         2        10        10        10
       275        17         2         2         2         2         0         4         4         4         2         3         5         6        12        12        12
       276        18         3         2         2         2         0         4         1         1         1         1         5        75        10         9         9
       277        18         4         4         2         1         0         3         2         4         1         4         3        22         9         9         9
       278        18         4         4         1         2         1         2         4         4         1         1         4        15         9         8         8
       279        18         4         3         2         1         0         4         2         3         1         2         1         8        10        11        10
       280        17         4         1         2         1         0         4         5         4         2         4         5        30         8         8         8
       281        17         3         2         1         1         1         4         4         4         3         4         3        19        11         9        10
       282        18         1         1         2         4         0         5         2         2         1         1         3         1        12        12        12
       283        18         1         1         2         2         0         5         4         4         1         1         4         4         8         9        10
       284        17         2         2         1         2         0         5         4         5         1         2         5         4        10         9        11
       285        17         1         1         1         2         0         4         3         3         1         2         4         2        12        10        11
       286        18         2         2         1         3         0         4         3         3         1         2         2         5        18        18        19
       287        17         1         1         1         3         0         4         3         3         1         1         3         6        13        12        12
       288        18         2         1         1         3         0         4         2         4         1         3         2         6        15        14        14
       289        18         4         4         1         2         0         5         4         3         1         1         2         9        15        13        15
       290        18         4         2         1         2         0         4         3         2         1         4         5        11        12        11        11
       291        17         4         3         1         3         0         4         2         2         1         2         3         0        15        15        15
       292        18         2         1         1         2         1         5         4         3         1         1         5        12        12        12        13
       293        17         3         1         2         4         0         3         1         2         1         1         3         6        18        18        18
       294        18         3         2         2         3         0         5         4         2         1         1         4         8        14        13        14
       295        17         3         3         1         1         0         4         4         3         1         3         5         4        14        12        11
       296        19         4         4         2         2         0         2         3         4         2         3         2         0        10         9         0
       297        18         4         3         2         2         0         4         4         5         1         2         2        10        10         8         8
       298        18         4         3         1         4         0         4         3         3         1         1         3         0        14        13        14
       299        18         4         4         1         1         0         1         4         2         2         2         1         5        16        15        16
       300        18         4         4         1         2         0         4         2         4         1         1         4        14        12        10        11
       301        17         4         4         2         1         0         4         1         1         2         2         5         0        11        11        10
       302        17         4         2         2         3         0         4         3         3         1         1         3         0        15        12        14
       303        17         3         2         1         4         0         5         2         2         1         2         5         0        17        17        18
       304        19         3         3         1         2         1         4         4         4         1         1         3        20        15        14        13
       305        18         2         4         1         2         1         4         4         3         1         1         3         8        14        12        12
       306        20         3         2         1         1         0         5         5         3         1         1         5         0        17        18        18
       307        19         4         4         2         1         1         4         3         4         1         1         4        38         8         9         8
       308        19         3         3         1         2         1         4         5         3         1         2         5         0        15        12        12
       309        19         1         1         1         2         1         4         4         3         1         3         3        18        12        10        10
       310        19         1         2         1         2         1         4         2         4         2         2         3         0         9         9         0
       311        19         2         1         3         2         0         3         4         1         1         1         2        20        14        12        13
       312        19         1         2         1         2         1         4         5         2         2         2         4         3        13        11        11
       313        19         3         2         2         2         1         4         2         2         1         2         1        22        13        10        11
       314        19         1         1         1         3         2         4         1         2         1         1         3        14        15        13        13
       315        19         2         3         1         3         1         4         1         2         1         1         3        40        13        11        11
       316        18         2         1         2         2         0         5         3         3         1         2         1         0         8         8         0
       317        18         4         3         1         3         0         4         3         4         1         1         5         9         9        10         9
       318        17         3         4         1         3         0         4         3         4         2         5         5         0        11        11        10
       319        18         4         4         1         2         0         4         4         4         3         3         5         2        11        11        11
       320        17         4         3         1         2         0         5         2         2         1         2         5        23        13        13        13
       321        17         2         2         1         2         0         4         2         2         1         1         3        12        11         9         9
       322        17         2         2         1         3         0         3         3         2         2         2         3         3        11        11        11
       323        17         3         1         1         3         0         3         4         3         2         3         5         1        12        14        15
       324        17         0         2         2         3         0         3         3         3         2         3         2         0        16        15        15
       325        18         4         4         1         3         0         4         3         3         2         2         3         3         9        12        11
       326        17         3         3         1         1         0         4         3         5         3         5         5         3        14        15        16
       327        17         2         2         4         1         0         4         4         5         5         5         4         8        11        10        10
       328        17         4         4         1         3         0         5         4         4         1         3         4         7        10         9         9
       329        17         4         4         2         3         0         4         3         3         1         2         4         4        14        14        14
       330        18         2         2         1         4         0         4         5         5         2         4         5         2         9         8         8
       331        17         2         4         1         3         0         4         4         3         1         1         5         7        12        14        14
       332        18         3         3         1         2         0         5         3         4         1         1         4         0         7         0         0
       333        18         2         2         1         2         0         4         3         3         1         1         2         0         8         8         0
       334        18         2         2         2         4         0         4         4         4         1         1         4         0        10         9         0
       335        17         3         4         1         3         0         4         4         5         1         3         5        16        16        15        15
       336        19         3         1         1         3         1         5         4         3         1         2         5        12        14        13        13
       337        17         3         2         1         2         0         4         3         2         2         3         2         0         7         8         0
       338        18         3         3         1         4         0         5         3         3         1         1         1         7        16        15        17
       339        17         3         2         1         2         0         4         3         3         2         3         2         4         9        10        10
       340        19         2         1         1         3         1         4         3         4         1         3         3         4        11        12        11
       341        18         4         4         1         2         1         4         3         3         2         2         2         0        10        10         0
       342        18         3         4         1         2         0         4         3         3         1         3         5        11        16        15        15
       343        17         2         2         1         2         1         3         3         1         1         2         4         0         9         8         0
       344        18         2         3         1         3         0         4         3         3         1         2         3         4        11        10        10
       345        18         3         2         1         3         0         5         4         3         2         3         1         7        13        13        14
       346        18         4         3         1         3         0         5         3         2         1         2         4         9        16        15        16
       347        18         4         3         1         3         0         5         4         5         2         3         5         0        10        10         9
       348        17         4         3         1         3         0         4         4         3         1         3         4         0        13        15        15
       349        18         3         2         2         1         1         2         5         5         5         5         5        10        11        13        13
       350        19         1         1         3         2         3         5         4         4         3         3         2         8         8         7         8
       351        17         3         3         2         2         0         4         5         4         2         3         3         2        13        13        13
       352        18         1         3         1         1         1         4         3         3         2         3         3         7         8         7         8
       353        19         1         1         3         1         1         4         4         4         3         3         5         4         8         8         8
       354        17         4         3         2         2         0         4         5         5         1         3         2         4        13        11        11
       355        18         3         3         1         2         0         5         3         4         1         1         5         0        10         9         9
       356        17         4         4         2         2         0         4         3         3         1         2         5         4        12        13        13
       357        17         3         2         2         2         0         1         2         3         1         2         5         2        12        12        11
       358        18         1         1         2         1         0         3         3         2         1         2         3         4        10        10        10
       359        18         1         1         2         3         0         5         3         2         1         1         4         0        18        16        16
       360        18         1         4         3         2         0         4         3         4         1         4         5         0        13        13        13
       361        18         1         1         2         2         1         4         4         3         2         3         5         2        13        12        12
       362        18         3         3         2         2         0         4         3         2         1         3         3         0        11        11        10
       363        17         4         4         1         2         0         2         3         4         1         1         1         0        16        15        15
       364        17         1         2         2         2         0         3         2         2         1         2         3         0        12        11        12
       365        18         1         3         2         2         0         3         3         4         2         4         3         4        10        10        10
       366        18         4         4         2         3         0         4         2         2         2         2         5         0        13        13        13
       367        17         1         1         3         1         1         5         2         1         1         2         1         0         7         6         0
       368        18         2         3         2         1         0         5         2         3         1         2         4         0        11        10        10
       369        18         4         4         3         2         0         3         2         2         4         2         5        10        14        12        11
       370        19         3         2         2         2         2         3         2         2         1         1         3         4         7         7         9
       371        18         1         2         3         1         0         4         3         3         2         3         3         3        14        12        12
       372        17         2         2         1         3         0         3         4         3         1         1         3         8        13        11        11
       373        17         1         2         1         1         0         3         5         5         1         3         1        14         6         5         5
       374        18         4         4         2         3         0         5         4         4         1         1         1         0        19        18        19
       375        18         1         1         4         3         0         4         3         2         1         2         4         2         8         8        10
       376        20         4         2         2         3         2         5         4         3         1         1         3         4        15        14        15
       377        18         4         4         1         2         0         5         4         3         3         4         2         4         8         9        10
       378        18         3         3         1         2         0         4         1         3         1         2         1         0        15        15        15
       379        17         3         1         1         2         0         4         5         4         2         3         1        17        10        10        10
       380        18         4         4         1         2         0         3         2         4         1         4         2         4        15        14        14
       381        18         2         1         2         1         0         4         4         3         1         3         5         5         7         6         7
       382        17         2         3         2         2         0         4         4         3         1         1         3         2        11        11        10
       383        19         1         1         2         1         1         4         3         2         1         3         5         0         6         5         0
       384        18         4         2         2         1         1         5         4         3         4         3         3        14         6         5         5
       385        18         2         2         2         3         0         5         3         3         1         3         4         2        10         9        10
       386        18         4         4         3         1         0         4         4         3         2         2         5         7         6         5         6
       387        19         2         3         1         3         1         5         4         2         1         2         5         0         7         5         0
       388        18         3         1         1         2         0         4         3         4         1         1         1         0         7         9         8
       389        18         1         1         2         2         1         1         1         1         1         1         5         0         6         5         0
       390        20         2         2         1         2         2         5         5         4         4         5         4        11         9         9         9
       391        17         3         1         2         1         0         2         4         5         3         4         2         3        14        16        16
       392        21         1         1         1         1         3         5         5         3         3         3         3         3        10         8         7
       393        18         3         2         3         1         0         4         4         1         3         4         5         0        11        12        10
       394        19         1         1         1         1         0         3         2         3         3         3         5         5         8         9         9
    </code>
  </pre>
</figure>
    
               <?php include('footer.php') ?>

</body>
</html>