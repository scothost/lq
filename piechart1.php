<?php
	require_once("Image/Graph.php");
	
	// create the graph
	$Graph =& new Image_Graph(400, 300);

    // add a TrueType font
    $Arial =& $Graph->addFont(new Image_Graph_Font_TTF("arial.ttf"));
    // set the font size to 15 pixels
    $Arial->setSize(11);
    // add a title using the created font    
		
	// create the plotarea
	$Graph->add(
        new Image_Graph_Layout_Vertical(
            new Image_Graph_Title("Meat Export", $Arial),
            $Plotarea = new Image_Graph_Plotarea(),
            5            
        )
    );
			
	// create the 1st dataset
	$Dataset1 =& new Image_Graph_Dataset_Trivial();
	$Dataset1->addPoint("Beef", rand(1, 10));
	$Dataset1->addPoint("Pork", rand(1, 10));
	$Dataset1->addPoint("Poultry", rand(1, 10));
	$Dataset1->addPoint("Camels", rand(1, 10));
	$Dataset1->addPoint("Other", rand(1, 10));
	// create the 1st plot as smoothed area chart using the 1st dataset
	$Plot1 =& $Plotarea->addPlot(new Image_Graph_Plot_Pie($Dataset1));
	$Plotarea->hideAxis();
	
	// create a Y data value marker
	$Marker =& $Plot1->add(new Image_Graph_Marker_Value(VALUE_X));
	// fill it with white
	$Marker->setFillColor(IMAGE_GRAPH_WHITE);
	// and use black border
	$Marker->setBorderColor(IMAGE_GRAPH_BLACK);
	// create a pin-point marker type
	$PointingMarker =& $Plot1->add(new Image_Graph_Marker_Pointing_Angular(20, $Marker));
	// and use the marker on the 1st plot
	$Plot1->setMarker($PointingMarker);	
	// format value marker labels as percentage values
	
	$Plot1->Radius = 2;
	
	$FillArray =& new Image_Graph_Fill_Array();
	$Plot1->setFillStyle($FillArray);
    $FillArray->add(new Image_Graph_Fill_Gradient(IMAGE_GRAPH_GRAD_RADIAL, IMAGE_GRAPH_GREEN, IMAGE_GRAPH_WHITE, 200));
    $FillArray->add(new Image_Graph_Fill_Gradient(IMAGE_GRAPH_GRAD_RADIAL, IMAGE_GRAPH_BLUE, IMAGE_GRAPH_WHITE, 200));
    $FillArray->add(new Image_Graph_Fill_Gradient(IMAGE_GRAPH_GRAD_RADIAL, IMAGE_GRAPH_YELLOW, IMAGE_GRAPH_WHITE, 200));
    $FillArray->add(new Image_Graph_Fill_Gradient(IMAGE_GRAPH_GRAD_RADIAL, IMAGE_GRAPH_RED, IMAGE_GRAPH_WHITE, 200));
    $FillArray->add(new Image_Graph_Fill_Gradient(IMAGE_GRAPH_GRAD_RADIAL, IMAGE_GRAPH_ORANGE, IMAGE_GRAPH_WHITE, 200));
		
	// output the Graph
	$Graph->done();
?>
