<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Data Set</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,700,800" rel="stylesheet">
  <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css" />
       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <script src="assets/js/main.js"></script>
</head>
<body>
          <?php include('header.php') ?><br><br>
    <center><h1>What Data Set Did We Use?</h1></center><br>
    <div class = "container d-flex justify-content-center">
  
            <div class="row">
                <center>
                <h5>We used the data set located here:<br><br>
                <a href = "http://archive.ics.uci.edu/ml/datasets/Student+Performance?fbclid=IwAR3ycSVbpNh-4exl1wIyLdXrJAl-qUdoq_HAVmFzsYyanKVrbOfwZdkkWBk">Student Performance Data Set</a></h5><br><br>
                    
                    <img src = "https://www.nicepng.com/png/full/94-940201_raised-hand-two-students-clipart.png" height = "200px">
                    
               </center>
            </div></div><br><br>
    <div class = "container">
    <center><h1>What are we trying to find?</h1><br>
        <h5>We're trying to find what attributes/variables linked together within the student performance data set. The data set contains the following attributes: </h5><br>
<h7>1. school - student's school (binary: "GP" - Gabriel Pereira or "MS" - Mousinho da Silveira)<br>
2. sex - student's sex (binary: "F" - female or "M" - male)<br>
3. age - student's age (numeric: from 15 to 22)<br>
4. address - student's home address type (binary: "U" - urban or "R" - rural)<br>
5. famsize - family size (binary: "LE3" - less or equal to 3 or "GT3" - greater than 3)<br>
6. Pstatus - parent's cohabitation status (binary: "T" - living together or "A" - apart)<br>
7. Medu - mother's education (numeric: 0 - none,  1 - primary education (4th grade), 2 – 5th to 9th grade, 3 – secondary education or 4 – higher education)<br>
8. Fedu - father's education (numeric: 0 - none,  1 - primary education (4th grade), 2 – 5th to 9th grade, 3 – secondary education or 4 – higher education)<br>
9. Mjob - mother's job (nominal: "teacher", "health" care related, civil "services" (e.g. administrative or police), "at_home" or "other")<br>
10. Fjob - father's job (nominal: "teacher", "health" care related, civil "services" (e.g. administrative or police), "at_home" or "other")<br>
11. reason - reason to choose this school (nominal: close to "home", school "reputation", "course" preference or "other")<br>
12. guardian - student's guardian (nominal: "mother", "father" or "other")<br>
13. traveltime - home to school travel time (numeric: 1 - less than 15 min., 2 - 15 to 30 min., 3 - 30 min. to 1 hour, or 4 - >1 hour)<br>
14. studytime - weekly study time (numeric: 1 - less than 2 hours, 2 - 2 to 5 hours, 3 - 5 to 10 hours, or 4 - >10 hours)<br>
15. failures - number of past class failures (numeric: n if between 1 to 3, else 4)<br>
16. schoolsup - extra educational support (binary: yes or no)<br>
17. famsup - family educational support (binary: yes or no)<br>
18. paid - extra paid classes within the course subject (Math or Portuguese) (binary: yes or no)<br>
19. activities - extra-curricular activities (binary: yes or no)<br>
20. nursery - attended nursery school (binary: yes or no)<br>
21. higher - wants to take higher education (binary: yes or no)<br>
22. internet - Internet access at home (binary: yes or no)<br>
23. romantic - with a romantic relationship (binary: yes or no)<br>
24. famrel - quality of family relationships (numeric: from 1 - very bad to 5 - excellent)<br>
25. freetime - free time after school (numeric: from 1 - very low to 5 - very high)<br>
26. goout - going out with friends (numeric: from 1 - very low to 5 - very high)<br>
27. Dalc - workday alcohol consumption (numeric: from 1 - very low to 5 - very high)<br>
28. Walc - weekend alcohol consumption (numeric: from 1 - very low to 5 - very high)<br>
29. health - current health status (numeric: from 1 - very bad to 5 - very good)<br>
30. absences - number of school absences (numeric: from 0 to 93)<br>
31. G1 - first period grade (numeric: from 0 to 20)<br>
32. G2 - second period grade (numeric: from 0 to 20)<br>
33. G3 - final grade (numeric: from 0 to 20, output target)<br>
</h7>
    </center></div>
        
        

    <br><br>
          <?php include('footer.php') ?>

</body>

</html>





