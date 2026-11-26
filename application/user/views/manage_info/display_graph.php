
<style> 
.graph {                    
    width: 370px;  /* the image width  */ 
    height: 226px; /* the image height */  
    position: relative; 
    background: url("../download/css/barchart_background.gif") no-repeat; 
}

.graph ul li {  
    position: absolute; 
    width: 30px;  /* the width the vertical bar */ 
    height: 200px;  /* the max height of the vertical bar */ 
    bottom: 21px;   /* distance between bottom of image and x-axis */   
    text-align: center; 
    font-weight: normal; 
    line-height: 2.5em;
    border: 1px solid;
    list-style: none;
}

/* left = the distance between image left and bar left */
.graph li.mon { left: 30px; }
.graph li.tue { left: 80px; }
.graph li.wed { left: 130px; }
.graph li.thu { left: 180px; }
.graph li.fri { left: 230px; }
.graph li.sat { left: 280px; }
.graph li.sun { left: 330px; }

.bar {
margin-bottom: 10px;
color: #fff;
padding: 4px;
text-align: center;
background: -webkit-gradient(linear, left top, left bottom, from(#669c12), to(#a4dd4c));
background-color: #ff7617;
-webkit-border-radius: 2px;
-moz-border-radius: 2px;
border-radius: 2px;
-webkit-animation-name:bar;
-webkit-animation-duration:0.5s;
-webkit-animation-iteration-count:1;
-webkit-animation-timing-function:ease-out;
}


</style>


<div class="graph">
    <ul>
        <li class="bar mon" style="height: 50px;">25</li>
        <li class="bar tue" style="height: 200px;">100</li>
        <li class="bar wed" style="height: 100px;">50</li>
        <li class="bar thu" style="height: 150px;">75</li>
        <li class="bar fri" style="height: 50px;">25</li>
        <li class="bar sat" style="height: 75px;">37.5</li>
        <li class="bar sun" style="height: 180px;">90</li>        
    </ul>
</div
