<?php

/**
 * Initialize global vars
 */
$tools     = array();
$materials = array();
$processes = array();

$links = array('tool-material'    => array(),
	       'tool-process'     => array(),
	       'material-process' => array(),
	       );

$nodes = $indexes = array('tools'     => array(),
			  'materials' => array(),
			  'processes' => array()
			  );

/**
 * Parse CSV
 */
$raw = file_get_contents('MakingThings_Alpha.csv');
$lines = explode("\n",$raw);

// Loop through line-by-line
for ($l = 1; $l < count($lines); $l++){

  $terms = explode(",",$lines[$l]);

  // Generate nodes
  $tool     = ucwords(strtolower(trim($terms[0])));
  $material = ucwords(strtolower(trim($terms[1])));
  $process  = ucwords(strtolower(trim($terms[2])));

  // Get node IDs for all nodes
  $tool_id     = getNodeId('tools', $tool);
  $material_id = getNodeId('materials', $material);
  $process_id  = getNodeId('processes', $process);

  // Create links
  if ($tool && $material)
    createLink('tool-material', $tool, $material);

  if ($tool && $process)
    createLink('tool-process', $tool, $process);

  if ($material && $process)
    createLink('material-process', $material, $process);

}

/**
 * Generate and print JSON
 */
foreach ($links as $linkType => $linkValues){
  for ($v = 0; $v < count($linkValues); $v++){
    $links[$linkType][] = array('source' => $linkValues[$v][0],
				'target' => $linkValues[$v][1],
				'value'  => 1);
  }
}
$json = array('nodes' => $nodes,
	      'links' => $links);
echo json_encode($json);

exit;





/*******************************************************************
 * Helper Functions
 */
function getNodeId($nodeType, $nodeValue){

  global $indexes, $nodes;

  if (!$nodeValue)
    return false;

  $node_idx = array_search($nodeValue,$indexes[$nodeType]);
  if ($node_idx === false){
    $node_idx = array_push($indexes[$nodeType],$nodeValue) - 1;
    $nodes[$nodeType][] = array('id'   => $node_idx + 1,
		                'name' => $nodeValue);
  } else {
    return $node_idx + 1;
  }

}

function createLink($linkType, $linkSource, $linkTarget){
  global $links;
  $link = array($linkSource,$linkTarget);
  $link_idx = array_search($link, $links[$linkType]);
  if ($link_idx === false)
    $links[$linkType][] = $link;
}

?>
