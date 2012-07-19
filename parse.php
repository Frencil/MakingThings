<?php

/**
 * Initialize global vars
 */
$nodes = array();
$links = array();

$nodeSizes = array();

/**
 * Parse CSV
 */
$raw = file_get_contents('MakingThings_Alpha.csv');
$lines = explode("\n",$raw);

// Loop through line-by-line
for ($l = 1; $l < count($lines); $l++){

  $terms = explode(",",$lines[$l]);

  $tool     = ucwords(strtolower(trim($terms[0])));
  $tool_idx = getNodeIdx('tools', $tool);
  
  $material     = ucwords(strtolower(trim($terms[1])));
  $material_idx = getNodeIdx('materials', $material);

  $process = ucwords(strtolower(trim($terms[2])));

  if ($tool_idx !== false && $material_idx !== false)
    createLink($tool_idx, $material_idx, $process);

}


/**
 * Generate and print JSON
 */
for ($n = 0; $n < count($nodes); $n++){
  $nodes[$n]['size'] = $nodeSizes[$n];
}
for ($l = 0; $l < count($links); $l++){
  $links[$l]['value'] = 1;
}

$json = array('nodes' => $nodes,
	      'links' => $links);

echo json_encode($json);

exit;




/*******************************************************************
 * Helper Functions
 */
function getNodeIdx($nodeType, $nodeValue){

  global $nodes, $nodeSizes;

  if (!$nodeValue)
    return false;

  $nodeObject = array('name'  => $nodeValue,
                      'group' => $nodeType);

  $node_idx = array_search($nodeObject,$nodes);
  if ($node_idx === false){
    $node_idx    = count($nodes);
    $nodes[]     = $nodeObject;
    $nodeSizes[] = 3;
  } else {
    $nodeSizes[$node_idx] += 0.5;
  }

  return $node_idx;

}

function createLink($linkSource, $linkTarget, $linkName){
  global $links;
  $link = array('source' => $linkSource,
                'target' => $linkTarget,
                'name'   => $linkName);
  $link_idx = array_search($link, $links);
  if ($link_idx === false)
    $links[] = $link;
}

?>